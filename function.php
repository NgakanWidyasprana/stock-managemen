<?php
session_start();

// Membuat Koneksi ke Database
//$conn = mysqli_connect("localhost","root","","stockbarang");
//Get Heroku ClearDB connection information
$cleardb_url = parse_url(getenv("CLEARDB_DATABASE_URL"));
$cleardb_server = $cleardb_url["host"];
$cleardb_username = $cleardb_url["user"];
$cleardb_password = $cleardb_url["pass"];
$cleardb_db = substr($cleardb_url["path"],1);
$active_group = 'default';
$query_builder = TRUE;
// Connect to DB
$conn = mysqli_connect($cleardb_server, $cleardb_username, $cleardb_password, $cleardb_db);

// Menambah Stock
if(isset($_POST['addnewbarang'])){
    $namabarang = $_POST['namabarang'];
    $deskripsi = $_POST['deskripsi'];
    $stock = $_POST['stock'];

    // Menambahkan
    $addtotable = mysqli_query($conn,"insert into stock (namabarang, deskripsi, stock) values('$namabarang','$deskripsi','$stock')");
    
    // Cek
    if($addtotable){
        header('location:index.php');
    }
};

// Menambah Barang Masuk
if(isset($_POST['barangmasuk'])){
    $barangnya = $_POST['barangnya'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];

    // Stock Lama
    $cekstocksekarang = mysqli_query($conn,"select * from stock where idbarang='$barangnya'");
    $ambildatanya = mysqli_fetch_array($cekstocksekarang);

    // Stock Baru
    $stocksekarang = $ambildatanya['stock'];
    $updatestocksekarang = $stocksekarang + $qty;

    $addtomasuk = mysqli_query($conn,"insert into masuk (idbarang, keterangan, qty) values('$barangnya','$penerima','$qty')");
    $updatestockmasuk = mysqli_query($conn,"update stock set stock='$updatestocksekarang' where idbarang='$barangnya'");
    
    // Cek
    if($addtomasuk&&$updatestockmasuk){
        header('location:masuk.php');
    }
}

// Menambah Barang Keluar
if(isset($_POST['barangkeluar'])){
    $barangnya = $_POST['barangnya'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];

    // Stock Lama
    $cekstocksekarang = mysqli_query($conn,"select * from stock where idbarang='$barangnya'");
    $ambildatanya = mysqli_fetch_array($cekstocksekarang);

    // Stock Baru
    $stocksekarang = $ambildatanya['stock'];
    $updatestocksekarang = $stocksekarang - $qty;

    $addtokeluar = mysqli_query($conn,"insert into keluar (idbarang, penerima, qty) values('$barangnya','$penerima','$qty')");
    $updatestockmasuk = mysqli_query($conn,"update stock set stock='$updatestocksekarang' where idbarang='$barangnya'");
    
    // Cek
    if($addtomasuk&&$updatestockmasuk){
        header('location:keluar.php');
    }
}
?>
