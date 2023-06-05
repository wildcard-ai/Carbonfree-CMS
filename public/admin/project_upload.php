<?php
require_once('../../private/initialize.php');

$errors = [];
$insertedIds = [];
$pathUrls = [];
$extensions = ["jpg", "jpeg", "png", "gif", "webp"];
$uploadLimit = 2097152;
$path = "../uploads/";

create_directory_if_not_exists($path);

if (!isset($_FILES["files"])) {
  die(json_encode(["success" => false, "error" => "No file was uploaded."]));
}

$project_id = $_POST["project_id"];
$all_files = count($_FILES['files']['tmp_name']);
$fileNames = [];

// Flag variable to track invalid files
$isValid = true; // Flag variable to track if all files are valid

for ($i = 0; $i < $all_files; $i++) {
  $file_name = $_FILES['files']['name'][$i];
  $file_tmp = $_FILES['files']['tmp_name'][$i];
  $file_type = $_FILES['files']['type'][$i];
  $file_size = $_FILES['files']['size'][$i];
  $file_ext = get_file_extension($file_name);
  $fileNames[] = $file_name;

  $filename = generate_unique_filename($file_ext);
  $file = generate_upload_path($filename, $path);
  $upload_name = generate_upload_url($filename);

  if (!is_valid_extension($file_ext, $extensions)) {
    $isValid = false; // Set the flag if any file is invalid
    $errors[] = 'Extension not allowed: ' . $file_name . ' ' . $file_type;
  }

  if (!is_valid_file_size($file_size, $uploadLimit)) {
    $isValid = false; // Set the flag if any file is invalid
    $errors[] = 'File size exceeds limit: ' . $file_name . ' ' . $file_type;
  }
}

if ($isValid) {
  for ($i = 0; $i < $all_files; $i++) {
    $file_tmp = $_FILES['files']['tmp_name'][$i];
    $file_ext = get_file_extension($_FILES['files']['name'][$i]);

    $filename = generate_unique_filename($file_ext);
    $file = generate_upload_path($filename, $path);
    $upload_name = generate_upload_url($filename);

    move_uploaded_file_to_destination($file_tmp, $file);

    $result = insert_image_by_project_id($db, $project_id, $upload_name, 1);

    if ($result === true) {
      $new_id = mysqli_insert_id($db);
      $insertedIds[] = $new_id;
      $pathUrls[$upload_name] = url_for($upload_name);
    } else {
      $errors[] = $result;
    }
  }
}

if (empty($errors)) {
  echo json_encode(["success" => true, "file_names" => $fileNames, "ids" => $insertedIds, "pathUrls" => $pathUrls]);
} else {
  echo json_encode(["success" => false, "error" => $errors]);
}

db_disconnect($db);
?>
