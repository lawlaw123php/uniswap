# UNISWAP CRUD Operations - Complete Study Guide

## Table of Contents
1. Database Initialization
2. CREATE Operations (User Registration & Admin Add)
3. READ Operations (User List & Search)
4. UPDATE Operations (User Edit)
5. DELETE Operations (User Delete with Cascading)
6. Key Concepts & Best Practices
7. Quiz Preparation Checklist

---

## 1. DATABASE INITIALIZATION - connect.php

### Purpose
Establishes database connection and creates/initializes default admin account on page load.

### Key Code Breakdown

#### Connection Setup
```php
$servername = "localhost";
$username = "root";
$password = "";
$database = "uniswap";
$port = 3306;

mysqli_report(MYSQLI_REPORT_OFF);  // Suppress error reports
$connection = mysqli_connect($servername, $username, $password, $database, $port);

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}
mysqli_set_charset($connection, "utf8mb4");  // Support special characters
```

#### Admin Account Initialization
**Step 1: Check if admin tables exist**
```php
$adminProfileExists = mysqli_query($connection, "SHOW TABLES LIKE 'tbladminprofile'");
$adminAccountExists = mysqli_query($connection, "SHOW TABLES LIKE 'tbladminaccount'");
```

**Step 2: If tables exist, check if any admin exists**
```php
if ($adminProfileExists && mysqli_num_rows($adminProfileExists) > 0 && 
    $adminAccountExists && mysqli_num_rows($adminAccountExists) > 0) {
    
    $adminCheck = mysqli_query($connection, "SELECT COUNT(*) AS total FROM tbladminaccount");
    $adminRow = $adminCheck ? mysqli_fetch_assoc($adminCheck) : null;
```

**Step 3A: If NO admin exists, CREATE default admin**
```php
if ($adminRow && (int)$adminRow['total'] === 0) {
    // INSERT into tbladminprofile
    mysqli_query($connection, "INSERT INTO tbladminprofile(firstname, lastname, gender) 
        VALUES ('System', 'Administrator', 'Male')");
    
    // INSERT into tbladminaccount with LAST_INSERT_ID() to link them
    mysqli_query($connection, "INSERT INTO tbladminaccount(adminprofileid, emailadd, username, password) 
        VALUES (LAST_INSERT_ID(), 'admin@uniswap.local', 'adminmaster', '$2y$10$...')");
}
```
**Important**: `LAST_INSERT_ID()` automatically gets the ID from the previous insert.

**Step 3B: If admin EXISTS, UPDATE password**
```php
else {
    mysqli_query($connection, "UPDATE tbladminaccount SET password = '$2b$12$...' 
        WHERE username = 'adminmaster' OR emailadd = 'admin@uniswap.local'");
}
```

#### Add Password Column to USER Table
```php
mysqli_query($connection, "ALTER TABLE `USER` ADD COLUMN IF NOT EXISTS `password` 
    VARCHAR(255) DEFAULT NULL");
```

---

## 2. CREATE OPERATIONS - User Registration & Admin Add User

### CREATE Operation 1: User Sign Up (user_signup.php)

**Type**: Self-registration (Users sign themselves up)

#### Form Fields
```
- Student ID (required)
- First Name (required)
- Last Name (required)
- Contact Number (optional)
- Email (required)
- Password (required)
```

#### Code Walkthrough

**Step 1: Check if form submitted**
```php
if(isset($_POST['btnSignup'])){
    // Process data
}
```

**Step 2: Retrieve and sanitize form data**
```php
$studentid = mysqli_real_escape_string($connection, trim($_POST['txtstudentid']));
$firstname = mysqli_real_escape_string($connection, trim($_POST['txtfirstname']));
$lastname = mysqli_real_escape_string($connection, trim($_POST['txtlastname']));
$contact = mysqli_real_escape_string($connection, trim($_POST['txtcontact']));
$email = mysqli_real_escape_string($connection, trim($_POST['txtemail']));
$password = trim($_POST['txtpassword'] ?? '');
```
**Why?** `mysqli_real_escape_string()` prevents SQL injection attacks.

**Step 3: Validate all required fields**
```php
if($studentid === '' || $firstname === '' || $lastname === '' || $email === '' || $password === ''){
    $error = 'Please complete all required fields.';
}
```

**Step 4: Check for duplicate Student ID or Email**
```php
$checkSql = "SELECT studentID FROM `USER` WHERE studentID='".$studentid."' OR email='".$email."'";
$checkResult = mysqli_query($connection, $checkSql);

if(mysqli_num_rows($checkResult) > 0){
    $error = 'Student ID or email already exists.';
}
```
**Purpose**: Prevent duplicate accounts.

**Step 5: Hash password (MOST IMPORTANT)**
```php
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
$hashed_password_esc = mysqli_real_escape_string($connection, $hashed_password);
```
**Why?** Never store plain text passwords! `password_hash()` is the secure way.

**Step 6: INSERT new user**
```php
$sql = "INSERT INTO `USER`(studentID, firstName, lastName, contactNumber, email, role, password) 
    VALUES('".$studentid."', '".$firstname."', '".$lastname."', '".$contact."', '".$email."', 'Student', '".$hashed_password_esc."')";
mysqli_query($connection, $sql);
```

**Step 7: Redirect on success**
```php
header('Location: user_login.php?signup=success');
exit;
```

---

### CREATE Operation 2: Admin Add User (user_add.php)

**Type**: Admin-only user creation (Admin adds users manually)

#### Difference from user_signup.php
- **No password hashing** (Admin sets it separately)
- **Can assign role** (Student or Admin)
- **Admin verification** via `admin_guard.php`

#### Code Walkthrough

**Step 1: Display form**
```html
<form method="post">
    <input type="text" name="txtstudentid" required>
    <input type="text" name="txtfirstname" required>
    <input type="text" name="txtlastname" required>
    <input type="text" name="txtcontact">
    <input type="email" name="txtemail">
    <select name="txtrole">
        <option value="Student">Student</option>
        <option value="Admin">Admin</option>
    </select>
    <button type="submit" name="btnSave">Save User</button>
</form>
```

**Step 2: Check if form submitted**
```php
if(isset($_POST['btnSave'])){
    $studentid = $_POST['txtstudentid'];
    $firstname = $_POST['txtfirstname'];
    $lastname = $_POST['txtlastname'];
    $contact = $_POST['txtcontact'];
    $email = $_POST['txtemail'];
    $role = $_POST['txtrole'];
    
    // Check if student ID already exists
    $checkSql = "SELECT * FROM `USER` WHERE studentID='".$studentid."'";
    $checkResult = mysqli_query($connection, $checkSql);
    
    if(mysqli_num_rows($checkResult) > 0){
        echo "<script language='javascript'>
            alert('Student ID already exists.');
        </script>";
    } else {
        // INSERT into USER
        $sql = "INSERT INTO `USER`(studentID, firstName, lastName, contactNumber, email, role) 
            VALUES('".$studentid."', '".$firstname."', '".$lastname."', '".$contact."', '".$email."', '".$role."')";
        mysqli_query($connection, $sql);
        
        echo "<script language='javascript'>
            alert('User saved.');
            window.location.href='user_list.php';
        </script>";
    }
}
```

**Key Difference**: No password field! Admin can set password later.

---

## 3. READ OPERATIONS - User List & Search

### READ Operation: User List (user_list.php)

**Type**: Display all users with statistics and search functionality

#### Statistics (Counts)
```php
$sqlTotal = "SELECT COUNT(*) AS total FROM `USER`";
$resultTotal = mysqli_query($connection, $sqlTotal);
$rowTotal = mysqli_fetch_assoc($resultTotal);
$totalUsers = $rowTotal ? $rowTotal['total'] : 0;

// Similarly for sellers and buyers
$sqlSellers = "SELECT COUNT(*) AS total FROM SELLER";
$sqlBuyers = "SELECT COUNT(*) AS total FROM BUYER";
```

#### Search Functionality
```php
$search = '';
if(isset($_GET['search'])){
    $search = $_GET['search'];
}

$sql = "SELECT * FROM `USER`";
if($search != ''){
    $safeSearch = mysqli_real_escape_string($connection, $search);
    $sql .= " WHERE studentID LIKE '%".$safeSearch."%' 
            OR firstName LIKE '%".$safeSearch."%' 
            OR lastName LIKE '%".$safeSearch."%' 
            OR email LIKE '%".$safeSearch."%'";
}
$sql .= " ORDER BY studentID DESC";

$result = mysqli_query($connection, $sql);
```

**What searches?** Student ID, First Name, Last Name, Email

**How?** Using `LIKE '%search%'` (matches partial text)

#### Display Results in Table
```php
if(mysqli_num_rows($result) > 0){
    while($row = mysqli_fetch_assoc($result)){
        echo "<tr>";
        echo "<td>".$row['studentID']."</td>";
        echo "<td>".$row['firstName']."</td>";
        echo "<td>".$row['lastName']."</td>";
        echo "<td>".$row['contactNumber']."</td>";
        echo "<td>".$row['email']."</td>";
        echo "<td>".(isset($row['role']) ? $row['role'] : 'Student')."</td>";
        echo "<td><a href='user_edit.php?id=".$row['studentID']."'>Edit</a> | 
                  <a href='user_delete.php?id=".$row['studentID']."'>Delete</a></td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='7'>No users found.</td></tr>";
}
```

---

## 4. UPDATE OPERATIONS - Edit User

### UPDATE Operation: Edit User (user_edit.php)

**Type**: Modify existing user information

#### Two-Part Process

**PART 1: Retrieve current user data (READ)**
```php
$id = $_GET['id'];
$sql = "SELECT * FROM `USER` WHERE studentID='".$id."'";
$result = mysqli_query($connection, $sql);
$row = mysqli_fetch_assoc($result);  // Get one row

// Store in variables to display in form
$studentid = $row ? $row['studentID'] : '';
$firstname = $row ? $row['firstName'] : '';
$lastname = $row ? $row['lastName'] : '';
$contact = $row ? $row['contactNumber'] : '';
$email = $row ? $row['email'] : '';
$role = $row ? $row['role'] : 'Student';
```

**PART 2: Display form with current values**
```php
<input type="text" value="<?php echo $studentid; ?>" disabled>  <!-- Disabled: read-only -->
<input type="text" name="txtfirstname" value="<?php echo $firstname; ?>" required>
<input type="text" name="txtlastname" value="<?php echo $lastname; ?>" required>
<input type="text" name="txtcontact" value="<?php echo $contact; ?>">
<input type="email" name="txtemail" value="<?php echo $email; ?>">
<select name="txtrole">
    <option value="Student" <?php echo $role == 'Student' ? 'selected' : ''; ?>>Student</option>
    <option value="Admin" <?php echo $role == 'Admin' ? 'selected' : ''; ?>>Admin</option>
</select>
```

**PART 3: Process UPDATE on form submission**
```php
if(isset($_POST['btnUpdate'])){
    $firstname = $_POST['txtfirstname'];
    $lastname = $_POST['txtlastname'];
    $contact = $_POST['txtcontact'];
    $email = $_POST['txtemail'];
    $role = $_POST['txtrole'];
    
    // UPDATE only these fields (NOT studentID)
    $sql = "UPDATE `USER` SET 
            firstName='".$firstname."', 
            lastName='".$lastname."', 
            contactNumber='".$contact."', 
            email='".$email."', 
            role='".$role."' 
            WHERE studentID='".$id."'";
    
    mysqli_query($connection, $sql);
    
    echo "<script language='javascript'>
        alert('User updated.');
        window.location.href='user_list.php';
    </script>";
}
```

**Key Points**:
- Student ID is DISABLED (cannot edit)
- Only these fields can be edited: firstName, lastName, contactNumber, email, role
- Uses WHERE clause to identify which row to update

---

## 5. DELETE OPERATIONS - Delete User (Cascading)

### DELETE Operation: Delete User (user_delete.php)

**Type**: Delete user and all related data (Cascading Delete)

**Why cascading?** If a user is also a buyer or seller, we must delete their related records first.

#### Deletion Order (Important!)

**Step 1: Check if user is a BUYER and delete their TRANSACTIONS**
```php
$sqlBuyer = "SELECT buyerID FROM BUYER WHERE studentID='".$id."'";
$resultBuyer = mysqli_query($connection, $sqlBuyer);

while($buyerRow = mysqli_fetch_assoc($resultBuyer)){
    mysqli_query($connection, "DELETE FROM `TRANSACTION` WHERE buyerID='".$buyerRow['buyerID']."'");
}
```

**Step 2: Check if user is a SELLER and delete their POSTS and TRANSACTIONS**
```php
$sqlSeller = "SELECT sellerID FROM SELLER WHERE studentID='".$id."'";
$resultSeller = mysqli_query($connection, $sqlSeller);

while($sellerRow = mysqli_fetch_assoc($resultSeller)){
    // Delete transactions related to this seller's posts
    mysqli_query($connection, "DELETE t FROM `TRANSACTION` t 
        INNER JOIN POSTS p ON t.itemID = p.itemID 
        WHERE p.sellerID='".$sellerRow['sellerID']."'");
    
    // Delete the seller's posts
    mysqli_query($connection, "DELETE FROM POSTS WHERE sellerID='".$sellerRow['sellerID']."'");
}
```

**Step 3: Delete BUYER record**
```php
$sqlDeleteBuyer = "DELETE FROM BUYER WHERE studentID='".$id."'";
mysqli_query($connection, $sqlDeleteBuyer);
```

**Step 4: Delete SELLER record**
```php
$sqlDeleteSeller = "DELETE FROM SELLER WHERE studentID='".$id."'";
mysqli_query($connection, $sqlDeleteSeller);
```

**Step 5: Delete USER record**
```php
$sqlDelete = "DELETE FROM `USER` WHERE studentID='".$id."'";
mysqli_query($connection, $sqlDelete);

echo "<script language='javascript'>
    alert('User deleted.');
    window.location.href='user_list.php';
</script>";
```

**Why this order?**
```
TRANSACTION depends on → BUYER & POSTS
POSTS depends on → SELLER
BUYER depends on → USER
SELLER depends on → USER
USER is independent

So delete in reverse dependency order:
TRANSACTION → POSTS → BUYER → SELLER → USER
```

---

## 6. KEY CONCEPTS & BEST PRACTICES

### 1. SQL Injection Prevention
```php
// ❌ UNSAFE
$sql = "SELECT * FROM USER WHERE studentID = '".$_POST['id']."'";

// ✅ SAFE
$safe_id = mysqli_real_escape_string($connection, $_POST['id']);
$sql = "SELECT * FROM USER WHERE studentID = '".$safe_id."'";
```

### 2. Password Hashing
```php
// ❌ WRONG - Never do this
$sql = "INSERT INTO USER(password) VALUES('".$_POST['password']."')"; // Plain text!

// ✅ CORRECT
$hashed = password_hash($_POST['password'], PASSWORD_DEFAULT);
$sql = "INSERT INTO USER(password) VALUES('".$hashed."')";
```

### 3. Form Data Flow
```
USER SUBMITS FORM
    ↓
isset($_POST['btnName']) - Check if form was submitted
    ↓
Retrieve form data from $_POST[]
    ↓
Validate (required fields, duplicates, etc.)
    ↓
Sanitize with mysqli_real_escape_string()
    ↓
Execute SQL query
    ↓
Show feedback (alert) and redirect
```

### 4. READ Pattern
```php
$sql = "SELECT * FROM TABLE WHERE condition";
$result = mysqli_query($connection, $sql);

// Single row
if(mysqli_num_rows($result) > 0){
    $row = mysqli_fetch_assoc($result);
    echo $row['columnname'];
}

// Multiple rows
while($row = mysqli_fetch_assoc($result)){
    echo $row['columnname'];
}
```

### 5. CREATE Pattern
```php
// Validate → Duplicate Check → Hash (if password) → INSERT → Redirect
```

### 6. UPDATE Pattern
```php
// Get current data → Display in form → Process update → Redirect
// Always use WHERE clause to specify which row!
```

### 7. DELETE Pattern
```php
// Delete dependencies first → Delete main record → Redirect
// Use proper WHERE clause to identify the record
```

---

## 7. QUIZ PREPARATION CHECKLIST

### Concepts to Know
- [ ] What does CRUD stand for?
- [ ] What does `mysqli_query()` do?
- [ ] What is `mysqli_num_rows()` used for?
- [ ] What is `mysqli_fetch_assoc()` used for?
- [ ] What is `mysqli_real_escape_string()` for?
- [ ] What is `password_hash()` and why is it important?
- [ ] What does `LAST_INSERT_ID()` do?
- [ ] What is cascading delete and why is it needed?
- [ ] What does `LIKE '%search%'` do?
- [ ] What does the `WHERE` clause do?

### Code Questions You Might Face
- [ ] Explain the flow of user_signup.php
- [ ] What's the difference between user_signup.php and user_add.php?
- [ ] Why do we check for duplicates before INSERT?
- [ ] What happens if you don't hash a password?
- [ ] How does the search in user_list.php work?
- [ ] How does edit.php retrieve current user data?
- [ ] Why is the Student ID disabled in edit form?
- [ ] Explain the deletion order in user_delete.php
- [ ] What would happen if you deleted USER before TRANSACTIONS?
- [ ] How does connect.php create a default admin account?

### Common Error Scenarios
- [ ] User tries to sign up with existing email → Error message shown
- [ ] Click edit without passing ID parameter → SELECT returns no data
- [ ] User deletes a seller → What tables are affected?
- [ ] Search for non-existent user → "No users found" message
- [ ] INSERT fails due to duplicate key → Error handling needed
- [ ] Password not hashed → Security vulnerability
- [ ] SQL Injection attempt with ' → Prevented by real_escape_string()

---

## Summary Table

| Operation | File | Purpose | Key SQL |
|-----------|------|---------|---------|
| CREATE | user_signup.php | Self-register with password hashing | INSERT + password_hash() |
| CREATE | user_add.php | Admin adds user manually | INSERT without password |
| READ | user_list.php | View all users + search | SELECT with LIKE |
| UPDATE | user_edit.php | Modify user info | UPDATE with WHERE |
| DELETE | user_delete.php | Delete user + cascading | DELETE with dependencies |

---

Good luck on your quiz! Focus on understanding the logic flow, not just memorizing code.
