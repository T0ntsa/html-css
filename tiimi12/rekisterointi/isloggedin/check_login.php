<?php
session_start();
if (isset($_SESSION["kayttaja"])) {
    echo json_encode(["status" => "loggedin", "user" => $_SESSION["kayttaja"]]);
}
// else {
//     echo json_encode(["status" => "notloggedin"]);
// }
?>