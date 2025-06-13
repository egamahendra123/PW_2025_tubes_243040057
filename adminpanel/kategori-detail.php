<?php
        require "session.php";
        require "../koneksi.php";

        $id = $_GET['p'];
        $query = mysqli_query($con, "SELECT * FROM kategori WHERE id = '$id'");
        $data = mysqli_fetch_array($query);

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
    <title>Detail Kategori</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>
<body>
    <?php require "navbar.php"?>

    <div class="container mt-5">
    <h2>Detail kategori</h2>

        <div class="col-12 col-md-6">
             <form action="" method="post">
              <div>
                <label for="kategori">kategori</label>
                <input type="text" name="kategori" id="kategori" class="form-control" value="<?php echo $data['nama']?>">
              </div>

            <!-- tommbol button edit dan delete kategori-->
              <div class="mt-5">
                <button type="submit" class="btn btn-primary me-3" name="editBtn" >simpan <i class="bi bi-pencil-square"></i></button>
                <button type="submit" class="btn btn-danger" name="deleteBtn">hapus <i class="bi bi-trash"></i></button>
              </div>
            </form>
            <!-- ------------- -->
            <?php
                if(isset($_POST['editBtn'])){
                    $kategori = htmlspecialchars($_POST['kategori']);

                    if($data['nama']==$kategori){
                        ?>
                          <meta http-equiv="refresh" content="1; url=kategori.php" />
                        <?php
                    }
                    else{
                        $query = mysqli_query($con, "SELECT * FROM kategori WHERE nama='$kategori'");
                        $jumlahdata = mysqli_num_rows($query);
                        
                        if($jumlahdata >0){
                             ?>
                                 <div class="alert alert-warning mt-3" role="alert">
                                 Kategori Sudah Ada 
                                 </div>
                             <?php
                        }
                        else{
                            $querysimpan = mysqli_query($con,"UPDATE  kategori SET nama='$kategori' WHERE id='$id'");
                        if($querysimpan){
                ?>
                        <div class="alert alert-primary mt-3" role="alert">
                            Kategori berhasil di update
                        </div>

                        <meta http-equiv="refresh" content="1; url=kategori.php" />

                <?php    
                        }
                        else{
                            echo mysqli_error($con);
                        }  
                        }
                    }
                }

                if(isset($_POST['deleteBtn'])){
                    $queryCheck = mysqli_query($con, "SELECT * FROM produk WHERE kategori_id='$id'");
                    $dataCount = mysqli_num_rows($queryCheck);
                    
                    if($dataCount>0){

                     ?>
                        <div class="alert alert-warning mt-3" role="alert">
                            Kategori tidak bisa di hapus karena sudah di gunakan di produk
                        </div>
                    <?php
                    die();
                    }

                   $queryDelete = mysqli_query($con, "DELETE FROM kategori WHERE id='$id'");

                   if($queryDelete){
                    ?>
                        <div class="alert alert-primary mt-3" role="alert">
                            Kategori berhasil di hapus
                        </div>

                        <meta http-equiv="refresh" content="2; url=kategori.php" />
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