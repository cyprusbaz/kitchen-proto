<?php
session_start();
require 'db.php';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT id, vardas, pavardė, slaptažodis, role_id FROM Naudotojas WHERE el_paštas = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if ($password === $user['slaptažodis']) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['vardas'] = $user['vardas'];
            $_SESSION['role_id'] = $user['role_id'];
            if ($user['role_id'] == 1) header("Location: client.php");
            elseif ($user['role_id'] == 2) header("Location: manager_orders.php");
            else header("Location: admin.php");
            exit;
        } else $error = "Neteisingas slaptažodis!";
    } else $error = "Naudotojas nerastas!";
}
?>
<!DOCTYPE html>
<html lang="lt">
<head>
<meta charset="UTF-8">
<title>Prisijungimas</title>
<style>
/* Modernus login dizainas */
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

/* Header */
header {
    text-align: center;
    padding: 30px 0;
    background: rgba(255,255,255,0.05);
    backdrop-filter: blur(10px);
    color: #fff;
    box-shadow: 0 4px 15px rgba(0,0,0,0.3);
}

/* Navigation */
nav a, nav .btn {
    display: inline-flex;    
    justify-content: center;  
    align-items: center;      
    padding: 12px 25px;    
    margin: 0 10px;
    border-radius: 10px;
    text-decoration: none;
    font-size: 16px;
    font-weight: 500;
    transition: 0.3s;
    color: #fff;
    background: #e63946;    
}
nav {
    display: flex;
    justify-content: center; 
    align-items: center;     
    gap: 20px;           
    background: rgba(255,255,255,0.1);
    padding: 10px;
}

nav .btn {
    display: inline-block;  
    text-align: center;
    padding: 12px 25px;
    border-radius: 10px;
    color: #fff;
    background: #e63946;
    text-decoration: none;
}

nav .btn:hover {
    background: #c1121f;
}

nav a.btn:hover {
    background: #c1121f;
}

nav a:not(.btn) {
    background: transparent;   
    color: #f5f5f5;
}

nav a:not(.btn):hover {
    color: #ffd700;
}

main {
    flex: 1;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 50px 20px;
}

form {
    background: rgba(255,255,255,0.1);
    backdrop-filter: blur(15px);
    border-radius: 20px;
    padding: 40px;
    width: 100%;
    max-width: 400px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.3);
    color: #f5f5f5;
    display: flex;
    flex-direction: column;
    align-items: center;
}

form input[type="email"], form input[type="password"] {
    width: 100%;
    padding: 12px;
    margin-bottom: 20px;
    border: 1px solid rgba(255,255,255,0.3);
    border-radius: 10px;
    font-size: 15px;
    background: rgba(255,255,255,0.05);
    color: #fff;
}

form input[type="submit"] {
    background: #ffb400;
    color: #2b2b2b;
    border: none;
    padding: 12px 25px;
    border-radius: 10px;
    font-size: 16px;
    cursor: pointer;
    transition: 0.3s;
}

form input[type="submit"]:hover {
    background: #ffa500;
}

p.error {
    color: #ff6b6b;
    margin-top: 10px;
    text-align: center;
}

footer {
    text-align: center;
    color: rgba(255,255,255,0.7);
    padding: 20px 0;
    font-size: 14px;
}
</style>
</head>
<body>

<header><h1>Prisijungimas</h1></header>
<nav><a href="index.php">Pradžia</a>
            <a href="register.php" >Registruotis</a></nav>

<main>
<form method="POST">
    <input type="email" name="email" placeholder="El. paštas" required>
    <input type="password" name="password" placeholder="Slaptažodis" required>
    <input type="submit" value="Prisijungti">
    <?php if($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
</form>
</main>

<footer>
    &copy; <?= date("Y") ?> Virtuvės baldų užsakymo sistema
</footer>

</body>
</html>
