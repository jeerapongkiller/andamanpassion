<?php
if(!empty($_GET['name'])){
    $name_file = $_GET['name'].".pdf";
    $file = "../assets/invoice_pdf/".$name_file;
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.urldecode($name_file));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file)); 
    ob_clean();
    flush();
    readfile($file); 
    echo "Complete!";
}else{
    echo "Erorr!";
}
?>