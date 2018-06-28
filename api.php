<?php
/*
  API Backend versi 1.0
  API dibangun dengan PHP versi 7.0.30
  author: Muhammad Hervian
  
  File api.php berisi:
  1. manajemen file
  2. manipulasi data file
*/
// variabel constant
define('SEPARATOR', '/');
define('DATA_DIR', 'file' . SEPARATOR);
define('INSERT_DATA', '1');
define('READ_DATA', '2');
define('UPDATE_DATA', '3');
define('DELETE_DATA', '4');

// variabel global
$filename = null;
$statConn = null;
/*=============================== manajemen file =================================*/
// fungsi cek data file
function is_file_exists($file) {
    return (file_exists(DATA_DIR . $file))? true : false;
}

// fungsi koneksi ke file
function connect_file($file) {
    
    // cek adakah file yang dikoneksi 
    try {
        if (!is_file_exists($file)) {
            // lempar exception error
            throw new Exception('File "' . $file . '" tidak dapat dikoneksi 
            karena tidak ada atau salah nama file');
        }
    }
    catch (Exception $e) {
        // tampilkan error
        printf("<b>ERROR</b>: %s",$e->getMessage());
        return false;
    }
    
    // inisiasi variabel global
    $GLOBALS['filename'] = $file;
    $GLOBALS['statConn'] = true;
    
    return true;
}

// fungsi cek status koneksi
function check_connection() {
    return ($GLOBALS['statConn'] && $GLOBALS['filename'] !== "")? true : false; 
}

/*=========================== manipulasi data file ==============================*/
// fungsi membaca data di file
function read_data() {
    $data = array();
    $indeks = 0;
    
    // cek sudah terkoneksi dengan file atau belum
    try {
        if (!check_connection()) {
            throw new Exception('File belum dikoneksi');
        }
    } 
    catch (Exception $e) {
        printf("<b>ERROR</b>: %s",$e->getMessage());
        return false;
    }
    
    // membuka file
    $objFile = fopen(DATA_DIR . $GLOBALS['filename'], 'r') 
                or die('Unable to open file for read data.');
    // extract data perbaris dari file
    while (!feof($objFile)) {
        $strData = fgets($objFile); // baca baris string pada file
        $data[$indeks] = preg_replace('/\n/', '', $strData); // bersihkan special character \n dari string
        $indeks++;
    }
    
    // close the file
    fclose($objFile);
    
    return $data;
}

// fungsi menulis data ke file: Parameter(array)
function write_data($data = null) {
    $indeks = 0;
    // cek sudah terkoneksi dengan file atau belum
    try {
        if (!check_connection()) {
            throw new Exception('File belum dikoneksi');
        }
    } 
    catch (Exception $e) {
        printf("<b>ERROR</b>: %s",$e->getMessage());
        return false;
    }
    
    // cek apakah data kosong
    try {
        if (empty($data) || count($data) == 0) {
            throw new Exception('Data parameter kosong');
        }
    }
    catch (Exception $e) {
        printf("<b>ERROR</b>: %s", $e->getMessage());
        return false;
    }
    
    // membuka file
    $objFile = fopen(DATA_DIR . $GLOBALS['filename'], 'w') or die('Unable to open file for write data.');
    // menghitung jumlah data array yang masuk di parameter
    $length = count($data);
    
    // tulis data ke file
    while ($indeks < $length) {
        $str = $data[$indeks];
        if ($indeks !== $length - 1) 
            $str .= "\n";
        
        fwrite($objFile, $str); // write data to file
        
        $indeks++; // increment 1
    }
    
    fclose($objFile);
    
    return true;
}

// fungsi memfilter data dengan constraint : Parameter(array, array)
function filter_data($data = null, $constraint = null) {
    $indeks = 0;
    $hasilFilter = array();
    $sesuai = false;
    $regex = "";
    
    // cek sudah terkoneksi dengan file atau belum
    try {
        if (!check_connection()) {
            throw new Exception('File belum dikoneksi');
        }
    } 
    catch (Exception $e) {
        printf("<b>ERROR</b>: %s",$e->getMessage());
        return false;
    }
    
    try {
        if (empty($data) && empty($constraint)) {
            throw new Exception('Nilai parameter pada fungsi filter data tidak ada');
        }
    }
    catch (Exception $e) {
        printf("<b>ERROR</b>: %s", $e->getMessage());
        return false;
    }
    
    // buat regular expression
    while ($indeks < count($constraint)) {
        $regex .= $constraint[$indeks];
        if ($indeks < count($constraint) - 1)
            $regex .= "[\ a-zA-z\.\d\-]+";
        else
            $regex .= "";
        $indeks++;
    }
    $regex = "/" . $regex . "/";
    
    // melakukan match
    $indeks = 0;
    $no = 0;
    while ($indeks < count($data)) {
        if (preg_match($regex, $data[$indeks], $match)) {
            $hasilFilter[$no][0] = $data[$indeks];
            $hasilFilter[$no][1] = $indeks;
            $no++;
        }
        $indeks++;
    }
    
    return $hasilFilter;
    
}

// fungsi manipulasi/query data : Parameter(string, array, array)
function query_data($perintah = null, $data = null, $constraint = null) {
    try {
        if (empty($perintah)) {
            throw new Exception('Parameter perintah query kosong atau salah');
        }
    }
    catch (Exception $e) {
        printf("<b>ERROR</b>: %s", $e->getMessage());
        return false;
    }
    
    // melakukan switch berdasarkan nilai perintah
    switch ($perintah) {
        case INSERT_DATA:    
            // baca data
            $tmpData = read_data(); // output array 1 dimensi dengan nilai array string
            
            // cek data tanggal kosong
            if ($data[5] == "") {
                $data[5] = date("Y-m-d");
            }
            // string nama dengan karakter spasi diganti underscore
            $data[1] = explode(" ", $data[1]);
            $data[1] = implode("_", $data[1]);
            
            $data = implode(" ", $data);
            
            if (count($tmpData) == 0 || $tmpData[0] == "") {
                $tmpData[0] = $data; // insert data for the first time
            } else {
                $tmpData[count($tmpData)] = $data;
            }
            
            write_data($tmpData); // output data ke file
            
            break;
        case READ_DATA:
            $tmpData = read_data();
            
            // cek parameter constraint jika ada nilai
            if (!empty($constraint)) {
                // filter data berdasarkan constraint
                $dataBaru = filter_data($tmpData, $constraint); // output array 2 dimensi
                $indeks = 0;
                // ubah data array 2 dimensi menjadi 1 dimensi
                while($indeks < count($dataBaru)) {
                    $dataBaru[$indeks] = $dataBaru[$indeks][0]; 
                    $indeks++;
                }
                
                $tmpData = $dataBaru;
            }
            
            return $tmpData;
            
            break;
        case UPDATE_DATA:
            $tmpData = read_data();
            
            // cek data tanggal kosong
            if ($data[5] == "") {
                $data[5] = date("Y-m-d");
            }
            // string nama dengan karakter spasi diganti underscore
            $data[1] = explode(" ", $data[1]);
            $data[1] = implode("_", $data[1]);
            
            if (!empty($constraint)) {
                $hasilFilter = filter_data($tmpData, $constraint);
            }
            
            // ganti nilai lama dengan baru
            $hasilFilter[0][0] = implode(" ", $data);
            $noindeks = $hasilFilter[0][1];
            
            // update ke data 
            $tmpData[$noindeks] = $hasilFilter[0][0];
            write_data($tmpData);
            
            break;
        case DELETE_DATA:
            $dataBaru = array("");
            $x = 0;
            $noindeks = 0;
            $tmpData = read_data();
            
            if (!empty($constraint)) {
                $hasilFilter = filter_data($tmpData, $constraint); // output array 2 dimensi
            }
            
            // ambil nilai indeks dari hasil filter
            $nohapus = $hasilFilter[0][1];
            
            // isi ulang semua data array
            while ($x < count($tmpData)) {
                if ($x !== $nohapus) {
                    $dataBaru[$noindeks] = $tmpData[$x];
                    $noindeks++;
                }
                $x++;
            }
            $tmpData = $dataBaru;
            
            write_data($tmpData);
            
            break;
        default:
            return;
    }
}
?>