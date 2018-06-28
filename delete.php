<?php
require 'api.php';

$id = $_GET['id'];

// koneksi ke file
connect_file('data.txt');

// insert data
query_data(DELETE_DATA, null, array($id));

header('location:tabel.php');
?>