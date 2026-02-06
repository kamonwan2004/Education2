<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$role = $_SESSION['role'];

// ถ้าเป็นครูและมีการกดปุ่มสร้าง
if ($role == 'teacher' && isset($_POST['add_subject'])) {
    $name = $_POST['subject_name'];
    mysqli_query($conn, "INSERT INTO subjects (name) VALUES ('$name')");
    $success = "สร้างหลักสูตรสำเร็จ!";
}

// ดึงข้อมูลวิชามาโชว์
$result = mysqli_query($conn, "SELECT * FROM subjects");
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>รายวิชา | Education Platform</title>

    <style>
        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: linear-gradient(135deg, #16a34a, #4ade80);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            width: 850px;
            background: rgba(255,255,255,0.96);
            border-radius: 22px;
            padding: 35px 40px;
            box-shadow: 0 25px 60px rgba(0,0,0,0.25);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        h2 {
            margin: 0;
            color: #065f46;
        }

        .back {
            text-decoration: none;
            color: #16a34a;
            font-weight: 600;
        }

        .back:hover {
            text-decoration: underline;
        }

        .success {
            background: #dcfce7;
            color: #166534;
            padding: 10px 14px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .subject-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 18px;
        }

        .subject-card {
            background: #f0fdf4;
            border-radius: 16px;
            padding: 18px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            transition: transform 0.25s, box-shadow 0.25s;
        }

        .subject-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 18px 40px rgba(34,197,94,0.35);
        }

        .subject-name {
            font-size: 16px;
            font-weight: 600;
            color: #064e3b;
        }

        .teacher-box {
            margin-top: 35px;
            padding-top: 25px;
            border-top: 1px dashed #bbf7d0;
        }

        .teacher-box h3 {
            margin-bottom: 15px;
            color: #065f46;
        }

        .form-group {
            display: flex;
            gap: 10px;
        }

        input {
            flex: 1;
            padding: 12px;
            border-radius: 10px;
            border: 1px solid #bbf7d0;
            background: #f0fdf4;
            font-size: 14px;
        }

        input:focus {
            outline: none;
            border-color: #22c55e;
            box-shadow: 0 0 0 3px rgba(34,197,94,0.25);
        }

        button {
            padding: 12px 18px;
            border: none;
            border-radius: 10px;
            background: linear-gradient(135deg, #22c55e, #16a34a);
            color: #ffffff;
            font-size: 14px;
            cursor: pointer;
            transition: 0.2s;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(34,197,94,0.4);
        }
    </style>
</head>

<body>

<div class="container">

    <div class="header">
        <h2>📚 รายวิชาทั้งหมด</h2>
        <a href="admin_menu.php" class="back">← กลับเมนูหลัก</a>
    </div>

    <?php if (isset($success)): ?>
        <div class="success"><?php echo $success; ?></div>
    <?php endif; ?>

    <div class="subject-list">
        <?php while($row = mysqli_fetch_assoc($result)): ?>
            <div class="subject-card">
                <div class="subject-name"><?php echo $row['name']; ?></div>
            </div>
        <?php endwhile; ?>
    </div>

    <?php if ($role == 'teacher'): ?>
        <div class="teacher-box">
            <h3>➕ สร้างหลักสูตรใหม่ (เฉพาะครู)</h3>
            <form method="post" class="form-group">
                <input type="text" name="subject_name" placeholder="ชื่อรายวิชา" required>
                <button type="submit" name="add_subject">บันทึก</button>
            </form>
        </div>
    <?php endif; ?>

</div>

</body>
</html>