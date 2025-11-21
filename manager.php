<?php
require 'functions.php';
$inventory = get_inventory();
$orders = get_sent_orders();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['restock'])) {
// paprastas papildymas: pridėti prie sandėlio
foreach ($_POST['add'] as $k=>$v) {
$v = (int)$v; if ($v<=0) continue;
$_SESSION['inventory'][$k] += $v;
}
header('Location: manager.php'); exit;
}
?>
<!doctype html>
<html lang="lt">
<head>
<meta charset="utf-8">
<title>Vadybininkas - Sandėlis</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
<header>
<h1>Vadybininko skydelis</h1>
<nav>
<a href="index.php">Užsakymas</a>
<a href="manager.php">Vadybininkas</a>
<a href="stats.php">Statistika</a>
</nav>
</header>


<div class="card">
<h2>Sandėlio likučiai</h2>
<form method="post">
<table class="table">
<tr><th>Komponentas</th><th>Turime</th><th>Pridėti</th></tr>
<?php foreach ($inventory as $k=>$v): ?>
<tr>
<td><?php echo $k; ?></td>
<td><?php echo $v; ?></td>
<td><input class="input-small" type="number" name="add[<?php echo $k; ?>]" value="0" min="0"></td>
</tr>
<?php endforeach; ?>
</table>
<p><button class="btn" name="restock" type="submit">Papildyti sandėlį</button></p>
</form>
</div>


<div class="card">
<h2>Gauti užsakymai</h2>
<?php if(empty($orders)) echo '<p>Nėra gautų užsakymų.</p>'; else: continue ?>
<?php foreach ($orders as $o): ?>
<div class="card">
<strong>Laikas:</strong> <?php echo htmlspecialchars($o['time']); ?><br>
<strong>Vardas:</strong> <?php echo htmlspecialchars($o['customer']); ?>
(<em><?php echo htmlspecialchars($o['email']); ?></em>)<br>
<strong>Spalva:</strong> <?php echo htmlspecialchars($o['color']); ?><br>
<strong>Komponentai:</strong>
<ul>
<?php foreach ($o['items'] as $k=>$q) if((int)$q>0) echo '<li>' . $k . ' — ' . (int)$q . '</li>'; ?>
</ul>
</div>
<?php endforeach; ?>
<?php endif; ?>
</div>


</div>
</body>
</html>