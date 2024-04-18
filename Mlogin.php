<?php
session_start();
require('../dbconnect.php');

if (!empty($_POST)) {
    // エラー項目の確認
    $error = array();

    if ($_POST['manager_code'] == '') {
        $error['manager_code'] = 'blank';
    }
    if ($_POST['employee_code'] == '') {
        $error['employee_code'] = 'blank';
    }
    if ($_POST['password'] == '') {
        $error['password'] = 'blank';
    }

    if (empty($error)) {
        // ログイン処理
        $manager_code = $_POST['manager_code'];
        $employee_code = $_POST['employee_code'];
        $password = $_POST['password'];

        // マネージャーのパスワードを取得
        $stmt_manager = $db->prepare('SELECT * FROM managers WHERE manager_code = ?');
        $stmt_manager->execute([$manager_code]);
        $manager = $stmt_manager->fetch();

        // マネージャーのパスワードと入力されたパスワードを比較
        if ($manager && $password === $manager['password']) {
            // マネージャーコードが正しい場合、従業員のパスワードを取得
            $stmt_employee = $db->prepare('SELECT * FROM employees WHERE employee_code = ?');
            $stmt_employee->execute([$employee_code]);
            $employee = $stmt_employee->fetch();

            // 従業員のパスワードと入力されたパスワードを比較
            if ($employee && $password === $employee['password']) {
                // ログイン成功の処理
                $_SESSION['employee'] = $employee;
                header('Location: ../content/content.php');
                exit();
            } else {
                // ログイン失敗の処理
                $error['login'] = 'failed';
            }
        } else {
            // ログイン失敗の処理
            $error['login'] = 'failed';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログインページ</title>
    <link rel="stylesheet" href="index01.css"> <!-- CSSファイルのリンク -->
</head>
<body>
    <div class="container">
        <h2>ログイン</h2>
        <form action="Mlogin.php" method="POST">
            <div class="form-group">
                <label for="manager_code">マネージャーコード:</label>
                <input type="text" id="manager_code" name="manager_code" maxlength="6">
                <?php if (isset($error['manager_code']) && $error['manager_code'] == 'blank'): ?>
                <p>* マネージャーコードを入力してください</p>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="employee_code">社員コード:</label>
                <input type="text" id="employee_code" name="employee_code" maxlength="6">
                <?php if (isset($error['employee_code']) && $error['employee_code'] == 'blank'): ?>
                <p>* 社員コードを入力してください</p>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="password">パスワード:</label>
                <input type="password" id="password" name="password" maxlength="255">
                <?php if (isset($error['password']) && $error['password'] == 'blank'): ?>
                <p>* パスワードを入力してください</p>
                <?php endif; ?>
            </div>
            <button type="submit">ログイン</button>
        </form>
        <div class="manager-login">
            <p>クルーの方は<a href="login.php">こちら</a></p>
        </div>
    </div>
</body>
</html>
