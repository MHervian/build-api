<?php
require 'api.php';

$nama = $_POST['nama'];
$tglLahir = $_POST['tglLahir'];
$kota = $_POST['kota'];
$posisi = $_POST['posisi'];
$tglMasuk = $_POST['tglMasuk'];
$id = $_POST['id'];

// koneksi ke file
connect_file('data.txt');

$data = array($id, $nama, $tglLahir, $kota, $posisi, $tglMasuk);

// insert data
query_data(UPDATE_DATA, $data, array($id));

header('location:tabel.php');
?>