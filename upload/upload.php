<?php

header('Content-type: application/json');

require_once ('../vendor/nusoap/lib/nusoap.php');
include '../config.php';
include '../upload/db.php';
include '../analyser/fpa_operations.php';

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

// Ensure user_id and order_id exists

if (empty($user_id) || empty($order_id)) {
    echo json_encode(array(
        'error' => "user_id or order_id is not found"
    ));
    exit;
}

// Update status 0 to 1
$guery = "UPDATE `orders` SET `status` = '1' WHERE `orders`.`order_id` = " . $order_id;
mysqli_query($connection, $guery);

$user_directory = dirname(__FILE__) . "/../orders/" . FillZero($order_id,6) ."/";
_log("New client:" . $user_directory);
// Check if the folder of this user exists
if (!file_exists($user_directory)) {
   
    // CREATE order folder structure.
    mkdir($user_directory, 0777, true);
    mkdir($user_directory."Clients", 0777, true);
    mkdir($user_directory."Clients/csv", 0777, true);
    mkdir($user_directory."Clients/par", 0777, true);
    mkdir($user_directory."Clients/pdf", 0777, true);
    mkdir($user_directory."Clients/xml", 0777, true);
    mkdir($user_directory."images", 0777, true);
    mkdir($user_directory."json", 0777, true);
    mkdir($user_directory."uploads", 0777, true);
    mkdir($user_directory."output", 0777, true);
   
}


if (isset($_FILES['media']['name']) && !empty($_FILES['media']['name']) && isset($_POST['file_submit'])) {
    $fileCount = count($_FILES['media']['name']);

    $dataJson = array();
    for ($i = 0; $i < $fileCount; $i++) {
        $RandomNum = time();

        $name = $_FILES['media']['name'][$i];
        $ext = pathinfo($name, PATHINFO_EXTENSION); // To get extension
        $name2 = pathinfo($name, PATHINFO_FILENAME);
        // File name without extension
        $name2 = str_replace(' ', '_', strtolower($name2));
        $name2 = preg_replace('/[^a-zA-Z0-9_]+/', '', strtolower($name2));

        $uploadName = "$name2.$ext";
        $data = array();
        if (!empty($name)) {
            move_uploaded_file($_FILES["media"]["tmp_name"][$i], $user_directory . 'uploads/'.$uploadName);

            $query = "INSERT INTO `videos` (`order_id`,`user_id`,`file_name`,`status`) VALUES ($order_id,$user_id,'$uploadName','upload')";
            $insert = mysqli_query($connection, $query);
            $video_id = mysqli_insert_id($connection);

            $data['video_id'] = $video_id;
            $data['file_name'] = $uploadName;

            $command = FFPROBE_PATH . " -loglevel 0 -print_format json -show_streams -show_format $user_directory".'uploads/'."$uploadName > " . $user_directory."json/$uploadName.json 2>&1";
            
            $output = shell_exec($command);
           
            // Process the json created by the above script, and extract only required data
            $file = glob($user_directory."json/$uploadName.json")[0];
            
            $fileName = basename($file);
            $jsonData = json_decode(file_get_contents($file));

            $data["user_id"] = $user_id;
            $data["order_id"] = $order_id;

            // Process streams if we can find all.
            $streams = $jsonData->streams;
            foreach ($streams as $key => $value) {
                foreach ($value as $keyName => $valueData) {
                    if (in_array($keyName, array(
                                "width",
                                "height",
                                "duration",
                                "color_space",
                                "color_transfer",
                                "filename"
                            ))) {
                        $data[$keyName] = $valueData;
                    }
                }
            }

            // Process format if we can find more
            $format = $jsonData->format;
            foreach ($format as $keyName => $valueData) {
                if (in_array($keyName, array(
                            "width",
                            "height",
                            "duration",
                            "color_space",
                            "color_transfer",
                            "filename"
                        ))) {
                    $data[$keyName] = $valueData;
                }
            }

            
            $secondsHalf = "00.00.01";
            if (isset($data['duration'])) {
                $secondsHalf = round($data['duration'] / 2);
                $secondsHalf = gmdate("H:i:s", $secondsHalf);
                $data['duration'] = convert($data['duration']);
            }

            // Before writing the json, lets process it again for the thumnail.
            $videoName = basename($data['filename']);
            $image = pathinfo($videoName, PATHINFO_FILENAME);

            $imagePath =  $user_directory."images/$image.jpg";

            // Extract the image from the url:
            $commandImage = FFMPEG_PATH . " -ss $secondsHalf -i " .  $user_directory."uploads/$videoName -vframes 1 -q:v 2 $imagePath";
            $outputImage = shell_exec($commandImage);

            $data['image'] = siteURL() . '../orders/'.FillZero($order_id,6).'/images/' . $image . '.jpg';
            $data['filename'] = siteURL() . '../orders/'.FillZero($order_id,6).'/upload/' . $videoName;
            $data['json'] = $file;
            
            $db_completejson = file_get_contents($data['json']);

            unset($data["json"]);
            unset($data["filename"]);
            $db_json = json_encode($data);

            // Update db 
            $guery = "UPDATE `videos` SET  `jobid` = -1, `json` = '" . $db_json . "', `completejson` = '" . $db_completejson . "' WHERE `videos`.`id` = " . $video_id;

            if (!mysqli_query($connection, $guery)) {
                echo "Error updating record: " . mysqli_error($connection);
                die;
            }

            $dataJson[] = $data;


        }
    }
}

$guery = "UPDATE `orders` SET  `user_id` = " . $user_id . " WHERE `orders`.`order_id` = " . $order_id;

if (!mysqli_query($connection, $guery)) {
    echo "Error updating record: " . mysqli_error($connection);
    die;
}
echo json_encode($dataJson);

