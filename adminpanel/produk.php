<?php
require "session.php";
require "../koneksi.php";

$query = mysqli_query($con, "SELECT a.*, b.nama AS nama_kategori FROM produk a JOIN kategori b ON a.kategori_id = b.id");
$jumlahproduk = mysqli_num_rows($query);
$querykategori = mysqli_query($con, "SELECT * FROM kategori");

function generateRandomString($length = 0) {
    $characters = '0123456789abcdefghihjlmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produk</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../fontawesome/css/fontawesome.min.css">
    <!-- Library html2pdf untuk cetak PDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script> 
</head>
<style>
    .no-decoration{
        text-decoration: none;
    }
    form div{
        margin-bottom: 10px;
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
                    <i class="fas fa-align-justify"></i> Produk
                </li>
            </ol>
        </nav>

        <!-- Form Tambah Produk -->
        <div class="mt-3">
            <h3>Tambah Produk</h3>
            <form action="" method="post" enctype="multipart/form-data">
                <div>
                    <label for="nama" class="mb-1">Nama</label>
                    <input type="text" id="nama" name="nama" class="form-control" style="width: 500px;" required>
                </div>
                <div>
                    <label for="kategori" class="mb-1">Kategori</label>
                    <select name="kategori" id="kategori" class="form-control" style="width: 500px;" required>
                        <option value="">Pilih Satu</option>
                        <?php 
                            while($data=mysqli_fetch_array($querykategori)){
                        ?>
                            <option value="<?php echo $data['id']; ?>"><?php echo $data['nama']; ?></option>
                        <?php
                            }
                        ?>
                    </select>
                </div>
                <div>
                    <label for="harga" class="mb-1">Harga</label>
                    <input type="number" class="form-control" name="harga" style="width: 500px;" required>
                </div>
                <div>
                    <label for="foto" class="mb-1">Foto</label>
                    <input type="file" name="foto" id="foto" class="form-control" style="width: 500px;">
                </div>
                <div>
                    <label for="detail" class="mb-1">Detail</label>
                    <textarea name="detail" id="detail" cols="30" rows="10" class="form-control" style="width: 500px;"></textarea>
                </div>
                <div>
                    <label for="ketersediaan_stok" class="mb-1">Ketersediaan Stok</label>
                    <select name="ketersediaan_stok" id="ketersediaan_stok" class="form-control" style="width: 500px;">
                        <option value="tersedia">Tersedia</option>
                        <option value="habis">Habis</option>
                    </select>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary" name="simpan">Simpan</button>
                </div>
            </form>

            <!-- Pesan Setelah Submit -->
            <?php
            if(isset($_POST['simpan'])){
                $nama = htmlspecialchars($_POST['nama']);
                $kategori = htmlspecialchars($_POST['kategori']);
                $harga = htmlspecialchars($_POST['harga']);
                $detail = htmlspecialchars($_POST['detail']);
                $ketersediaan_stok = htmlspecialchars($_POST['ketersediaan_stok']);

                $target_dir = "../image/";
                $nama_file = basename($_FILES["foto"]["name"]);
                $imageFileType = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));
                $image_size = $_FILES["foto"]["size"];
                $random_name = generateRandomString(20);
                $new_name = $random_name . "." . $imageFileType;

                if($nama=='' || $kategori=='' || $harga==''){
                    echo "<div class='alert alert-danger mt-3' role='alert'>Semua kolom wajib diisi.</div>";
                } else {
                    if($nama_file!=''){
                        if($image_size > 500000){
                            echo "<div class='alert alert-warning mt-3' role='alert' style='width: 500px;'>File tidak boleh lebih dari 500 KB.</div>";
                        } elseif(!in_array($imageFileType, ['jpg','png','gif'])){
                            echo "<div class='alert alert-warning mt-3' role='alert' style='width: 500px;'>File harus berupa JPG, PNG, atau GIF.</div>";
                        } else {
                            move_uploaded_file($_FILES["foto"]["tmp_name"], $target_dir . $new_name);
                        }
                    }

                    $queryTambah = mysqli_query($con, "INSERT INTO produk (kategori_id, nama, harga, foto, detail, ketersediaan_stok)
                        VALUES ('$kategori', '$nama', '$harga', '$new_name', '$detail','$ketersediaan_stok')");

                    if($queryTambah){
                        echo "
                        <div class='alert alert-primary mt-3' role='alert' style='width: 500px;'>
                            Produk berhasil tersimpan
                        </div>
                        <meta http-equiv='refresh' content='2; url=produk.php' />";
                    } else {
                        echo mysqli_error($con);
                    }
                }
            }
            ?>
        </div>

        <!-- Daftar Produk -->
        <div class="mt-3 mb-5">
            <h2>List Produk</h2>

            <!-- Tombol Cetak PDF -->
            <button onclick="generatePDF()" class="btn btn-danger mb-3">Cetak ke PDF</button>

            <div class="table-responsive mt-5">
                <table class="table" id="table-to-print">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Ketersediaan Stok</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if($jumlahproduk == 0){
                            echo '<tr><td colspan="6" class="text-center">Data Produk Tidak Tersedia</td></tr>';
                        } else {
                            $jumlah = 1;
                            while($data = mysqli_fetch_array($query)){
                        ?>
                            <tr>
                                <td><?= $jumlah++; ?></td>
                                <td><?= $data['nama'] ?></td>
                                <td><?= $data['nama_kategori'] ?></td>
                                <td>Rp <?= number_format($data['harga'], 0, ',', '.') ?></td>
                                <td><?= ucfirst($data['ketersediaan_stok']) ?></td>
                                <td>
                                    <a href="produk-detail.php?p=<?= $data['id'] ?>" class="btn btn-info">
                                        <i class="fas fa-search"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php } } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Script untuk Generate PDF -->
    <script>
        function generatePDF() {
            const element = document.getElementById('table-to-print');
            const opt = {
                margin:       0.5,
                filename:     'daftar_produk.pdf',
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2 },
                jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
            };
            html2pdf().set(opt).from(element).save();
        }
    </script>

    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../fontawesome/js/all.min.js"></script>
</body>
</html>