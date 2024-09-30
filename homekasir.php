<?php
session_start();
include "koneksi.php";

// Menghitung total jumlah item di keranjang
$total_items = 0;
if (isset($_SESSION['shopping_cart'])) {
  foreach ($_SESSION['shopping_cart'] as $item) {
    $total_items += $item['Quantity'];
  }
}
$_SESSION['total_items'] = $total_items;

// Menambahkan produk makanan atau minuman ke shopping cart
if (isset($_POST['add_to_cart'])) {
  $MakananId = isset($_POST['MakananId']) ? $_POST['MakananId'] : null;
  $MinumanId = isset($_POST['MinumanId']) ? $_POST['MinumanId'] : null;
  $NamaProduk = $_POST['NamaProduk'];
  $Harga = $_POST['Harga'];

  $cart_item = array(
    'MakananId' => $MakananId,
    'MinumanId' => $MinumanId,
    'NamaProduk' => $NamaProduk,
    'Harga' => $Harga,
    'Quantity' => 1
  );

  // Jika keranjang sudah ada di session
  if (isset($_SESSION['shopping_cart'])) {
    $cart = $_SESSION['shopping_cart'];

    // Cek apakah produk makanan atau minuman sudah ada di keranjang
    $found = false;
    foreach ($cart as $key => $item) {
      if ($item['MakananId'] == $MakananId && $item['MinumanId'] == $MinumanId) {
        $_SESSION['shopping_cart'][$key]['Quantity']++; // Perbarui jumlah produk
        $found = true;
        break;
      }
    }

    // Jika produk belum ada, tambahkan ke keranjang
    if (!$found) {
      $_SESSION['shopping_cart'][] = $cart_item;
    }
  } else {
    // Jika keranjang belum ada, buat baru
    $_SESSION['shopping_cart'] = array($cart_item);
  }
}

// Fungsi untuk mencari produk
$search_query = "";
if (isset($_POST['search'])) {
  $search_query = $_POST['search_query'];
}

// Query untuk menampilkan produk makanan dan minuman berdasarkan pencarian
$query_makanan = "SELECT * FROM makanan WHERE NamaProduk LIKE '%$search_query%'";
$query_minuman = "SELECT * FROM minuman WHERE NamaProduk LIKE '%$search_query%'";

// Ambil hasil dari tabel makanan dan minuman
$ambil_makanan = mysqli_query($koneksi, $query_makanan);
$ambil_minuman = mysqli_query($koneksi, $query_minuman);
?>




<!DOCTYPE html>
<html>

<head>
  <title>Home Kasir</title>
  <script src="https://unpkg.com/feather-icons"></script>
  <link href="css/home.css" rel="stylesheet" type="text/css" />
  <style>

    .cart-count {
      position: absolute;
      top: -10px;
      right: -10px;
      background-color: red;
      color: white;
      border-radius: 50%;
      padding: 5px;
      font-size: 12px;
    }

    .tentang .menu-card:hover {
      cursor: pointer;
      background-color: white;
      width: 17rem;
      height: 18.5rem;
      color: black;
      transition: 0.6s;
      -webkit-transition: 0.6s;
      -moz-transition: 0.6s;
      -ms-transition: 0.6s;
      -o-transition: 0.6s;
    }

    .tentang .img-cardd {
      border-radius: 50%;
      margin-top: 0.5rem;
      width: 60%;
      object-fit: contain;
      object-position: center;
    }

    .tentang .hminuman {
      margin-top: 6rem;
    }

    /* Style untuk tombol dropdown */
    .dropdown {
      position: relative;
      display: inline-block;
    }

    /* Style untuk isi dropdown (sembunyi dulu) */
    .dropdown-content {
      display: none;
      position: absolute;
      background-color: #f9f9f9;
      min-width: 160px;
      box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
      z-index: 1;
    }

    /* Style untuk link dalam dropdown */
    .dropdown-content a {
      color: rgb(0, 0, 0);
      padding: 10px 12px;
      text-decoration: none;
      display: block;
    }

    /* Hover efek untuk link dropdown */
    .dropdown-content a:hover {
      background-color: #f1f1f1;
    }

    /* Menampilkan isi dropdown saat hover */
    .dropdown:hover .dropdown-content {
      display: block;
    }

    /* Style untuk tombol utama */
    .dropdown:hover .dropbtn {
      background-color: #3e8e41;
    }

    /* Style untuk tombol tambah menu */
    .tmbhmn {
      color: white;
      padding: 10px 20px;
      text-align: center;
      text-decoration: none;
      display: inline-block;
      font-size: 16px;
      margin: 4px 2px;
      border-radius: 5px;
    }
  </style>
</head>

<body>
  <!-- Navigation -->
  <nav class="navbar">
    <h1>Kenangan<span>mantan.</span></h1>
    <ul>
      <li><a href="#home">Home</a></li>
      <li><a href="#about">Tentang Kami</a></li>
      <li><a href="#menu">Produk</a></li>
      <li><a href="riwayat.php">Riwayat</a></li>
    </ul>
  </nav>

  <!-- Hero Section -->
  <section class="hero" id="home">
    <main class="content">
      <h2>Mari Nikmati Secangkir <span>Kopi</span></h2>
      <p>
        Dibuat dari biji kopi Indonesia pilihan untuk pengalaman minum kopi terbaik setiap hari, dan selalu membuat hari-harimu penuh semangat.
      </p>
    </main>
    <div class="image">
      <img class="imgg" src="img/main.jpg" alt="" />
    </div>
  </section>

  <!-- About Section -->
  <section class="About" id="about">
    <main class="img-history">
      <img src="img/about.jpg" alt="" />
      <div class="history2">
        <h2>CEO OFF KENANGAN MANTAN</h2>
        <p>
          Lorem, ipsum dolor sit amet consectetur adipisicing elit. Architecto tenetur deleniti quae numquam, consequatur impedit, enim ipsa et rerum expedita iure facilis atque esse aliquam repellat consequuntur reiciendis quis consectetur mollitia ipsum tempore. Odit maxime odio molestias, architecto rerum tempora?
        </p>
        <p>
          Lorem ipsum dolor sit amet consectetur adipisicing elit. Aliquam, tenetur dolores. Perspiciatis perferendis voluptas ullam aut impedit veritatis labore adipisci, quaerat eum saepe. Autem quod voluptatem non dolor excepturi! Deleniti.
        </p>
        <p>
          Lorem ipsum dolor sit amet consectetur adipisicing elit. Et, unde. In, hic eligendi doloremque delectus, a voluptatibus voluptates atque quaerat numquam asperiores accusamus magnam odit.
        </p>
        <p>
          Lorem ipsum, dolor sit amet consectetur adipisicing elit. Similique repellendus libero consequuntur corporis sapiente laborum et id recusandae quam? Repellat minima corrupti natus nisi fuga incidunt laboriosam a itaque odio debitis obcaecati placeat dignissimos quia nostrum molestiae, praesentium quae impedit.
        </p>
        <button>Learn More</button>
      </div>
    </main>
    <div class="history">
      <h2>OUR HISTORY</h2>
      <p>
        Lorem, ipsum dolor sit amet consectetur adipisicing elit. Architecto tenetur deleniti quae numquam, consequatur impedit, enim ipsa et rerum expedita iure facilis atque esse aliquam repellat consequuntur reiciendis quis consectetur mollitia ipsum tempore. Odit maxime odio molestias, architecto rerum tempora?
      </p>
      <p>
        Lorem ipsum dolor sit amet consectetur adipisicing elit. Aliquam, tenetur dolores. Perspiciatis perferendis voluptas ullam aut impedit veritatis labore adipisci, quaerat eum saepe. Autem quod voluptatem non dolor excepturi! Deleniti.
      </p>
      <p>
        Lorem ipsum dolor sit amet consectetur adipisicing elit. Et, unde. In, hic eligendi doloremque delectus, a voluptatibus voluptates atque quaerat numquam asperiores accusamus magnam odit.
      </p>
      <p>
        Lorem ipsum, dolor sit amet consectetur adipisicing elit. Similique repellendus libero consequuntur corporis sapiente laborum et id recusandae quam? Repellat minima corrupti natus nisi fuga incidunt laboriosam a itaque odio debitis obcaecati placeat dignissimos quia nostrum molestiae, praesentium quae impedit.
      </p>
      <button>Learn More</button>
      <img src="img/person.jpg" alt="" />
    </div>
  </section>

  <!-- Produk Section -->
  <!-- Produk Section -->
  <section class="tentang" id="menu">
    <!-- Search & Tambah -->
    <div class="tbh">
      <div class="src">
        <form method="POST" action="#menu">
          <input type="text" name="search_query" placeholder="cari produk..." value="<?php echo $search_query; ?>">
          <button type="submit" name="search">Cari</button>
        </form>
      </div>
      <nav class="tbh-mn">
        <div class="dropdown">
          <a class="tmbhmn">+ Tambah Menu Masakan</a>
          <div class="dropdown-content">
            <a href="dataminuman.php">Menu Minuman</a>
            <a href="dataproduk.php">Menu Makanan</a>
          </div>
        </div>
        <a href="keranjang.php">
          <i data-feather="shopping-cart" class="sopcart"></i>
          <?php if (isset($_SESSION['total_items']) && $_SESSION['total_items'] > 0) { ?>
            <span class="cart-count"><?php echo $_SESSION['total_items']; ?></span>
          <?php } ?>
        </a>
      </nav>
    </div>

    <!-- Hasil Pencarian Makanan -->
    <h2>OUR DRINKS</h2>
    <div class="row" >
      <?php
      while ($array_minuman = mysqli_fetch_array($ambil_minuman, MYSQLI_ASSOC)) {
      ?>
        <div class="menu-card">
          <img src="img/<?php echo "$array_minuman[foto_produk]"; ?>" alt="" class="img-card">
          <h3 class="h-card"><?php echo "$array_minuman[NamaProduk]"; ?></h3>
          <p class="hrg">Rp<?php echo  "$array_minuman[Harga]"; ?></p>
          <form method="POST" action="#menu">
            <input type="hidden" name="MinumanId" value="<?php echo $array_minuman['MinumanId']; ?>">
            <input type="hidden" name="NamaProduk" value="<?php echo $array_minuman['NamaProduk']; ?>">
            <input type="hidden" name="Harga" value="<?php echo $array_minuman['Harga']; ?>">
            <button type="submit" name="add_to_cart" class="add">Add To Cart</button>
          </form>
        </div>
      <?php
      }
      ?>
    </div>

    <!-- Hasil Pencarian Minuman -->
    <h2 class="hminuman" id="menumakanan">OUR FOODS</h2>
    <div class="row">
      <?php
      while ($array_makanan = mysqli_fetch_array($ambil_makanan, MYSQLI_ASSOC)) {
      ?>
        <div class="menu-card">
          <img src="img/<?php echo "$array_makanan[foto_produk]"; ?>" alt="" class="img-cardd">
          <h3 class="h-cardd"><?php echo "$array_makanan[NamaProduk]"; ?></h3>
          <p class="hrg">Rp<?php echo  "$array_makanan[Harga]"; ?></p>
          <form method="POST" action="#menu">
            <input type="hidden" name="MakananId" value="<?php echo $array_makanan['MakananId']; ?>">
            <input type="hidden" name="NamaProduk" value="<?php echo $array_makanan['NamaProduk']; ?>">
            <input type="hidden" name="Harga" value="<?php echo $array_makanan['Harga']; ?>">
            <button type="submit" name="add_to_cart" class="add">Add To Cart</button>
          </form>
        </div>
      <?php
      }
      ?>
    </div>
  </section>

  <!-- Footer -->
  <div class="copyrigth">
    <p>&#169; Website ini dibuat pada 02-08-2024</p>
  </div>

  <script>
    feather.replace();
  </script>
</body>

</html>



<!-- TUGAS REVISI -->
<!-- untuk nominal tidak boleh 0 harus 1 -->
<!-- ketika cekout tidak boleh melebihi stok -->