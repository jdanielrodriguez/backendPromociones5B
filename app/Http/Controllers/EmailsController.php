<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;

use App\Http\Requests;
define('CORREO', 'send@ordenes.online');
define('EMPRESA', 'Ordenes Online');
abstract class EmailsController extends Controller
{
    const CORREO = 'send@ordenes.online';
    const EMPRESA = 'Ordenes Online';
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
