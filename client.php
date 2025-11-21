<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$detales = [
    'Spintelė' => ['kaina' => 120, 'kiekis' => 1],
    'Stalviršis' => ['kaina' => 80, 'kiekis' => 1],
    'Šaldytuvas' => ['kaina' => 500, 'kiekis' => 1],
    'Orkaitė' => ['kaina' => 400, 'kiekis' => 1],
    'Kaitlentė' => ['kaina' => 200, 'kiekis' => 1],
    'Indaplovė' => ['kaina' => 300, 'kiekis' => 1],
];

$total = 0.0;
$success = '';
$order_id = null;


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_order'])) {
    $user_id = intval($_SESSION['user_id']);


    $isdestymas = isset($_POST['isdestymas']) ? trim($_POST['isdestymas']) : '';
    $spalva_spinteliu = isset($_POST['spalva_spinteliu']) ? trim($_POST['spalva_spinteliu']) : '#ffffff';
    $spalva_stalvirsio = isset($_POST['spalva_stalvirsio']) ? trim($_POST['spalva_stalvirsio']) : '#cccccc';
    $buitine = isset($_POST['buitine_technika']) ? (array)$_POST['buitine_technika'] : [];

    $total = 0.0;
    if (isset($detales['Spintelė'])) $total += floatval($detales['Spintelė']['kaina']);
    if (isset($detales['Stalviršis'])) $total += floatval($detales['Stalviršis']['kaina']);

    foreach ($buitine as $item) {
        if (isset($detales[$item])) {
            $total += floatval($detales[$item]['kaina']);
        }
    }

    $buitine_str = !empty($buitine) ? implode(', ', $buitine) : '';

    $vizualizacija_path = null;
    if (!empty($_POST['vizualizacija_data'])) {
        $img_data = str_replace('data:image/png;base64,', '', $_POST['vizualizacija_data']);
        $img_data = str_replace(' ', '+', $img_data);
        $img = base64_decode($img_data);
        if (!is_dir('uploads')) mkdir('uploads', 0777, true);
        $file_name = 'uploads/viz_' . time() . '.png';
        file_put_contents($file_name, $img);
        $vizualizacija_path = $file_name;
    }


    $stmt = $conn->prepare("INSERT INTO Užsakymas 
        (data, būsena_id, naudotojas_id, isdestymas, spalva_spinteliu, spalva_stalvirsio, buitine_technika, vizualizacija_paveikslelis, kaina)
        VALUES (NOW(), 1, ?, ?, ?, ?, ?, ?, ?)");

    if (!$stmt) {
        die("Prepare klaida: " . $conn->error);
    }


$stmt->bind_param(
    "isssssd",
    $user_id,
    $isdestymas,
    $spalva_spinteliu,
    $spalva_stalvirsio,
    $buitine_str,
    $vizualizacija_path,
    $total
);

    $stmt->close();


    $stmt = $conn->prepare("INSERT INTO Užsakymas 
        (data, būsena_id, naudotojas_id, isdestymas, spalva_spinteliu, spalva_stalvirsio, buitine_technika, vizualizacija_paveikslelis, kaina)
        VALUES (NOW(), 1, ?, ?, ?, ?, ?, ?, ?)");

    if (!$stmt) {
        die("Prepare klaida (2): " . $conn->error);
    }

    $stmt->bind_param(
        "isssssd",
        $user_id,
        $isdestymas,
        $spalva_spinteliu,
        $spalva_stalvirsio,
        $buitine_str,
        $vizualizacija_path,
        $total
    );

    if (!$stmt->execute()) {

        die("Execute klaida: " . $stmt->error);
    }

    $order_id = $stmt->insert_id;
    $stmt->close();

    $success = "Užsakymas pateiktas sėkmingai! Bendra suma: €" . number_format($total, 2);
}
?>

<!DOCTYPE html>
<html lang="lt">
<head>
<meta charset="UTF-8">
<title>Virtuvės baldų užsakymas</title>
<style>

body {
    font-family: 'Segoe UI', sans-serif;
    margin: 0; padding:0;
    min-height:100vh;
    display:flex; flex-direction:column;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: #f5f5f5;
}
header { text-align:center; padding:30px 0; background:rgba(255,255,255,0.05); backdrop-filter:blur(10px); box-shadow:0 4px 15px rgba(0,0,0,0.3);}
nav { display:flex; justify-content:center; align-items:center; gap:20px; background:rgba(255,255,255,0.1); padding:10px;}
nav a, nav .btn { display:inline-flex; justify-content:center; align-items:center; padding:12px 25px; border-radius:10px; text-decoration:none; font-size:16px; font-weight:500; transition:0.3s; color:#fff; background:#e63946;}
nav a.btn:hover { background:#c1121f;}
nav a:not(.btn){ background:transparent; color:#f5f5f5;}
nav a:not(.btn):hover{ color:#ffd700;}

.container { background: rgba(255,255,255,0.1); backdrop-filter: blur(15px); border-radius: 20px; padding:40px; width:90%; max-width:800px; margin:30px auto; box-shadow:0 8px 30px rgba(0,0,0,0.3); color:#f5f5f5; text-align:center;}
h1,h2{color:#fff;}
input, select, button {padding:8px; margin:8px 0; width:100%; border-radius:10px;}
button, .btn {background:#ffb400; color:#2b2b2b; border:none; padding:12px 25px; font-size:16px; cursor:pointer; margin:5px;}
button:hover, .btn:hover {background:#ffa500;}
canvas { border:2px solid #fff; margin-top:15px;}
.success {background:#c8e6c9; padding:10px; border-radius:10px; margin-bottom:15px; text-align:center; color:#2b2b2b;}
table { margin:auto; color:#2b2b2b; background:#fff; border-collapse:collapse; width:80%;}
table th, table td { padding:8px; border:1px solid #ccc; text-align:center;}
.download-btn {
  display: inline-block;
  padding: 10px 18px;
  background-color: #deb887; 
  color: white;
  text-decoration: none;
  border-radius: 8px;
  font-weight: bold;
  margin-top: 20px; 
  transition: background-color 0.3s ease;
}

.download-btn:hover {
  background-color: #c9a06a; 
}
</style>
</head>
<body>

<header><h1>Virtuvės baldų užsakymas</h1></header>

<nav>
    <a href="index.php">Pradžia</a>
    <a href="client_orders.php">Mano užsakymai</a>
    <a href="logout.php" class="btn">Atsijungti (<?= htmlspecialchars($_SESSION['vardas']) ?>)</a>
</nav>

<main>
<div class="container">
<h2>Pateikite užsakymą</h2>

<?php if($success) echo "<div class='success'>$success</div>"; ?>

<form method="POST" id="orderForm">
    <label>Išdėstymas:</label><br>
    <label><input type="radio" name="isdestymas" value="kampu" required> Kampu</label>
    <label><input type="radio" name="isdestymas" value="linijoje"> Linijoje</label><br><br>

    <label>Spintelių spalva:</label>
    <select name="spalva_spinteliu">
        <option value="#f5deb3">Šviesiai ruda</option>
        <option value="#deb887">Vidutinė ruda</option>
        <option value="#8b4513">Tamsiai ruda</option>
        <option value="#ffffff">Balta</option>
        <option value="#000000">Juoda</option>
    </select><br>

    <label>Stalviršio spalva:</label>
    <select name="spalva_stalvirsio">
        <option value="#f5deb3">Šviesiai ruda</option>
        <option value="#deb887">Vidutinė ruda</option>
        <option value="#8b4513">Tamsiai ruda</option>
        <option value="#ffffff">Balta</option>
        <option value="#000000">Juoda</option>
    </select><br>

    <label>Buitinė technika:</label><br>
    <?php foreach(['Šaldytuvas','Orkaitė','Kaitlentė','Indaplovė'] as $tech): ?>
        <label><input type="checkbox" name="buitine_technika[]" value="<?= $tech ?>"><?= $tech ?></label><br>
    <?php endforeach; ?>

    <button type="button" class="btn" onclick="generateVisualization()">Peržiūrėti vizualizaciją</button><br>
    <canvas id="vizualizacija" width="500" height="300"></canvas>
    <input type="hidden" name="vizualizacija_data" id="vizualizacija_data"><br>

    <button type="submit" class="btn" name="submit_order">Pateikti užsakymą</button>
</form>

<?php if(!empty($order_id)): ?>
    <a href="generate_pdf.php?id=<?= $order_id ?>" class="download-btn" target="_blank">Atsisiųsti PDF</a>
<?php endif; ?>

<h3>Detalių kiekiai ir kainos</h3>
<table border="1" cellpadding="8">
    <tr>
        <th>Detalė</th><th>Kiekis</th><th>Kaina (€)</th>
    </tr>
    <?php foreach($detales as $det => $info): ?>
    <tr>
        <td><?= htmlspecialchars($det) ?></td>
        <td><?= $info['kiekis'] ?></td>
        <td><?= number_format($info['kaina'],2) ?></td>
    </tr>
    <?php endforeach; ?>
    <tr>
        <td><b>Bendra suma</b></td>
        <td></td>
        <td><b>€<?= number_format($total,2) ?></b></td>
    </tr>
</table>

</div>
</main>

<footer>&copy; <?= date("Y") ?> Virtuvės baldų užsakymo sistema</footer>

<script>
function generateVisualization() {
    const canvas = document.getElementById('vizualizacija');
    const ctx = canvas.getContext('2d');
    const spalvaSpinteliu = document.querySelector('select[name="spalva_spinteliu"]').value;
    const spalvaStalvirsio = document.querySelector('select[name="spalva_stalvirsio"]').value;
    const radio = document.querySelector('input[name="isdestymas"]:checked');
    const isdestymas = radio ? radio.value : 'linijoje';

    ctx.clearRect(0,0,canvas.width,canvas.height);

    ctx.fillStyle = spalvaSpinteliu;
    if(isdestymas==='linijoje') {
        ctx.fillRect(50,150,400,80);
        ctx.fillStyle = spalvaStalvirsio;
        ctx.fillRect(50,140,400,10);
    } else {
        ctx.fillRect(50,150,250,80);
        ctx.fillRect(50,150,80,150);
        ctx.fillStyle = spalvaStalvirsio;
        ctx.fillRect(50,140,250,10);
        ctx.fillRect(50,140,10,150);
    }

    ctx.fillStyle = "#666";
    const technika = Array.from(document.querySelectorAll('input[name="buitine_technika[]"]:checked')).map(x=>x.value);
    if(technika.includes("Šaldytuvas")) ctx.fillRect(420,100,50,100);
    if(technika.includes("Orkaitė")) ctx.fillRect(150,180,40,40);
    if(technika.includes("Kaitlentė")) ctx.fillRect(210,140,60,20);
    if(technika.includes("Indaplovė")) ctx.fillRect(280,180,40,40);

    document.getElementById('vizualizacija_data').value = canvas.toDataURL('image/png');
}
</script>
</body>
</html>
