<?php
    require "koneksi.php";

    $queryKategori = mysqli_query($con, "SELECT * FROM kategori");

    //ambil produk sesuai nama produk/keywoard
    if(isset($_GET['keyword'])) {
        $queryProduk = mysqli_query($con, "SELECT * FROM produk WHERE nama LIKE '%" . $_GET['keyword'] . "%'");
    }
    //ambil produk sesuai kategori
    else if(isset($_GET['kategori'])) {
        $queryGetKategoriId = mysqli_query($con, "SELECT id FROM kategori WHERE nama='$_GET[kategori]'");
        $kategoriId = mysqli_fetch_array($queryGetKategoriId);
        $queryProduk = mysqli_query($con, "SELECT * FROM produk WHERE kategori_id ='$kategoriId[id]'");
    }
    //ngambil produk default
    else{
        $queryProduk = mysqli_query($con, "SELECT * FROM produk");
    }

    $countData = mysqli_num_rows($queryProduk);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klik Kita</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="fontawesome/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php require "navbar2.php";?>
    
     <!-- baner -->
      <div class="container-fluid banner-produk d-flex align-items-center">
        <div class="container">
            <h1 class="text-white text-center">Produk</h1>
        </div>
      </div>

        <!-- baner -->
        <div class="container py-5">
            <div class="row ">
                <h3>Kategori</h3>
                <div class="col-lg-3 mb-4"> 
                    <ul class="list-group">
                        <?php while ($kategori = mysqli_fetch_array($queryKategori)){?>
                        <a  class="no-decoration" href="produk.php?kategori=<?php echo $kategori['nama']; ?>">
                            <li class="list-group-item"><?php echo $kategori['nama']; ?></li>
                        </a>
                        <?php } ?>
                    </ul> 
                </div>

                <div class="col-lg-9">
                    <h3 class="text-center mb-3">Produk</h3>
                    <div class="row">
                        <?php
                            if($countData<1){
                        ?>
                            <div class="alert alert-warning mt-3 " role="alert">
                                Maaf, kami tidak menemukan produk "
                                <strong>
                                <?php echo htmlspecialchars($_GET['keyword']); ?>
                                </strong>". <br>
                                Coba gunakan kata kunci lain atau cek kategori lainnya.
                            </div>

                        <?php
                            }
                        ?>

                        <?php while($produk = mysqli_fetch_array($queryProduk)){ ?>
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                <div class="image-box">
                                    <img src="image/<?php echo $produk['foto']; ?>" class="card-img-top" alt="...">
                                </div>
                                <div class="card-body">
                                    <h4 class="card-title"><?php echo $produk['nama']; ?></h4>
                                    <p class="card-text text-truncate"><?php echo $produk['detail']; ?></p>
                                    <p class="card-text text-harga">Rp <?php echo $produk['harga']; ?></p>
                                    <a href="produk-detail.php?nama=<?php echo $produk['nama']; ?>" class="btn warna1 text-white">Lihat Detail</a>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>         
            </div>
        </div>

        <!-- footer -->
<?php require "footer.php"; ?>

    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="fontawesome/js/all.min.js"></script>
</body>
</html>