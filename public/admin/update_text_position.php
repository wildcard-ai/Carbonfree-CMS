<?php
require_once('../../private/initialize.php');

// Check connection
if (!$db) {
  die(json_encode(["success" => false, "error" => "Connection failed."]));
}

// Retrieve the image data from the request body
$json = file_get_contents('php://input');
$data = json_decode($json, true);

$text_position = $data['textPosition'];
// var_dump($data);

if (empty($data)) {
  die(json_encode(["success" => false, "error" => "Empty data."]));
}

$sql = "UPDATE display_options SET text_position = '" . $text_position . "'";
$result = mysqli_query($db, $sql);

// Check if the update was successful
if ($result === false) {
  die(json_encode($result));
}

// Database update successful
echo json_encode(["success" => true, "message" => "Text position updated successfully."]);

// Close database connection
db_disconnect($db);
?>
