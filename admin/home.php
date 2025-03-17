<?php
session_start();
$userid = $_SESSION['userid'];
include '../config/koneksi.php';
if ($_SESSION['status'] != 'login') {
    echo "<script>
    alert('Anda belum Login!');
    location.href='../index.php';
    </script>";
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Galeri Foto - Home</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@1/css/pico.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            max-width: 100%;
            overflow-x: hidden;
        }

        .navbar {
            padding: 1rem;
            background-color: var(--card-background-color);
        }

        .navbar ul {
            margin: 0;
        }

        .gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1rem;
            margin: 1rem 0;
        }

        .gallery-item {
            position: relative;
            overflow: hidden;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
            transition: all 0.3s;
        }

        .gallery-item:hover {
            box-shadow: 0 14px 28px rgba(0, 0, 0, 0.25), 0 10px 10px rgba(0, 0, 0, 0.22);
            transform: translateY(-5px);
        }

        .gallery-item img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            display: block;
        }

        .image-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: rgba(0, 0, 0, 0.7);
            padding: 0.5rem;
            color: white;
        }

        .image-overlay h3 {
            margin: 0;
            font-size: 1rem;
        }

        .image-overlay p {
            margin: 0;
            font-size: 0.8rem;
        }

        .btn-like {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: rgba(255, 255, 255, 0.7);
            border-radius: 50%;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-like a {
            color: #ff0000;
            text-decoration: none;
        }

        .modal-foto article {
            max-width: 90vw;
            width: 100%;
        }

        .modal-flex-container {
            display: flex;
            flex-direction: column;
        }

        @media (min-width: 768px) {
            .modal-flex-container {
                flex-direction: row;
            }

            .modal-image-container {
                flex: 1;
            }

            .modal-comment-container {
                flex: 1;
                padding-left: 1rem;
            }
        }

        .modal-image-container img {
            width: 100%;
            height: auto;
            object-fit: contain;
            max-height: 70vh;
        }

        .comment-section {
            max-height: 30vh;
            overflow-y: auto;
            margin-bottom: 1rem;
        }

        .comment {
            background-color: var(--card-background-color);
            padding: 0.5rem;
            margin-bottom: 0.5rem;
            border-radius: 4px;
        }

        .comment p {
            margin: 0;
        }

        .album-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .album-badge {
            padding: 0.25rem 0.5rem;
            background-color: var(--primary);
            color: white;
            border-radius: 4px;
            text-decoration: none;
        }

        .album-badge:hover {
            background-color: var(--primary-hover);
        }

        footer {
            margin-top: 2rem;
            padding: 1rem;
            text-align: center;
            background-color: var(--card-background-color);
        }

        #navbar {
            position: sticky;
            top: 0;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        #navbar.scrolled {
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            background-color: var(--card-sectionning-background-color);
        }
    </style>
</head>

<body>
    <nav class="container-fluid navbar" id="navbar">
        <li><strong><a href="index.php"><i class="fa-solid fa-gallery-thumbnails"> Galeri Foto</i></a></strong></li>
        <ul>
            <li><a href="home.php" class="active"><i class="fas fa-home"></i> Home</a></li>
            <li><a href="album.php"><i class="fas fa-folder"></i> Album</a></li>
            <li><a href="foto.php"><i class="fas fa-image"></i> Foto</a></li>
            <li><a href="../config/aksi_logout.php" role="button"><i class="fas fa-sign-out-alt"></i> Keluar</a></li>
        </ul>
    </nav>

    <main class="container">
        <h2>Koleksi Foto Anda</h2>

        <div class="album-badges">
            <span>Album: </span>
            <a href="home.php" class="album-badge"><i class="fas fa-images"></i> Semua</a>
            <?php
            $album = mysqli_query($koneksi, "SELECT * FROM album WHERE userid='$userid'");
            while ($row = mysqli_fetch_array($album)) { ?>
                <a href="home.php?albumid=<?php echo $row['albumid'] ?>" class="album-badge"><?php echo $row['namaalbum'] ?></a>
            <?php } ?>
        </div>

        <div class="gallery">
            <?php
            if (isset($_GET['albumid'])) {
                $albumid = $_GET['albumid'];
                $query = mysqli_query($koneksi, "SELECT foto.*, user.namalengkap, album.namaalbum 
                                                 FROM foto 
                                                 INNER JOIN user ON foto.userid = user.userid 
                                                 INNER JOIN album ON foto.albumid = album.albumid 
                                                 WHERE foto.userid='$userid' AND foto.albumid='$albumid'");
            } else {
                $query = mysqli_query($koneksi, "SELECT foto.*, user.namalengkap, album.namaalbum 
                                                 FROM foto 
                                                 INNER JOIN user ON foto.userid = user.userid 
                                                 INNER JOIN album ON foto.albumid = album.albumid 
                                                 WHERE foto.userid='$userid'");
            }

            while ($data = mysqli_fetch_array($query)) { ?>
                <div class="gallery-item" data-target="modal-<?php echo $data['fotoid'] ?>">
                    <img src="../assets/img/<?php echo $data['lokasifile'] ?>" alt="<?php echo $data['judulfoto'] ?>">
                    <div class="btn-like">
                        <?php
                        $fotoid = $data['fotoid'];
                        $ceksuka = mysqli_query($koneksi, "SELECT * FROM likefoto WHERE fotoid='$fotoid' AND userid='$userid'");
                        if (mysqli_num_rows($ceksuka) == 1) { ?>
                            <a href="../config/proses_like.php?fotoid=<?php echo $data['fotoid'] ?>" onclick="event.stopPropagation();"><i class="fas fa-heart"></i></a>
                        <?php } else { ?>
                            <a href="../config/proses_like.php?fotoid=<?php echo $data['fotoid'] ?>" onclick="event.stopPropagation();"><i class="far fa-heart"></i></a>
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
                            <a href="#" aria-label="Close" class="close" data-target="modal-<?php echo $data['fotoid'] ?>"></a>
                        </div>
                        <div class="modal-flex-container">
                            <div class="modal-image-container">
                                <img src="../assets/img/<?php echo $data['lokasifile'] ?>" alt="<?php echo $data['judulfoto'] ?>">
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
                                    <form action="../config/proses_komentar.php" method="POST" class="comment-form">
                                        <input type="hidden" name="fotoid" value="<?php echo $data['fotoid'] ?>">
                                        <div class="grid">
                                            <input type="text" name="isikomentar" placeholder="Tambah Komentar" required>
                                            <button type="submit" name="kirimkomentar" class="primary">Kirim</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </article>
                </dialog>
            <?php } ?>
        </div>
    </main>

    <footer class="container-fluid">
        <small>&copy;2024 Projek Galeri Foto | Mukhammad Bagus Prastyo</small>
    </footer>

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

            // Tutup modal jika klik tombol close
            if (e.target.classList.contains('close')) {
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

        // Navbar scroll effect
        window.addEventListener("scroll", function() {
            const navbar = document.getElementById("navbar");
            if (window.scrollY > 50) {
                navbar.classList.add("scrolled");
            } else {
                navbar.classList.remove("scrolled");
            }
        });
    </script>
</body>

</html>