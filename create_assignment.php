<?php
include 'config.php';
session_start();

if ($_SESSION['role'] != 'teacher') { die("หน้านี้สำหรับครูเท่านั้น"); }

if (isset($_POST['create'])) {
    $subject_id = $_POST['subject_id'];
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $type = $_POST['type'];
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $due_date = $_POST['due_date'];

    $attachment = "";
    if (!empty($_FILES["attachment"]["name"])) {
        $target_dir = "uploads/questions/";
        if (!is_dir($target_dir)) { mkdir($target_dir, 0777, true); }
        $attachment = $target_dir . time() . "_" . basename($_FILES["attachment"]["name"]);
        move_uploaded_file($_FILES["attachment"]["tmp_name"], $attachment);
    }

    $sql = "INSERT INTO assignments (subject_id, title, type, description, attachment_link, due_date)
            VALUES ('$subject_id', '$title', '$type', '$description', '$attachment', '$due_date')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('ประกาศสำเร็จ!'); window.location='admin_menu.php';</script>";
    }
}

$subjects = mysqli_query($conn, "SELECT * FROM subjects");
?>

<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>สร้างข้อสอบ / การบ้าน</title>

<style>
body{
    font-family: sans-serif;
    background: #f4faf6;
    padding: 30px;
}

.container{
    max-width: 600px;
    margin: auto;
    background: white;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

h2{
    text-align: center;
    color: #2e7d32;
    margin-bottom: 20px;
}

label{
    font-weight: bold;
    display: block;
    margin-bottom: 5px;
}

input, select, textarea{
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 6px;
}

.radio-group{
    margin-bottom: 15px;
}

.radio-group input{
    width: auto;
}

button{
    background: #2e7d32;
    color: white;
    border: none;
    padding: 12px;
    width: 100%;
    border-radius: 6px;
    font-size: 16px;
    cursor: pointer;
}

button:hover{
    background: #256628;
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
</style>
</head>

<body>

<div class="container">

    <!-- ปุ่มกลับ -->
    <a href="admin_menu.php" class="btn-back">⬅ กลับหน้าหลัก</a>

    <h2>📝 สร้างข้อสอบ / สั่งการบ้าน</h2>

    <form method="post" enctype="multipart/form-data">

        <label>รายวิชา</label>
        <select name="subject_id" required>
            <?php while($s = mysqli_fetch_assoc($subjects)): ?>
                <option value="<?php echo $s['id']; ?>">
                    <?php echo $s['name']; ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label>หัวข้อ</label>
        <input type="text" name="title" required>

        <label>ประเภท</label>
        <div class="radio-group">
            <input type="radio" name="type" value="homework" checked> การบ้าน
            <input type="radio" name="type" value="exam"> ข้อสอบ
        </div>

        <label>รายละเอียด</label>
        <textarea name="description" rows="4"></textarea>

        <label>ไฟล์โจทย์ (ถ้ามี)</label>
        <input type="file" name="attachment">

        <label>กำหนดส่ง</label>
        <input type="date" name="due_date" required>

        <button type="submit" name="create">✅ บันทึกประกาศ</button>

    </form>
</div>

</body>
</html>