<?php
include "koneksi.php";
?>
<html>

<head>
    <title>FORM EDIT PRODUK</title>
    <link href="css/dataproduk.css" rel="stylesheet" type="text/css" />
</head>

<body>
    <?php
    $makanan = $_GET['idnya'];
    $persiapan_query = mysqli_prepare(
        $koneksi,
        "SELECT * FROM minuman WHERE MinumanId=?"
    );
    mysqli_stmt_bind_param($persiapan_query, "i", $makanan);
    $eksekusi_query = mysqli_stmt_execute($persiapan_query);
    if ($eksekusi_query == false) {
    ?>
        <script language="javascrript">
            alert("<?php echo mysqli_stmt_error($persiapan_query); ?>");
            history.go(-1)
        </script>
    <?php
        exit();
    }
    $ambil = mysqli_stmt_get_result($persiapan_query);
    $array = mysqli_fetch_array($ambil, MYSQLI_ASSOC);
    ?>
    <div align="center">
        <fieldset class="iptprd">
            <legend>EDIT DATA PRODUK</legend>
            <form action="editminuman.php" method="post"
                name="form1" id="form1">
                <table>
                    <tr>
                        <td>NAMA PRODUK</td>
                        <td>
                            <input type="hidden" value="<?php echo "$_GET[idnya]" ?>"
                                name="MinumanId">
                            <input type="text" name="minuman" id="minuman"
                                value="<?php echo "$array[NamaProduk]" ?>">
                        </td>
                    </tr>
                    <tr>
                        <td>HARGA</td>
                        <td><input type="text" name="harga" id="harga"
                                value="<?php echo "$array[Harga]" ?>"></td>
                    </tr>
                    <tr>
                        <td>STOK</td>
                        <td><input type="text" name="stok" id="stok"
                                value="<?php echo "$array[Stok]" ?>"></td>
                    </tr>
                    <tr>
                        <td colspan="2" align="right">
                            <input type="submit" value="UPDATE" onclick="return confirm('Apakah yakin data akan diubdate?')">
                        </td>
                    </tr>
                </table>
            </form>
        </fieldset>
    </div>
    <p>
    <div align="center">
        <a href="dataminuman.php">
            <input type="submit" value="KEMBALI"></a>
    </div>
</body>

</html>