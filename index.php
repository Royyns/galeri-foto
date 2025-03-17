
<?php
session_start();
include_once 'config/koneksi.php';

$userid = isset($_SESSION['userid']) ? $_SESSION['userid'] : null;
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Galeri Foto</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@1/css/pico.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <nav class="container-fluid navbar" id="navbar">
        <li><strong><a href="index.php"><i class="fa-solid fa-gallery-thumbnails"> Galeri Foto</i></a></strong></li>
        <ul>
            <?php if (isset($_SESSION['status']) && $_SESSION['status'] == 'login') { ?>
                <li><a href="config/aksi_logout.php" role="button"><i class="fas fa-sign-out-alt"></i> Keluar</a></li>
            <?php } else { ?>
                <li><a href="register.php" role="button"><i class="fas fa-user-plus"></i> Daftar</a></li>
                <li><a href="login.php" role="button"><i class="fas fa-sign-in-alt"></i> Masuk</a></li>
            <?php } ?>
        </ul>
    </nav>

    <main class="container">
        <div class="gallery">
            <?php
            $query = mysqli_query($koneksi, "SELECT * FROM foto INNER JOIN user ON foto.userid=user.userid INNER JOIN album ON foto.albumid=album.albumid");
            while ($data = mysqli_fetch_array($query)) {
            ?>
                <div class="gallery-item" data-target="modal-<?php echo $data['fotoid'] ?>">
                    <img src="assets/img/<?php echo $data['lokasifile'] ?>" alt="<?php echo $data['judulfoto'] ?>">
                    <div class="btn-like">
                        <?php
                        $fotoid = $data['fotoid'];
                        $ceksuka = mysqli_query($koneksi, "SELECT * FROM likefoto WHERE fotoid='$fotoid' AND userid='$userid'");
                        if ($userid && mysqli_num_rows($ceksuka) == 1) { ?>
                            <a href="config/proses_like.php?fotoid=<?php echo $data['fotoid'] ?>" onclick="event.stopPropagation();"><i class="fas fa-heart"></i></a>
                        <?php } elseif ($userid) { ?>
                            <a href="config/proses_like.php?fotoid=<?php echo $data['fotoid'] ?>" onclick="event.stopPropagation();"><i class="far fa-heart"></i></a>
                        <?php } else { ?>
                            <a href="login.php" onclick="event.stopPropagation();"><i class="far fa-heart"></i></a>
                        <?php } ?>
                    </div>
                    <div class="image-overlay">
                        <h3><?php echo $data['judulfoto'] ?></h3>
                        <p>
                            <?php
                            $like = mysqli_query($koneksi, "SELECT * FROM likefoto WHERE fotoid='$fotoid'");
                            echo mysqli_num_rows($like) . ' Suka | ';

                            $jmlkomen = mysqli_query($koneksi, "SELECT * FROM komentarfoto WHERE fotoid='$fotoid'");
                            echo mysqli_num_rows($jmlkomen) . ' Komentar';
                            ?>
                        </p>
                    </div>
                </div>

                <!-- Modal untuk setiap foto -->
                <dialog id="modal-<?php echo $data['fotoid'] ?>" class="modal-foto">
                    <article>
                        <div class="modal-header">
                            <div>
                                <h3><?php echo $data['judulfoto'] ?></h3>
                                <p><small>Oleh: <?php echo $data['namalengkap'] ?> • <?php echo $data['tanggalunggah'] ?> • Album: <?php echo $data['namaalbum'] ?></small></p>
                            </div>
                            <a href="#" aria-label="Close" class="close-btn" data-target="modal-<?php echo $data['fotoid'] ?>">×</a>
                        </div>
                        <div class="modal-flex-container">
                            <div class="modal-image-container">
                                <img src="assets/img/<?php echo $data['lokasifile'] ?>" alt="<?php echo $data['judulfoto'] ?>">
                                <p class="image-description"><?php echo $data['deskripsifoto'] ?></p>
                            </div>
                            
                            <div class="modal-comment-container">
                                <h4>Komentar</h4>
                                <div class="comment-section">
                                    <?php
                                    $fotoid = $data['fotoid'];
                                    $komentar = mysqli_query($koneksi, "SELECT * FROM komentarfoto INNER JOIN user ON komentarfoto.userid=user.userid WHERE komentarfoto.fotoid='$fotoid'");
                                    if (mysqli_num_rows($komentar) > 0) {
                                        while ($row = mysqli_fetch_array($komentar)) {
                                    ?>
                                        <div class="comment">
                                            <strong><?php echo $row['namalengkap'] ?></strong>
                                            <p><?php echo $row['isikomentar'] ?></p>
                                        </div>
                                    <?php } 
                                    } else { ?>
                                        <p class="no-comments">Belum ada komentar</p>
                                    <?php } ?>
                                </div>
                                
                                <div class="comment-form-container">
                                    <?php if ($userid) { ?>
                                        <form action="config/proses_komentar.php" method="POST" class="comment-form">
                                            <input type="hidden" name="fotoid" value="<?php echo $data['fotoid'] ?>">
                                            <div class="comment-input-group">
                                                <input type="text" name="isikomentar" placeholder="Tambah Komentar" required>
                                                <button type="submit" name="kirimkomentar" class="btn-primary">Kirim</button>
                                            </div>
                                        </form>
                                    <?php } else { ?>
                                        <p class="login-to-comment"><a href="#" data-target="modal-login">Login</a> untuk menambahkan komentar.</p>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </article>
                </dialog>
            <?php } ?>
        </div>
    </main>

    <!-- Modal Login -->
    <dialog id="modal-login">
        <article>
            <header>
                <a href="#" aria-label="Close" class="close" data-target="modal-login"></a>
                <h3>Login</h3>
            </header>
            <form action="config/aksi_login.php" method="POST">
                <label for="username">
                    Username
                    <input type="text" id="username" name="username" placeholder="Username" required>
                </label>
                <label for="password">
                    Password
                    <input type="password" id="password" name="password" placeholder="Password" required>
                </label>
                <button type="submit" class="contrast">Login</button>
                <p>Belum punya akun? <a href="register.php">Daftar sekarang</a></p>
            </form>
        </article>
    </dialog>

    <footer class="container">
    <small>&copy;2024 Projek Galeri Foto | Mukhammad Bagus Prastyo</small>

    <script src="script.js"></script>
    <script>
        // Modal handling
        document.addEventListener('click', function(e) {
            // Buka modal dengan data-target
            const target = e.target.getAttribute('data-target') || 
                          (e.target.parentElement ? e.target.parentElement.getAttribute('data-target') : null) || 
                          (e.target.closest('[data-target]') ? e.target.closest('[data-target]').getAttribute('data-target') : null);
            
            if (target) {
                e.preventDefault();
                const modal = document.getElementById(target);
                if (modal) {
                    if (modal.tagName === 'DIALOG') {
                        if (modal.open) {
                            modal.close();
                        } else {
                            modal.showModal();
                        }
                    }
                }
            }
            
            // Tutup modal jika klik tombol close atau klik di luar konten modal
            if (e.target.classList.contains('close-btn') || 
                e.target.classList.contains('close')) {
                const dialog = e.target.closest('dialog');
                if (dialog) {
                    dialog.close();
                }
            }
            
            // Tutup modal jika klik di luar konten
            if (e.target.tagName === 'DIALOG') {
                e.target.close();
            }
        });
    </script>
</body>

</html>
