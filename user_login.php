<?php
session_start();
include 'connect.php';

if(isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true){
    header('Location: index.php');
    exit;
}

$error = '';

if(isset($_POST['btnLogin'])){
    $login    = mysqli_real_escape_string($connection, trim($_POST['txtlogin']));
    $password = trim($_POST['txtpassword'] ?? '');

    if($login === '' || $password === ''){
        $error = 'Please enter your Student ID/email and password.';
    } else {
        /* No role filter — let password_verify handle auth; block Admins after */
        $sql    = "SELECT * FROM `USER`
                   WHERE (studentID='$login' OR email='$login')
                   LIMIT 1";
        $result = mysqli_query($connection, $sql);

        if($result === false){
            $error = 'Database error: ' . mysqli_error($connection);
        } else {
            $row = mysqli_fetch_assoc($result);
            if($row && !empty($row['password']) && password_verify($password, $row['password'])){
                /* Block admin accounts from student login */
                $userRole = $row['role'] ?? 'Student';
                if(strtolower($userRole) === 'admin'){
                    $error = 'Admin accounts must use the admin login page.';
                } else {
                    $_SESSION['user_logged_in']  = true;
                    $_SESSION['user_studentid']  = $row['studentID'];
                    $_SESSION['user_name']       = $row['firstName'].' '.$row['lastName'];
                    $_SESSION['user_firstname']  = $row['firstName'];
                    $_SESSION['user_email']      = $row['email'];
                    $_SESSION['user_role']       = $userRole;
                    header('Location: index.php');
                    exit;
                }
            } elseif($row && empty($row['password'])){
                $error = 'No password set for this account. Please register a new account.';
            } else {
                $error = 'Invalid login credentials.';
            }
        }
    }
}

$title       = 'Login';
$hide_navbar = true;
$stylesheets = ['css/auth.css'];
require_once 'includes/header.php';
?>

<div class="auth-page">
    <div class="auth-bg"></div>

    <div class="auth-left">
        <div class="auth-brand-row">
            <div class="auth-brand">UNISWAP</div>
            <span class="auth-brand-x">x</span>
            <img src="img/cit_logo.png" alt="CIT-U" class="auth-cit-logo">
        </div>
        <div class="auth-tagline">A University Exclusive E-Commerce Marketplace App</div>
        <p class="auth-desc">
            Buy and sell textbooks, uniforms, lab gear, and tools
            with fellow students on campus. Safe, easy, and exclusive
            to your university community.
        </p>
    </div>

    <div class="auth-right">
        <div class="auth-card">
            <div class="auth-card-logo">
                <img src="img/cit_logo.png" alt="CIT-U Logo">
            </div>
            <h1>Login</h1>

            <?php if(isset($_GET['signup']) && $_GET['signup'] == 'success'): ?>
                <div class="notice success">Registration successful. Please login.</div>
            <?php endif; ?>
            <?php if($error !== ''): ?>
                <div class="notice error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="post">
                <div class="auth-field">
                    <label>Email / Student ID</label>
                    <input type="text" name="txtlogin" placeholder="student@university.edu"
                           value="<?php echo isset($_POST['txtlogin']) ? htmlspecialchars($_POST['txtlogin']) : ''; ?>" required>
                </div>
                <div class="auth-field">
                    <label>Password</label>
                    <input type="password" name="txtpassword" placeholder="••••••••" required>
                </div>
                <button class="auth-submit" type="submit" name="btnLogin">Login</button>
            </form>

            <div class="auth-helper">
                Don't have an account? <a href="user_signup.php">Register here.</a>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
