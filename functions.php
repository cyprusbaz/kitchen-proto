<?php
session_start();


// Inicijuojame sandelyje turimus komponentus (imitacija)
if (!isset($_SESSION['inventory'])) {
$_SESSION['inventory'] = [
'spintele1' => 10,
'spintele2' => 8,
'stalc1' => 12,
'stalc2' => 9,
'kampine' => 5
];
}


// Tas pats - statistika apie pasirinktus komponentus
if (!isset($_SESSION['stats'])) {
$_SESSION['stats'] = [
'spintele1' => 0,
'spintele2' => 0,
'stalc1' => 0,
'stalc2' => 0,
'kampine' => 0
];
}


// Imituotos baldų kainos už vienetą
$PRICES = [
'spintele1' => 120.0,
'spintele2' => 150.0,
'stalc1' => 80.0,
'stalc2' => 95.0,
'kampine' => 200.0
];


function get_inventory() {
return $_SESSION['inventory'];
}


function get_stats() {
return $_SESSION['stats'];
}


function get_prices() {
global $PRICES; return $PRICES;
}


function place_order($order) {
// order: ['items'=>['spintele1'=>2,...], 'color'=>'balta', 'customer'=>'Vardas', 'email'=>'...']
$inventory =& $_SESSION['inventory'];
$stats =& $_SESSION['stats'];
$errors = [];


foreach ($order['items'] as $key => $qty) {
$qty = (int)$qty;
if ($qty <= 0) continue;
if (!isset($inventory[$key]) || $inventory[$key] < $qty) {
$errors[] = "{$key} - nepakanka sandelyje (prašėte: {$qty}, turime: " . ($inventory[$key] ?? 0) . ")";
}
}


if (!empty($errors)) return ['ok'=>false,'errors'=>$errors];


// atnaujiname sandelyje likučius ir statistiką
foreach ($order['items'] as $key => $qty) {
$qty = (int)$qty;
if ($qty <= 0) continue;
$inventory[$key] -= $qty;
$stats[$key] += $qty;
}


// išsaugome "siuntimą" į sesiją kaip apsikeitimą su vadybininku
if (!isset($_SESSION['sent_orders'])) $_SESSION['sent_orders'] = [];
$_SESSION['sent_orders'][] = array_merge($order, ['time'=>date('c')]);


return ['ok'=>true];
}


function get_sent_orders() {
return $_SESSION['sent_orders'] ?? [];
}