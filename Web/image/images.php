<?php
$image_file_location = "https://ams.andamanpassion.com/image/myfile.pdf";

$imagick = new Imagick(); 
$imagick->readImage('myfile.pdf'); 
$imagick->writeImage('output.jpg'); 
?>