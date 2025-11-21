<?php
session_start();
require 'db.php';

if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 2) {
    header("Location: login.php");
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['new_status'])) {
    $order_id = intval($_POST['order_id']);
    $new_status = intval($_POST['new_status']);
    $stmt = $conn->prepare("UPDATE Užsakymas SET būsena_id = ? WHERE id = ?");
    $stmt->bind_param("ii", $new_status, $order_id);
    $stmt->execute();
    $stmt->close();
}

$orders = $conn->query("
    SELECT u.id, u.data, b.pavadinimas AS busena, n.vardas, n.pavardė, u.būsena_id
    FROM Užsakymas u
    JOIN Būsena b ON u.būsena_id = b.id
    JOIN Naudotojas n ON u.naudotojas_id = n.id
");


$statuses = $conn->query("SELECT id, pavadinimas FROM Būsena");
$statusOptions = [];
while ($s = $statuses->fetch_assoc()) {
    $statusOptions[$s['id']] = $s['pavadinimas'];
}


$products = $conn->query("SELECT id, pavadinimas, kiekis FROM Prekė");


$readyOrders = [];
$ordersCheck = $conn->query("
    SELECT u.id AS order_id
    FROM Užsakymas u
    JOIN Užsakymo_prekė up ON u.id = up.užsakymas_id
    JOIN Prekė p ON up.prekė_id = p.id
    WHERE p.kiekis < up.kiekis
    GROUP BY u.id
");
$notReady = [];
while ($row = $ordersCheck->fetch_assoc()) {
    $notReady[] = $row['order_id'];
}
?>

<!DOCTYPE html>
<html lang="lt">
<head>
<meta charset="UTF-8">
<title>Vadybininko skydas</title>
<style>
body {
    font-family: 'Segoe UI', sans-serif;
    margin: 0;
    padding: 0;
    min-height: 100vh;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: #f5f5f5;
}

header {
    text-align: center;
    padding: 30px 0;
    background: rgba(255,255,255,0.05);
    backdrop-filter: blur(10px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.3);
}

nav {
    display: flex;
    justify-content: center;
    gap: 20px;
    padding: 10px;
    background: rgba(255,255,255,0.1);
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
    color: #fff;
    transition: 0.3s;
}

nav a:hover { color: #ffd700; }
nav .btn { background: #e63946; }
nav .btn:hover { background: #c1121f; }

.container {
    width: 90%;
    max-width: 1200px;
    margin: 30px auto;
    background: rgba(255,255,255,0.1);
    backdrop-filter: blur(15px);
    padding: 30px;
    border-radius: 20px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.3);
    color: #f5f5f5;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 30px;
}

table, th, td {
    border: 1px solid rgba(255,255,255,0.3);
}

th, td {
    padding: 10px;
    text-align: center;
}

th { background: rgba(255,255,255,0.2); }

button {
    background: #ffb400;
    color: #2b2b2b;
    border: none;
    padding: 8px 15px;
    border-radius: 10px;
    cursor: pointer;
    transition: 0.3s;
}

button, .btn {background:#ffb400; color:#2b2b2b; border:none; padding:12px 25px; font-size:16px; cursor:pointer; margin:5px;}
button:hover, .btn:hover {background:#ffa500;}

.ready { color: #00ff00; font-weight: bold; }
.not-ready { color: #ff6b6b; font-weight: bold; }

</style>
</head>
<body>
<header>
    <h1>Vadybininko skydas</h1>
</header>

<nav>
    <a href="index.php">Pradžia</a>
    <a href="manager_orders.php">Užsakymai</a>
    <a href="stats.php">Populiariausios prekės</a>
    <a href="logout.php" class="btn">Atsijungti (<?= htmlspecialchars($_SESSION['vardas']) ?>)</a>
</nav>

<div class="container">
    <h2>Sandėlio likučiai</h2>
    <table>
        <tr>
            <th>Prekė</th>
            <th>Kiekis sandėlyje</th>
        </tr>
        <?php while($p = $products->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($p['pavadinimas']) ?></td>
            <td><?= $p['kiekis'] ?></td>
        </tr>
        <?php endwhile; ?>
    </table>

    <h2>Užsakymai</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Klientas</th>
            <th>Data</th>
            <th>Būsena</th>
            <th>Keisti būseną</th>
            <th>Įvykdymas</th>
        </tr>
        <?php while ($row = $orders->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['vardas'] . ' ' . $row['pavardė']) ?></td>
            <td><?= htmlspecialchars($row['data']) ?></td>
            <td><?= htmlspecialchars($row['busena']) ?></td>
            <td>
                <form method="POST">
                    <input type="hidden" name="order_id" value="<?= $row['id'] ?>">
                    <select name="new_status">
                        <?php foreach ($statusOptions as $id => $name): ?>
                            <option value="<?= $id ?>" <?= $id==$row['būsena_id']?'selected':'' ?>><?= htmlspecialchars($name) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit">Keisti</button>
                </form>
            </td>
            <td>
                <?php if(!in_array($row['id'], $notReady)): ?>
                    <span class="ready">Galima įvykdyti</span>
                <?php else: ?>
                    <span class="not-ready">Laukiama komponentų</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <button onclick="alert('Siųsti laiškus klientams, kuriems galima įvykdyti')">Siųsti laiškus</button>
</div>

</body>
</html>
