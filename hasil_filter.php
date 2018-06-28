<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Halaman Daftar Anggota</title>
    </head>
    <body>
        <a href="tabel.php">&larr; Ke halaman daftar anggota</a>
        <h2>Hasil Pencarian</h2>
        <hr>
        <table border="1">
            <tr>
                <th>No</th>
                <th>ID</th>
                <th>Nama</th>
                <th>Tanggal Lahir</th>
                <th>Kota Lahir</th>
                <th>Posisi</th>
                <th>Tanggal Join</th>
                <th>Aksi</th>
            </tr>
            <?php
            require 'api.php';
            
            connect_file('data.txt');
            $data = $_POST['cari'];
            $constraint = array();
            
            $x = 0;
            $no = 0;
            while ($x < count($data)) {
                if ($data[$x] !== "") {
                    $constraint[$no] = $data[$x];
                    $no++;
                } 
                $x++;
            }
            
            $data = query_data(READ_DATA, null, $constraint);
            // explode semua string dalam array
            $x = 0;
            while ($x < count($data)) {
                // pecahkan masing - masing string menjadi array dalam array
                $data[$x] = explode(" ", $data[$x]);
                ?>
            <tr>
                <td><?php echo $x + 1;?></td>
                <td><?php echo $data[$x][0];?></td>
                <td><?php echo $data[$x][1];?></td>
                <td><?php echo $data[$x][2];?></td>
                <td><?php echo $data[$x][3];?></td>
                <td><?php echo $data[$x][4];?></td>
                <td><?php echo $data[$x][5];?></td>
                <td>
                    <a href="edit.php?id=<?php echo $data[$x][0];?>">Edit</a>
                    <a href="delete.php?id=<?php echo $data[$x][0];?>">Delete</a>
                </td>
            </tr>
            <?php
                $x++;
            }
            ?>
        </table>
    </body>
</html>