<?php
session_start();
require 'connect.php';

$eroor = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // รับค่าจากฟอร์ม
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    // 1) ตรวจสอบความถูกต้องเบื้องต้น
    if (empty($username) || empty($password)) {
        $eroor = 'กรุณกรอกชื่อผู้ใช้และรหัสผ่านให้ครบ';
    } elseif ($password !== $confirm) {
        $eroor = 'รหัสผ่านทั้งสองช่องไม่รงกัน';
    } else {
        // 2) ตรวจสอบว่าชื่อผู้ใช้ซ้ำหรือไม่
        $stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE username = ?");
        mysqli_stmt_bind_param($stmt, 's', $username);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        
        if (mysqli_stmt_num_rows($stmt) > 0) {
            $eroor = 'ชื่อผู้ใช้นี้มีแล้ว';
        } else {
            // 3) เข้ารหัสผ่าน
            $hash = password_hash($password, PASSWORD_DEFAULT);

            // 4) บันทึกข้อมูลลงตาราง users
            $stmt = mysqli_prepare($conn,
                 "INSERT INTO users (username, password) VALUES (?, ?)"
            );
            mysqli_stmt_bind_param($stmt, 'ss', $username, $hash);
            if (mysqli_stmt_execute($stmt)) {
                // สมัครสำเร็จ -> ไปหน้า login
                header('Location: login.php');
                exit;
            } else {
                $eroor = 'เกิดข้อผิดพลาดในการสมัคร';
            }
        }
        mysqli_stmt_close($stmt);
    }
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>สมัครสมาชิก</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="container">
        <h1>สมัครสมาชิก</h1>
        <?php if (!empty($error)): ?>
            <p style="color:red;"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="post">
            <fieldset>
                <legend>ข้อมูลผู้ใช้</legend>
                <label for="username">ชื่อผู้ใช้</label>
                <input type="text" id="username" name="username" required>

                <label for="password">รหัสผ่าน</label>
                <input type="password" id="password" name="password" required>

                <label for="confirm_password">ยืนยันรหัสผ่าน</label>
                <input type="password" id="confirm_password" name="confirm_password" required>

            </fieldset>
            <div class="button-group">
                <input type="reset" value="ล้างข้อมูล">
                <input type="submit" value="สมัครสมาชิก">
            </div>
        </form>
        <p>หากมีปัญหา<a href="login.php">เข้าสู่ระบบที่นี่</a></p>
    </div>
</body>

</html>
