<?php
include "koneksi.php";

// Menghapus data produk yang stoknya 0
mysqli_query($koneksi, "DELETE FROM makanan WHERE Stok = 0");

?>
<!DOCTYPE html>
<html>

<head>
  <title>FORM DATA PRODUK</title>
  <link href="css/dataproduk.css" rel="stylesheet" type="text/css" />
  <style>
    body {
      color: white;
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

    #dataproduk {
      min-height: 100vh;
      background-position: center;
      background-image: url(img/home.jpg);
      background-size: cover;
      background-repeat: no-repeat;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      color: white;
      padding-top: 100px;
      /* Adjust for fixed navbar */
      text-align: center;
    }
  </style>
</head>

<body>
  <nav class="navbar">
    <h1>Kenangan<span>mantan.</span></h1>
    <ul>
      <li><a href="homekasir.php">Home</a></li>
      <li><a href="homekasir.php?#about">Tentang Kami</a></li>
      <li><a href="homekasir.php?#menu">Produk</a></li>
      <li><a href="riwayat.php">Riwayat </a></li>
    </ul>
  </nav>

  <section id="dataproduk">
    <fieldset class="iptprd">
      <legend>INPUT DATA MAKANAN</legend>
      <form method="post" action="inputproduk.php">
        <table border="0" cellspacing="0" cellpadding="10">
          <tr>
            <td>Nama Makanan</td>
            <td><input type="text" name="makanan" required /></td>
          </tr>
          <tr>
            <td>Harga</td>
            <td><input type="text" name="harga" required /></td>
          </tr>
          <tr>
            <td>Stok</td>
            <td><input type="text" name="stok" required /></td>
          </tr>
          <tr>
            <td>Foto Makanan</td>
            <td><input type="file" name="foto" accept=".jpg, .png" required /></td>
          </tr>
          <tr>
            <td colspan="2" align="right">
              <input type="submit" value="SIMPAN" />
            </td>
          </tr>
        </table>
      </form>
    </fieldset>
    <p></p>
    <p></p>
    <fieldset class="dtprdk">
      <legend>DATA MAKANAN</legend>
      <table border="1" cellspacing="0" cellpadding="10">
        <tr>
          <td>
            <div align="center"><strong>NO</strong></div>
          </td>
          <td>
            <div align="center"><strong>NAMA MAKANAN</strong></div>
          </td>
          <td>
            <div align="center"><strong>HARGA</strong></div>
          </td>
          <td>
            <div align="center"><strong>STOK</strong></div>
          </td>
          <td>
            <div align="center"><strong>EDIT</strong></div>
          </td>
          <td>
            <div align="center"><strong>DELETE</strong></div>
          </td>
        </tr>
        <?php
        $no = 1;
        $ambil = mysqli_query($koneksi, "SELECT * FROM makanan");
        while ($array = mysqli_fetch_array($ambil, MYSQLI_ASSOC)) {
        ?>
          <tr class="trprdk">
            <td>
              <div align="center">
                <?php echo "$no";
                $no++; ?>
              </div>
            </td>
            <td>
              <div align="center"><?php echo $array['NamaProduk']; ?></div>
            </td>
            <td>
              <div align="center">Rp<?php echo $array['Harga']; ?></div>
            </td>
            <td>
              <div align="center"><?php echo $array['Stok']; ?></div>
            </td>
            <td>
              <div align="center">
                <a href="editproduk2.php?idnya=<?php echo $array['MakananId']; ?>">EDIT</a>
              </div>
            </td>
            <td>
              <div align="center">
                <a href="deleteproduk.php?idnya=<?php echo $array['MakananId']; ?>" onclick="return confirm('Yakin akan dihapus?')">HAPUS</a>
              </div>
            </td>
          </tr>
        <?php
        }
        ?>
      </table>
    </fieldset>
  </section>
</body>

</html>