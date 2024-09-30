<?php
include "koneksi.php";
session_start();

// cek penjualan
$penjualan_id = $_GET['idnya'];

// Persiapkan query SQL untuk mendapatkan data penjualan
$persiapan_query_penjualan = mysqli_prepare(
    $koneksi,
    "SELECT penjualan.*, pelanggan.NamaPelanggan 
    FROM penjualan 
    INNER JOIN pelanggan ON pelanggan.PelangganId = penjualan.PelangganId 
    WHERE penjualan.PenjualanId = ?"
);

if (!$persiapan_query_penjualan) {
    die("Error preparing query: " . mysqli_error($koneksi));
}

mysqli_stmt_bind_param($persiapan_query_penjualan, "i", $penjualan_id);
mysqli_stmt_execute($persiapan_query_penjualan);
$ambil_hasil_penjualan = mysqli_stmt_get_result($persiapan_query_penjualan);

// Jadikan array
$array_penjualan = mysqli_fetch_assoc($ambil_hasil_penjualan);

// Jika datanya tidak ditemukan
if (!$array_penjualan) {
?>
    <script>
        alert('Penjualan tidak ditemukan');
        document.location = "homekasir.php?pg=riwayatpenjualan";
    </script>
<?php
    exit;
}

// Query untuk mendapatkan detail produk (makanan dan minuman)
$persiapan_query_detail = mysqli_prepare(
    $koneksi,
    "SELECT detailpenjualan.*, 
            IFNULL(makanan.NamaProduk, minuman.NamaProduk) AS NamaProduk, 
            IFNULL(makanan.Harga, minuman.Harga) AS Harga 
     FROM detailpenjualan 
     LEFT JOIN makanan ON makanan.MakananId = detailpenjualan.MakananId 
     LEFT JOIN minuman ON minuman.MinumanId = detailpenjualan.MinumanId 
     WHERE PenjualanId = ?"
);

if (!$persiapan_query_detail) {
    die("Error preparing query: " . mysqli_error($koneksi));
}

mysqli_stmt_bind_param($persiapan_query_detail, "i", $penjualan_id);
mysqli_stmt_execute($persiapan_query_detail);
$ambil_hasil_detail = mysqli_stmt_get_result($persiapan_query_detail);

?>

<!DOCTYPE html>
<html>

<head>
    <title>FORM DATA DETAIL PENJUALAN</title>
    <style>
        body {
            min-height: 100vh;
            background-position: center;
            background-image: url(img/home.jpg);
            background-size: cover;
            background-repeat: no-repeat;
            padding-top: 6rem;
            color: white;
            text-align: center;
        }

        fieldset {
            margin: 1rem 0;
            border: 2px dashed rgb(108, 98, 98);
            display: inline-block;
            font-size: 20px;
            font-family: "Optima", 'Times New Roman', Times, serif;
            padding: 1em 2em;
        }

        legend {
            color: white;
            margin-bottom: 10px;
            padding: 0.5em 1em;
            background-color: black;
        }

        table {
            margin: 20px auto;
            border-collapse: collapse;
            background-color: rgba(0, 0, 0, 0.7);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.5);
            color: white;
        }

        table th,
        table td {
            padding: 15px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        table th {
            background-color: rgba(60, 60, 60, 0.9);
            text-transform: uppercase;
        }

        table tr:nth-child(even) {
            background-color: rgba(255, 255, 255, 0.1);
        }
    </style>
</head>

<body>
    <div align="center">
        <fieldset>
            <legend>DETAIL PRODUK</legend>
            <table border="1" cellspacing="0" cellpadding="10">
                <tr>
                    <td>
                        <div align="center"><strong>NO</strong></div>
                    </td>
                    <td>
                        <div align="center"><strong>NAMA PRODUK</strong></div>
                    </td>
                    <td>
                        <div align="center"><strong>JUMLAH</strong></div>
                    </td>
                    <td>
                        <div align="center"><strong>HARGA</strong></div>
                    </td>
                    <td>
                        <div align="center"><strong>TOTAL</strong></div>
                    </td>
                </tr>
                <?php
                $no = 1;
                while ($detail_produk = mysqli_fetch_assoc($ambil_hasil_detail)) {
                    $subtotal = $detail_produk['Harga'] * $detail_produk['JumlahProduk'];
                ?>
                    <tr>
                        <td>
                            <div align="center"><?php echo $no++; ?></div>
                        </td>
                        <td>
                            <div align="center"><?php echo $detail_produk['NamaProduk']; ?></div>
                        </td>
                        <td>
                            <div align="center"><?php echo $detail_produk['JumlahProduk']; ?></div>
                        </td>
                        <td>
                            <div align="center">Rp<?php echo number_format($detail_produk['Harga'], 0, ',', '.'); ?></div>
                        </td>
                        <td>
                            <div align="center">Rp<?php echo number_format($subtotal, 0, ',', '.'); ?></div>
                        </td>
                    </tr>
                <?php } ?>
                <tr>
                    <td colspan="4">
                        <div align="center"><strong>SUB TOTAL</strong></div>
                    </td>
                    <td>
                        <div align="center"><strong>Rp<?php echo number_format($array_penjualan['TotalHarga'], 0, ',', '.'); ?></strong></div>
                    </td>
                </tr>
            </table>
        </fieldset>

        <div align="center">
            <a href="riwayat.php?pg=riwayatpenjualan">
                <input type="button" value="KEMBALI">
            </a>
        </div>
    </div>
</body>

</html>