<?php
session_start();
if(!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true){
    header('Location: user_login.php');
    exit;
}
include 'connect.php';

$studentID = $_SESSION['user_studentid'];
$msg = '';
$error = '';

/* Fetch user */
$result = mysqli_query($connection, "SELECT * FROM `USER` WHERE studentID='" . mysqli_real_escape_string($connection,$studentID) . "' LIMIT 1");
$user   = mysqli_fetch_assoc($result);

if(isset($_POST['btnConfirm'])){
    $fn    = mysqli_real_escape_string($connection, trim($_POST['txtfirstname']));
    $ln    = mysqli_real_escape_string($connection, trim($_POST['txtlastname']));
    $email = mysqli_real_escape_string($connection, trim($_POST['txtemail']));
    $phone = mysqli_real_escape_string($connection, trim($_POST['txtphone'] ?? ''));

    if($fn === '' || $ln === '' || $email === ''){
        $error = 'First name, last name, and email are required.';
    } else {
        /* Check email not taken by someone else */
        $emailCheck = mysqli_query($connection, "SELECT studentID FROM `USER` WHERE email='$email' AND studentID!='$studentID' LIMIT 1");
        if(mysqli_num_rows($emailCheck) > 0){
            $error = 'That email is already in use by another account.';
        } else {
            mysqli_query($connection, "UPDATE `USER` SET firstName='$fn', lastName='$ln', email='$email', contactNumber='$phone' WHERE studentID='$studentID'");
            $_SESSION['user_name']      = $fn.' '.$ln;
            $_SESSION['user_firstname'] = $fn;
            $_SESSION['user_email']     = $email;
            $user['firstName']     = $fn;
            $user['lastName']      = $ln;
            $user['email']         = $email;
            $user['contactNumber'] = $phone;
            $msg = 'Profile updated successfully.';
        }
    }
}

/* Password change */
if(isset($_POST['btnPassword'])){
    $current = trim($_POST['txtcurrent'] ?? '');
    $new1    = trim($_POST['txtnew1']    ?? '');
    $new2    = trim($_POST['txtnew2']    ?? '');
    if($current === '' || $new1 === '' || $new2 === ''){
        $error = 'Please fill in all password fields.';
    } elseif($new1 !== $new2){
        $error = 'New passwords do not match.';
    } elseif(strlen($new1) < 6){
        $error = 'New password must be at least 6 characters.';
    } elseif(!password_verify($current, $user['password'] ?? '')){
        $error = 'Current password is incorrect.';
    } else {
        $hashed = mysqli_real_escape_string($connection, password_hash($new1, PASSWORD_DEFAULT));
        mysqli_query($connection, "UPDATE `USER` SET password='$hashed' WHERE studentID='$studentID'");
        $msg = 'Password changed successfully.';
    }
}

$title = 'My Profile';
require_once 'includes/header.php';
?>

<div class="uni-page">
<div class="profile-layout">

    <!-- Sidebar -->
    <div class="profile-sidebar">
        <div class="sidebar-account-label">Account</div>
        <div class="sidebar-user">
            <div class="sidebar-avatar">👤</div>
            <div class="sidebar-name"><?php echo htmlspecialchars(($user['firstName']??'Name').' '.($user['lastName']??'')); ?></div>
            <div style="color:rgba(255,255,255,0.5);font-size:0.7rem;margin-top:4px;"><?php echo htmlspecialchars($user['studentID']); ?></div>
        </div>
        <div class="sidebar-links">
            <a href="user_profile.php" class="active">My Profile</a>
            <a href="user_dashboard.php">Dashboard</a>
            <a href="user_new_listing.php">+ New Listing</a>
            <a href="user_logout.php" class="sidebar-logout">Logout</a>
        </div>
    </div>

    <!-- Edit Card -->
    <div class="profile-edit-card">
        <div class="profile-hello">Hello <?php echo htmlspecialchars($user['firstName'] ?? 'Name'); ?>!</div>

        <?php if($msg): ?><div class="notice success"><?php echo htmlspecialchars($msg); ?></div><?php endif; ?>
        <?php if($error): ?><div class="notice error"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>

        <!-- Profile Info -->
        <div class="profile-edit-title">Edit Profile</div>
        <form method="post">
            <div class="profile-form-row">
                <div class="profile-form-group">
                    <label class="form-group-label">First Name</label>
                    <input type="text" name="txtfirstname" value="<?php echo htmlspecialchars($user['firstName']??''); ?>" required>
                </div>
                <div class="profile-form-group">
                    <label class="form-group-label">Last Name</label>
                    <input type="text" name="txtlastname" value="<?php echo htmlspecialchars($user['lastName']??''); ?>" required>
                </div>
            </div>
            <div class="profile-form-group">
                <label class="form-group-label">Email</label>
                <input type="email" name="txtemail" value="<?php echo htmlspecialchars($user['email']??''); ?>" required>
            </div>
            <div class="profile-form-group">
                <label class="form-group-label">Contact Number</label>
                <input type="text" name="txtphone" value="<?php echo htmlspecialchars($user['contactNumber']??''); ?>">
            </div>
            <div class="profile-form-group" style="background:#f5f5f5;padding:10px;border-radius:6px;">
                <label class="form-group-label">Student ID</label>
                <div style="font-size:0.9rem;padding:2px 0;"><?php echo htmlspecialchars($user['studentID']); ?></div>
            </div>
            <div class="btn-center">
                <button type="submit" name="btnConfirm" class="btn-yellow">Save Profile</button>
            </div>
        </form>

        <hr style="margin:28px 0;border:none;border-top:1px solid #eee;">

        <!-- Change Password -->
        <div class="profile-edit-title">Change Password</div>
        <form method="post">
            <div class="profile-form-group">
                <label class="form-group-label">Current Password</label>
                <input type="password" name="txtcurrent" placeholder="••••••••">
            </div>
            <div class="profile-form-row">
                <div class="profile-form-group">
                    <label class="form-group-label">New Password</label>
                    <input type="password" name="txtnew1" placeholder="••••••••">
                </div>
                <div class="profile-form-group">
                    <label class="form-group-label">Confirm New</label>
                    <input type="password" name="txtnew2" placeholder="••••••••">
                </div>
            </div>
            <div class="btn-center">
                <button type="submit" name="btnPassword" class="btn-yellow">Change Password</button>
            </div>
        </form>
    </div>

</div>
</div>

<?php require_once 'includes/footer.php'; ?>
