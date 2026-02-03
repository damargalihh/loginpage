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
            </a>
            <button class="menu-toggle" onclick="toggleMenu()">
                <i class="fas fa-bars"></i>
            </button>
            <ul class="nav-menu" id="navMenu">
                <li><a href="index.php"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="status.php" class="active"><i class="fas fa-wifi"></i> Status</a></li>
                <li><a href="contact.php"><i class="fas fa-headset"></i> Kontak</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <div class="card" style="max-width: 480px;">
            <div class="card-body" style="padding: 35px;">
                <!-- Header -->
                <div style="text-align: center; margin-bottom: 25px;">
                    <h2 style="font-size: 1.4rem; color: var(--gray-800); margin-bottom: 5px;">Status Penggunaan Internet</h2>
                    <p style="color: var(--gray-500); font-size: 0.85rem;">Mahasiswa</p>
                </div>

                <!-- Status Table -->
                <div style="background: var(--gray-100); border-radius: 12px; overflow: hidden;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr style="border-bottom: 1px solid var(--gray-200);">
                            <td style="padding: 12px 15px; font-size: 0.85rem; color: var(--gray-600);">Username</td>
                            <td style="padding: 12px 15px; font-weight: 500; color: var(--gray-800);"><?php echo htmlspecialchars($nim); ?></td>
                        </tr>
                        <tr style="border-bottom: 1px solid var(--gray-200);">
                            <td style="padding: 12px 15px; font-size: 0.85rem; color: var(--gray-600);">IP Address</td>
                            <td style="padding: 12px 15px; font-weight: 500; color: var(--gray-800);"><?php echo $ip_address; ?></td>
                        </tr>
                        <tr style="border-bottom: 1px solid var(--gray-200);">
                            <td style="padding: 12px 15px; font-size: 0.85rem; color: var(--gray-600);">MAC Address</td>
                            <td style="padding: 12px 15px; font-weight: 500; color: var(--gray-800);"><?php echo isset($_SERVER['HTTP_X_MAC_ADDRESS']) ? $_SERVER['HTTP_X_MAC_ADDRESS'] : 'F8:A2:D6:BD:0F:EF'; ?></td>
                        </tr>
                        <tr style="border-bottom: 1px solid var(--gray-200);">
                            <td style="padding: 12px 15px; font-size: 0.85rem; color: var(--gray-600);">Connected / Left</td>
                            <td style="padding: 12px 15px; font-weight: 500; color: var(--gray-800);"><span id="sessionDuration"><?php echo sprintf('%dh%dm%ds', $hours, $minutes, $seconds); ?></span> / <span style="color: var(--success-color);">4h40m59s</span></td>
                        </tr>
                        <tr style="border-bottom: 1px solid var(--gray-200);">
                            <td style="padding: 12px 15px; font-size: 0.85rem; color: var(--gray-600);">Download / Upload</td>
                            <td style="padding: 12px 15px; font-weight: 500; color: var(--gray-800);">
                                <span style="color: var(--primary-color);">40.2 MiB</span> / <span style="color: var(--secondary-color);">25.9 MiB</span>
                            </td>
                        </tr>
                        <tr style="border-bottom: 1px solid var(--gray-200);">
                            <td style="padding: 12px 15px; font-size: 0.85rem; color: var(--gray-600);">Sisa Kuota</td>
                            <td style="padding: 12px 15px; font-weight: 600; color: var(--success-color);">Unlimited</td>
                        </tr>
                        <tr>
                            <td style="padding: 12px 15px; font-size: 0.85rem; color: var(--gray-600);">Status Refresh</td>
                            <td style="padding: 12px 15px; font-weight: 500; color: var(--gray-800);">1m</td>
                        </tr>
                    </table>
                </div>

                <!-- Logout Button -->
                <a href="logout.php" class="btn btn-danger btn-block" style="margin-top: 25px;">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
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
            document.getElementById('sessionDuration').textContent = hours + 'h' + minutes + 'm' + seconds + 's';
        }
        setInterval(updateSessionDuration, 1000);
    </script>
</body>
</html>