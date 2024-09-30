<?php
include "koneksi.php";
session_start();

// Jika keranjang kosong, redirect ke homekasir.php
if (empty($_SESSION['shopping_cart'])) {
    header("Location: homekasir.php");
    exit();
}

// Menghapus produk dari keranjang
if (isset($_POST['remove_item'])) {
    $MakananId = $_POST['MakananId'];
    $MinumanId = $_POST['MinumanId'];

    foreach ($_SESSION['shopping_cart'] as $key => $item) {
        if ($item['MakananId'] == $MakananId && $item['MinumanId'] == $MinumanId) {
            unset($_SESSION['shopping_cart'][$key]);
            break;
        }
    }
    $_SESSION['shopping_cart'] = array_values($_SESSION['shopping_cart']); // Reset array index setelah penghapusan
}

// Mengubah jumlah produk
if (isset($_POST['update_quantity'])) {
    $MakananId = $_POST['MakananId'];
    $MinumanId = $_POST['MinumanId'];
    $Quantity = $_POST['Quantity'];

    foreach ($_SESSION['shopping_cart'] as $key => $item) {
        if ($item['MakananId'] == $MakananId && $item['MinumanId'] == $MinumanId) {
            $_SESSION['shopping_cart'][$key]['Quantity'] = $Quantity;
            break;
        }
    }
}

// Menghitung total jumlah item di keranjang
$total_items = 0;
$total_belanja = 0;
if (isset($_SESSION['shopping_cart'])) {
    foreach ($_SESSION['shopping_cart'] as $item) {
        $total_items += $item['Quantity'];
        $total_belanja += $item['Harga'] * $item['Quantity'];
    }
}
$_SESSION['total_items'] = $total_items;
?>


<!DOCTYPE html>
<html>

<head>
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="css/keranjang.css">
    <style>
        /* conten1 */

        #shopp {
            min-height: 100vh;
            background-position: center;
            background-image: url(img/home.jpg);
            background-size: cover;
            background-repeat: no-repeat;
            display: flex;
            align-items: center;
            color: white;
        }

        #shopp table {
            width: 100%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: rgba(0, 0, 0, 0.7);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.5);
            color: white;
        }

        #shopp table th,
        #shopp table td {
            padding: 15px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        #shopp input[type="text"],
        #shopp input[type="number"] {
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 10px;
            margin-top: 4px;
            color: black;
            width: 100%;
            max-width: 300px;
        }

        #shopp button[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        #shopp button[type="submit"]:hover {
            background-color: #45a049;
        }

        #shopp .checkout-button {
            margin-left: 0.1rem;
            max-width: 300px;
            margin-bottom: 2rem;
        }

        #shopp label {
            margin-right: 12rem;
            font-weight: bold;
        }

        .radio {
            margin-left: 10rem;
            margin-top: -1rem;
        }

        .radio .w {
            margin-left: 1.3rem;
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar">
        <h1>Kenangan<span>mantan.</span></h1>
        <ul>
            <li><a href="homekasir.php">Home</a></li>
            <li><a href="homekasir.php?#about">Tentang Kami</a></li>
            <li><a href="homekasir.php?#menu">Produk</a></li>
            <li><a href="riwayat.php">Riwayat</a></li>
        </ul>
    </nav>

    <!-- conten1 -->
    <section id="shopp">
        <h2 class="sopp">Your Shopping Cart</h2>

        <?php if (!empty($_SESSION['shopping_cart'])) { ?>
            <form method="POST" action="checkout.php">
                <table>
                    <thead>
                        <tr>
                            <th>Nama Produk</th>
                            <th>Harga</th>
                            <th>Quantity</th>
                            <th>Total Harga</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($_SESSION['shopping_cart'] as $item) {
                            $total_harga = $item['Harga'] * $item['Quantity'];
                        ?>
                            <tr>
                                <td><?php echo $item['NamaProduk']; ?></td>
                                <td>Rp<?php echo $item['Harga']; ?></td>
                                <td>
                                    <form method="POST" action="keranjang.php" style="display:inline-block;">
                                        <input type="hidden" name="MakananId" value="<?php echo $item['MakananId']; ?>">
                                        <input type="hidden" name="MinumanId" value="<?php echo $item['MinumanId']; ?>">
                                        <input type="number" name="Quantity" value="<?php echo $item['Quantity']; ?>" min="1">
                                        <button type="submit" name="update_quantity">Update</button>
                                    </form>
                                </td>
                                <td>Rp<?php echo $total_harga; ?></td>
                                <td>
                                    <form method="POST" action="keranjang.php" style="display:inline-block;">
                                        <input type="hidden" name="MakananId" value="<?php echo $item['MakananId']; ?>">
                                        <input type="hidden" name="MinumanId" value="<?php echo $item['MinumanId']; ?>">
                                        <button type="submit" name="remove_item">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <td colspan="3"><strong>Total Belanja</strong></td>
                            <td><strong>Rp<?php echo $total_belanja; ?></strong></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>

                <!-- Tambahkan input hidden untuk mengirimkan total belanja -->
                <input type="hidden" name="TotalHarga" value="<?php echo $total_belanja; ?>">

                <div>
                    <label for="NamaPelanggan">Nama Pembeli:</label>
                </div>
                <div>
                    <input type="text" name="NamaPelanggan" id="NamaPelanggan" required>
                </div>
                <div>
                    <label for="Pembayaran">Uang Pembeli:</label>
                </div>
                <div>
                    <input type="text" name="Pembayaran" value="">
                </div>
                <button type="submit" name="checkout" class="checkout-button">Checkout</button>
            </form>

        <?php } else { ?>
            <p>Keranjang Anda kosong.</p>
        <?php } ?>
    </section>
</body>

</html>