<?php
require_once('../../private/initialize.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $image_id = $_POST['image_id'];

  // Update the image's draft status to 0 (not a draft)
  $sql = "UPDATE images SET is_draft = 0 WHERE id = '" . db_escape($db, $image_id) . "'";
  $result = mysqli_query($db, $sql);

  if ($result) {
    // Success response
    echo json_encode(['success' => true]);
  } else {
    // Error response
    echo json_encode(['success' => false]);
  }
} else {
  // Invalid request method
  echo json_encode(['success' => false]);
}
?>
