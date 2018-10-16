<?php
require '../php/session.php';
session_unset();
$_SESSION['logout'] = true;
header( 'Location: ./Login.php' ) ;
?>