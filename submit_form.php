<?php
// データベース接続の設定
$servername = "localhost"; // MySQLサーバーのホスト名
$username = "root"; // MySQLユーザー名
$password = "Kaito8270"; // MySQLパスワード
$dbname = "ptjs_"; // 使用するデータベース名

// フォームデータからの入力を受け取る
$store_name = $_POST['store_name'];
$name = $_POST['name'];
$inquiry_content = $_POST['inquiry_content'];

try {
    // データベースへの接続
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // エラーモードを例外モードに設定
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // SQL文を準備
    $sql = "INSERT INTO inquiry (store_name, name, inquiry_content) VALUES (:store_name, :name, :inquiry_content)";
    // プリペアドステートメントを作成
    $stmt = $conn->prepare($sql);
    // パラメータをバインド
    $stmt->bindParam(':store_name', $store_name);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':inquiry_content', $inquiry_content);
    // クエリを実行
    $stmt->execute();

    // 成功メッセージを表示
    echo "<div class='success-message'>お問い合わせが送信されました。</div>";
    echo "<div class='success-message'>2秒後に元の画面に戻ります。</div>";
    echo "<div class='success-message'>少々お待ちください。</div>";

    // 2秒後に元のページにリダイレクト
    header("refresh:2;url=inquiry.php");

} catch(PDOException $e) {
    // エラーメッセージを表示
    echo "エラー: " . $e->getMessage();
}

// データベース接続を閉じる
$conn = null;
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- CSSファイルをリンク -->
    <link rel="stylesheet" href="submit_form.css">
</head>
<body>
    <!-- HTMLコンテンツ -->
</body>
</html>
