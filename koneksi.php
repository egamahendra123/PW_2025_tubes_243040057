<?php
$con = mysqli_connect('localhost', 'root', '', 'klikita');

// cek koneksi 
if (mysqli_connect_errno()) {
  echo "Failed to conect to MySQL: " . mysqli_connect_error();
  exit();
}
?>