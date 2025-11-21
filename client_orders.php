<?php
session_start();
require 'db.php';

// Patikriname, ar prisijungęs klientas
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Gauname kliento užsakymus
$user_id = $_SESSION['user_id'];
$sql = "
SELECT u.*, b.pavadinimas AS busena
FROM Užsakymas u
JOIN Būsena b ON u.būsena_id = b.id
WHERE u.naudotojas_id = ?
ORDER BY u.data DESC
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="lt">
<head>
<meta charset="UTF-8">
<title>Mano užsakymai</title>
<style>
/* ---------------------------
   Dizainas identiškas index.php
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
    background: #e63946;
}

nav .btn:hover {
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
    width: 95%;
    max-width: 1000px;
    margin: 30px auto;
    box-shadow: 0 8px 30px rgba(0,0,0,0.3);
    color: #f5f5f5;
}

h1, h2 {
    text-align: center;
    margin-bottom: 25px;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

th, td {
    padding: 10px;
    border: 1px solid rgba(255,255,255,0.3);
    text-align: center;
}

th {
    background: rgba(255,255,255,0.1);
}
.btn {
    display: inline-flex;   
    justify-content: center;   
    align-items: center;       
    white-space: nowrap;         
    padding: 12px 25px;
    border-radius: 10px;
    background: #ffb400;
    color: #2b2b2b;
    text-decoration: none;
    font-size: 16px;
    font-weight: 500;
    cursor: pointer;
    transition: 0.3s;
}

.btn:hover {
    background: #ffa500;
}

button, .btn {
    background: #ffb400;
    color: #2b2b2b;
    border: none;
    padding: 8px 16px;
    border-radius: 8px;
    cursor: pointer;
    transition: 0.3s;
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

.color-box {
    width: 30px;
    height: 30px;
    margin: 0 auto;
    border-radius: 5px;
}
</style>
</head>
<body>

<header>
    <h1>Mano užsakymai</h1>
</header>

<nav>
    <a href="index.php">Pradžia</a>
    <a href="client.php">Užsakyti baldus</a>
    <a href="logout.php" class="btn">Atsijungti (<?= htmlspecialchars($_SESSION['vardas']) ?>)</a>
</nav>

<main>
<div class="container">
    <?php if($result->num_rows == 0): ?>
        <p>Šiuo metu neturite užsakymų.</p>
    <?php else: ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Data</th>
                <th>Išdėstymas</th>
                <th>Spintelių spalva</th>
                <th>Stalviršio spalva</th>
                <th>Buitinė technika</th>
                <th>Bendra kaina</th>
                <th>Būsena</th>
                <th>Vizualizacija</th>
                <th>PDF</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['data'] ?></td>
                <td><?= htmlspecialchars($row['isdestymas']) ?></td>
                <td><div class="color-box" style="background:<?= htmlspecialchars($row['spalva_spinteliu']) ?>"></div></td>
                <td><div class="color-box" style="background:<?= htmlspecialchars($row['spalva_stalvirsio']) ?>"></div></td>
                <td><?= htmlspecialchars($row['buitine_technika']) ?></td>
                <td><?= number_format($row['kaina'],2) ?> €</td>
                <td><?= htmlspecialchars($row['busena']) ?></td>
                <td>
                    <?php if($row['vizualizacija_paveikslelis'] && file_exists($row['vizualizacija_paveikslelis'])): ?>
                        <img src="<?= htmlspecialchars($row['vizualizacija_paveikslelis']) ?>" width="100">
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>
                <td>
                    <a href="generate_pdf.php?id=<?= $row['id'] ?>" class="btn">Atsisiųsti PDF</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    <?php endif; ?>
</div>
</main>

<footer>
    &copy; <?= date("Y") ?> Virtuvės baldų užsakymo sistema
</footer>

</body>
</html>
