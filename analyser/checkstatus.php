<?php

header('Content-type: application/json');

include '../config.php';
include '../upload/db.php';

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


$order_id = (isset($_REQUEST['order_id'])) ? $_REQUEST['order_id'] : '';

$order = mysqli_query($connection, "SELECT * FROM `videos` WHERE status IN ('In Progress','upload') AND order_id =" . $order_id);


$orderstatus = TRUE;
while ($row = mysqli_fetch_assoc($order)) {

    $id = $row['id'];
    $subtitle = $row['subtitle'];
    $extre = $row['extra'];
    $assetno = $row['assetno'];
    $order_fcom = $row['order_fcom'];
    $status = $row['status'];
    $jobid = $row['jobid'];
    $user_id = $row['user_id'];
    $order_id = $row['order_id'];
    $uploadName = $row['file_name'];
    $del_FAT = $row['del_FAT'];
    $fileloaction = dirname(__FILE__) . "/../orders/" . FillZero($order_id,6) . "/uploads/" . $uploadName;
    if ($status == 'In Progress' && $jobid != -1) {

        $clientsDir = dirname(__FILE__) . "/../orders/" . FillZero($order_id,6);
        $fullxmlfile =  "/Clients/".$clientsDir . 'xml/' . $jobid . '_' . $uploadName . '.xml';
        _log("XML filename:" . $fullxmlfile);
        
         $outputvideofile = $clientsDir . 'output/' . $jobid . '_' . $uploadName . '.xml';
        _log("output video file:" . $outputvideofile);

        if (file_exists($fullxmlfile)) {
            // remove file
            
            if($del_FAT == 1 && file_exists($outputvideofile)){
                 unlink($outputvideofile);
            }

            $xml = simplexml_load_string(str_replace('&', '&amp;', file_get_contents($fullxmlfile))); //Fix [RBA/15/04/2014]
            //print_r($xml);
            if ($xml) {

                if ($xml->summary) {
                    $status = $xml->summary['status'];
                }
            }
        }
        
       
        $query = "UPDATE `videos` SET  `status` = '" . $status . "' WHERE `videos`.`id` = " . $id;
    } else {
        
        $resultapi = SubmitJob($user_id, $fileloaction, $uploadName, $assetno, $subtitle, $extre, $order_fcom, $order_id);
        if ($resultapi == -1):
            $orderstatus = FALSE;
        endif;
        $guery = "UPDATE `videos` SET  `jobid` = " . $resultapi . " WHERE `videos`.`id` = " . $id;
    }

    if (!mysqli_query($connection, $query)) {
        echo "Error updating record: " . mysqli_error($connection);
        die;
    }
}

if ($orderstatus = TRUE):
    $query = "UPDATE `orders` SET `status` = '2' WHERE `orders`.`order_id` = " . $order_id;
    mysqli_query($connection, $query);
endif;

echo json_encode(array(
    'message' => 'success'
));
exit();
