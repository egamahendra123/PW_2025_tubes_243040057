<?php
        require "session.php";
        require "../koneksi.php";

        $id = $_GET['p'];
        $query = mysqli_query($con, "SELECT a.*, b.nama AS nama_kategori FROM produk a 
        JOIN kategori b ON a.kategori_id=b.id WHERE a.id = '$id'");
        $data = mysqli_fetch_array($query);

        $querykategori = mysqli_query($con,"SELECT * FROM  kategori WHERE id!='$data[kategori_id]'");

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
    <title>Produk detail</title>
        <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">

</head>

<style>
    form div{
        margin-top: 10px;
    }
</style>

<body>
    <?php require "navbar.php"?>
        <div class="container mt-5">
                <h2>Detail Produk</h2>
            <div class="col-12 col-md-6 mb-5">
                <form action="" method="post" enctype="multipart/form-data">
                    <div>
                        <label for="nama" class="mb-1">Nama</label>
                        <input type="text" id="nama" name="nama" value="<?php echo $data['nama']?>" class="form-control" style="width: 500px;" required >
                    </div>
                    <div>
                        <label for="kategori" class="mb-1">Kategori </label>
                        <select name="kategori" id="kategori"  class="form-control" style="width: 500px;" required >
                            <option value="<?php echo $data['kategori_id']; ?>">
                                <?php
                                    echo $data['nama_kategori'];
                                ?>
                            </option>
                            <?php 
                                while($dataKategori=mysqli_fetch_array($querykategori)){
                            ?>
                                <option value="<?php echo $dataKategori['id']; ?>"><?php echo $dataKategori['nama']; ?></option>
                            <?php
                                }
                            ?>
                        </select>
                    </div>
                    <div>
                        <label for="harga" class="mb-1">Harga</label>
                        <input type="number"  class="form-control" value="<?php echo $data['harga']; ?>" name="harga" style="width: 500px;" required >
                    </div>
                    <div>
                        <div>
                            <label for="currentFoto">Foto Produk sekarang</label>
                            <img src="../image/<?php echo $data['foto'] ?>" alt="" width="300px">
                        </div>
                        <label for="foto" class="mb-1">Foto</label>
                        <input type="file" name="foto" id="foto" class="form-control" style="width: 500px;">
                    </div>
                    <div>
                        <label for="detail" class="mb-1">Detail</label>
                        <textarea name="detail" id="detail" cols="30" rows="10" class="form-control" style="width: 500px;">
                            <?php echo $data['detail']; ?>
                        </textarea>
                    </div>
                    <div>
                        <label for="ketersediaan_stok" class="mb-1">ketersediaan Stok</label>
                        <select name="ketersediaan_stok" id="ketersediaan_stok" class="form-control" style="width: 500px;">
                            <option value="<?php echo $data['ketersediaan_stok']; ?>">
                                <?php echo $data['ketersediaan_stok']; ?>
                            </option>
                            <?php
                                if($data['ketersediaan_stok']=='tersedia'){
                            ?>
                                <option value="habis">habis</option>
                            <?php
                                }
                                else{
                            ?>
                                <option value="tersedia">tersedia</option>

                            <?php
                            }
                            ?>  
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary me-3" name="simpan">simpan</button>
                        <button type="submit" class="btn btn-danger" name="hapus">hapus</button>
                    </div>
                </form>

                <?php
                    if(isset($_POST['simpan'])){
                        $nama = htmlspecialchars($_POST['nama']);
                        $kategori = htmlspecialchars($_POST['kategori']);
                        $harga = htmlspecialchars($_POST['harga']);
                        $detail = htmlspecialchars($_POST['detail']);
                        $ketersediaan_stok = htmlspecialchars($_POST['ketersediaan_stok']);

                        // Validasi field yang required
                        if($nama=='' || $kategori=='' || $harga==''){
                ?>
                            <div class="alert alert-warning mt-3" role="alert" style="width: 500px;">
                                Nama, kategori, dan harga tidak boleh kosong
                            </div>
                <?php
                        }
                        else{
                            // Update detail produk terlebih dahulu
                            $queryUpdate = mysqli_query($con, "UPDATE produk SET kategori_id='$kategori', nama='$nama', harga='$harga', detail='$detail', 
                            ketersediaan_stok='$ketersediaan_stok' WHERE id=$id ");

                            $updateSuccess = false;
                            
                            if($queryUpdate){
                                $updateSuccess = true;
                                
                                // Cek apakah ada foto yang di-upload
                                $nama_file = basename($_FILES["foto"]["name"]);
                                if($nama_file != ''){
                                    //   upload foto   //
                                    $target_dir = "../image/";
                                    $target_file = $target_dir . $nama_file;
                                    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                                    $image_size = $_FILES["foto"]["size"];
                                    $random_name = generateRandomString(20);
                                    $new_name = $random_name . "." . $imageFileType;

                                    if($image_size > 500000){
                ?>
                                        <div class="alert alert-warning mt-3" role="alert" style="width: 500px;">
                                            File tidak boleh lebih dari 500 kb
                                        </div>
                <?php
                                        $updateSuccess = false;
                                    }
                                    else{
                                        if($imageFileType != 'jpg' && $imageFileType != 'png' && $imageFileType != 'jpeg' ){
                ?>
                                            <div class="alert alert-warning mt-3" role="alert" style="width: 500px;">
                                                File wajib bertipe jpg, jpeg, atau png
                                            </div>
                <?php                      
                                            $updateSuccess = false;
                                        }
                                        else{
                                            if(move_uploaded_file($_FILES["foto"]["tmp_name"], $target_dir . $new_name)){
                                                $queryUpdateFoto = mysqli_query($con,"UPDATE produk SET foto='$new_name' WHERE id=$id ");
                                                
                                                if(!$queryUpdateFoto){
                                                    echo mysqli_error($con);
                                                    $updateSuccess = false;
                                                }
                                            } else {
                                                $updateSuccess = false;
                                            }
                                        }
                                    }
                                }
                                
                                // Tampilkan pesan sukses jika update berhasil
                                if($updateSuccess){
                ?>
                                    <div class="alert alert-success mt-3" role="alert" style="width: 500px;">
                                        Produk berhasil di update
                                    </div>
                                    <meta http-equiv="refresh" content="2; url=produk.php" />
                <?php
                                }
                            }
                            else{
                                echo mysqli_error($con);
                            }
                        }
                    }

                    if(isset($_POST['hapus'])){
                       $queryDelete = mysqli_query($con, "DELETE FROM produk WHERE id='$id'");
                       
                       if($queryDelete){
                ?>
                            <div class="alert alert-primary mt-3" role="alert">
                                Produk berhasil di hapus
                            </div>
                            <meta http-equiv="refresh" content="2; url=produk.php" />
                <?php
                       }
                       else{
                         echo mysqli_error($con);
                       }  
                    }
                ?>    
                
            </div>
        </div>
    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>