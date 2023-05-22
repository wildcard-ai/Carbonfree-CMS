<?php
  require_once('../../private/initialize.php');

  // Check connection
  if (!$db) {
    die(json_encode(["success" => false, "error" => "Connection failed."]));
  }

  // Retrieve the imageList data from the request body
  $jsondata = file_get_contents('php://input');
  $image_ids = json_decode($jsondata, true);

  if (empty($image_ids)) {
    die(json_encode(["success" => false, "error" => "No images selected for deletion."]));
  }

  $result = delete_images_by_id($db, $image_ids);

  // Execute SQL query to update record in database
  if ($result === true) {
    echo json_encode(["success" => true, "message" => "Images deleted successfully."]);
  } else {
    echo json_encode($result);
  }

  // Close database connection
  db_disconnect($db);
?>