<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    die("Pääsy kielletty.");
}
?>

<h1>Tervetuloa admin-sivulle!</h1>
<a href="manage_users.php">Hallitse käyttäjiä</a>
<a href="logout.php">Kirjaudu ulos</a>
