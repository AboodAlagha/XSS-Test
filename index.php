<?php
session_start();
$conn = new mysqli("localhost", "root", "", "xss_demo");

if (isset($_POST['login'])) {
    $user = $_POST['username'];
    $pass = $_POST['password'];
    $result = $conn->query("SELECT * FROM users WHERE username='$user' AND password='$pass'");
    if ($result->num_rows > 0) {
        $_SESSION['user'] = $user;
    }
}

if (isset($_POST['submit_comment']) && isset($_SESSION['user'])) {
    $comment = $_POST['comment'];
    $stmt = $conn->prepare("INSERT INTO comments (user_comment) VALUES (?)");
    $stmt->bind_param("s", $comment);
    $stmt->execute();
}

$all_comments = $conn->query("SELECT * FROM comments ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>XSS Secure Project</title>
    <style>
        body { font-family: sans-serif; background: #f4f7f6; padding: 40px; }
        .container { background: white; padding: 25px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); max-width: 500px; margin: auto; }
        input, textarea { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer; }
        .comment-item { background: #eee; padding: 10px; margin-top: 10px; border-radius: 5px; border-right: 5px solid #28a745; word-wrap: break-word; }
    </style>
</head>
<body>

<div class="container">
    <?php if (!isset($_SESSION['user'])): ?>
        <h2>تسجيل الدخول</h2>
        <form method="POST">
            <input type="text" name="username" placeholder="admin" required>
            <input type="password" name="password" placeholder="123" required>
            <button name="login">دخول</button>
        </form>
    <?php else: ?>
        <h2>أهلاً، <?php echo htmlspecialchars($_SESSION['user'], ENT_QUOTES, 'UTF-8'); ?></h2>
        
        <form method="POST">
            <textarea name="comment" placeholder="اكتب تعليقك هنا..." required></textarea>
            <button name="submit_comment">نشر التعليق الآمن</button>
        </form>
        <p><a href="logout.php">تسجيل الخروج</a></p>

        <hr>
        <h3>التعليقات (المحمية):</h3>
        <?php while($row = $all_comments->fetch_assoc()): ?>
            <div class="comment-item">
                <?php echo htmlspecialchars($row['user_comment'], ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endwhile; ?>
    <?php endif; ?>
</div>

</body>
</html>
