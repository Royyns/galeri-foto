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
  <title>Admin Galeri Foto - Album</title>
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

    .action-buttons {
      display: flex;
      gap: 0.5rem;
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
      <li><a href="album.php" class="active"><i class="fas fa-folder"></i> Album</a></li>
      <li><a href="foto.php"><i class="fas fa-image"></i> Foto</a></li>
      <li><a href="../config/aksi_logout.php" role="button"><i class="fas fa-sign-out-alt"></i> Keluar</a></li>
    </ul>
  </nav>

  <main class="container">
    <h2>Manajemen Album</h2>

    <div class="grid-container">
      <div>
        <article>
          <header>
            <h3>Tambah Album</h3>
          </header>
          <form action="../config/aksi_album.php" method="POST">
            <label for="namaalbum">
              Nama Album
              <input type="text" id="namaalbum" name="namaalbum" placeholder="Nama album baru" required>
            </label>
            <label for="deskripsi">
              Deskripsi
              <textarea id="deskripsi" name="deskripsi" placeholder="Deskripsi album" required></textarea>
            </label>
            <button type="submit" name="tambah" class="primary">Tambah Album</button>
          </form>
        </article>
      </div>

      <div>
        <article>
          <header>
            <h3>Daftar Album</h3>
          </header>
          <div class="table-container">
            <table>
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Nama Album</th>
                  <th scope="col">Deskripsi</th>
                  <th scope="col">Tanggal</th>
                  <th scope="col">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $no = 1;
                $userid = $_SESSION['userid'];
                $sql = mysqli_query($koneksi, "SELECT * FROM album WHERE userid='$userid'");
                while ($data = mysqli_fetch_array($sql)) {
                ?>
                  <tr>
                    <td><?php echo $no++ ?></td>
                    <td><?php echo $data['namaalbum'] ?></td>
                    <td><?php echo $data['deskripsi'] ?></td>
                    <td><?php echo $data['tanggalbuat'] ?></td>
                    <td class="action-buttons">
                      <button href="#" class="primary" data-target="modal-edit-<?php echo $data['albumid'] ?>"><i class="bx bxs-edit"></i></button>
                      <button href="#" class="contrast" data-target="modal-hapus-<?php echo $data['albumid'] ?>"><i class="bx bxs-trash-alt"></i></button>
                    </td>
                  </tr>

                  <!-- Modal Edit -->
                  <dialog id="modal-edit-<?php echo $data['albumid'] ?>">
                    <article>
                      <header>
                        <h3>Edit Album</h3>
                        <a href="#" aria-label="Close" class="close" data-target="modal-edit-<?php echo $data['albumid'] ?>"></a>
                      </header>
                      <form action="../config/aksi_album.php" method="POST">
                        <input type="hidden" name="albumid" value="<?php echo $data['albumid'] ?>">
                        <label for="edit-namaalbum-<?php echo $data['albumid'] ?>">
                          Nama Album
                          <input type="text" id="edit-namaalbum-<?php echo $data['albumid'] ?>" name="namaalbum" value="<?php echo $data['namaalbum'] ?>" required>
                        </label>
                        <label for="edit-deskripsi-<?php echo $data['albumid'] ?>">
                          Deskripsi
                          <textarea id="edit-deskripsi-<?php echo $data['albumid'] ?>" name="deskripsi" required><?php echo $data['deskripsi']; ?></textarea>
                        </label>
                        <footer>
                          <button type="submit" name="edit" class="primary">Simpan Perubahan</button>
                        </footer>
                      </form>
                    </article>
                  </dialog>

                  <!-- Modal Hapus -->
                  <dialog id="modal-hapus-<?php echo $data['albumid'] ?>">
                    <article>
                      <header>
                        <h3>Konfirmasi Hapus</h3>
                        <a href="#" aria-label="Close" class="close" data-target="modal-hapus-<?php echo $data['albumid'] ?>"></a>
                      </header>
                      <form action="../config/aksi_album.php" method="POST">
                        <input type="hidden" name="albumid" value="<?php echo $data['albumid'] ?>">
                        <p>Apakah anda yakin akan menghapus album <strong><?php echo $data['namaalbum'] ?></strong>?</p>
                        <footer>
                          <button href="#" role="button" class="secondary" data-target="modal-hapus-<?php echo $data['albumid'] ?>">Batal</button>
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