<?php
require_once 'config.php';

// Update logout time di history
if (isset($_SESSION['user_id'])) {
    try {
        $conn = getConnection();
        $stmt = $conn->prepare("UPDATE login_history SET logout_time = NOW() WHERE user_id = ? AND logout_time IS NULL ORDER BY login_time DESC LIMIT 1");
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $stmt->close();
        $conn->close();
    } catch (Exception $e) {
        // Ignore database errors
    }
}

// Hapus semua session
$_SESSION = array();

// Hapus session cookie
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 42000, '/');
}

// Destroy session
session_destroy();

// Redirect options
$redirect_to_mikrotik = false; // Set true jika ingin redirect ke MikroTik logout

if ($redirect_to_mikrotik) {
    // Redirect ke MikroTik logout page
    header('Location: http://192.168.88.1/logout');
} else {
    // Redirect ke halaman logout sukses
    header('Location: logout_success.php');
}
exit;
?>
