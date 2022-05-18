<?php

include '../config.php';
include '../upload/db.php';

// Temporary disabled processing due to FFMPEG not working...

if (!ENABLE_PROCESSING) {
    $dataJson = array(
        'status' => 'error',
        'message' => 'Processing Disabled'
    );
    echo json_encode($dataJson);
    exit;
}

$user_id = (isset($_REQUEST['user_id'])) ? $_REQUEST['user_id'] : '';

$user = mysqli_query($connection, "SELECT * FROM `users` WHERE user_id =" . $user_id);
$data = mysqli_fetch_assoc($user);

if ($data != null):
    $dataJson = array(
        'status' => 'error',
        'message' => 'User alredy exists'
    );
    echo json_encode($dataJson);
    exit;

else:
    $query = "INSERT INTO `users` (`user_id`) VALUES (" . $user_id . ")";
    mysqli_query($connection, $query);
    $dataJson = array(
        'status' => 'success',
        'message' => 'User created'
    );
    echo json_encode($dataJson);
    exit;
endif;

