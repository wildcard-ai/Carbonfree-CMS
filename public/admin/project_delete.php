<?php
  require_once('../../private/initialize.php');

  // Check if connection was successful
  if (!$db) {
    die(json_encode(["success" => false, "error" => "Connection failed."]));
  }

  // Retrieve ID of the page to be deleted
  $project_id = $_POST['project_id'];

  // Construct DELETE query
  $sql = "DELETE FROM projects WHERE id=" . db_escape($db, $project_id);

  // Execute SQL query to update record in database
  if (mysqli_query($db, $sql)) {
    echo json_encode(["success" => true, "message" => "Page with ID $project_id was deleted successfully.", "redirect" => url_for('/admin/')]);
  } else {
    echo json_encode(["success" => false, "error" => "Error deleting record." . mysqli_error($db)]);
  }

  // Close database connection
  db_disconnect($db);
?>
