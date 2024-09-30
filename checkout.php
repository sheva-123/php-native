<?php
include "koneksi.php";
session_start();

// Mengambil data dari session keranjang
$cart = isset($_SESSION['shopping_cart']) ? $_SESSION['shopping_cart'] : array();

if (isset($_POST['checkout']) && !empty($cart)) {
    $NamaPelanggan = mysqli_real_escape_string($koneksi, $_POST['NamaPelanggan']);
    $Pembayaran = mysqli_real_escape_string($koneksi, $_POST['Pembayaran']);
    $TotalHarga = mysqli_real_escape_string($koneksi, $_POST['TotalHarga']);

    // Validasi jika pembayaran kurang dari total harga
    if ($Pembayaran < $TotalHarga) {
        echo "<script language='javascript'>
        alert('Pembayaran tidak boleh 0!');
        history.go(-1);
        </script>";
        exit;
    }

    // Cek stok terlebih dahulu
    foreach ($cart as $item) {
        $MakananId = isset($item['MakananId']) ? $item['MakananId'] : null;
        $MinumanId = isset($item['MinumanId']) ? $item['MinumanId'] : null;
        $Quantity = $item['Quantity'];

        // Validasi stok makanan
        if (!empty($MakananId)) {
            $query_cek_stok_makanan = "SELECT Stok FROM makanan WHERE MakananId = '$MakananId'";
            $result_stok_makanan = mysqli_query($koneksi, $query_cek_stok_makanan);
            $stok_makanan = mysqli_fetch_assoc($result_stok_makanan)['Stok'];

            if ($Quantity > $stok_makanan) {
                echo "<script language='javascript'>
                alert('Jumlah pesanan makanan melebihi stok yang tersedia!');document.location = 'keranjang.php';
                history.go(-1);
                </script>";
                exit;
            }
            // header('location:keranjang.php');

        }

        // Validasi stok minuman
        if (!empty($MinumanId)) {
            $query_cek_stok_minuman = "SELECT Stok FROM minuman WHERE MinumanId = '$MinumanId'";
            $result_stok_minuman = mysqli_query($koneksi, $query_cek_stok_minuman);
            $stok_minuman = mysqli_fetch_assoc($result_stok_minuman)['Stok'];

            if ($Quantity > $stok_minuman) {
                echo "<script language='javascript'>
                alert('Jumlah pesanan minuman melebihi stok yang tersedia!');document.location = 'keranjang.php';
                history.go(-1);
                </script>";
                exit;
            }
            // header('location:keranjang.php');
        }
    }

    // Simpan data pelanggan ke tabel `pelanggan`
    $query_pelanggan = "INSERT INTO pelanggan (NamaPelanggan) VALUES ('$NamaPelanggan')";
    if (mysqli_query($koneksi, $query_pelanggan)) {
        $PelangganId = mysqli_insert_id($koneksi);

        // Simpan data penjualan ke tabel `penjualan`
        $query_penjualan = "INSERT INTO penjualan (TanggalPenjualan, TotalHarga, Pembayaran, PelangganId) 
                            VALUES (NOW(), '$TotalHarga', '$Pembayaran', '$PelangganId')";

        if (mysqli_query($koneksi, $query_penjualan)) {
            $PenjualanId = mysqli_insert_id($koneksi);

            // Simpan detail transaksi ke tabel `detailpenjualan`
            foreach ($cart as $item) {
                $MakananId = isset($item['MakananId']) ? $item['MakananId'] : null;
                $MinumanId = isset($item['MinumanId']) ? $item['MinumanId'] : null;
                $Quantity = $item['Quantity'];
                $SubTotal = $item['Harga'] * $Quantity;

                // Simpan detail penjualan untuk Makanan
                if (!empty($MakananId)) {
                    $query_riwayat_detail_makanan = "INSERT INTO detailpenjualan (PenjualanId, MakananId, JumlahProduk, SubTotal) 
                                                     VALUES ('$PenjualanId', '$MakananId', '$Quantity', '$SubTotal')";
                    mysqli_query($koneksi, $query_riwayat_detail_makanan);

                    // Kurangi stok makanan
                    $queryUpdateStokMakanan = "UPDATE makanan SET Stok = Stok - $Quantity WHERE MakananId = '$MakananId'";
                    mysqli_query($koneksi, $queryUpdateStokMakanan);
                }

                // Simpan detail penjualan untuk Minuman
                if (!empty($MinumanId)) {
                    $query_riwayat_detail_minuman = "INSERT INTO detailpenjualan (PenjualanId, MinumanId, JumlahProduk, SubTotal) 
                                                     VALUES ('$PenjualanId', '$MinumanId', '$Quantity', '$SubTotal')";
                    mysqli_query($koneksi, $query_riwayat_detail_minuman);

                    // Kurangi stok minuman
                    $queryUpdateStokMinuman = "UPDATE minuman SET Stok = Stok - $Quantity WHERE MinumanId = '$MinumanId'";
                    mysqli_query($koneksi, $queryUpdateStokMinuman);
                }
            }

            // Hapus keranjang setelah checkout
            unset($_SESSION['shopping_cart']);

            // Alihkan ke halaman riwayat
            echo "<script>
                alert('Transaksi berhasil! Terima kasih telah berbelanja.');
                document.location = 'riwayat.php';
            </script>";
        } else {
            echo "Terjadi kesalahan dalam proses penjualan.";
        }
    } else {
        echo "Terjadi kesalahan dalam proses penyimpanan data pelanggan.";
    }
} else {
    echo "Keranjang belanja kosong atau proses checkout tidak valid.";
}
?>
