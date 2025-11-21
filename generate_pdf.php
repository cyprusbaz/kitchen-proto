<?php
require('fpdf/fpdf.php');
require('db.php');



if (!isset($_GET['id'])) {
    die("Nenurodytas užsakymo ID");
}

$order_id = intval($_GET['id']);

$query = "
    SELECT 
        u.id, u.data, u.isdestymas, u.spalva_spinteliu, u.spalva_stalvirsio, 
        u.buitine_technika, u.vizualizacija_paveikslelis,
        n.vardas, n.pavardė
    FROM Užsakymas u
    JOIN Naudotojas n ON u.naudotojas_id = n.id
    WHERE u.id = ?
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','',12);

$pdf->SetFont('Arial','B',14);
$pdf->Cell(0,10, iconv('UTF-8','ISO-8859-13','Virtuvės baldų užsakymo suvestinė'),0,1,'C');
$pdf->Ln(10);

$pdf->SetFont('Arial','',12);
$pdf->Cell(0,8,"Užsakymo ID: " . $order['id'],0,1);
$pdf->Cell(0,8,"Klientas: " . $order['vardas'] . " " . $order['pavardė'],0,1);
$pdf->Cell(0,8,"Data: " . $order['data'],0,1);
$pdf->Ln(5);

$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,8,"Pasirinkimai:",0,1);
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,8,"Išdėstymas: " . ucfirst($order['isdestymas']),0,1);
$pdf->Cell(0,8,"Spintelių spalva: " . $order['spalva_spinteliu'],0,1);
$pdf->Cell(0,8,"Stalviršio spalva: " . $order['spalva_stalvirsio'],0,1);
$pdf->Cell(0,8,"Buitinė technika: " . $order['buitine_technika'],0,1);
$pdf->Ln(10);


if (!empty($order['vizualizacija_paveikslelis']) && file_exists($order['vizualizacija_paveikslelis'])) {
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(0,8,"Virtuvės vizualizacija:",0,1);
    $pdf->Image($order['vizualizacija_paveikslelis'], 30, $pdf->GetY(), 150);
}

$pdf->Output('D', 'uzsakymas_' . $order_id . '.pdf');

?>
