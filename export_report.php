<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

/* ================= Export CSV ================= */
if (isset($_GET['export']) && $_GET['export'] == 'csv') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=report_grades.csv');

    $output = fopen('php://output', 'w');
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF)); // แก้ภาษาไทยใน Excel

    fputcsv($output, ['ชื่อนักเรียน', 'รายวิชา', 'คะแนน']);

    $sql = "SELECT u.name s_name, sub.name sub_name, g.score
            FROM grades g
            JOIN students s ON g.student_id = s.id
            JOIN users u ON s.user_id = u.id
            JOIN subjects sub ON g.subject_id = sub.id";
    $res = mysqli_query($conn, $sql);

    while ($row = mysqli_fetch_assoc($res)) {
        fputcsv($output, $row);
    }
    fclose($output);
    exit();
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>Report | Education Platform</title>

<style>
*{box-sizing:border-box;font-family:'Segoe UI',sans-serif;}
body{
    margin:0;min-height:100vh;
    background:linear-gradient(135deg,#16a34a,#4ade80);
    display:flex;justify-content:center;align-items:center;
}
.container{
    width:950px;
    background:rgba(255,255,255,.97);
    border-radius:22px;
    padding:35px 40px;
    box-shadow:0 25px 60px rgba(0,0,0,.25);
}
.header{
    display:flex;justify-content:space-between;align-items:center;
    margin-bottom:25px;
}
.header h2{margin:0;color:#065f46;}
.actions a{
    text-decoration:none;font-weight:600;
    margin-left:10px;
}
.export{
    background:linear-gradient(135deg,#22c55e,#16a34a);
    color:white;padding:10px 18px;border-radius:12px;
}
.export:hover{box-shadow:0 10px 25px rgba(34,197,94,.4);}
.back{color:#16a34a;}
.back:hover{text-decoration:underline;}

table{
    width:100%;border-collapse:collapse;margin-top:15px;
}
th,td{padding:14px;border-bottom:1px solid #e5e7eb;text-align:left;}
th{background:#22c55e;color:white;}
tr:hover{background:#f0fdf4;}
.score{font-weight:600;color:#065f46;}

.footer{
    text-align:center;margin-top:25px;
    font-size:13px;color:#047857;
}
</style>
</head>

<body>

<div class="container">

<div class="header">
    <h2>📊 รายงานสรุปผลการเรียน</h2>
    <div class="actions">
        <a href="?export=csv" class="export">📥 ดาวน์โหลด Excel</a>
        <a href="admin_menu.php" class="back">← กลับเมนูหลัก</a>
    </div>
</div>

<table>
<thead>
<tr>
    <th>ชื่อนักเรียน</th>
    <th>รายวิชา</th>
    <th>คะแนน</th>
</tr>
</thead>
<tbody>
<?php
$sql = "SELECT u.name s_name, sub.name sub_name, g.score
        FROM grades g
        JOIN students s ON g.student_id = s.id
        JOIN users u ON s.user_id = u.id
        JOIN subjects sub ON g.subject_id = sub.id";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>{$row['s_name']}</td>
                <td>{$row['sub_name']}</td>
                <td class='score'>{$row['score']}</td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='3' style='text-align:center;'>ยังไม่มีข้อมูล</td></tr>";
}
?>
</tbody>
</table>

<div class="footer">
    © 2026 Education Platform
</div>

</div>

</body>
</html>