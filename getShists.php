<?php
// データベースの設定情報を定義します。
$servername = "localhost";
$username = "";
$password = "";
$dbname = "";

// PDOを使用してデータベースに接続します。
// PDOはエラー処理やセキュリティ（プリペアドステートメント）などの面でmysqliより推奨されます。
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // エラーモードを例外モードに設定
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// 従業員コードを取得します。もし従業員コードが渡されていない場合はエラーを返します。
if (!isset($_GET['employee_code'])) {
    http_response_code(400);
    echo json_encode(array("message" => "従業員コードが指定されていません。"));
    exit;
}

$employee_code = $_GET['employee_code'];

// スケジュールデータを取得するクエリを定義します。
$sql = "SELECT shift_id, title, description, start_date, start_time, end_date, end_time, calendar_color, calendar_text_color FROM shifts WHERE start_date >= :startDate AND end_date <= :endDate";

// パラメータをバインドしてクエリを実行します。
$stmt = $conn->prepare($sql);
$stmt->bindParam(':employee_code', $employee_code);
try {
    // クエリの実行
    $stmt->execute();
    $scheduleList = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // JSON形式でレスポンスを返す
    header('Content-Type: application/json');
    echo json_encode($scheduleList);
} catch (PDOException $e) {
    // エラーハンドリング
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
