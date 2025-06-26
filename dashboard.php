<?php
session_start();
// ตรวจสอบว่าล็อกอินแล้วหรือไม่
if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>แดชบอร์ด</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <div class="container">
   <h1>ยินดีต้อนรับ, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
   <p>นี่คือหน้าแดชบอร์ด สำหรับผู้ใช้งานระบบ</p>
   <div class="button-group">
    <a href="logout.php"><button>ออกจากระบบ</button></a>
   </div>
  </div>
</body>
</html>
