<?php
session_start();
require('../dbconnect.php');

if (isset($_COOKIE['employee_code']) && $_COOKIE['employee_code'] != '') {
    $_POST['employee_code'] = $_COOKIE['employee_code'];
    $_POST['password'] = $_COOKIE['password'];
    $_POST['save'] = 'on';
}

if (!empty($_POST)) {
    // エラー項目の確認
    $error = array();

    if (isset($_POST['employee_code'])) {
        $employee_code = $_POST['employee_code'];
    } else {
        $employee_code = ''; // 社員コードが送信されていない場合、空の値を設定
    }

    if ($employee_code == '') {
        $error['employee_code'] = 'blank';
    }

    if (empty($_POST['password'])) {
        $error['password'] = 'blank';
    }

    if (empty($error)) {
        // ログイン処理
        $employee_code = $_POST['employee_code'];
        $password = $_POST['password'];

        // データベースから従業員情報を取得
        $stmt = $db->prepare('SELECT * FROM employees WHERE employee_code = ?');
        $stmt->execute([$employee_code]);
        $employee = $stmt->fetch();

        // データベースから取得したパスワードと入力されたパスワードを比較
        if ($employee && !empty($employee['password']) && $password === $employee['password']) {
        // ログイン成功の処理
        $_SESSION['employee'] = $employee;
        $_SESSION["employee_code"] = $employee_code;
        $_SESSION["loggedin"] = true;

        if ($_POST['save'] == 'on') {
            $expire = time() + 60 * 60 * 24 * 14; // 14日間有効
            setcookie('employee_code', $_POST['employee_code'], $expire);
            setcookie('password', $_POST['password'], $expire);
        }

            header('Location: ../content/content.php');
            exit();
        } else {
            // ログイン失敗の処理
            $error['login'] = 'failed';
        }
    } else {
        // ログイン失敗の処理
        $error['login'] = 'blank';
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
        <form action="login.php" method="POST">
            <div class="form-group">
                <label class="input_text" for="employee_code">社員コード:</label>
                <input type="text" id="employee_code" name="employee_code" maxlength="6" 
                value="<?php echo isset($_POST['employee_code']) ? htmlspecialchars($_POST['employee_code'], ENT_QUOTES) : ''; ?>">
                <?php if (isset($error['employee_code']) && $error['employee_code'] == 'blank'): ?>
                <p class="error-message">* 社員コードを入力してください</p>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label class="input_text" for="password">パスワード:</label>

                <div class="password-input-container">
                    <input type="password" id="password" name="password" maxlength="255" 
                     value="<?php echo isset($_POST['password']) ? htmlspecialchars($_POST['password'], ENT_QUOTES) : ''; ?>">
                    <button id="togglePassword" type="button">表示</button>
                </div>

                <?php if (isset($error['password']) && $error['password'] == 'blank'): ?>
                <p class="error-message">* パスワードを入力してください</p>
                <?php endif; ?>
                <?php if (isset($error['login']) && $error['login'] === 'failed'): ?>
                <p class="error-message">ログインに失敗しました。<br>社員コードまたはパスワードが正しいかご確認ください。</p>
                <?php endif; ?>
            </div>
            <div class="input_save">
                <input type="checkbox" name="save" value="on">
                <label for="save">次回からは自動的にログインする</label>
            </div>
            <button type="submit">ログイン</button>
        </form>
        <div class="manager-login">
            <p>マネージャーの方は<a href="Mlogin.php">こちら</a></p>
        </div>
    </div>

    <script>
        document.getElementById('togglePassword').addEventListener('click', function() {
            var passwordField = document.getElementById('password');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                this.textContent = '非表示';
            } else {
                passwordField.type = 'password';
                this.textContent = '表示';
            }
        });
    </script>

</body>
</html>
