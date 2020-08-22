<?php
    //Load the signature helper functions
    include "libraries/VideoSignature.php";
    $vs = new VideoSignature();
    //Load the video streaming functions
    include "lbraries/VideoStreaming.php";

    if(isset($_REQUEST['s']) && $filepath = $vs->getFilepath($_REQUEST['s'])){
        $stream = new VideoStream($filepath);
        $stream->start;
    }
    else{
        header("HTTP/1.0 403 Forbidden");
        echo "This URL has expired.";
    }
?>