<?php
$imagick = new Imagick();
$imagick->readImage('https://ams.andamanpassion.com/image/myfile.pdf');
$imagick->writeImages('converted.jpg', false);
?>