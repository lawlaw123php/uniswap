<?php
$servername = "localhost";
$username   = "root";
$password   = "";
$database   = "uniswap";
$port       = 3306;

mysqli_report(MYSQLI_REPORT_OFF);

$connection = new mysqli($servername, $username, $password, $database, $port);

if ($connection->connect_errno) {
    die("Connection failed: " . $connection->connect_error);
}

$connection->set_charset("utf8mb4");

/* Auto-add missing columns (compatible with all MariaDB/MySQL versions) */
$neededCols = [
    'password' => "VARCHAR(255) DEFAULT NULL",
    'role'     => "VARCHAR(10) NOT NULL DEFAULT 'Student'",
];

foreach($neededCols as $col => $def){
    $check = mysqli_query($connection,
        "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS
         WHERE TABLE_SCHEMA = '$database'
           AND TABLE_NAME   = 'USER'
           AND COLUMN_NAME  = '$col'
         LIMIT 1"
    );
    if($check && mysqli_num_rows($check) === 0){
        mysqli_query($connection, "ALTER TABLE `USER` ADD COLUMN `$col` $def");
    }
}
?>
