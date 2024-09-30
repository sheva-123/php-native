<?php
include 'koneksi.php';
$id = $_POST["MinumanId"];
$minuman = $_POST["minuman"];
$harga = $_POST["harga"];
$stok = $_POST["stok"];
$persiapan_query = mysqli_prepare(
    $koneksi,
    "UPDATE minuman SET NamaProduk=?, Harga=?,
Stok=? WHERE MinumanId=?"
);
mysqli_stmt_bind_param(
    $persiapan_query,
    "sdii",
    $minuman,
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
header('location:dataminuman.php');
?>