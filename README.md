# api.php
API perkembangan saya dengan inspirasi mysql php module
/*=============================
  API Backend version 1.0
  Built in PHP version 7.0.30
  Author       : MUHAMMAD HERVIAN
  Release Date : June 28, 2018
/*=============================

File api.php memiliki dua bagian:
1. Manajemen File => memiliki fungsi - fungsi untuk memanajemen file
   list fungsi:
    - is_file_exists($file : string)
	melakukan pengecekan file tersedia atau tidak
    
    - connect_file($file : string)
        koneksi ke file
    
    - check_connection()
        melakukan pengecekan status koneksi ke file

2. Manipulasi Data File => Berhubungan dengan manipulasi data di file
    list fungsi:
    - read_data() 
        membaca data dari file
    
    - write_data($data : array)
        menulis/output data ke file
    
    - filter_data($data : array, $constraint : array)
        memfilter data - data berdasarkan constraint
    
    - query_data($perintah : string, $data : array, $constraint : array)
        melakukan query data berdasarkan parameter pertama, $perintah, dengan nilai constant:
        + INSERT_DATA : Menulis/menambah data
        + READ_DATA : Membaca data
        + UPDATE_DATA : Mengubah data
        + DELETE_DATA : Menghapus data
