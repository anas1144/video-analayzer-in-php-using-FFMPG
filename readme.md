### Installation

Clone the repo
```sh
git clone https://bitbucket.org/evidu/video-analyser.git
```

Make sure you have installed the FFMPEG on your Server/OS

Please note, that without FFMPEG installation on your machine it will not work. 

Help Article on FFMPEG Installation on Windows: https://www.wikihow.com/Install-FFmpeg-on-Windows 

## Usage

Directory structure is self explanatory. (Create Folder if Does not exists for Initial Run)

json  
processed  
videos  
uploads 


## FFMPEG Checker  

config.php file is set now so youc an set your FFMPEG path in it:  

FFMPEG_PATH : for local env, set to only 'ffmpeg' for live server it can be for example: '/opt/ffmpeg/ffmpeg'  
FFPROBE_PATH : for local env, set to only 'ffprobe' for live server it can be for example: '/opt/ffmpeg/ffprobe'  
ENABLE_PROCESSING : In order to check if your give path is correct, please run the checker.php in browser if returns valid out such as https://prnt.sc/1u348dq then set to true if the result says not found or not installed then set it to false  


## Manual Video Processing Usage

Please upload the videos which you wish to process into videos folder. 

And then run the file in your localhost. i.e. http://localhost/video_analyzer/process.php?user_id={user_id}&order_id={order_id}

It will process all videos and generate the each json in json, with user_id and order_id sub folders

Then you can run the api.php file in browser to get all json output. i.e. http://localhost/video_analyzer/api.php?user_id={user_id}&order_id={order_id}  


## Client side and Server side usage

upload.php is used for server side usage now, if you want to use uploader UI for videos uploading then give its path in the UI form action. example: http://localhost/upload.php it will process the video files according to order id and user id entered in form and will return the json in output section.  

ui/index.html this file is for client side video uploading, just update the action as per above explained and start using.  

In case you used the uploader only without processing enabled, and you want to process the previously uploaded videos then you can run that upload.php in browser with user_id and order_id param. i.e. http://localhost/video_analyzer/upload.php?user_id={user_id}&order_id={order_id}  


----------------


## Note:

The new Client and Server side architecture is separate from the manual video processing. You can still use the old method "Manual Video Processing Usage"

