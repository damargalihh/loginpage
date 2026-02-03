<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout Berhasil - Hotspot UMPKU</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <meta http-equiv="refresh" content="5;url=login.php">
</head>
<body>
    <!-- Background Animation -->
    <div class="bg-animated">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
    </div>

    <!-- Navigation -->
    <nav class="navbar">
        <div class="container">
            <a href="index.php" class="navbar-brand">
                <img src="logo_web_umpku_color.png" alt="Logo UMPKU">
            </a>
            <ul class="nav-menu" id="navMenu">
                <li><a href="index.php"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a></li>
                <li><a href="register.php"><i class="fas fa-user-plus"></i> Daftar</a></li>
                <li><a href="contact.php"><i class="fas fa-headset"></i> Kontak</a></li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <div class="card">
            <div class="card-body" style="text-align: center; padding: 50px 40px;">
                <div style="width: 80px; height: 80px; background: linear-gradient(135deg, var(--success-color), #66BB6A); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 25px;">
                    <i class="fas fa-check" style="font-size: 2.5rem; color: white;"></i>
                </div>
                
                <h1 style="color: var(--success-color); margin-bottom: 10px; font-size: 1.5rem;">Logout Berhasil</h1>
                <p style="color: var(--gray-500); margin-bottom: 30px;">
                    Terima kasih telah menggunakan Hotspot UMPKU
                </p>

                <a href="login.php" class="btn btn-primary btn-block">
                    <i class="fas fa-sign-in-alt"></i> Login Kembali
                </a>

                <p style="color: var(--gray-400); margin-top: 20px; font-size: 0.85rem;">
                    Redirect dalam <span id="countdown">5</span> detik...
                </p>
            </div>
        </div>
    </main>

    <script>
        let countdown = 5;
        const countdownEl = document.getElementById('countdown');
        const timer = setInterval(() => {
            countdown--;
            countdownEl.textContent = countdown;
            if (countdown <= 0) {
                clearInterval(timer);
                window.location.href = 'login.php';
            }
        }, 1000);
    </script>
</body>
</html>