<?php
include 'config.php';
session_start();

// ตรวจสอบสิทธิ์
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'teacher') {
    die("หน้านี้สำหรับครูเท่านั้น <a href='admin_menu.php'>กลับหน้าหลัก</a>");
}

$sql = "SELECT 
            s.id AS submission_id,
            u.name AS student_name,
            a.title AS task_title,
            a.type AS task_type,
            a.attachment_link AS question_file,
            s.file_link AS student_file,
            s.submitted_at
        FROM submissions s
        JOIN students st ON s.student_id = st.id
        JOIN users u ON st.user_id = u.id
        JOIN assignments a ON s.assignment_id = a.id
        ORDER BY s.submitted_at DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>ตรวจงานและข้อสอบ</title>

<style>
body{
    font-family: sans-serif;
    background: #f4faf6;
    padding: 30px;
}

.container{
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

h2{
    color: #2e7d32;
    margin-bottom: 10px;
}

.top-bar{
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.btn-back{
    background: #6c757d;
    color: white;
    padding: 8px 14px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 14px;
}

.btn-back:hover{
    background: #5a6268;
}

table{
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
}

th, td{
    border: 1px solid #dee2e6;
    padding: 12px;
    text-align: left;
}

th{
    background: #2e7d32;
    color: white;
}

tr:nth-child(even){
    background: #f9f9f9;
}

.badge{
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
    color: white;
}

.bg-exam{ background: #dc3545; }
.bg-homework{ background: #28a745; }

.btn{
    padding: 6px 10px;
    border-radius: 5px;
    font-size: 13px;
    text-decoration: none;
    color: white;
}

.btn-question{ background: #6c757d; }
.btn-view{ background: #17a2b8; }

.btn:hover{
    opacity: 0.85;
}
</style>
</head>

<body>

<div class="container">

    <div class="top-bar">
        <h2>📋 ตรวจงานและข้อสอบ</h2>
        <a href="admin_menu.php" class="btn-back">⬅ กลับหน้าหลัก</a>
    </div>

    <p>ครูผู้ตรวจ: <strong><?php echo $_SESSION['name']; ?></strong></p>

    <table>
        <thead>
            <tr>
                <th>วัน-เวลาที่ส่ง</th>
                <th>นักเรียน</th>
                <th>หัวข้อ</th>
                <th>ประเภท</th>
                <th>โจทย์</th>
                <th>ไฟล์นักเรียน</th>
            </tr>
        </thead>
        <tbody>
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo date('d/m/Y H:i', strtotime($row['submitted_at'])); ?></td>
                <td><?php echo $row['student_name']; ?></td>
                <td><?php echo $row['task_title']; ?></td>
                <td>
                    <span class="badge <?php echo ($row['task_type']=='exam')?'bg-exam':'bg-homework'; ?>">
                        <?php echo ($row['task_type']=='exam')?'ข้อสอบ':'การบ้าน'; ?>
                    </span>
                </td>
                <td>
                    <?php if($row['question_file']): ?>
                        <a href="<?php echo $row['question_file']; ?>" target="_blank" class="btn btn-question">📄 โจทย์</a>
                    <?php else: ?> - <?php endif; ?>
                </td>
                <td>
                    <a href="<?php echo $row['student_file']; ?>" target="_blank" class="btn btn-view">🔍 ตรวจงาน</a>
                </td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" style="text-align:center;">ยังไม่มีการส่งงาน</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>

</div>

</body>
</html>