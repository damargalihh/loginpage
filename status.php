<?php
require_once 'config.php';

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$nim = $_SESSION['nim'];
$nama = $_SESSION['nama'];
$login_time = $_SESSION['login_time'];

// Hitung waktu sesi
$session_duration = time() - $login_time;
$hours = floor($session_duration / 3600);
$minutes = floor(($session_duration % 3600) / 60);
$seconds = $session_duration % 60;

// Ambil data user lengkap
try {
    $conn = getConnection();
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    
    // Ambil history login
    $stmt2 = $conn->prepare("SELECT * FROM login_history WHERE user_id = ? ORDER BY login_time DESC LIMIT 5");
    $stmt2->bind_param("i", $user_id);
    $stmt2->execute();
    $history_result = $stmt2->get_result();
    $login_history = $history_result->fetch_all(MYSQLI_ASSOC);
    $stmt2->close();
    
    $conn->close();
} catch (Exception $e) {
    $user = null;
    $login_history = [];
}

// Simulasi data penggunaan (dalam implementasi nyata, ini dari MikroTik API)
$data_used = rand(100, 500); // MB
$data_limit = 1024; // 1 GB
$data_percentage = ($data_used / $data_limit) * 100;

$ip_address = $_SERVER['REMOTE_ADDR'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Koneksi - Hotspot UMPKU</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .progress-bar {
            width: 100%;
            height: 10px;
            background: var(--gray-200);
            border-radius: 10px;
            overflow: hidden;
            margin-top: 10px;
        }
        .progress-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--success-color), var(--primary-color));
            border-radius: 10px;
            transition: width 0.5s ease;
        }
        .progress-bar-fill.warning {
            background: linear-gradient(90deg, var(--warning-color), #ff9800);
        }
        .progress-bar-fill.danger {
            background: linear-gradient(90deg, var(--danger-color), #c82333);
        }
    </style>
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
                <div class="brand-text">
                    <span>Hotspot UMPKU</span>
                </div>
            </a>
            <button class="menu-toggle" onclick="toggleMenu()">
                <i class="fas fa-bars"></i>
            </button>
            <ul class="nav-menu" id="navMenu">
                <li><a href="index.php"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="status.php" class="active"><i class="fas fa-wifi"></i> Status</a></li>
                <li><a href="contact.php"><i class="fas fa-headset"></i> Kontak</a></li>
                <li><a href="logout.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <div class="card" style="max-width: 500px;">
            <div class="card-body" style="padding: 40px;">
                <!-- Profile -->
                <div style="text-align: center; margin-bottom: 30px;">
                    <div style="width: 70px; height: 70px; background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; color: white; font-size: 1.8rem; font-weight: 600;">
                        <?php echo strtoupper(substr($nama, 0, 1)); ?>
                    </div>
                    <h2 style="font-size: 1.3rem; color: var(--gray-800); margin-bottom: 5px;"><?php echo htmlspecialchars($nama); ?></h2>
                    <p style="color: var(--gray-500); font-size: 0.9rem;">NIM: <?php echo htmlspecialchars($nim); ?></p>
                </div>

                <!-- Status -->
                <div style="background: var(--gray-100); border-radius: 12px; padding: 20px; margin-bottom: 25px;">
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 15px;">
                        <span class="status-dot online"></span>
                        <span style="font-weight: 600; color: var(--success-color);">Terhubung</span>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div>
                            <p style="font-size: 0.75rem; color: var(--gray-500); margin-bottom: 3px;">IP Address</p>
                            <p style="font-weight: 500; color: var(--gray-700);"><?php echo $ip_address; ?></p>
                        </div>
                        <div>
                            <p style="font-size: 0.75rem; color: var(--gray-500); margin-bottom: 3px;">Durasi</p>
                            <p style="font-weight: 500; color: var(--gray-700);" id="sessionDuration"><?php echo sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Logout Button -->
                <a href="logout.php" class="btn btn-danger btn-block">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>

                <div class="footer-text" style="margin-top: 25px;">
                    <a href="contact.php" class="link" style="font-size: 0.9rem;"><i class="fas fa-headset"></i> Laporkan Masalah</a>
                </div>
            </div>
        </div>
    </main>

    <script src="js/main.js"></script>
    <script>
        let sessionStart = <?php echo $login_time; ?>;
        function updateSessionDuration() {
            let now = Math.floor(Date.now() / 1000);
            let duration = now - sessionStart;
            let hours = Math.floor(duration / 3600);
            let minutes = Math.floor((duration % 3600) / 60);
            let seconds = duration % 60;
            document.getElementById('sessionDuration').textContent = 
                String(hours).padStart(2, '0') + ':' + String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');
        }
        setInterval(updateSessionDuration, 1000);
    </script>
</body>
</html>