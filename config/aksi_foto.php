<?php 
session_start();
include 'koneksi.php';

if (isset($_POST['tambah'])) {
    $judulFoto = $_POST['judulfoto'];
    $deskripsiFoto = $_POST['deskripsifoto'];
    $tanggalUnggah = date('Y-m-d');
    $albumId = $_POST['albumid'];
    $userId = $_SESSION['userid'];
    $foto = $_FILES['lokasifile']['name'];
    $tmp = $_FILES['lokasifile']['tmp_name'];
    $lokasi = '../assets/img/';
    $namaFoto = rand() . '-' . $foto;

    move_uploaded_file($tmp, $lokasi . $namaFoto);

    $sql = mysqli_query($koneksi, "INSERT INTO foto (judulfoto, deskripsifoto, tanggalunggah, lokasifile, albumid, userid) VALUES ('$judulFoto', '$deskripsiFoto', '$tanggalUnggah', '$namaFoto', '$albumId', '$userId')");

    echo "<script>
    alert('Data Berhasil Disimpan!');
    location.href='../admin/foto.php';
    </script>";
}

if (isset($_POST['edit'])) {
    $fotoid = $_POST['fotoid'];
    $judulFoto = $_POST['judulfoto'];
    $deskripsiFoto = $_POST['deskripsifoto'];
    $tanggalUnggah = date('Y-m-d');
    $albumId = $_POST['albumid'];
    $userId = $_SESSION['userid'];
    $foto = $_FILES['lokasifile']['name'];
    $tmp = $_FILES['lokasifile']['tmp_name'];
    $lokasi = '../assets/img/';
    $namaFoto = rand() . '-' . $foto;

    if ($foto == null) {
        $sql = mysqli_query($koneksi, "UPDATE foto SET judulfoto='$judulFoto', deskripsifoto='$deskripsiFoto', tanggalunggah='$tanggalUnggah', albumid='$albumId' WHERE fotoid='$fotoid'");
    } else {
        $query = mysqli_query($koneksi, "SELECT * FROM foto WHERE fotoid='$fotoid'");
        $data = mysqli_fetch_array($query);
        if (is_file('../assets/img/'.$data['lokasifile'])) {
            unlink('../assets/img/'.$data['lokasifile']);
        }
        move_uploaded_file($tmp, $lokasi . $namaFoto);
        $sql = mysqli_query($koneksi, "UPDATE foto SET judulfoto='$judulFoto', deskripsifoto='$deskripsiFoto', tanggalunggah='$tanggalUnggah', lokasifile='$namaFoto', albumid='$albumId' WHERE fotoid='$fotoid'");
    }
    echo "<script>
    alert('Data Berhasil Diperbarui!');
    location.href='../admin/foto.php';
    </script>";
}

if (isset($_POST['hapus'])) {
    $fotoid = $_POST['fotoid'];
    $query = mysqli_query($koneksi, "SELECT * FROM foto WHERE fotoid='$fotoid'");
    $data = mysqli_fetch_array($query);
    if (is_file('../assets/img/'.$data['lokasifile'])) {
        unlink('../assets/img/'.$data['lokasifile']);
    }

    $sql = mysqli_query($koneksi, "DELETE FROM foto WHERE fotoid='$fotoid'");
    echo "<script>
    alert('Data Berhasil Dihapus!');
    location.href='../admin/foto.php';
    </script>";

}