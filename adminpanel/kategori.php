<?php
require "session.php";
require "../koneksi.php";

// Konfigurasi Pagination
$jumlah_per_halaman = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page > 1) ? ($page * $jumlah_per_halaman) - $jumlah_per_halaman : 0;

// Query dengan LIMIT dan OFFSET
$querykategori = mysqli_query($con, "SELECT * FROM kategori LIMIT $start, $jumlah_per_halaman");
$jumlahkategori = mysqli_num_rows(mysqli_query($con, "SELECT * FROM kategori"));

// Hitung total halaman
$total_halaman = ceil($jumlahkategori / $jumlah_per_halaman);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategori</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../fontawesome/css/fontawesome.min.css">
</head>
<style>
    .no-decoration{
        text-decoration: none;
    }
</style>
<body>
    <?php require "navbar.php";?>
    <div class="container mt-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="../adminpanel" class="no-decoration text-muted">
                        <i class="fas fa-home"></i> Home
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <i class="fas fa-align-justify"></i> Kategori
                </li>
            </ol>
        </nav>

        <!-- Form penambahan -->
        <div class="my-5 col-12 col-md-6">
            <h3>Tambah Kategori</h3>
            <form action="" method="post">
                <div>
                    <label for="kategori">Kategori</label>
                    <input type="text" id="kategori" name="kategori" placeholder="input nama kategori" class="form-control">
                </div>
                <div class="mt-3">
                    <button class="btn btn-primary" type="submit" name="simpan_kategori">Simpan</button>
                </div>
            </form>

            <?php
            if(isset($_POST['simpan_kategori'])){
                $kategori = htmlspecialchars($_POST['kategori']);

                $queryExist = mysqli_query($con, "SELECT nama FROM kategori WHERE nama ='$kategori'");
                $jumlahdataKategoriBaru = mysqli_num_rows($queryExist);

                if($jumlahdataKategoriBaru > 0){
                    echo '<div class="alert alert-warning mt-3" role="alert">Kategori Sudah Ada</div>';
                } else {
                    $querysimpan = mysqli_query($con,"INSERT INTO kategori (nama) VALUES ('$kategori')");

                    if($querysimpan){
                        echo '
                        <div class="alert alert-primary mt-3" role="alert">
                            Kategori berhasil tersimpan
                        </div>
                        <meta http-equiv="refresh" content="1; url=kategori.php" />';
                    } else {
                        echo mysqli_error($con);
                    }
                }
            }
            ?>
        </div>
        <!-- Akhir form -->

        <div class="mt-3">
            <h2>List Kategori</h2>
            <div class="table-responsive mt-5">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if($jumlahkategori == 0){
                            echo '
                            <tr>
                                <td colspan="3" class="text-center">Data Kategori Tidak Tersedia</td>
                            </tr>';
                        } else {
                            $jumlah = $start + 1;
                            while($data = mysqli_fetch_array($querykategori)){
                        ?>
                            <tr>
                                <td><?= $jumlah++; ?></td>
                                <td><?= $data['nama']; ?></td>
                                <td>
                                    <a href="kategori-detail.php?p=<?= $data['id']; ?>" class="btn btn-info">
                                        <i class="fas fa-search"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php } } ?>
                    </tbody>
                </table>
            </div>

            <!-- Package -->
            <?php if($total_halaman > 1): ?>
            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center">
                    <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $page - 1 ?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php for($i = 1; $i <= $total_halaman; $i++): ?>
                        <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($page < $total_halaman): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $page + 1 ?>" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
            <?php endif; ?>
        </div>

    </div>

    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../fontawesome/js/all.min.js"></script>
</body>
</html>