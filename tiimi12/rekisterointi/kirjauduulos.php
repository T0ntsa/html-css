<?php
session_start();
unset($_SESSION["kayttaja"]);
header("Location: ../rekisterointi/html/kirjaudu.html");
?>