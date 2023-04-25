<?php

    function generatePassword(){
        $key = "";
        $pattern = "1234567890abcdefghijklmnopqrstuvwxyz";
        //$pattern = "1234567890abcdefghijklmñnopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZ.-_*/=[]{}#@|~¬&()?¿";
        $max = strlen($pattern)-1;
        for($i = 0; $i < 6; $i++){
            $key .= substr($pattern, mt_rand(0,$max), 1);
        }

        $to = "jphdanipat99@hotmail.com";
        $subject = "Token para acceso al sistema";
        $message = "Un usuario ha solicitado acceder al sistema, es necesario proporcionarle la siguiente clave '.$key.'";
        
        mail($to, $subject, $message);

        return $key;

        echo "Token enviado";
    }
    generatePassword();
?>