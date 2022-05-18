<?php header('Content-type: application/json');
include 'upload/db.php';
$user_id = (isset($_GET['user_id'])) ? $_GET['user_id'] : '';
$order_id = (isset($_GET['order_id'])) ? $_GET['order_id'] : '';

if (empty($user_id) || empty($order_id))
{
    echo json_encode(array(
        'error' => "user_id and order_id is required"
    ));
    exit;
}

$user = mysqli_query($connection, "SELECT * FROM orders WHERE status ='2' AND user_id=" . $user_id . " order_id=" . $order_id);

$dataJson = array();
while ($row = mysqli_fetch_assoc($files))
{

    $dataJson[] = json_decode($row['json']);
}

echo json_encode($dataJson);

