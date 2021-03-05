<?php
namespace Src\System;

class Encript {

    public static function saltEncription($value){
        $hash_format = "$2y$10$";
        $salt = "salt22CharactersOrmore";
        $format_and_salt = $hash_format . $salt;
        $hash = $format_and_salt.(md5($value));

        return $hash;
    }

    public static function generatePassword($_len) {
        $_alphaSmall = 'abcdefghijklmnopqrstuvwxyz';            // small letters
        $_alphaCaps  = strtoupper($_alphaSmall);                // CAPITAL LETTERS
        $_numerics   = '1234567890';                            // numerics
        $_specialChars = '`~!@#$%^&*()-_=+]}[{;:,<.>/?\'"\|';   // Special Characters

        $_container = $_alphaSmall.$_alphaCaps.$_numerics.$_specialChars;   // Contains all characters
        $password = '';         // will contain the desired pass

        for($i = 0; $i < $_len; $i++) {                                 // Loop till the length mentioned
            $_rand = rand(0, strlen($_container) - 1);                  // Get Randomized Length
            $password .= substr($_container, $_rand, 1);                // returns part of the string [ high tensile strength ;) ] 
        }

        return $password;       // Returns the generated Pass
}
}
?>