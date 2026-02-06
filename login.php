<?php
include 'config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT id, name, role, password FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);

    if ($user && $password == $user['password']) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['name'] = $user['name'];
        header("Location: admin_menu.php");
        exit();
    } else {
        $error_message = "อีเมลหรือรหัสผ่านไม่ถูกต้อง";
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Education Platform</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            height: 100vh;
            overflow: hidden;
            position: relative;
        }

        /* พื้นหลังรูปภาพพร้อมเอฟเฟกต์ */
        .background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -2;
        }

        .background::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=1920');
            /* ทางเลือกอื่น: */
            /* ห้องสมุด: https://images.unsplash.com/photo-1521587760476-6c12a4b040da?w=1920 */
            /* หนังสือเปิด: https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?w=1920 */
            /* มหาวิทยาลัย: https://images.unsplash.com/photo-1562774053-701939374585?w=1920 */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            filter: blur(3px);
            transform: scale(1.1);
        }

        .background::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, 
                rgba(15, 23, 42, 0.88) 0%, 
                rgba(30, 58, 138, 0.85) 50%, 
                rgba(15, 23, 42, 0.88) 100%);
            backdrop-filter: blur(2px);
        }

        /* Overlay Pattern สำหรับความหรูหรา */
        .overlay-pattern {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background-image: 
                linear-gradient(30deg, rgba(255,255,255,0.03) 12%, transparent 12.5%, transparent 87%, rgba(255,255,255,0.03) 87.5%, rgba(255,255,255,0.03)),
                linear-gradient(150deg, rgba(255,255,255,0.03) 12%, transparent 12.5%, transparent 87%, rgba(255,255,255,0.03) 87.5%, rgba(255,255,255,0.03)),
                linear-gradient(30deg, rgba(255,255,255,0.03) 12%, transparent 12.5%, transparent 87%, rgba(255,255,255,0.03) 87.5%, rgba(255,255,255,0.03)),
                linear-gradient(150deg, rgba(255,255,255,0.03) 12%, transparent 12.5%, transparent 87%, rgba(255,255,255,0.03) 87.5%, rgba(255,255,255,0.03));
            background-size: 80px 140px;
            background-position: 0 0, 0 0, 40px 70px, 40px 70px;
        }

        /* Container หลัก */
        .login-container {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            position: relative;
        }

        /* การ์ดล็อกอิน - สไตล์องค์กร */
        .login-card {
            background: rgba(255, 255, 255, 0.97);
            width: 100%;
            max-width: 480px;
            padding: 55px 50px;
            border-radius: 16px;
            box-shadow: 
                0 30px 90px rgba(0, 0, 0, 0.4),
                0 0 1px rgba(0, 0, 0, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.5);
            position: relative;
            animation: fadeInUp 0.8s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* แถบสีทองด้านบน - สไตล์หรูหรา */
        .card-header-accent {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, 
                #c9a55a 0%, 
                #d4af37 25%, 
                #f4e4c1 50%, 
                #d4af37 75%, 
                #c9a55a 100%);
            border-radius: 16px 16px 0 0;
        }

        /* โลโก้องค์กร */
        .logo-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 90px;
            height: 90px;
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            border-radius: 50%;
            font-size: 48px;
            box-shadow: 
                0 15px 35px rgba(30, 58, 138, 0.3),
                0 5px 15px rgba(0, 0, 0, 0.1),
                inset 0 -2px 8px rgba(0, 0, 0, 0.2);
            position: relative;
        }

        .logo::before {
            content: '';
            position: absolute;
            top: -5px;
            left: -5px;
            right: -5px;
            bottom: -5px;
            border-radius: 50%;
            border: 2px solid rgba(212, 175, 55, 0.3);
        }

        /* หัวข้อ - สไตล์องค์กร */
        h2 {
            text-align: center;
            margin: 20px 0 8px;
            color: #0f172a;
            font-size: 28px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .subtitle {
            text-align: center;
            font-size: 15px;
            color: #475569;
            margin-bottom: 40px;
            font-weight: 400;
            letter-spacing: 0.3px;
        }

        /* ฟอร์ม */
        .form-group {
            margin-bottom: 26px;
            position: relative;
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-size: 13px;
            font-weight: 600;
            color: #1e293b;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 18px;
            color: #64748b;
            z-index: 1;
        }

        input {
            width: 100%;
            padding: 15px 16px 15px 48px;
            border-radius: 8px;
            border: 1.5px solid #cbd5e1;
            background: #ffffff;
            font-size: 15px;
            color: #0f172a;
            transition: all 0.3s ease;
            font-weight: 400;
        }

        input:focus {
            outline: none;
            border-color: #3b82f6;
            background: #ffffff;
            box-shadow: 
                0 0 0 3px rgba(59, 130, 246, 0.08),
                0 2px 8px rgba(0, 0, 0, 0.05);
        }

        input::placeholder {
            color: #94a3b8;
        }

        /* ปุ่ม - สไตล์องค์กร */
        button {
            width: 100%;
            padding: 16px;
            margin-top: 12px;
            border: none;
            border-radius: 8px;
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            color: white;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 
                0 10px 25px rgba(30, 58, 138, 0.25),
                0 2px 8px rgba(0, 0, 0, 0.1);
            letter-spacing: 0.8px;
            text-transform: uppercase;
            position: relative;
            overflow: hidden;
        }

        button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, 
                transparent, 
                rgba(255, 255, 255, 0.2), 
                transparent);
            transition: left 0.5s ease;
        }

        button:hover::before {
            left: 100%;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 
                0 15px 35px rgba(30, 58, 138, 0.35),
                0 5px 15px rgba(0, 0, 0, 0.15);
            background: linear-gradient(135deg, #1e40af 0%, #2563eb 100%);
        }

        button:active {
            transform: translateY(0px);
        }

        /* ลิงก์ */
        .extra {
            text-align: center;
            margin-top: 32px;
            padding-top: 28px;
            border-top: 1px solid #e2e8f0;
            font-size: 14px;
            color: #64748b;
        }

        .extra a {
            color: #3b82f6;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
            position: relative;
        }

        .extra a::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: #3b82f6;
            transition: width 0.3s ease;
        }

        .extra a:hover::after {
            width: 100%;
        }

        .extra a:hover {
            color: #2563eb;
        }

        /* ข้อความ Error - สไตล์องค์กร */
        .error-message {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #7f1d1d;
            padding: 14px 18px;
            border-radius: 8px;
            margin-bottom: 24px;
            font-size: 14px;
            border-left: 4px solid #dc2626;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.15);
            font-weight: 500;
        }

        /* เส้นตกแต่งมุม */
        .corner-decoration {
            position: absolute;
            width: 40px;
            height: 40px;
            border: 2px solid rgba(212, 175, 55, 0.2);
        }

        .corner-decoration.top-left {
            top: 15px;
            left: 15px;
            border-right: none;
            border-bottom: none;
            border-radius: 8px 0 0 0;
        }

        .corner-decoration.bottom-right {
            bottom: 15px;
            right: 15px;
            border-left: none;
            border-top: none;
            border-radius: 0 0 8px 0;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .login-card {
                padding: 45px 35px;
                max-width: 95%;
            }

            h2 {
                font-size: 24px;
            }

            .logo {
                width: 75px;
                height: 75px;
                font-size: 40px;
            }

            .corner-decoration {
                width: 30px;
                height: 30px;
            }
        }
    </style>
</head>

<body>

<!-- พื้นหลังรูปภาพ -->
<div class="background"></div>
<div class="overlay-pattern"></div>

<div class="login-container">
    <div class="login-card">
        <div class="card-header-accent"></div>
        <div class="corner-decoration top-left"></div>
        <div class="corner-decoration bottom-right"></div>
        
        <div class="logo-container">
            <div class="logo">🎓</div>
        </div>
        
        <h2>Education Platform</h2>
        <p class="subtitle">ระบบจัดการการศึกษาครบวงจร</p>

        <?php if (isset($error_message)): ?>
        <div class="error-message">
            <span>⚠️</span>
            <span><?php echo htmlspecialchars($error_message); ?></span>
        </div>
        <?php endif; ?>

        <form method="post">
            <div class="form-group">
                <label>อีเมล</label>
                <div class="input-wrapper">
                    <span class="input-icon">📧</span>
                    <input type="email" name="email" placeholder="your@email.com" required>
                </div>
            </div>

            <div class="form-group">
                <label>รหัสผ่าน</label>
                <div class="input-wrapper">
                    <span class="input-icon">🔒</span>
                    <input type="password" name="password" placeholder="••••••••" required>
                </div>
            </div>

            <button type="submit">เข้าสู่ระบบ</button>

            <div class="extra">
                ยังไม่มีบัญชี? <a href="register.php">สมัครสมาชิก</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>