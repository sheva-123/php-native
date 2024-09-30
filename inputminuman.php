<?php
include 'koneksi.php';
$minuman = $_POST['minuman'];
$harga = $_POST['harga'];
$stok = $_POST['stok'];
$foto = $_POST['foto'];

// Validasi agar stok lebih dari 0
if ($stok <= 0) {
?>
    <script language="javascript">
        alert("Stok harus lebih dari 0!");
        history.go(-1);
    </script>
<?php
    exit();
}
header('location:dataminuman.php');

$persiapan_query = mysqli_prepare(
    $koneksi,
    "INSERT INTO minuman(NamaProduk,Harga,Stok,foto_produk)
     VALUES(?, ?, ?, ?) "
);
mysqli_stmt_bind_param(
    $persiapan_query,
    "sdis",
    $minuman,
    $harga,
    $stok,
    $foto
);
$eksekusi_query = mysqli_stmt_execute($persiapan_query);

if ($eksekusi_query == false) {
?>
    <script language="javascript">
        alert("<?php echo mysqli_stmt_error($persiapan_query); ?>");
        history.go(-1)
    </script>
<?php
    exit();
}
header('location:homekasir.php?pg=#menu');
?>