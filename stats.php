<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role_id'] != 2 && $_SESSION['role_id'] != 3)) {
    header("Location: login.php");
    exit;
}

$query = "
    SELECT p.pavadinimas, SUM(up.kiekis) AS pasirinkimo_skaicius
    FROM Užsakymo_prekė up
    JOIN Prekė p ON up.prekė_id = p.id
    GROUP BY up.prekė_id
    ORDER BY pasirinkimo_skaicius DESC
    LIMIT 5
";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="lt">
<head>
<meta charset="UTF-8">
<title>Populiariausios prekės</title>
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

nav a, nav .btn {
    display: inline-flex;
    justify-content: center;
    align-items: center;
    padding: 12px 25px;
    border-radius: 10px;
    text-decoration: none;
    font-size: 16px;
    font-weight: 500;
    transition: 0.3s;
    color: #fff;
    background: #e63946;
}

nav a:not(.btn) {
    background: transparent;
    color: #f5f5f5;
}

nav a:hover, nav .btn:hover {
    background: #c1121f;
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
    text-align: center;
}

h2 {
    font-size: 28px;
    margin-bottom: 20px;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

th, td {
    padding: 12px;
    border-bottom: 1px solid rgba(255,255,255,0.3);
    text-align: left;
}

th {
    background-color: rgba(255,255,255,0.2);
}

td {
    background-color: rgba(255,255,255,0.05);
}
button, .btn {background:#ffb400; color:#2b2b2b; border:none; padding:12px 25px; font-size:16px; cursor:pointer; margin:5px;}
button:hover, .btn:hover {background:#ffa500;}

.ready { color: #00ff00; font-weight: bold; }
.not-ready { color: #ff6b6b; font-weight: bold; }
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
    <h1>Vadybininko statistika</h1>
</header>

<nav>
        <a href="index.php">Pradžia</a>
    <a href="manager_orders.php">Užsakymai</a>
    <a href="stats.php">Populiariausios prekės</a>
    <a href="logout.php" class="btn">Atsijungti (<?= htmlspecialchars($_SESSION['vardas']) ?>)</a>
</nav>

<div class="container">
    <h2>5 dažniausiai pasirenkami komponentai</h2>
    <table>
        <tr>
            <th>Prekė</th>
            <th>Pasirinkta kartų</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['pavadinimas']) ?></td>
            <td><?= $row['pasirinkimo_skaicius'] ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<footer>
    &copy; <?= date("Y") ?> Virtuvės baldų užsakymo sistema
</footer>

</body>
</html>
