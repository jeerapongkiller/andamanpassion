<?php 
    $imagick = new Imagick();
    // $imagick->readimage('IV63110001.pdf'); 
    $imagick->readPdf("images/IV63110001.pdf[0]");
    // $imagick->writeImage('test.jpg');

    // $images = array("image.jpg", "image1.jpg");

    // $pdf = new Imagick($images);
    // $pdf->setImageFormat('pdf');
    // $pdf->writeImages('combined.pdf', true); 
?>

<?php

// header('Content-type: image/jpeg');

// $image = new Imagick('image.jpg');

// $image->thumbnailImage(100, 0);

// echo $image;

?>