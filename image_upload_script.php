<?php
function upload() {
// Access the $_FILES global variable for this specific file being uploaded
// and create local PHP variables from the $_FILES array of information
$fileName = $_FILES["uploaded_file"]["name"]; // The file name
$fileTmpLoc = $_FILES["uploaded_file"]["tmp_name"]; // File in the PHP tmp folder
$fileType = $_FILES["uploaded_file"]["type"]; // The type of file it is
$fileSize = $_FILES["uploaded_file"]["size"]; // File size in bytes
$fileErrorMsg = $_FILES["uploaded_file"]["error"]; // 0 for false... and 1 for true
$kaboom = explode(".", $fileName); // Split file name into an array using the dot
$fileExt = end($kaboom); // Now target the last array element to get the file extension
// START PHP Image Upload Error Handling --------------------------------------------------
if (!$fileTmpLoc) { // if file not chosen
	echo '<script language="javascript">';
	echo 'alert("ERROR: Please browse for a file before clicking the upload button.")';
	echo '</script>';
    //exit();
} else if($fileSize > 5242880) { // if file size is larger than 5 Megabytes
	echo '<script language="javascript">';
	echo 'alert("ERROR: Your file was larger than 5 Megabytes in size.")';
	echo '</script>';
    unlink($fileTmpLoc); // Remove the uploaded file from the PHP temp folder
} else if (!preg_match("/.(gif|jpg|png)$/i", $fileName) ) {
     // This condition is only if you wish to allow uploading of specific file types    
     echo "ERROR: Your image was not .gif, .jpg, or .png.";
     unlink($fileTmpLoc); // Remove the uploaded file from the PHP temp folder
     exit();
} else if ($fileErrorMsg == 1) { // if file upload error key is equal to 1
	echo '<script language="javascript">';
	echo 'alert("ERROR: An error occured while processing the file. Try again.")';
	echo '</script>';
}
// END PHP Image Upload Error Handling ----------------------------------------------------
// Place it into your "uploads" folder mow using the move_uploaded_file() function
$moveResult = move_uploaded_file($fileTmpLoc, "img/$fileName");
// Check to make sure the move result is true before continuing
if ($moveResult != true) {
	echo '<script language="javascript">';
	echo 'alert("ERROR: File not uploaded. Try again.")';
	echo '</script>';
    unlink($fileTmpLoc); // Remove the uploaded file from the PHP temp folder
    exit();
}
unlink($fileTmpLoc); // Remove the uploaded file from the PHP temp folder
// ---------- Include Universal Image Resizing Function --------
include_once("ak_php_img_lib_1.0.php");
$target_file = "img/$fileName";
$resized_file = "img/$fileName";
$wmax = 100;
$hmax = 100;
ak_img_resize($target_file, $resized_file, $wmax, $hmax, $fileExt);
// ----------- End Universal Image Resizing Function -----------
// Display things to the page so you can see what is happening for testing purposes
	echo '<script language="javascript">';
	echo 'alert("Upload successful.")';
	echo '</script>';
}
?>