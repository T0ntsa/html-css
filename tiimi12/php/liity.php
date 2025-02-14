<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $etunimi = htmlspecialchars($_POST["etunimi"]);
    $sukunimi = htmlspecialchars($_POST["sukunimi"]);
    $puhelin = htmlspecialchars($_POST["puhelin"]);
    $email = htmlspecialchars($_POST["email"]);
    $salasana = password_hash($_POST["salasana"], PASSWORD_DEFAULT);

    // Tietokantayhteys
    $conn = new mysqli("localhost", "käyttäjänimi", "salasana", "tietokanta");

    if ($conn->connect_error) {
        die("Yhteys epäonnistui: " . $conn->connect_error);
    }

    $sql = "INSERT INTO jasenet (etunimi, sukunimi, puhelin, email, salasana) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $etunimi, $sukunimi, $puhelin, $email, $salasana);

    if ($stmt->execute()) {
        echo "<script>alert('Rekisteröityminen onnistui!'); window.close();</script>";
    } else {
        echo "Virhe: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!-- <?php
//$json=isset($_POST["user"]) ? $_POST["user"] : "";

// if (!($user=tarkistaJson($json))){
//     print "Täytä kaikki kentät";
//     exit;
// }
// mysqli_report(MYSQLI_REPORT_ALL ^ MYSQLI_REPORT_INDEX);
// try{
//     $yhteys=mysqli_connect("db", "pullapulu", "kukkuu123", "kayttaja");
// }
// catch(Exception $e){
//     print "Yhteysvirhe";
//     exit;
// }

//Tehdään sql-lause, jossa kysymysmerkeillä osoitetaan paikat
//joihin laitetaan muuttujien arvoja
// $sql="insert into kayttaja (tunnus, salasana) values(?, SHA2(?, 256))";//sama kuin SHA2(?, 0)
// try{
//     $stmt=mysqli_prepare($yhteys, $sql);
//     mysqli_stmt_bind_param($stmt, 'ss', $user->tunnus, $user->salasana);
//     mysqli_stmt_execute($stmt);
//     mysqli_close($yhteys);
//     print $json;
// }
// catch(Exception $e){
//     print "Tunnus jo olemassa tai muu virhe!";
// }
// ?>


// <?php
// function tarkistaJson($json){
//     if (empty($json)){
//         return false;
//     }
//     $user=json_decode($json, false);
//     if (empty($user->tunnus) || empty($user->salasana)){
//         return false;
//     }
//     return $user;
// }
// ?> -->
