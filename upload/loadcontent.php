<?php
include '../config.php';
include '../upload/db.php';

$type = (isset($_REQUEST['type'])) ? $_REQUEST['type'] : '';

//ids
if ($type == 'ids') {
    $user_id = (isset($_REQUEST['user_id'])) ? $_REQUEST['user_id'] : '';
    $order = mysqli_query($connection, "SELECT * FROM `orders` WHERE status ='0' ORDER BY order_id DESC LIMIT 1");
    $data = mysqli_fetch_assoc($order);

    if ($data != null) :
        $order_id = $data['order_id'];
    else :
        $query = "INSERT INTO `orders` (`status`) VALUES ('0')";
        $insert = mysqli_query($connection, $query);
        $order_id = mysqli_insert_id($connection);
    endif;
    $firstuser = mysqli_query($connection, "SELECT * FROM users");
    $user_id = ($user_id != '') ? $user_id : mysqli_fetch_assoc($firstuser)['user_id'];

    echo json_encode(array(
        "order_id" => $order_id,
        "user_id" => $user_id,
        "fill_order_id" => FillZero($order_id, 6)
    ));
    exit;
} else if ($type == 'loadusers') {
    $user_id = (isset($_REQUEST['user_id'])) ? $_REQUEST['user_id'] : '';
    $user = mysqli_query($connection, "SELECT * FROM users");

    while ($row = mysqli_fetch_assoc($user)) {
        ?>
        <option <?= ($user_id == $row['user_id']) ? 'selected' : '' ?> value="<?= $row['user_id']; ?>"><?= $row['user_id']; ?></option>
        <?php
    }
    return;
} else if ($type == "loadtable") {
    $user_id = (isset($_REQUEST['user_id'])) ? $_REQUEST['user_id'] : '';
    $files = mysqli_query($connection, "SELECT * FROM orders WHERE user_id='$user_id' AND `status` IN ('1','2') order by order_id DESC");

    while ($row = mysqli_fetch_assoc($files)) {
        $thisorder_id = $row['order_id'];
        $count = mysqli_query($connection, "SELECT COUNT(*) as totalfiles FROM `videos` WHERE  `order_id` =" . $row['order_id']);
        $totalfiles = mysqli_fetch_assoc($count);
        ?>
        <div>
            <tr>
                <td>
                    <label class="font-weight-bold progress-label">% COMPLETED</label>
                    <div class="progress-bar">
                        <div class="progress"></div>
                    </div>
                </td>
                <td>
                    <label class="font-weight-bold text-danger">Order</label>
                    <div><?php print FillZero($row['order_id'], 6); ?></div>
                    <input type="hidden" class="order_ids" value="<?php print $row['order_id']; ?>">
                </td>
                <td>
                    <label class="font-weight-bold text-danger">Ordered On</label>
                    <div><?= date("d-m-Y", strtotime($row['creation_date'])); ?></div>
                </td>
                <td>
                    <label class="font-weight-bold text-danger">Est.Completion</label>
                    <div></div>
                </td>
                <td>


                </td>


            </tr>

            <?php
            $file = mysqli_query($connection, "SELECT * FROM `videos` WHERE `order_id` =" . $row['order_id']);
            while ($filensme = mysqli_fetch_assoc($file)) {
                $video_id = $filensme['id'];
                $fileName = $filensme['file_name'];
                $order_id = $filensme['order_id'];
                $json = json_decode($filensme['json']);
                ?>
                <tr>
                    <td>

                        <div class="file-name"><?= $filensme['file_name'] ?></div>

                    </td>
                    <td>

                        <div>
                            <div role="alert" class="<?= ($filensme['jobid'] == -1) ? '' : (($filensme['status'] == 'Passed') ? 'alert alert-success' : (($filensme['status'] == 'Failed') ? 'alert alert-danger' : 'alert alert-warning')) ?>"> <?= (($filensme['jobid'] == -1) ? (($filensme['status'] == 'upload') ? '<button type="button" class="btn btn-light" onclick="singleordersstutas('.$order_id.')">Submit</button>':'<button type="button" class="btn btn-light" onclick="singleordersstutas('.$order_id.')">Re-submit</button>') : $filensme['status'] ) ?></div>
                        </div>

                    </td>
                    <td>
                        <label class="font-weight-bold text-danger">Duration</label>
                        <div><?= $json->duration ?></div>

                    </td>
                    <td>
                        <label class="font-weight-bold text-danger">Resolution</label>
                        <div><?= $json->width . "x" . $json->height ?></div>

                    </td>
                    <td>

                        <div> <?= ($filensme['jobid'] != -1 && ($filensme['status'] == 'Passed' || $filensme['status'] == 'Failed' )) ? '<span class="viewresult"><a href="analyser/getpdf.php?filename=' . $fileName . '&video_id=' . $video_id . '"><ion-icon name="download-outline"></ion-icon></a></span>' : '<span style="color:gray;"><ion-icon style="color:gray;" name="download-outline"></ion-icon></span>' ?></div>

                    </td>
                </tr>
                <?php
            }
            ?>

        </div>
        <?php
    }
   
} else if ($type = "maxsize") {
    ?>
    You can upload a maximum number of <?php echo ini_get('max_file_uploads'); ?> files. The cumulative total size cannot exceed <?php echo ini_get('post_max_size'); ?>
    <?php
}
?>