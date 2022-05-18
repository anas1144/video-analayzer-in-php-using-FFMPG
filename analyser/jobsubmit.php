<?php

include '../config.php';
include '../upload/db.php';

header('Content-type: application/json');

require_once ('../vendor/nusoap/lib/nusoap.php');
include 'fpa_operations.php';

// Temporary disabled the processing due to FFMPEG not working...

if (!ENABLE_PROCESSING) {
    $dataJson = array(
        'message' => 'Processing Disabled'
    );
    echo json_encode($dataJson);
    exit;
}

$user_id = (isset($_REQUEST['user_id'])) ? $_REQUEST['user_id'] : '';
$order_id = (isset($_REQUEST['order_id'])) ? $_REQUEST['order_id'] : '';
$order_fcoms = (isset($_REQUEST['order_fcom'])) ? $_REQUEST['order_fcom'] : '';

$user_directory = dirname(__FILE__) . "/../orders/" . FillZero($order_id,6) . "/";
$order = mysqli_query($connection, "SELECT * FROM `videos` WHERE status ='upload' AND order_id =" . $order_id);

$orderstatus = TRUE;
while ($row = mysqli_fetch_assoc($order)) {
    $id = $row['id'];

    $subtitle = 'subtitle-' . $row['id'];
    $extre = 'extra-' . $row['id'];
    $assetno = 'assetno-' . $row['id'];
    $order_fcom = 'order_fcom-' . $row['id'];
    $del_FAT = 'Del-FAT-' . $row['id'];

    foreach ($_POST as $key => $item) {
        if ($subtitle == $key) {
            $subtitle = $item;
        }
        if ($extre == $key) {
            $extre = $item;
        }
        if ($assetno == $key) {
            $assetno = $item;
        }
        if ($order_fcom == $key) {
            $order_fcom = $item;
        }
        if ($del_FAT == $key) {
            $del_FAT = $item;
        }
    }

    $guery = "UPDATE `videos` SET `status` = 'In Progress',`subtitle` = '" . $subtitle . "',`extra` = '" . $extre . "',`assetno` = '" . $assetno . "',`order_fcom` = '" . $order_fcom . "',`del_FAT` = '" . $del_FAT . "' WHERE `videos`.`id` = " . $id;
    mysqli_query($connection, $guery);

    $uploadName = $row["file_name"];

    $fileloaction = $user_directory . "uploads/" . $uploadName;

    $resultapi = SubmitJob($user_id, $fileloaction, $uploadName, $assetno, $subtitle, $extre, $order_fcom, $order_id);
    if ($resultapi == -1):
        $orderstatus = FALSE;
    endif;

    // update db data
    $guery = "UPDATE `videos` SET  `jobid` = " . $resultapi . " WHERE `videos`.`id` = " . $id;

    if (!mysqli_query($connection, $guery)) {
        echo "Error updating record: " . mysqli_error($connection);
        die;
    }
}
if ($orderstatus = TRUE):
    $guery = "UPDATE `orders` SET `status` = '2' WHERE `orders`.`order_id` = " . $order_id;
    mysqli_query($connection, $guery);
endif;

echo json_encode(array(
    'message' => 'success'
));
exit();

