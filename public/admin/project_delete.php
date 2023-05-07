<?php
  require_once('../../private/initialize.php');

  // Check if connection was successful
  if (!$db) {
    die(json_encode(["success" => false, "error" => "Connection failed."]));
  }

  // Retrieve ID of the project to be deleted
  $project_id = $_POST['project_id'];

  // Delete project from database
  $delete_result = delete_project($db, $project_id);

  if ($delete_result === true) {
    $_SESSION['message'] = "The project was deleted successfully.";
    echo json_encode(["success" => true, "message" => "The project was deleted successfully."]);
  } else {
    echo json_encode($delete_result);
  }

  // Close database connection
  db_disconnect($db);
?>