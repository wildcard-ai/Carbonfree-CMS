<?php
require_once('../../private/initialize.php');

if (hasDraftImages()) {
  $result = deleteDraftImages();

  if ($result) {
    $response = ['message' => 'Drafts deleted successfully'];
  } else {
    $response = ['message' => 'Error deleting drafts'];
  }
} else {
  $response = ['message' => 'No drafts'];
}

header('Content-Type: application/json');
echo json_encode($response);

db_disconnect($db);
?>
