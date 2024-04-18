<?PHP
session_start();

// ログイン済みかどうかを確認
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../login/login.php");
    exit;
}

// ログアウトボタンが押された場合の処理
if (isset($_POST['logout'])) {
    // セッション変数を全て解除する
    $_SESSION = array();

    // セッションクッキーを削除する
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 42000, '/');
    }

    // 最後に、セッションを破壊する
    session_destroy();

    // ログインページにリダイレクト
    header("location: ../login/login.php");
    exit;
}


//データベース接続　龍の目
require('../dbconnect.php');

// ユーザー情報の取得クエリ
$sql = "SELECT e.name, r.role_name
        FROM employees e
        JOIN roles r ON e.role_id = r.role_id
        WHERE e.employee_code = ?";

if($stmt = $db->prepare($sql)){
    
    // パラメータをバインド
    $employee_code = $_SESSION["employee_code"]; 
    $stmt->bindParam(1, $employee_code, PDO::PARAM_STR);

    // クエリの実行
    if($stmt->execute()){
        // 結果を取得
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $name = $row['name'];
            $role = $row['role_name'];
        }
    } 
}


// データベース接続を閉じる
$db = null;
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>お問い合わせページ</title>
    <link rel="stylesheet" href="index02.css">
    <link rel="stylesheet" href="inquiry.css">
</head>
<body>
<div class="htmlContainer">
<!-- header -->
    <header class="header is-active">
        <div class="header__fixed">
            <div class="header__fixed__inner">
                <div class="header__fixed__nav">
                    <p class="header__fixed__logo">
                        <a href="content.php">
                        <img class="lazyloaded" src="../PTSHIFT.png" data-src="" width="182" height="44" alt=""></a>
                    </p>
                    <div class="globalMenu__button__wrap">
                        <div class="globalMenu__login">
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                                <input type="submit" value="ログアウト">
                            </form>
                        </div>
                        <div class="globalMenu__signup">
                            <div class="globalMenu__button">
                                <a href="inquiry.php" target="_blank" rel="noopener" data-analysis-trigger="entry_01">お問い合わせ</a>
                            </div>
                        </div>
                    </div>
                    <p></p>
                    <div class="globalBtn c-hover" id="globalBtn">
                        <div class="globalBtn__line globalBtn__line-1"></div>
                        <div class="globalBtn__line globalBtn__line-2"></div>
                        <div class="globalBtn__line globalBtn__line-3"></div>
                    </div>
                </div>
                <nav>
                    <div class="globalMenu">
                        <div class="globalMenu__list">
                            <a href="#" data-analysis-trigger="case">シフト提出</a>
                        </div>
                        <div class="globalMenu__list">
                            <a href="#" data-analysis-trigger="function">機能</a>
                        </div>
                        <div class="globalMenu__list">
                            <a href="#" data-analysis-trigger="ep">複数店舗で使いたい場合</a>
                        </div>
                        <div class="globalMenu__list">
                            <a href="#" target="_blank" rel="noopener" data-analysis-trigger="faq_115000412811">よくある質問</a>
                        </div>  
                        <div class="globalMenu__list">
                            <a href="#" data-analysis-trigger="seminar_list">セミナー</a>
                        </div>
                        <div class="globalMenu__list is-nobdr">
                            <a href="#" data-analysis-trigger="document_list">資料一覧</a>
                        </div>
                        <div class="globalMenu__login">
                            <div class="globalMenu__login__botton">
                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                                    <input class="logout-input" type="submit" name="logout" value="ログアウト">
                                </form>
                            </div>
                        </div>
                        <div class="globalMenu__signup">
                            <div class="globalMenu__button">
                                <a href="inquiry.php" target="_blank" rel="noopener" data-analysis-trigger="entry_01">お問い合わせ</a>
                            </div>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </header>

    <div id="user_name">
        <div class="user_fixed">
            <p class="user_greeting">お疲れ様です！ 役職<!-- php echo htmlspecialchars($role); ?> --> <!-- php echo htmlspecialchars($name); ?> --> さん</p>            
        </div>
    </div>

    <main>
    <div class="container">
    <h2>お問い合わせフォーム</h2>
    <form action="submit_form.php" method="post">
        <label for="store_name" class="form-label">店舗名:</label>
        <input type="text" id="store_name" name="store_name" class="form-input" required>

        <label for="name" class="form-label">従業員コード:</label>
        <input type="text" id="name" name="name" class="form-input" required>

        <label for="inquiry_content" class="form-label">お問い合わせ内容:</label>
        <textarea id="inquiry_content" name="inquiry_content" class="form-input form-textarea" required></textarea>

        <input type="submit" value="送信" class="form-submit">
    </form>
</div>

    </main>


    <footer>
    <div class="footer">
        <p>&copy; 2024 My Website</p>
    </div>
    </footer>

</div>
</html>
</body>
</html>
