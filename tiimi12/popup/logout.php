<?php
session_start();
session_unset();
session_destroy();
header("Location: ./index.html"); // Vaihda kirjautumissivusi osoite
exit;
?>