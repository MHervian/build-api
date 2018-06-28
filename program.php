<?php
require 'api.php';

$nama = $_POST['nama'];
$tglLahir = $_POST['tglLahir'];
$kota = $_POST['kota'];
$posisi = $_POST['posisi'];
$tglMasuk = $_POST['tglMasuk'];
$id = "" . mt_rand(0,9999);

// koneksi ke file
connect_file('data.txt');

$data = array($id, $nama, $tglLahir, $kota, $posisi, $tglMasuk);

// insert data
query_data(INSERT_DATA, $data, null);

header('location:tabel.php');
?>