<?php

  require_once('../../private/initialize.php');

  // Check connection
  if (!$db) {
    die(json_encode(["success" => false, "error" => "Connection failed."]));
  }

  // Get input values
  $cover_file = $_FILES['file'] ?? null;
  $project_id = $_POST['project_id'] ?? null;

  // Extension
  $extension = pathinfo($cover_file["name"], PATHINFO_EXTENSION);

  // List of allowed extensions
  $allowed_extensions = ["jpg", "jpeg", "png", "gif"];

  // Check if file is allowed
  if (!in_array($extension, $allowed_extensions)) {
    die(json_encode(["success" => false, "error" => "Invalid file extension."]));
  }

  // Generate a unique filename to avoid overwriting existing files
  $filename = uniqid() . "." . $extension;
  $target = "../uploads/" . $filename;
  $cover_path = "uploads/" . $filename;

  // Move uploaded file to target directory
  if (!move_uploaded_file($cover_file["tmp_name"], $target)) {
    die(json_encode(["success" => false, "error" => "File upload failed."]));
  }

  // Prepare SQL query
  $sql = "UPDATE projects SET cover_path='" . mysqli_real_escape_string($db, $cover_path) . "'";
  $sql .= " WHERE id=" . mysqli_real_escape_string($db, $project_id);

  // Execute SQL query to update record in database
  if (mysqli_query($db, $sql)) {
    echo json_encode(["success" => true, "message" => "Record updated successfully.", "cover_path" => url_for($cover_path)]);
  } else {
    echo json_encode(["success" => false, "error" => "Error updating record."]);
  }

  // Close database connection
  mysqli_close($db);
?>
