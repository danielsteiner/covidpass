<?php
require "../boot/bootstrap.php";
$target_dir = "../zertifikate/";
$upload = $_FILES["certificate"];
$target_file = $target_dir.basename($upload["name"]);
$data = [
    "upload" => $upload,
    "target_file" => $target_file
];

try{

    if(move_uploaded_file($_FILES["certificate"]["tmp_name"], $target_file)) {
        $certificateData = parseCertificate($data);
        $pass = createPass($certificateData);

        unlink ($target_file);
        unlink($target_file."-003.png");
    }
} catch(Exception $ex) {
    echo $ex->getMessage();
    echo "<br>";
    unlink ($target_file);
    unlink($target_file."-003.png");
    echo "Uploaded PDF File was deleted.";
}
die();
?>