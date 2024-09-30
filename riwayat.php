<?php
include "koneksi.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $NamaPelanggan = $_POST['NamaPelanggan'];
    $Pembayaran = $_POST['Pembayaran'];
    $TotalHarga = $_POST['TotalHarga'];

    // Insert ke tabel `pelanggan`
    $query_pelanggan = "INSERT INTO pelanggan (NamaPelanggan) VALUES ('$NamaPelanggan')";
    mysqli_query($koneksi, $query_pelanggan);
    $PelangganId = mysqli_insert_id($koneksi);

    // Insert ke tabel `penjualan`
    $query_penjualan = "INSERT INTO penjualan (TanggalPenjualan, TotalHarga, Pembayaran, PelangganId) 
                        VALUES (NOW(), '$TotalHarga', '$Pembayaran', '$PelangganId')";
    mysqli_query($koneksi, $query_penjualan);
    $PenjualanId = mysqli_insert_id($koneksi);

    // Insert ke tabel `riwayat_detail`
    foreach ($_SESSION['shopping_cart'] as $item) {
        $MakananId = $item['MakananId'];
        $MinumanId = $item['MinumanId'];
        $Quantity = $item['Quantity'];
        $SubTotal = $item['Harga'] * $Quantity;

        $query_riwayat_detail = "INSERT INTO riwayat_detail (PenjualanId, MakananId, MinumanId, JumlahProduk, SubTotal) 
                                 VALUES ('$PenjualanId', '$MakananId', '$MinumanId', '$Quantity', '$SubTotal')";
        mysqli_query($koneksi, $query_riwayat_detail);
    }

    // Hapus keranjang setelah checkout
    unset($_SESSION['shopping_cart']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Penjualan</title>
    <link rel="stylesheet" href="riwayat.css">
    <style>
        /* CSS tetap sama */
        body {
            color: white;
        }

        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            text-decoration: none;
            scroll-behavior: smooth;
        }

        .navbar {
            display: flex;
            text-align: center;
            justify-content: space-between;
            background-color: rgb(1, 1, 1, 0.8);
            padding: 1.4rem;
            border-bottom: 2px solid white;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 9999;
        }

        .navbar h1 {
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            font-style: italic;
            font-weight: bold;
        }

        .navbar h1 span {
            color: #bc9667;
            font-style: italic;
            font-weight: bold;
        }

        .navbar .mndd {
            display: none;
            flex-direction: column;
            position: absolute;
            width: 5.4rem;
        }

        .navbar .mndd a:hover {
            background-color: #352006;
            color: #bc9667;
        }


        .services:hover .mndd {
            display: block;
            background-color: #352006;
            border-radius: 5px;
        }

        .navbar .mndd li {
            padding: 0 1.4rem;
            margin: 0.5rem -1rem;
            font-size: 13px;
        }

        .navbar ul li {
            display: inline-block;
            text-align: center;
        }

        .navbar ul li a {
            color: white;
            margin-right: 18px;
            font-weight: bold;
            padding: 6px;
        }

        .navbar ul li a:hover {
            transition: 0.3s;
            background-color: #bc9667;
            padding: 6px;
            border-radius: 3px;
        }

        #riwayat {
            min-height: 100vh;
            background-position: center;
            background-image: url(img/home.jpg);
            background-size: cover;
            background-repeat: no-repeat;
            padding-top: 6rem;
            color: white;
            text-align: center;
        }

        #riwayat fieldset {
            margin: 1rem 0;
            border: 2px dashed rgb(108, 98, 98);
            display: inline-block;
            font-size: 20px;
            font-family: "Optima", 'Times New Roman', Times, serif;
            padding: 1em 2em;
        }

        #riwayat legend {
            color: white;
            margin-bottom: 10px;
            padding: 0.5em 1em;
            background-color: black;
        }

        #riwayat table {
            margin: 20px auto;
            border-collapse: collapse;
            background-color: rgba(0, 0, 0, 0.7);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.5);
            color: white;
        }

        #riwayat table th,
        #riwayat table td {
            padding: 15px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        #riwayat table th {
            background-color: rgba(60, 60, 60, 0.9);
            text-transform: uppercase;
        }

        #riwayat table tr:nth-child(even) {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .pagination a {
            color: white;
            margin: 0 5px;
            padding: 5px 10px;
            background-color: rgba(255, 255, 255, 0.1);
            text-decoration: none;
            border-radius: 3px;
            transition: background-color 0.3s ease;
        }

        .pagination a:hover {
            background-color: rgba(0, 0, 0, 0.7);
            color: white;
        }

        .pagination a.active {
            background-color: rgba(0, 0, 0, 0.7);
            color: white;
            pointer-events: none;
        }
    </style>
</head>

<body>
    <div align="center">
        <nav class="navbar">
            <h1>Kenangan<span>mantan.</span></h1>
            <ul>
                <li><a href="homekasir.php">Home</a></li>
                <li><a href="homekasir.php?#about">Tentang Kami</a></li>
                <li><a href="homekasir.php?#menu">Produk</a></li>
                <li><a href="#">Riwayat</a></li>
            </ul>
        </nav>
        <section id="riwayat">
            <fieldset>
                <legend>DATA RIWAYAT PENJUALAN</legend>
                <table border="1" cellspacing="0" cellpadding="10">
                    <tr>
                        <td><div align="center"><strong>NO</strong></div></td>
                        <td><div align="center"><strong>TANGGAL PENJUALAN</strong></div></td>
                        <td><div align="center"><strong>PELANGGAN</strong></div></td>
                        <td><div align="center"><strong>TOTAL HARGA</strong></div></td>
                        <td><div align="center"><strong>PEMBAYARAN</strong></div></td>
                        <td><div align="center"><strong>KEMBALIAN</strong></div></td>
                        <td><div align="center"><strong>TINDAKAN</strong></div></td>
                    </tr>

                    <?php
                    $page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;
                    $limit = 3;
                    $limitStart = ($page - 1) * $limit;

                    $ambil = mysqli_query($koneksi, "SELECT * FROM penjualan
                        LEFT JOIN pelanggan ON pelanggan.PelangganId = penjualan.PelangganId 
                        LIMIT $limitStart, $limit");
                    $no = $limitStart + 1;
                    while ($array = mysqli_fetch_array($ambil, MYSQLI_ASSOC)) {
                    ?>
                        <tr>
                            <td><div align="center"><?php echo $no++; ?></div></td>
                            <td><div align="center"><?php echo $array['TanggalPenjualan']; ?></div></td>
                            <td><div align="center"><?php echo $array['NamaPelanggan']; ?></div></td>
                            <td><div align="center"><?php echo "Rp" . $array['TotalHarga']; ?></div></td>
                            <td><div align="center"><?php echo "Rp" . $array['Pembayaran']; ?></div></td>
                            <td><div align="center"><?php echo "Rp" . $array['Pembayaran'] - $array['TotalHarga']; ?></div></td>
                            <td><div align="center"><a href="detailpenjualan.php?idnya=<?php echo $array['PenjualanId']; ?>">DETAIL</a></div></td>
                        </tr>
                    <?php
                    }
                    ?>

                </table>

                <!-- Pagination -->
                <?php
                $SqlQuery = mysqli_query($koneksi, "SELECT COUNT(*) AS jumlah FROM penjualan");
                $JumlahData = mysqli_fetch_array($SqlQuery)['jumlah'];
                $jumlahPage = ceil($JumlahData / $limit);

                echo '<div class="pagination">';

                // Link Prev
                if ($page > 1) {
                    $prevPage = $page - 1;
                    echo '<a href="riwayat.php?page=' . $prevPage . '" class="paging">Back</a>';
                }

                // Link Number
                for ($i = 1; $i <= $jumlahPage; $i++) {
                    if ($i == $page) {
                        echo '<a class="active">' . $i . '</a>';
                    } else {
                        echo '<a href="riwayat.php?page=' . $i . '">' . $i . '</a>';
                    }
                }

                // Link Next
                if ($page < $jumlahPage) {
                    $nextPage = $page + 1;
                    echo '<a href="riwayat.php?page=' . $nextPage . '" class="paging">Next</a>';
                }

                echo '</div>';
                ?>

            </fieldset>
        </section>
    </div>
</body>

</html>
