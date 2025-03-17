<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Galeri Foto</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@1/css/pico.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        #navbar {
            position: sticky;
            top: 0;
            z-index: 1000;
            transition: all 0.3s ease;
            padding: 1rem;
            background-color: var(--card-background-color);
        }

        #navbar.scrolled {
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            background-color: var(--card-sectionning-background-color);
        }

        .login-container {
            max-width: 400px;
            margin: 2rem auto;
            padding: 2rem;
            background-color: var(--card-background-color);
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .text-center {
            text-align: center;
        }

        footer {
            margin-top: 2rem;
            padding: 1rem;
            text-align: center;
            background-color: var(--card-background-color);
        }
    </style>
</head>

<body>
    <nav class="container-fluid" id="navbar">
        <li><strong><a href="index.php"><i class="fa-solid fa-gallery-thumbnails"> Galeri Foto</i></a></strong></li>
        <ul>
            <li><a href="register.php" role="button"><i class="fas fa-user-plus"></i> Daftar</a></li>
            <li><a href="login.php" role="button"><i class="fas fa-sign-in-alt"></i> Masuk</a></li>
        </ul>
    </nav>

    <main class="container">
        <div class="login-container">
            <h3 class="text-center">Login</h3>
            <form action="config/aksi_login.php" method="POST">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Username" required>

                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Password" required>

                <button type="submit" name="kirim" class="contrast">Masuk</button>
            </form>
            <p class="text-center">Belum punya akun? <a href="register.php">Daftar sekarang</a></p>
        </div>
    </main>

    <footer class="container-fluid">
        <small>&copy;2024 Projek Galeri Foto | Mukhammad Bagus Prastyo</small>
    </footer>

    <script>
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