<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Halaman Pendaftaran Anggota Komunitas</title>
    </head>
    <body>
        <?php
        require 'api.php';
        
        connect_file('data.txt');
        
        // get data by id
        $data = query_data(READ_DATA, null, array($_GET['id']));
        $data[0] = explode(" ", $data[0]);
        
        ?>
        <h2>Halaman Edit Anggota</h2>
        <a href="tabel.php">&larr; Balik ke halaman Daftar Anggota</a> <hr>
        <form method="post" action="proses_edit.php">
            <input type="hidden" name="id" value="<?php echo $data[0][0];?>">
            <label>Nama: <input type="text" name="nama" value="<?php echo implode(" ", explode("_",$data[0][1]));?>"></label><br>
            <label>Tanggal Lahir: <input type="date" name="tglLahir" value="<?php echo $data[0][2];?>" required></label><br>
            <label>Kota Lahir: <input type="text" name="kota" value="<?php echo $data[0][3];?>"></label><br>
            <label>Posisi:
                <select name="posisi">
                    <option>Ketua</option>
                    <option>Pengurus</option>
                    <option>Anggota</option>
                </select>
            </label><br>
            <label>Tanggal Daftar: <input type="date" name="tglMasuk" value="<?php echo $data[0][5];?>"></label><br>
            <input type="submit" value="Update">
        </form>
    </body>
</html>