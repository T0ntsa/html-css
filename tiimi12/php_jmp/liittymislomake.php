<?php

// Tietokantayhteyden tiedot
session_start();
include 'config.php';  // Ensure config.php is included

// Luodaan yhteys tietokantaan
$conn = new mysqli($servername, $username, $password, $dbname);

// Tarkistetaan yhteys
if ($conn->connect_error) {
    die("Yhteys epäonnistui: " . $conn->connect_error);
}

$successMessage = "";
$errorMessage = "";

// Tarkistetaan, onko lomake lähetetty
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $phone = $_POST["phone"];
    $email = $_POST["email"];
    $message = $_POST["text"];
    
    // Valmistellaan SQL-lauseke
    $stmt = $conn->prepare("INSERT INTO jasenet (nimi, puhelin, sahkoposti, viesti) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $phone, $email, $message);
    
    // Suoritetaan SQL-lauseke ja tarkistetaan onnistuminen
    if ($stmt->execute()) {
        $successMessage = "Jäsenyys rekisteröity onnistuneesti!";
    } else {
        $errorMessage = "Virhe tallennuksessa: " . $stmt->error;
    }
    
    // Suljetaan SQL-lauseke
    $stmt->close();
}

// Suljetaan tietokantayhteys
$conn->close();

// Lomake käyttäjän syötteelle
echo "<form method='post' style='margin-bottom: 20px;'>
    <label>Etunimi:</label><br>
    <input type='text' name='name' required><br>
    <label>Sukunimi:</label><br>
    <input type='text' name='name' required><br>
    <label>Puhelinnumero:</label><br>
    <input type='text' name='phone' required><br>
    <label>Sähköposti:</label><br>
    <input type='email' name='email' required><br>
    <input type='submit' value='Lähetä'>
</form>";

// Tulostetaan onnistumis- tai virheviesti, jos sellainen on
if (!empty($successMessage)) {
    echo "<p style='color: green;'>$successMessage</p>";
}
if (!empty($errorMessage)) {
    echo "<p style='color: red;'>$errorMessage</p>";
}

?>
