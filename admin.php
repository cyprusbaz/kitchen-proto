<?php
require 'db.php';
if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 3) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_role'])) {
    $user_id = intval($_POST['user_id']);
    $new_role = intval($_POST['role_id']);
    $stmt = $conn->prepare("UPDATE Naudotojas SET role_id = ? WHERE id = ?");
    $stmt->bind_param("ii", $new_role, $user_id);
    $stmt->execute();
}

$users = $conn->query("
    SELECT n.id, n.vardas, n.pavardė, r.pavadinimas AS role, n.role_id
    FROM Naudotojas n
    JOIN Rolė r ON n.role_id = r.id
");

$roles_result = $conn->query("SELECT id, pavadinimas FROM Rolė");
$roles = [];
while ($r = $roles_result->fetch_assoc()) {
    $roles[$r['id']] = $r['pavadinimas'];
}
?>
<!DOCTYPE html>
<html lang="lt">
<head>
<meta charset="UTF-8">
<title>Administratoriaus skydas</title>
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
    margin: 0 10px;
    border-radius: 10px;
    text-decoration: none;
    font-size: 16px;
    font-weight: 500;
    transition: 0.3s;
    color: #fff;
}
nav a.btn {
    background: #e63946;
}
nav a.btn:hover {
    background: #c1121f;
}
nav a:not(.btn):hover {
    color: #ffd700;
}

main {
    flex: 1;
    display: flex;
    justify-content: center;
    align-items: flex-start;
    padding: 40px 20px;
}
.container {
    background: rgba(255,255,255,0.1);
    backdrop-filter: blur(15px);
    border-radius: 20px;
    padding: 40px;
    width: 90%;
    max-width: 900px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.3);
    text-align: center;
    color: #fff;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}
th, td {
    border: 1px solid rgba(255,255,255,0.2);
    padding: 12px;
    text-align: center;
}
th {
    background: rgba(255,255,255,0.2);
    color: #fff;
}
tr:nth-child(even) {
    background: rgba(255,255,255,0.1);
}

select {
    padding: 8px;
    border-radius: 8px;
    border: none;
    background: rgba(255,255,255,0.2);
    color: #fff;
    font-weight: bold;
}
option {
    color: #000;
}
button {
    background: #ffb400;
    color: #2b2b2b;
    border: none;
    padding: 8px 16px;
    border-radius: 10px;
    cursor: pointer;
    transition: 0.3s;
}
button:hover {
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
    <h1>Administratoriaus skydas</h1>
</header>

<nav>
    <a href="index.php">Pradžia</a>
    <a href="admin.php">Naudotojai</a>
    <a href="logout.php" class="btn">Atsijungti (<?= htmlspecialchars($_SESSION['vardas']) ?>)</a>
</nav>

<main>
    <div class="container">
        <h2>Naudotojų sąrašas ir rolių valdymas</h2>

        <table>
            <tr>
                <th>ID</th>
                <th>Vardas</th>
                <th>Pavardė</th>
                <th>Rolė</th>
                <th>Veiksmas</th>
            </tr>
            <?php while ($u = $users->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($u['id']) ?></td>
                <td><?= htmlspecialchars($u['vardas']) ?></td>
                <td><?= htmlspecialchars($u['pavardė']) ?></td>
                <td>
                    <form method="POST" style="margin:0; display:flex; justify-content:center; gap:10px;">
                        <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                        <select name="role_id">
                            <?php foreach ($roles as $rid => $rname): ?>
                                <option value="<?= $rid ?>" <?= ($u['role_id'] == $rid ? 'selected' : '') ?>>
                                    <?= htmlspecialchars($rname) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" name="update_role">Atnaujinti</button>
                    </form>
                </td>
                <td><?= htmlspecialchars($u['role']) ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</main>

<footer>
    &copy; <?= date("Y") ?> Virtuvės baldų užsakymo sistema
</footer>

</body>
</html>
