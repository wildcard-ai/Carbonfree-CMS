<?php
  require_once('../../private/initialize.php');

  // Check connection
  if (!$db) {
    die(json_encode(["success" => false, "error" => "Connection failed."]));
  }

  // Get uploaded file
  $file = $_FILES['file'] ?? null;

  // Get file extension
  $extension = pathinfo($file['name'], PATHINFO_EXTENSION);

  // List of allowed extensions
  $allowed_extensions = ["jpg", "jpeg", "png", "gif"];

  // Check if file extension is allowed
  if (!in_array($extension, $allowed_extensions)) {
    die(json_encode(["success" => false, "error" => "Invalid file extension."]));
  }

  // Generate a unique filename to avoid overwriting existing files
  $filename = uniqid() . "." . $extension;

  // Set target directory and filename
  $target = "../uploads/" . $filename;

  // Remove ".." from target directory to prevent directory traversal
  $trimmed_target = str_replace('..', '', $target);

  // Move uploaded file to target directory
  if (!move_uploaded_file($file['tmp_name'], $target)) {
    die(json_encode(["success" => false, "error" => "File upload failed."]));
  }

  // Get input values
  $project = [];
  $project['id'] = $_POST['project_id'] ?? null;
  $project['cover_path'] = $trimmed_target;

  // Prepare SQL query
  $result = update_project_by_id($db, $project);

  // Execute SQL query to update record in database
  if ($result === true) {
    echo json_encode(["success" => true, "message" => "Thumbnail updated successfully.", "cover_path" => url_for($project['cover_path'])]);
  } else {
    echo json_encode($result);
  }

  // Close database connection
  db_disconnect($db);
?>
