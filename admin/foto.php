<?php
session_start();
include '../config/koneksi.php';
if ($_SESSION['status'] != 'login') {
  echo "<script>
  alert('Anda Belum Login!');
  location.href='../index.php';
  </script>";
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Galeri Foto - Foto</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@1/css/pico.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
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

    .grid-container {
      display: grid;
      grid-template-columns: 1fr;
      gap: 1rem;
    }

    @media (min-width: 768px) {
      .grid-container {
        grid-template-columns: 1fr 2fr;
      }
    }

    .table-container {
      overflow-x: auto;
    }

    footer {
      margin-top: 2rem;
      padding: 1rem;
      text-align: center;
      background-color: var(--card-background-color);
    }

    .preview-image {
      max-width: 100px;
      max-height: 100px;
      object-fit: cover;
    }

    .file-preview {
      display: flex;
      align-items: center;
      gap: 1rem;
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
      <li><a href="home.php"><i class="fas fa-home"></i> Home</a></li>
      <li><a href="album.php"><i class="fas fa-folder"></i> Album</a></li>
      <li><a href="foto.php" class="active"><i class="fas fa-image"></i> Foto</a></li>
      <li><a href="../config/aksi_logout.php" role="button"><i class="fas fa-sign-out-alt"></i> Keluar</a></li>
    </ul>
  </nav>

  <main class="container">
    <h2>Manajemen Foto</h2>

    <div class="grid-container">
      <div>
        <article>
          <header>
            <h3>Tambah Foto</h3>
          </header>
          <form action="../config/aksi_foto.php" method="POST" enctype="multipart/form-data">
            <label for="judulfoto">
              Judul Foto
              <input type="text" id="judulfoto" name="judulfoto" placeholder="Judul foto baru" required>
            </label>
            <label for="deskripsifoto">
              Deskripsi
              <textarea id="deskripsifoto" name="deskripsifoto" placeholder="Deskripsi foto" required></textarea>
            </label>
            <label for="albumid">
              Album
              <select id="albumid" name="albumid" required>
                <?php
                $userid = $_SESSION['userid'];
                $sql_album = mysqli_query($koneksi, "SELECT * FROM album WHERE userid='$userid'");
                while ($data_album = mysqli_fetch_array($sql_album)) { ?>
                  <option value="<?php echo $data_album['albumid'] ?>"><?php echo $data_album['namaalbum'] ?></option>
                <?php } ?>
              </select>
            </label>
            <label for="lokasifile">
              File Foto
              <input type="file" id="lokasifile" name="lokasifile" required>
            </label>
            <button type="submit" name="tambah" class="">Tambah Foto</button>
          </form>
        </article>
      </div>

      <div>
        <article>
          <header>
            <h3>Daftar Foto</h3>
          </header>
          <div class="table-container">
            <table>
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Foto</th>
                  <th scope="col">Judul Foto</th>
                  <th scope="col">Deskripsi</th>
                  <th scope="col">Tanggal</th>
                  <th scope="col">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $no = 1;
                $userid = $_SESSION['userid'];
                $sql = mysqli_query($koneksi, "SELECT * FROM foto WHERE userid='$userid'");
                while ($data = mysqli_fetch_array($sql)) {
                ?>
                  <tr>
                    <td><?php echo $no++ ?></td>
                    <td><img src="../assets/img/<?php echo $data['lokasifile'] ?>" class="preview-image"></td>
                    <td><?php echo $data['judulfoto'] ?></td>
                    <td><?php echo $data['deskripsifoto'] ?></td>
                    <td><?php echo $data['tanggalunggah'] ?></td>
                    <td class="action-buttons">
                      <button href="#" class="primary" data-target="modal-edit-<?php echo $data['fotoid'] ?>"><i class="bx bxs-edit"></i></button>
                      <button href="#" class="contrast" data-target="modal-hapus-<?php echo $data['fotoid'] ?>"><i class="bx bxs-trash-alt"></i></button>
                    </td>
                  </tr>

                  <!-- Modal Edit -->
                  <dialog id="modal-edit-<?php echo $data['fotoid'] ?>">
                    <article>
                      <header>
                        <h3>Edit Foto</h3>
                        <a href="#" aria-label="Close" class="close" data-target="modal-edit-<?php echo $data['fotoid'] ?>"></a>
                      </header>
                      <form action="../config/aksi_foto.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="fotoid" value="<?php echo $data['fotoid'] ?>">
                        <label for="edit-judulfoto-<?php echo $data['fotoid'] ?>">
                          Judul Foto
                          <input type="text" id="edit-judulfoto-<?php echo $data['fotoid'] ?>" name="judulfoto" value="<?php echo $data['judulfoto'] ?>" required>
                        </label>
                        <label for="edit-deskripsifoto-<?php echo $data['fotoid'] ?>">
                          Deskripsi
                          <textarea id="edit-deskripsifoto-<?php echo $data['fotoid'] ?>" name="deskripsifoto" required><?php echo $data['deskripsifoto']; ?></textarea>
                        </label>
                        <label for="edit-albumid-<?php echo $data['fotoid'] ?>">
                          Album
                          <select id="edit-albumid-<?php echo $data['fotoid'] ?>" name="albumid">
                            <?php
                            $userid = $_SESSION['userid'];
                            $sql_album = mysqli_query($koneksi, "SELECT * FROM album WHERE userid='$userid'");
                            while ($data_album = mysqli_fetch_array($sql_album)) { ?>
                              <option <?php if ($data_album['albumid'] == $data['albumid']) { ?> selected="selected" <?php } ?> value="<?php echo $data_album['albumid'] ?>"><?php echo $data_album['namaalbum'] ?></option>
                            <?php } ?>
                          </select>
                        </label>
                        <label for="edit-lokasifile-<?php echo $data['fotoid'] ?>">
                          File Foto
                          <div class="file-preview">
                            <img src="../assets/img/<?php echo $data['lokasifile'] ?>" class="preview-image">
                            <input type="file" id="edit-lokasifile-<?php echo $data['fotoid'] ?>" name="lokasifile">
                          </div>
                          <small>Kosongkan jika tidak ingin mengubah foto</small>
                        </label>
                        <footer>
                          <button type="submit" name="edit" class="primary">Simpan Perubahan</button>
                        </footer>
                      </form>
                    </article>
                  </dialog>

                  <!-- Modal Hapus -->
                  <dialog id="modal-hapus-<?php echo $data['fotoid'] ?>">
                    <article>
                      <header>
                        <h3>Konfirmasi Hapus</h3>
                        <a href="#" aria-label="Close" class="close" data-target="modal-hapus-<?php echo $data['fotoid'] ?>"></a>
                      </header>
                      <form action="../config/aksi_foto.php" method="POST">
                        <input type="hidden" name="fotoid" value="<?php echo $data['fotoid'] ?>">
                        <p>Apakah anda yakin akan menghapus foto <strong><?php echo $data['judulfoto'] ?></strong>?</p>
                        <footer>
                          <button href="#" role="button" class="secondary" data-target="modal-hapus-<?php echo $data['fotoid'] ?>">Batal</button>
                          <hr>
                          <button type="submit" name="hapus" class="contrast">Hapus</button>
                        </footer>
                      </form>
                    </article>
                  </dialog>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </article>
      </div>
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
        (e.target.parentElement ? e.target.parentElement.getAttribute('data-target') : null);

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