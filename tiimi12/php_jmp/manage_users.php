<?php
session_start();
include 'db_connect.php';  // Ensure db_connect.php is included for database connection

// Check if the user is an admin
if ($_SESSION['role'] !== 'admin') {
    die("Pääsy kielletty.");
}

// Poista käyttäjä
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    // Use a prepared statement to delete the user
    $stmt = $conn->prepare("DELETE FROM users WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
}

// Päivitä käyttäjän tiedot
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_user'])) {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $password = $_POST['password']; // Password can be left blank if not changing
    $role = $_POST['role'];
    $puhnro = $_POST['puhnro'];

    // Validate that required fields are not empty
    if (empty($firstname) || empty($lastname) || empty($email) || empty($role) || empty($puhnro)) {
        die("? Kaikki kentät ovat pakollisia!");
    }

    // If the password is not empty, hash it before updating
    if (!empty($password)) {
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
    } else {
        // If password is empty, don't update it
        $passwordHash = null;
    }

    // Use prepared statement for update
    $stmt = $conn->prepare("UPDATE users SET 
        username = :username, 
        firstname = :firstname, 
        lastname = :lastname, 
        email = :email, 
        password = COALESCE(:password, password), 
        role = :role, 
        puhnro = :puhnro 
        WHERE id = :id");

    // Bind the parameters and execute the query
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':firstname', $firstname);
    $stmt->bindParam(':lastname', $lastname);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $passwordHash);  // Use null if password is not provided
    $stmt->bindParam(':role', $role);
    $stmt->bindParam(':puhnro', $puhnro);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
}

// Hae kaikki käyttäjät
$stmt = $conn->prepare("SELECT id, username, firstname, lastname, email, role, puhnro FROM users");
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Hallitse käyttäjiä</h2>
<table border="1">
    <tr>
        <th>Käyttäjänimi</th>
        <th>Sähköposti</th>
        <th>Rooli</th>
        <th>Toiminnot</th>
    </tr>
    <?php foreach ($result as $row) { ?>
    <tr>
        <td><?= htmlspecialchars($row['username']) ?></td>
        <td><?= htmlspecialchars($row['email']) ?></td>
        <td><?= htmlspecialchars($row['role']) ?></td>
        <td>
            <a href="manage_users.php?edit=<?= $row['id'] ?>">Muokkaa</a> |
            <a href="manage_users.php?delete=<?= $row['id'] ?>" onclick="return confirm('Haluatko varmasti poistaa käyttäjän?')">Poista</a>
        </td>
    </tr>
    <?php } ?>
</table>

<?php
// Jos muokataan käyttäjää, haetaan tiedot lomakkeeseen
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];

    // Use prepared statement to fetch user data
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<h2>Muokkaa käyttäjää</h2>
<form method="post">
    <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']) ?>">
    <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
    <input type="text" name="firstname" value="<?= htmlspecialchars($user['firstname']) ?>" required>
    <input type="text" name="lastname" value="<?= htmlspecialchars($user['lastname']) ?>" required>
    <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
    <input type="password" name="password" placeholder="Uusi salasana (jätä tyhjäksi ei muuteta)">
    <select name="role">
        <option value="user" <?= $user['role'] == 'user' ? 'selected' : '' ?>>Käyttäjä</option>
        <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
    </select>
    <input type="text" name="puhnro" value="<?= htmlspecialchars($user['puhnro']) ?>" required>
    <button type="submit" name="update_user">Päivitä</button>
</form>
<?php } ?>
