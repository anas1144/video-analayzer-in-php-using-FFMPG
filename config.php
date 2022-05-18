<?php
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
}

header("Access-Control-Allow-Origin: localhost"); //localhost
header("Access-Control-Allow-Origin: 127.0.0.1"); //localhost
header("Access-Control-Allow-Origin: google.com"); 

// Define the path of FFMPEG for live SERVER
// FFPROBE_PATH : for local env, set to only 'ffprobe' for live server it can be for example: '/opt/ffmpeg/ffprobe'
define('FFPROBE_PATH', 'C:\ffmpeg\bin\ffprobe.exe');

// FFMPEG_PATH: for local env, set to only 'ffmpeg' for live server it can be for example: '/opt/ffmpeg/ffmpeg'
define('FFMPEG_PATH', 'C:\ffmpeg\bin\ffmpeg.exe');

//ENABLE_PROCESSING : In order to check if the set path is correct, please run the checker.php in browser if returns valid output, then set to true if the result says "not found" or "not installed" then set it to false.
define('ENABLE_PROCESSING', true);

function convert($iSeconds)
{
    $min = intval($iSeconds / 60);
    return $min . ':' . str_pad(($iSeconds % 60) , 2, '0', STR_PAD_LEFT);
}

// Get the live server root domain and path (if any)
function siteURL()
{
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $domainName = $_SERVER['HTTP_HOST'];

    $baseUrl = substr($_SERVER['REQUEST_URI'], 0, strrpos($_SERVER['REQUEST_URI'], '/') + 1);
    return $protocol . $domainName . $baseUrl;
}

function FillZero($text, $len)
{
    while (strlen($text) < $len) $text = "0" . $text;
    return $text;
}

