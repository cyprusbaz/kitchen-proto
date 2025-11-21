<?php
session_start();
require 'db.php';

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $vardas = trim($_POST['vardas']);
    $pavarde = trim($_POST['pavarde']);
    $email = trim($_POST['email']);
    $tel = trim($_POST['tel']);
    $slaptazodis = trim($_POST['slaptazodis']);

    // Paprastas patikrinimas
    if (!$vardas || !$pavarde || !$email || !$slaptazodis) {
        $error = "Visi laukai privalomi!";
    } else {
        // Patikrinam, ar el. paštas jau registruotas
        $stmt = $conn->prepare("SELECT id FROM Naudotojas WHERE el_paštas=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $error = "Toks el. paštas jau naudojamas!";
        } else {
            // Registruojam naują vartotoją, role_id = 1 (Klientas)
            $stmt = $conn->prepare("INSERT INTO Naudotojas (vardas, pavardė, el_paštas, slaptažodis, tel_numeris, role_id) VALUES (?, ?, ?, ?, ?, 1)");
            $stmt->bind_param("sssss", $vardas, $pavarde, $email, $slaptazodis, $tel);
            if ($stmt->execute()) {
                $success = "Registracija sėkminga! Galite prisijungti.";
            } else {
                $error = "Įvyko klaida registruojant vartotoją.";
            }
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="lt">
<head>
<meta charset="UTF-8">
<title>Registracija</title>
<style>
body {
    font-family: 'Segoe UI', sans-serif;
    margin: 0;
    padding: 0;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: #f5f5f5;
}
header {
    text-align: center;
    padding: 30px 0;
    background: rgba(255,255,255,0.05);
    backdrop-filter: blur(10px);
    color: #fff;
    box-shadow: 0 4px 15px rgba(0,0,0,0.3);
}
nav {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 20px;
    background: rgba(255,255,255,0.1);
    padding: 10px;
}
nav a {
    text-decoration: none;
    padding: 12px 25px;
    border-radius: 10px;
    color: #f5f5f5;
    background: #ffb400;
    transition: 0.3s;
}
nav a:hover { background: #ffa500; }
.container {
    background: rgba(255,255,255,0.1);
    backdrop-filter: blur(15px);
    border-radius: 20px;
    padding: 40px;
    width: 90%;
    max-width: 500px;
    margin: 30px auto;
    box-shadow: 0 8px 30px rgba(0,0,0,0.3);
    color: #f5f5f5;
    text-align: center;
}
input {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border-radius: 8px;
    border: none;
}
button, .btn {
    background: #ffb400;
    color: #2b2b2b;
    border: none;
    padding: 12px 25px;
    border-radius: 10px;
    font-size: 16px;
    cursor: pointer;
    transition: 0.3s;
    margin: 5px;
}

button:hover, .btn:hover {
    background: #ffa500;
}
.success { background: #c8e6c9; padding: 10px; border-radius: 10px; margin-bottom: 15px; color: #2b2b2b;}
.error { background: #ffb3b3; padding: 10px; border-radius: 10px; margin-bottom: 15px; color: #2b2b2b;}
footer { text-align: center; color: rgba(255,255,255,0.7); padding: 20px 0; font-size: 14px;}
</style>
</head>
<body>
<header><h1>Registracija</h1></header>
<nav><a href="index.php">Pradžia</a><a href="login.php">Prisijungti</a></nav>

<main>
<div class="container">
    <?php if($error) echo "<div class='error'>$error</div>"; ?>
    <?php if($success) echo "<div class='success'>$success</div>"; ?>

    <form method="POST">
        <input type="text" name="vardas" placeholder="Vardas" required>
        <input type="text" name="pavarde" placeholder="Pavardė" required>
        <input type="email" name="email" placeholder="El. paštas" required>
        <input type="text" name="tel" placeholder="Telefono numeris">
        <input type="password" name="slaptazodis" placeholder="Slaptažodis" required>
        <button type="submit">Registruotis</button>
    </form>
</div>
</main>
<footer>
&copy; <?= date("Y") ?> Virtuvės baldų užsakymo sistema
</footer>
</body>
</html>
