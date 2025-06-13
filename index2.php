<?php
    require "koneksi.php";
    $queryProduk = mysqli_query($con, "SELECT id, nama, harga, foto, detail FROM produk LIMIT 6")
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klik kita</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="fontawesome/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php require "navbar2.php";?>

     <!----banner----->

    <div class="container-fluid banner d-flex align-items-center">
        <div class="container text-center text-white">
            <h1>Klik kita</h1>
            <h3>Mau cari apa?</h3>
           <div class="col-md-8 offset-md-2">
            <form method="get" action="produk.php">
                    <div class="input-group  input-group-lg my-4">
                        <input type="text" class="form-control" placeholder="Nama Produk" 
                            aria-label="Recipient’s username" aria-describedby="basic-addon2" name="keyword">
                        <button type="submit" class="btn warna1 text-white" id="basic-addon2">Search</button>
                    </div>
              </form>
           </div>
        </div>
    </div>

                <!--highlight kategori-->
    <div class="container-fluid py-4">
        <div class="container text-center">
            <h3>Kategori Terlaris</h3>

            <div class="row mt-5">
                <div class="col-md-4 mb-3">
                    <div class="highlighted-kategori kategori-baju-pria d-flex justify-content-center align-items-center">
                        <h4 class="text-white"><a class="no-decoration" href="produk.php?kategori=Baju Pria"> Baju Pria</a></h4>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="highlighted-kategori kategori-baju-wanita  d-flex justify-content-center align-items-center ">
                        <h4 class="text-white"><a class="no-decoration" href="produk.php?kategori=Baju Wanita">Baju Wanita</a></h4>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="highlighted-kategori  kategori-sepatu  d-flex justify-content-center align-items-center">
                        <h4 class="text-white"><a class="no-decoration" href="produk.php?kategori=Sepatu">Sepatu</a></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--------Tentang Kami-------->
    <div class="container-fluid warna1 py-5">
        <div class="container text-center">
            <h3>Tentang Kami</h3>
            <p class="fs-5 mt-3 text-justify">
                Selamat datang di Klik Kita, platform e-commerce terpercaya
                yang menyediakan berbagai produk berkualitas dengan harga terbaik. 
                Kami berdedikasi untuk memberikan pengalaman belanja online yang mudah,
                cepat, dan aman bagi seluruh pelanggan kami.Sejak didirikan pada tanggal 8 juni 2025,
                kami telah berkomitmen untuk menghadirkan produk-produk pilihan mulai dari 
                Smartphone, Baju Pria, Baju wanita, Sepatu dll dengan layanan pelanggan yang
                responsif dan pengiriman tepat waktu.Kami percaya bahwa kepuasan pelanggan 
                adalah prioritas utama. Oleh karena itu,kami selalu berupaya meningkatkan 
                kualitas produk dan layanan, serta memberikan berbagai promo menarik untuk 
                memanjakan Anda.Terima kasih telah mempercayakan kebutuhan belanja Anda kepada
                kami. Mari berbelanja dengan nyaman dan percaya diri di Klik Kita.
            </p>
        </div>
    </div>
    
    <!--------Produk nyaa-------->

    <div class="container-fluid py-5">
        <div class="container text-center">
            <h3 class="mt-4">Produk</h3>

            <div class="row mt-5">
                <?php while($data = mysqli_fetch_array($queryProduk)){ ?>

                <div class="col-md-4 col-md-3 mb-4">
                        <div class="card h-100">
                            <div class="image-box">
                                <img src="image/<?php echo $data['foto']; ?> " class="card-img-top" alt="...">
                                </div>
                                <div class="card-body">
                                <h4 class="card-title"><?php echo $data['nama']; ?></h4>
                                <p class="card-text text-truncate"><?php echo $data['detail']; ?></p>
                                <p class="card-text text-harga">Rp <?php echo $data['harga']; ?></p>
                                <a href="produk-detail.php?nama=<?php echo $data['nama']; ?>" class="btn warna1 text-white">Lihat Detail</a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <a class="btn btn-outline-warning mt-3 p-3 " href="produk.php">See More</a>
        </div>
    </div>

   <!-- footer -->
    <?php require "footer.php" ?>

    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="fontawesome/js/all.min.js"></script>
</body>
</html>