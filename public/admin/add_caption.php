<?php
require_once('../../private/initialize.php');

// Check connection
if (!$db) {
  die(json_encode(["success" => false, "error" => "Connection failed."]));
}

// Retrieve the image data from the request body
$json = file_get_contents('php://input');
$data = json_decode($json, true);
// var_dump($data);

if (empty($data)) {
  die(json_encode(["success" => false, "error" => "Empty data."]));
}

// Iterate over the received data and update the database
foreach ($data as $item) {
  $result = update_images_by_id($db, $item);

  // Check if the update was successful
  if ($result !== true) {
    die(json_encode($result));
  }
}

// Database update successful
echo json_encode(["success" => true, "message" => "Database updated successfully."]);

// Close database connection
db_disconnect($db);
?>
