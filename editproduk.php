<?php
include 'koneksi.php';
$id = $_POST["MakananId"];
$makanan = $_POST["makanan"];
$harga = $_POST["harga"];
$stok = $_POST["stok"];
$persiapan_query = mysqli_prepare(
    $koneksi,
    "UPDATE makanan SET NamaProduk=?, Harga=?,
Stok=? WHERE MakananId=?"
);
mysqli_stmt_bind_param(
    $persiapan_query,
    "sdii",
    $makanan,
    $harga,
    $stok,
    $id
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
header('location:dataproduk.php');
?>