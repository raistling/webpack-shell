<?php

global $INIT_REQUEST_PER_SESSION;
include_once('utils/config.php');
include_once('src/views/alertRequestPerSession.php');

session_name('login');
session_start();
function startSession($user, $token) {
    global $INIT_REQUEST_PER_SESSION;


    if (!isset($_SESSION['username'])) {
        $_SESSION['username'] = $user;
    }

    if (!isset($_SESSION['request'])) {
        $_SESSION['request'] = $INIT_REQUEST_PER_SESSION;
    }

    if (!isset($_SESSION['token'])) {
        $_SESSION['token'] = $token;
    }

}

function updateRequestPerSession() {
    $_SESSION['request'] += 1;
}

function alertSessionExceded($request){
    global $TOTAL_REQUEST_PER_SESSION, $ALERT_REQUEST_PER_SESSION;

    if ($request >= $ALERT_REQUEST_PER_SESSION){
        return alertRequestPerSessionView($request);
    }

    return '';
}
