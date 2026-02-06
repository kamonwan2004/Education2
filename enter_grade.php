<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$role = $_SESSION['role'];
$user_id = $_SESSION['user_id'];

/* ================= ครู: บันทึกคะแนน ================= */
if ($role == 'teacher') {

    if (isset($_POST['submit_grade'])) {
        $student_id = mysqli_real_escape_string($conn, $_POST['student_id']);
        $sub_id     = mysqli_real_escape_string($conn, $_POST['subject_id']);
        $score      = mysqli_real_escape_string($conn, $_POST['score']);

        $check_sql = "SELECT id FROM grades WHERE student_id='$student_id' AND subject_id='$sub_id'";
        $check_res = mysqli_query($conn, $check_sql);

        if (mysqli_num_rows($check_res) > 0) {
            $sql = "UPDATE grades SET score='$score' 
                    WHERE student_id='$student_id' AND subject_id='$sub_id'";
        } else {
            $sql = "INSERT INTO grades (student_id, subject_id, score) 
                    VALUES ('$student_id','$sub_id','$score')";
        }

        if (mysqli_query($conn, $sql)) {
            $success = "บันทึกคะแนนเรียบร้อยแล้ว";
        }
    }

    $students_list = mysqli_query($conn,
        "SELECT s.id, u.name FROM students s JOIN users u ON s.user_id = u.id");
    $subjects_list = mysqli_query($conn, "SELECT * FROM subjects");
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>Grades | Education Platform</title>

<style>
*{box-sizing:border-box;font-family:'Segoe UI',sans-serif;}
body{
    margin:0;min-height:100vh;
    background:linear-gradient(135deg,#16a34a,#4ade80);
    display:flex;justify-content:center;align-items:center;
}
.container{
    width:900px;
    background:rgba(255,255,255,0.97);
    border-radius:22px;
    padding:35px 40px;
    box-shadow:0 25px 60px rgba(0,0,0,.25);
}
.header{
    display:flex;justify-content:space-between;align-items:center;
    margin-bottom:25px;
}
.header h2{margin:0;color:#065f46;}
.back{color:#16a34a;font-weight:600;text-decoration:none;}
.back:hover{text-decoration:underline;}

.success{
    background:#dcfce7;color:#166534;
    padding:10px 14px;border-radius:10px;
    margin-bottom:20px;font-size:14px;
}

.form-box{
    background:#f0fdf4;
    border-radius:18px;
    padding:25px;
    box-shadow:0 10px 25px rgba(0,0,0,.1);
}
.form-group{margin-bottom:16px;}
label{display:block;margin-bottom:6px;color:#064e3b;font-weight:600;}
select,input{
    width:100%;padding:12px;
    border-radius:10px;border:1px solid #bbf7d0;
    background:#f0fdf4;font-size:14px;
}
input:focus,select:focus{
    outline:none;border-color:#22c55e;
    box-shadow:0 0 0 3px rgba(34,197,94,.25);
}
button{
    padding:12px 22px;border:none;border-radius:12px;
    background:linear-gradient(135deg,#22c55e,#16a34a);
    color:white;font-size:15px;cursor:pointer;
}
button:hover{transform:translateY(-2px);box-shadow:0 10px 25px rgba(34,197,94,.4);}

table{
    width:100%;border-collapse:collapse;margin-top:20px;
}
th,td{padding:14px;border-bottom:1px solid #e5e7eb;}
th{background:#22c55e;color:white;text-align:left;}
.pass{color:#16a34a;font-weight:600;}
.fail{color:#dc2626;font-weight:600;}
.center{text-align:center;}
</style>
</head>

<body>

<div class="container">

<div class="header">
    <h2>
        <?php echo ($role=='teacher') ? "👨‍🏫 บันทึกคะแนนนักเรียน" : "🎓 ผลการเรียนของคุณ"; ?>
    </h2>
    <a href="admin_menu.php" class="back">← กลับเมนูหลัก</a>
</div>

<?php if (isset($success)): ?>
    <div class="success"><?php echo $success; ?></div>
<?php endif; ?>

<?php if ($role == 'teacher'): ?>

<div class="form-box">
<form method="post">

    <div class="form-group">
        <label>นักเรียน</label>
        <select name="student_id" required>
            <option value="">-- เลือกนักเรียน --</option>
            <?php while($row=mysqli_fetch_assoc($students_list)): ?>
                <option value="<?php echo $row['id']; ?>">
                    <?php echo $row['name']; ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>

    <div class="form-group">
        <label>รายวิชา</label>
        <select name="subject_id" required>
            <option value="">-- เลือกรายวิชา --</option>
            <?php while($row=mysqli_fetch_assoc($subjects_list)): ?>
                <option value="<?php echo $row['id']; ?>">
                    <?php echo $row['name']; ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>

    <div class="form-group">
        <label>คะแนน (0–100)</label>
        <input type="number" name="score" min="0" max="100" step="0.01" required>
    </div>

    <button type="submit" name="submit_grade">บันทึกคะแนน</button>
</form>
</div>

<?php else: ?>

<table>
<thead>
<tr>
    <th>รายวิชา</th>
    <th>คะแนน</th>
    <th>ผลประเมิน</th>
</tr>
</thead>
<tbody>
<?php
$sql="SELECT s.name subject_name,g.score
      FROM grades g JOIN subjects s ON g.subject_id=s.id
      WHERE g.student_id=(SELECT id FROM students WHERE user_id='$user_id')";
$res=mysqli_query($conn,$sql);

if(mysqli_num_rows($res)>0){
    while($row=mysqli_fetch_assoc($res)){
        $pass = ($row['score']>=50);
        echo "<tr>
                <td>{$row['subject_name']}</td>
                <td>{$row['score']}</td>
                <td class='".($pass?'pass':'fail')."'>
                    ".($pass?'ผ่าน':'ไม่ผ่าน')."
                </td>
              </tr>";
    }
}else{
    echo "<tr><td colspan='3' class='center'>ยังไม่มีข้อมูลคะแนน</td></tr>";
}
?>
</tbody>
</table>

<?php endif; ?>

</div>
</body>
</html>