<?php
session_start();
require 'db.php';
?>
<!DOCTYPE html>
<html lang="lt">
<head>
<meta charset="UTF-8">
<title>Virtuvės baldų užsakymo sistema</title>
<style>
/* ---------------------------
   Modernus, profesionalus dizainas
---------------------------- */

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
    background: rgba(255,255,255,0.1);
    padding: 10px;
}

nav a {
    color: #f5f5f5;
    text-decoration: none;
    margin: 0 20px;
    font-weight: 500;
    transition: 0.3s;
}

nav a:hover {
    color: #ffd700;
}

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

.container {
    background: rgba(255,255,255,0.1);
    backdrop-filter: blur(15px);
    border-radius: 20px;
    padding: 40px;
    width: 90%;
    max-width: 800px;
    margin: 30px auto;
    box-shadow: 0 8px 30px rgba(0,0,0,0.3);
    color: #f5f5f5;
    text-align: center;
}

main h2 {
    font-size: 32px;
    margin-bottom: 20px;
    color: #fff;
}

main p {
    font-size: 16px;
    line-height: 1.6;
    margin-bottom: 25px;
    color: #e0e0e0;
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

footer {
    text-align: center;
    color: rgba(255,255,255,0.7);
    padding: 20px 0;
    font-size: 14px;
}
</style>
</head>
<body>

<header>
    <h1>Virtuvės baldų užsakymo sistema</h1>
</header>

<nav>
    <a href="index.php">Pradžia</a>

    <?php if (isset($_SESSION['user_id'])): 
        $role_id = $_SESSION['role_id'];
        if ($role_id == 1) echo '<a href="client.php">Užsakyti baldus</a><a href="client_orders.php">Mano užsakymai</a>';
        if ($role_id == 2) echo '<a href="manager_orders.php">Užsakymai</a><a href="stats.php">Populiariausios prekės</a>';
        if ($role_id == 3) echo '<a href="admin.php">Naudotojai</a>';
    ?>
        <a href="logout.php" class="btn">Atsijungti (<?= htmlspecialchars($_SESSION['vardas']) ?>)</a>
    <?php else: ?>
        <a href="login.php">Prisijungti</a>
                <a href="register.php">Registruotis</a>
    <?php endif; ?>
</nav>

<main>
    <div class="container">
        <h2>Sveiki atvykę!</h2>
        <p>Čia galite peržiūrėti, užsisakyti ar valdyti virtuvės baldų užsakymus.</p>

        <?php if (!isset($_SESSION['user_id'])): ?>
            <button onclick="window.location.href='login.php'">Prisijungti</button>
        <?php else: ?>
            <p>Jūs esate prisijungęs kaip <b><?= htmlspecialchars($_SESSION['vardas']) ?></b> (rolė: 
            <?php
                $roles = [1=>'Klientas',2=>'Vadybininkas',3=>'Administratorius'];
                echo $roles[$_SESSION['role_id']];
            ?>)
            </p>
            <p>Naudokite navigaciją viršuje, kad patektumėte į savo puslapį.</p>
        <?php endif; ?>
    </div>
</main>

<footer>
    &copy; <?= date("Y") ?> Virtuvės baldų užsakymo sistema
</footer>

</body>
</html>
