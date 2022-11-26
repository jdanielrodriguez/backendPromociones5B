<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

define('CORREO', 'no-reply@codeguate.com');
define('EMPRESA', 'Promociones 5B');
abstract class EmailsController extends Controller
{
    const CORREO = 'no-reply@codeguate.com';
    const EMPRESA = 'Promociones 5B';
    public static function enviarConfirm($objectReques, $objectSee)
    {
        Mail::send('emails.confirm', ['empresa' => self::EMPRESA, 'url' => 'https://www.ordenes.online/' . ($objectReques->proveedor ? $objectReques->proveedor->nombre . "/inicio" : "inicio"), 'app' => 'http://me.JoseDanielRodriguez.gt', 'password' => $objectReques->usuario->password, 'username' => $objectSee->username, 'email' => $objectSee->email, 'name' => $objectSee->nombre . ' ' . $objectSee->apellido,], function (Message $message) use ($objectSee) {
            $message->from(self::CORREO, self::EMPRESA)
                ->sender(self::CORREO, self::EMPRESA)
                ->to($objectSee->email, $objectSee->nombre . ' ' . $objectSee->apellido)
                ->replyTo(self::CORREO, self::EMPRESA)
                ->subject('Usuario Creado');
        });
    }
    public static function enviarReward($object)
    {
        $imagen = $object['winObj']->img . "" . $object['winOpt']->img;
        $template = '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
            <title>Comprobante de premio</title>
        </head>
        <body>
        <div class="">
            <img src="' . $imagen . '" style="height: 100%;" alt="">
            </div>
        </body>
        </html>';

        self::sendMail($object['email'], $object['name'], $template, $imagen);
        self::sendWhatsapp($object['phone'],$imagen);
    }
    static function sendMail($recepient, $name, $message, $image)
    {
        $mail = new PHPMailer(true); //Argument true in constructor enables exceptions
        //Enable SMTP debugging.
        $mail->SMTPDebug = false;
        //Set PHPMailer to use SMTP.
        $mail->isSMTP();
        //Set SMTP host name                          
        $mail->Host = "mail.codeguate.com";
        //Set this to true if SMTP host requires authentication to send email
        $mail->SMTPAuth = true;
        //Provide username and password     
        $mail->Username = "";
        $mail->Password = "";
        //If SMTP requires TLS encryption then set it
        $mail->SMTPSecure = "ssl";
        //Set TCP port to connect to
        $mail->Port = 465;
        //From email address and name
        $mail->From = self::CORREO;
        $mail->FromName = self::EMPRESA;

        //To address and name
        // $mail->addAddress($recepient); //Recipient name is optional
        // $mail->AddAttachment($image); 
        //Send HTML or Plain Text email
        $mail->isHTML(true);
        $mail->Subject = "Felicidades";
        $mail->Body = $message;
        $mail->MsgHTML($message);
        $mail->SetFrom(self::CORREO, self::EMPRESA); 
        $mail->AddReplyTo(self::CORREO, self::EMPRESA); 
        $mail->SetFrom(self::CORREO, self::EMPRESA); 
        $mail->AddReplyTo(self::CORREO, self::EMPRESA); 
        $mail->From = self::CORREO;
        $mail->FromName = self::EMPRESA;
        $mail->AltBody = "Felicidades";
        $mail->addAddress($recepient, $name);

        try {
            return $mail->send();
        } catch (Exception $e) {
            echo "Mailer Error: " . $mail->ErrorInfo . "<br>";
        }
    }

    static function sendWhatsapp($telefono, $imagen)
    {
        $curl = curl_init();
        if (strlen($telefono) == 8) {
            $telefono = '502' . trim($telefono);
        }
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://panel.rapiwha.com/send_message.php?apikey={api_key}&number=" . $telefono . "&text=" . $imagen,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    public static function enviarConfirmProvs($objectReques, $objectSee)
    {
        Mail::send('emails.confirmProvs', ['empresa' => self::EMPRESA, 'url' => 'https://www.ordenes.online/' . ($objectSee->proveedores ? $objectSee->proveedores[0]->nombre . "/inicio" : "inicio"), 'app' => 'http://me.JoseDanielRodriguez.gt', 'password' => '', 'username' => $objectSee->username, 'email' => $objectSee->email, 'token' => ($objectSee->proveedores ? $objectSee->proveedores[0]->token : ""), 'name' => $objectSee->nombre . ' ' . $objectSee->apellido,], function (Message $message) use ($objectSee) {
            $message->from(self::CORREO, self::EMPRESA)
                ->sender(self::CORREO, self::EMPRESA)
                ->to($objectSee->email, $objectSee->nombre . ' ' . $objectSee->apellido)
                ->replyTo(self::CORREO, self::EMPRESA)
                ->subject('Proveedor Creado');
        });
    }

    public static function enviarRecovery($objectUpdate, $pass)
    {
        Mail::send('emails.recovery', ['empresa' => $objectUpdate->empresaMostrar, 'url' => $objectUpdate->url, 'password' => $pass, 'username' => $objectUpdate->username, 'email' => $objectUpdate->email, 'name' => $objectUpdate->nombreProveedor], function (Message $message) use ($objectUpdate) {
            $message->from($objectUpdate->correoMostar, $objectUpdate->nombreMostrar)
                ->sender($objectUpdate->correoMostar, $objectUpdate->nombreMostrar)
                ->to($objectUpdate->email, $objectUpdate->nombreProveedor)
                ->replyTo($objectUpdate->correoMostar, $objectUpdate->nombreMostrar)
                ->subject('Contraseña Reestablecida');
        });
    }

    public static function enviarFactura($objectReques, $objectSee)
    {
        Mail::send('emails.factura', ['empresa' => self::EMPRESA, 'url' => 'https://www.ordenes.online/' . ($objectReques->proveedor ? $objectReques->proveedor->nombre . "/inicio" : "inicio"), 'app' => 'http://me.JoseDanielRodriguez.gt', 'password' => $objectReques->usuario->password, 'username' => $objectSee->username, 'email' => $objectSee->email, 'name' => $objectSee->nombre . ' ' . $objectSee->apellido,], function (Message $message) use ($objectSee) {
            $message->from(self::CORREO, self::EMPRESA)
                ->sender(self::CORREO, self::EMPRESA)
                ->to($objectSee->email, $objectSee->nombre . ' ' . $objectSee->apellido)
                ->replyTo(self::CORREO, self::EMPRESA)
                ->subject('Usuario Creado');
        });
    }

    public static function enviarPago($objectReques, $objectSee)
    {
        Mail::send('emails.pago', ['empresa' => self::EMPRESA, 'url' => 'https://www.ordenes.online/' . ($objectReques->proveedor ? $objectReques->proveedor->nombre . "/inicio" : "inicio"), 'app' => 'http://me.JoseDanielRodriguez.gt', 'password' => $objectReques->usuario->password, 'username' => $objectSee->username, 'email' => $objectSee->email, 'name' => $objectSee->nombre . ' ' . $objectSee->apellido,], function (Message $message) use ($objectSee) {
            $message->from(self::CORREO, self::EMPRESA)
                ->sender(self::CORREO, self::EMPRESA)
                ->to($objectSee->email, $objectSee->nombre . ' ' . $objectSee->apellido)
                ->replyTo(self::CORREO, self::EMPRESA)
                ->subject('Usuario Creado');
        });
    }

    public static function enviarConfirmacionVenta($objectReques, $objectSee)
    {
        Mail::send('emails.facturaConfirm', ['empresa' => self::EMPRESA, 'url' => 'https://www.ordenes.online/' . ($objectReques->proveedor ? $objectReques->proveedor->nombre . "/inicio" : "inicio"), 'app' => 'http://me.JoseDanielRodriguez.gt', 'password' => $objectReques->usuario->password, 'username' => $objectSee->username, 'email' => $objectSee->email, 'name' => $objectSee->nombre . ' ' . $objectSee->apellido,], function (Message $message) use ($objectSee) {
            $message->from(self::CORREO, self::EMPRESA)
                ->sender(self::CORREO, self::EMPRESA)
                ->to($objectSee->email, $objectSee->nombre . ' ' . $objectSee->apellido)
                ->replyTo(self::CORREO, self::EMPRESA)
                ->subject('Usuario Creado');
        });
    }
}
