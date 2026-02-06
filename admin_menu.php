<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$role = $_SESSION['role'];
$name = $_SESSION['name'];
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Main Menu | Education Platform</title>

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
            width: 900px;
            background: rgba(255,255,255,0.96);
            border-radius: 22px;
            padding: 35px 40px;
            box-shadow: 0 25px 60px rgba(0,0,0,0.25);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .welcome h1 {
            margin: 0;
            color: #065f46;
        }

        .welcome p {
            margin: 4px 0 0;
            font-size: 14px;
            color: #047857;
        }

        .logout {
            color: #dc2626;
            font-weight: 600;
            text-decoration: none;
        }

        .logout:hover {
            text-decoration: underline;
        }

        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
        }

        .menu-card {
            background: #f0fdf4;
            border-radius: 18px;
            padding: 22px;
            text-decoration: none;
            color: #064e3b;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            transition: transform 0.25s, box-shadow 0.25s;
        }

        .menu-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 40px rgba(34,197,94,0.35);
        }

        .menu-icon {
            font-size: 36px;
            margin-bottom: 12px;
        }

        .menu-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .menu-desc {
            font-size: 13px;
            color: #065f46;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 13px;
            color: #047857;
        }
    </style>
</head>

<body>

<div class="container">

    <div class="header">
        <div class="welcome">
            <h1>🎓 ยินดีต้อนรับ, <?php echo $name; ?></h1>
            <p>สิทธิ์การใช้งาน: <?php echo ucfirst($role); ?></p>
        </div>
        <a href="logout.php" class="logout">ออกจากระบบ</a>
    </div>

    <div class="menu-grid">

        <a href="admin_subjects.php" class="menu-card">
            <div class="menu-icon">📚</div>
            <div class="menu-title">รายวิชา / หลักสูตร</div>
            <div class="menu-desc">จัดการและดูข้อมูลรายวิชา</div>
        </a>

        <?php if ($role == 'teacher'): ?>

            <a href="enter_grade.php" class="menu-card">
                <div class="menu-icon">📝</div>
                <div class="menu-title">บันทึกเกรด</div>
                <div class="menu-desc">ประเมินผลและให้คะแนนนักเรียน</div>
            </a>

            <a href="create_assignment.php" class="menu-card">
                <div class="menu-icon">📄</div>
                <div class="menu-title">ออกข้อสอบ / การบ้าน</div>
                <div class="menu-desc">สร้างและจัดการงานที่มอบหมาย</div>
            </a>

            <a href="check_submissions.php" class="menu-card">
                <div class="menu-icon">✅</div>
                <div class="menu-title">ตรวจงานนักเรียน</div>
                <div class="menu-desc">ตรวจสอบและให้คะแนนงาน</div>
            </a>

        <?php else: ?>

            <a href="enter_grade.php" class="menu-card">
                <div class="menu-icon">📊</div>
                <div class="menu-title">ผลการเรียน</div>
                <div class="menu-desc">ดูคะแนนและผลการประเมิน</div>
            </a>

            <a href="submit_work.php" class="menu-card">
                <div class="menu-icon">📤</div>
                <div class="menu-title">ส่งการบ้าน</div>
                <div class="menu-desc">ดูข้อสอบและส่งงาน</div>
            </a>

        <?php endif; ?>

        <a href="export_report.php" class="menu-card">
            <div class="menu-icon">📑</div>
            <div class="menu-title">Report / Export</div>
            <div class="menu-desc">ออกรายงานและดาวน์โหลดข้อมูล</div>
        </a>

    </div>

    <div class="footer">
        © 2026 Education Platform
    </div>

</div>

</body>
</html>