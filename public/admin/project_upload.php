<?php
  require_once('../../private/initialize.php');

  $uploadPath = "../uploads/";
  $allowedExtensions = ["jpg", "jpeg", "png", "gif"];

  if (!isset($_FILES["file"])) {
    die(json_encode(["success" => false, "error" => "No file was uploaded."]));
  }

  $project_id = (int) $_POST["project_id"];
  $file = $_FILES["file"];
  $extension = pathinfo($file["name"], PATHINFO_EXTENSION);

  if (!in_array($extension, $allowedExtensions)) {
    die(json_encode(["success" => false, "error" => "Invalid file extension. Only JPG, JPEG, PNG and GIF files are allowed."]));
  }

  // create uploads folder if it doesn't exist
  if (!is_dir($uploadPath)) {
    mkdir($uploadPath);
  }

  $filename = uniqid() . "." . $extension;
  $target = $uploadPath . $filename;
  $path = "uploads/" . $filename;

  if (!move_uploaded_file($file["tmp_name"], $target)) {
    die(json_encode(["success" => false, "error" => "File upload failed."]));
  }

  $sql = "INSERT into images ";
  $sql .= "(project_id, path) ";
  $sql .= "VALUES (";
  $sql .= "'" . db_escape($db, $project_id) . "',";
  $sql .= "'" . db_escape($db, $path) . "'";
  $sql .= ")";

  if (mysqli_query($db, $sql)) {
    echo json_encode(["success" => true, "path" => url_for($path)]);
  } else {
    echo json_encode(["success" => false, "error" => "Error inserting record: " . mysqli_error($db)]);
  }

  db_disconnect($db);
?>