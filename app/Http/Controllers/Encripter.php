<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Encripter extends Controller
{

    //*********************************** Atributos
    
    private $key;
    private $validSalt;

    //***********************************  Funciones Públicas

    /**
     * Constructor de la clase, el cual inicializa los valores por defecto.
     */
    public function __construct(){
        $this->config();
    }

    private function strToHex($string){
        $hex = '';
        for ($i=0; $i<strlen($string); $i++){
            $ord = ord($string[$i]);
            $hexCode = dechex($ord);
            $hex .= substr('0'.$hexCode, -2);
        }
        return strToUpper($hex);
    }
    private function hexToStr($hex){
        $string='';
        for ($i=0; $i < strlen($hex)-1; $i+=2){
            $string .= chr(hexdec($hex[$i].$hex[$i+1]));
        }
        return $string;
    }
    /**
     * Función para obtener cadena encriptada.
     * @return string
     */
    public function encript($string){
        $newLetter = "";
        $string = base64_encode(mb_convert_encoding($string, 'UTF-8', 'UTF-8'));
        //valor de base 64 a hexadecimal
        $string = $this->strToHex(mb_convert_encoding($string, 'UTF-8', 'UTF-8'));
        //valor hexadecimal con el salt a base 64
        $salt = $this->key;
        $string = base64_encode(mb_convert_encoding(base64_encode(mb_convert_encoding($string, 'UTF-8', 'UTF-8')), 'UTF-8', 'UTF-8') . '@:@' . $salt);
        $newLetter = $string;
        return $newLetter;
    }

    /**
     * Función para obtener cadena desencriptada.
     * @return string
     */
    public function desencript($string){
        $newLetter = "";
        $salt = $this->key;
        //oobtener valor con salt
        $string = base64_decode(mb_convert_encoding($string, 'UTF-8', 'UTF-8'));
        //compara salt
        $newSaltId = strpos($string, '@:@');
        $newSalt = substr($string,$newSaltId+3);
        $this->validSalt = $newSalt == $salt;
        //oobtener base 64 sin salt
        $string = substr($string,0,$newSaltId+1);
        //oobtener valor octal
        $string = base64_decode(mb_convert_encoding($string, 'UTF-8', 'UTF-8'));
        //valor de hexadecimal a base 64
        $string = $this->hexToStr(mb_convert_encoding($string, 'UTF-8', 'UTF-8'));
        //valor de hexadecimal decifrar base 64
        $string = base64_decode(mb_convert_encoding($string, 'UTF-8', 'UTF-8'));
        $newLetter = $string;
        return $newLetter;
        
    }

    public function getValidSalt(){
        return $this->validSalt;
    }

    //*********************************** Funciones Privadas

    /**
     * Establece los valores por defecto.
     */
    private function config(){
      $this->key          = env('APP_KEY','base64:vh8QteX+yD8UP0p1EgmCmx5nTdHggwVvo6M0bsdvMfU=');
    }
}

?>
