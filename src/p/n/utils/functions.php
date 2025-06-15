<?php
    require_once('config.php');

    function firstChar($cadena) {
        return strtoupper($cadena[0]);
    }


    function roundTwoDecimals($n) {
        $n = floatval($n);
        return number_format($n, 2, ',', '.');
    }
