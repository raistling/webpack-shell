<?php
// set the cookies
setcookie("cookie[one]", "cookieone");
setcookie("cookie[two]", "cookietwo");
setcookie("cookie[three]", "cookiethree");

// establecer la fecha de expiración a una hora atrás
setcookie("TestCookie[uno]", "hola", time() + 3600, "/");
setcookie("TestCookie[dos]", "hola2", time() + 3600, "/");

if (isset($_COOKIE["TestCookie"]["uno"])) {
    echo $_COOKIE["TestCookie"]["uno"] . "<br>";
}
if (isset($_COOKIE["TestCookie"]["dos"])) {
    echo $_COOKIE["TestCookie"]["dos"] . "<br>";
}

// Expirar una cookie eliminándola
setcookie("TestCookie[uno]", "", time() - 3600, "/");

// Verificar si la cookie eliminada aún existe
if (isset($_COOKIE["TestCookie"]["uno"])) {
    echo $_COOKIE["TestCookie"]["uno"];
} else {
    echo "La cookie 'uno' ha sido eliminada. <br/>";
}


// Otra forma de depurar/prueba es ver todas las cookies
#print_r($_COOKIE);


// después de que la página se recargue, imprime
if (isset($_COOKIE['cookie'])) {
    foreach ($_COOKIE['cookie'] as $name => $value) {
        $name = htmlspecialchars($name);
        $value = htmlspecialchars($value);
        echo "$name : $value <br />\n";
    }
}
?>