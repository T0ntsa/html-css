<?php
session_start();
if (!isset($_SESSION["kayttaja"])) {
    // Käyttäjä ei ole kirjautunut → ohjataan kirjautumissivulle
    header("Location: login.html");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suojattu sivu</title>
</head>
<body>
    <h1>Tervetuloa, <?php echo htmlspecialchars($_SESSION["kayttaja"]); ?>!</h1>
    <p>Olet kirjautunut sisään.</p>
    <p>
        Login toimintaperiaate<br>
        Normi kylähdistys.html lisätään kirjaudu/rekisteröidy linkki<br>
        joka avaa pienen popup ikkunan tjs. Kun loggedin siirrytään<br>
        kyläyhdistys.php sivulle joka on ctrl c + v aiemmasta .html.<br><br>
        kyläyhdistys.php sivuun ne muutokset jotka halutaan että vain kirjautunut ne näkee.<br>
        Tehdään myös se .htaccess missä<br><br>
        FilesMatch "\.ini$"><br>
        Order Allow,Deny<br>
        Deny from all<br>
        /FilesMatch><br>
    </p>
    <text><br>
    <br>Tämä pätkä on check.js tiedostossa<br><br>
    document.addEventListener("DOMContentLoaded", function () {
    fetch("check_login.php")
        .then(response => response.json())
        .then(data => {
            if (data.status === "loggedin") {
                // Ohjataan suojatulle PHP-sivulle
                window.location.href = "suojattu.php";
            } else {
                window.location.href = "login.html";
            }
        })
        .catch(error => console.error("Virhe kirjautumistarkistuksessa:", error));
    });
    </text>
</body>
</html>