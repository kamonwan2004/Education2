<?php
include 'config.php';
session_start();
if ($_SESSION['role'] != 'student') { die("หน้านี้สำหรับนักเรียนเท่านั้น"); }

$query = "SELECT a.*, s.name as subject_name 
          FROM assignments a 
          JOIN subjects s ON a.subject_id = s.id 
          ORDER BY a.due_date ASC";
$assignments = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>ส่งการบ้าน / ข้อสอบ</title>

<style>
    body{
        font-family: 'Segoe UI', sans-serif;
        background: #f4f6f9;
        margin: 0;
        padding: 20px;
    }

    h2, h3{
        color: #333;
    }

    .container{
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.08);
    }

    table{
        width:100%;
        border-collapse: collapse;
        margin-top: 15px;
    }

    th, td{
        padding: 12px;
        border-bottom: 1px solid #ddd;
        text-align: left;
    }

    th{
        background: #f0f0f0;
    }

    tr:hover{
        background: #fafafa;
    }

    .badge{
        padding: 5px 10px;
        border-radius: 20px;
        color: white;
        font-size: 12px;
    }

    .bg-exam{ background: #dc3545; }
    .bg-work{ background: #28a745; }

    .btn{
        padding: 6px 12px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 14px;
    }

    .btn-select{
        background: #007bff;
        color: white;
    }

    .btn-select:hover{
        background: #0056b3;
    }

    .download{
        color: #007bff;
        text-decoration: none;
        font-weight: bold;
    }

    .download:hover{
        text-decoration: underline;
    }

    .form-box{
        margin-top: 20px;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
    }

    input[type="text"], input[type="file"]{
        width: 100%;
        padding: 8px;
        margin: 8px 0;
        border-radius: 5px;
        border: 1px solid #ccc;
    }
.btn-back{
    display: inline-block;
    margin-bottom: 15px;
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
    .btn-upload{
        background: #28a745;
        color: white;
        padding: 10px 15px;
        border-radius: 6px;
        border: none;
        cursor: pointer;
    }

    .btn-upload:hover{
        background: #218838;
    }
</style>
</head>

<body>

<div class="container">
    <a href="admin_menu.php" class="btn-back">⬅ กลับหน้าหลัก</a>
    <h2>📚 รายการงานที่ได้รับมอบหมาย</h2>

    <table>
        <tr>
            <th>ประเภท</th>
            <th>วิชา / หัวข้อ</th>
            <th>ไฟล์โจทย์</th>
            <th>กำหนดส่ง</th>
            <th>เลือก</th>
        </tr>

        <?php while($row = mysqli_fetch_assoc($assignments)): ?>
        <tr>
            <td>
                <span class="badge <?php echo ($row['type'] == 'exam') ? 'bg-exam' : 'bg-work'; ?>">
                    <?php echo ($row['type'] == 'exam') ? 'ข้อสอบ' : 'การบ้าน'; ?>
                </span>
            </td>

            <td>
                <strong><?php echo $row['subject_name']; ?></strong><br>
                <?php echo $row['title']; ?>
            </td>

            <td>
                <?php if($row['attachment_link']): ?>
                    <a class="download" href="<?php echo $row['attachment_link']; ?>" target="_blank">
                        📄 ดาวน์โหลด
                    </a>
                <?php else: ?>
                    -
                <?php endif; ?>
            </td>

            <td><?php echo $row['due_date']; ?></td>

            <td>
                <button class="btn btn-select"
                        onclick="document.getElementById('as_id').value='<?php echo $row['id']; ?>'">
                    เลือกงาน
                </button>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <div class="form-box">
        <h3>📤 ฟอร์มส่งไฟล์งาน</h3>
        <form method="post" enctype="multipart/form-data">
            <label>ID งานที่เลือก</label>
            <input type="text" id="as_id" name="assignment_id" readonly required>

            <label>ไฟล์คำตอบ</label>
            <input type="file" name="fileToUpload" required>

            <button class="btn-upload" type="submit" name="upload">
                ส่งงาน
            </button>
        </form>
    </div>
</div>

</body>
</html>