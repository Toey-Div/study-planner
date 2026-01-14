<?php
$servername = "localhost";
$username = "root";
$password = ""; // บน XAMPP Mac ปกติไม่มีรหัสผ่าน
$dbname = "study_planner_db";

// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8mb4");

if ($conn->connect_error) {
    // กรณีเชื่อมต่อไม่ได้
    die("Connection failed: " . $conn->connect_error);
}
?>