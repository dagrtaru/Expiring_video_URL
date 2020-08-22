<?php
    //Load the signature helper functions
    include "libraries/VideoSignature.php";
    $vs = new VideoSignature();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Check out my video!</title>
</head>
<body>
    <!-- /!\ Here, you need to insert a path to your video,
    relative to the getVideo.php file, not an actual URL! -->
    <video src="<?=$vs->getSignedURL("server/path/to/video.mp4");?>"></video>
</body>
</html>