<?php session_start(); ?>
<nav class="navbar navbar-expand-lg navbar-dark warna1">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="#">
            <img src="image/logonavbar (2).png" alt="Logo" width="50" height="50" class="d-inline-block align-text-top me-2">
        </a>
        <a class="navbar-brand" href="#">Klik.kita</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item me-4">
                <a class="nav-link" href="index2.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="tentang-kami.php">Tentang kami</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="produk.php">Produk</a>
            </li>

            <!-- Dinamis berdasarkan session -->
            <?php if (isset($_SESSION['login']) && $_SESSION['login'] === true): ?>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="adminpanel/index.php">Dashboard Admin</a>
                    </li>
                <?php endif; ?>

                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link" href="login.php">Login</a>
                </li>
            <?php endif; ?>

        </ul>
        </div>
    </div>
</nav>
