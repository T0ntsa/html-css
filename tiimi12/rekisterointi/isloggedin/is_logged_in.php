<?php
session_start();

// Jos käyttäjä ei ole kirjautunut, ohjataan hänet kirjautumissivulle
if (!isset($_SESSION["kayttaja"])) {
    header("Location: ./login.html"); // Vaihda kirjautumissivusi osoite
    exit;
}
?>