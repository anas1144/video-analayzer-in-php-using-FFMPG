<?php

use setasign\Fpdi\Fpdi;

include '../config.php';
include '../upload/db.php';
require_once('../vendor/autoload.php');

$filename = (isset($_REQUEST['filename'])) ? $_REQUEST['filename'] : '';
$video_id = (isset($_REQUEST['video_id'])) ? $_REQUEST['video_id'] : '';
$files = mysqli_query($connection, "SELECT * FROM videos WHERE id=" . $video_id);
$row = mysqli_fetch_assoc($files);
$jobid = $row['jobid'];
$order_id = $row['order_id'];

$filepath = dirname(__FILE__) . "/../orders/" . FillZero($order_id,6) . "/Clients/pdf/" . $jobid . "_" . $filename . ".pdf";


$watermark_size = 99;

// Source file and watermark config 
$text_image = dirname(__FILE__) . "/metadata/img/pdf-logp.png";

// Set source PDF file 
$pdf = new Fpdi();
if (file_exists("./" . $filepath)) {
    $pagecount = $pdf->setSourceFile($filepath);
} else {
    die('Source PDF not found!');
}

// Add watermark image to PDF pages 
for ($i = 1; $i <= $pagecount; $i++) {
    $tpl = $pdf->importPage($i);
    $size = $pdf->getTemplateSize($tpl);
    $pdf->addPage();
    $pdf->useTemplate($tpl, 1, 1, $size['width'], $size['height'], TRUE);
    $pdf_image_info = getimagesize($text_image);

    //Put the watermark 
    $water_mark_width = $pdf_image_info[0] / (102 - $watermark_size);
    $water_mark_height = $pdf_image_info[1] / (102 - $watermark_size);
    // $xxx_final = ($size['width']-60); 
    // $yyy_final = ($size['height']-15); 
    Rotate(0, 450, 400, $pdf);
    $xxx_final = ($size['width'] - ($water_mark_width + 15));
    $yyy_final = (23);

    $pdf->Image($text_image, $xxx_final, $yyy_final, $water_mark_width, $water_mark_height, 'png');
}

// Output PDF with watermark 
//$pdf->Output();
// // Download PDF file 
$pdf->Output('D', $filename . '.pdf');

// // Save PDF to local file 
// $pdf->Output('F', 'my-document.pdf');


function Rotate($angle, $x = -1, $y = -1, $pdf) {

    $angle *= M_PI / 180;
    $c = cos($angle);
    $s = sin($angle);
    $cx = $x;
    $cy = $y;
    $pdf->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm', $c, $s, -$s, $c, $cx, $cy, -$cx, -$cy));
}
