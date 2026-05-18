<?php
session_start();
include 'connect.php';

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: admin_dashboard.php');
    exit;
}

$error = '';

if (isset($_POST['btnLogin'])) {
    $login = mysqli_real_escape_string($connection, trim($_POST['txtlogin']));

    if ($login === '') {
        $error = 'Enter admin Student ID or email.';
    } else {
        $sql = "SELECT studentID, firstName, lastName, email, role FROM `USER` WHERE (studentID='" . $login . "' OR email='" . $login . "') AND role='Admin' LIMIT 1";
        $result = mysqli_query($connection, $sql);
        $row = mysqli_fetch_assoc($result);

        if ($row) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_studentid'] = $row['studentID'];
            $_SESSION['admin_name'] = $row['firstName'] . ' ' . $row['lastName'];
            $_SESSION['admin_email'] = $row['email'];
            $_SESSION['admin_role'] = $row['role'];
            header('Location: admin_dashboard.php');
            exit;
        } else {
            $error = 'Invalid admin credentials.';
        }
    }
}

$title = 'Admin Login';
$stylesheets = ['css/auth.css'];
require_once 'includes/header.php';
?>

<div class="auth-page">
    <div class="auth-card">
        <h1>Admin Login</h1>
        <p>Access the admin dashboard.</p>
        <p>Login using the admin Student ID or email.</p>

        <?php if ($error != ''): ?>
            <div class="notice error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="post">
            <label>Student ID or Email</label>
            <input type="text" name="txtlogin" required>
            <br><br>

            <div class="auth-actions">
                <button class="btn primary" type="submit" name="btnLogin">Login</button>
                <a class="btn secondary" href="index.php">Home</a>
            </div>
        </form>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
