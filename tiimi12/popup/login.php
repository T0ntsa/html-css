<?php
$initials=parse_ini_file("../.ht.asetukset.ini");

session_start();
$json=isset($_POST["user"]) ? $_POST["user"] : "";

if (!($user=tarkistaJson($json))){
    print "Täytä kaikki kentät";
    exit;
}

mysqli_report(MYSQLI_REPORT_ALL ^ MYSQLI_REPORT_INDEX);
// mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Tähän erillinen tk yhteys tiedosto eli ei passua tähän
try{
    // $yhteys=mysqli_connect("db", "root", "password", "kayttaja");
    $yhteys=mysqli_connect($initials["databaseserver"], $initials["username"], $initials["password"], $initials["database"]);
}
catch(Exception $e){
    print "Yhteysvirhe";
    exit;
}

//Tehdään sql-lause, jossa kysymysmerkeillä osoitetaan paikat
//joihin laitetaan muuttujien arvoja
$sql="select * from kayttaja where tunnus=? and salasana=SHA2(?, 256)";
try{
    //Valmistellaan sql-lause
    $stmt=mysqli_prepare($yhteys, $sql);
    //Sijoitetaan muuttujat oikeisiin paikkoihin
    mysqli_stmt_bind_param($stmt, 'ss', $user->tunnus, $user->salasana);
    //Suoritetaan sql-lause
    mysqli_stmt_execute($stmt);
    //Koska luetaan prepared statementilla, tulos haetaan
    //metodilla mysqli_stmt_get_result($stmt);
    $tulos=mysqli_stmt_get_result($stmt);
    if ($rivi=mysqli_fetch_object($tulos)){
        $_SESSION["kayttaja"]="$rivi->tunnus";
        print "ok";
        exit;
    }
    // if ($tulos->execute()) {
    //     echo "<script>alert('Rekisteröityminen onnistui!'); window.close();</script>";
    // } else {
    //     echo "Virhe: " . $tulos->error;
    // }
    //Suljetaan tietokantayhteys
    mysqli_close($yhteys);
    print $json;
}
catch(Exception $e){
    print "Jokin virhe!";
}
?>


<?php
function tarkistaJson($json){
    if (empty($json)){
        return false;
    }
    $user=json_decode($json, false);
    if (empty($user->tunnus) || empty($user->salasana)){
        return false;
    }
    return $user;
}
?>