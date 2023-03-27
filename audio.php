<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

header('Access-Control-Allow-Origin: *');

function generateRandomString($length = 5) {
    $characters = '0123456789abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

if(isset($_FILES["audio"])){
    $fileName = generateRandomString();
    if(file_exists("audios/" . $fileName)) unlink("audios/" . $fileName);
    $data = file_get_contents($_FILES['audio']['tmp_name']);    
    $fp = fopen("audios/" . $fileName . '.mp3', 'wb');

    fwrite($fp, $data);
    fclose($fp);

    echo $fileName;
}


?>
