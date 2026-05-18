<?php
session_start();
include 'connect.php';

$error = '';

if(isset($_POST['btnSignup'])){
    $studentid = mysqli_real_escape_string($connection, trim($_POST['txtstudentid']));
    $firstname = mysqli_real_escape_string($connection, trim($_POST['txtfirstname']));
    $lastname  = mysqli_real_escape_string($connection, trim($_POST['txtlastname']));
    $contact   = mysqli_real_escape_string($connection, trim($_POST['txtcontact']));
    $email     = mysqli_real_escape_string($connection, trim($_POST['txtemail']));
    $password  = trim($_POST['txtpassword'] ?? '');

    if($studentid==='' || $firstname==='' || $lastname==='' || $email==='' || $password===''){
        $error = 'Please complete all required fields.';
    } else {
        $checkResult = mysqli_query($connection, "SELECT studentID FROM `USER` WHERE studentID='".$studentid."' OR email='".$email."'");
        if(mysqli_num_rows($checkResult) > 0){
            $error = 'Student ID or email already exists.';
        } else {
            $hashed = mysqli_real_escape_string($connection, password_hash($password, PASSWORD_DEFAULT));
            mysqli_query($connection, "INSERT INTO `USER`(studentID,firstName,lastName,contactNumber,email,role,password) VALUES('".$studentid."','".$firstname."','".$lastname."','".$contact."','".$email."','Student','".$hashed."')");
            header('Location: user_login.php?signup=success');
            exit;
        }
    }
}

$title       = 'Sign Up';
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
            Join thousands of students buying and selling campus essentials.
            Create your account and start listing today.
        </p>
    </div>

    <div class="auth-right">
        <div class="auth-card">
            <div class="auth-card-logo">
                <img src="img/cit_logo.png" alt="CIT-U Logo">
            </div>
            <h1>Register</h1>

            <?php if($error !== ''): ?>
                <div class="notice error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="post">
                <div class="auth-field">
                    <label>Student ID</label>
                    <input type="text" name="txtstudentid" maxlength="10" required>
                </div>
                <div class="auth-field">
                    <label>First Name</label>
                    <input type="text" name="txtfirstname" required>
                </div>
                <div class="auth-field">
                    <label>Last Name</label>
                    <input type="text" name="txtlastname" required>
                </div>
                <div class="auth-field">
                    <label>Contact Number</label>
                    <input type="text" name="txtcontact">
                </div>
                <div class="auth-field">
                    <label>Email</label>
                    <input type="email" name="txtemail" required>
                </div>
                <div class="auth-field">
                    <label>Password</label>
                    <input type="password" name="txtpassword" required>
                </div>
                <button class="auth-submit" type="submit" name="btnSignup">Create Account</button>
            </form>

            <div class="auth-helper">
                Already have an account? <a href="user_login.php">Login here.</a>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
