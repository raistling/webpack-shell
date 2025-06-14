<?php

//visita https://api.nasa.gov/ y obtén tu api Key. Pega el código que recibes en el email
global $API_KEY;
$API_KEY = 'DEMO_KEY'; // API_KEY Default


global $ALERT_REQUEST_PER_SESSION,
       $TOTAL_REQUEST_PER_SESSION,
       $INIT_REQUEST_PER_SESSION;


$TOTAL_REQUEST_PER_SESSION = 50;
$ALERT_REQUEST_PER_SESSION = 45;
$INIT_REQUEST_PER_SESSION = 0;
