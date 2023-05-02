<?php
  require_once('../../private/initialize.php');

  // Check if connection was successful
  if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
  }

  // Retrieve ID of the page to be deleted
  $project_id = $_GET['project_id'];

  // Construct DELETE query
  $sql = "DELETE FROM projects WHERE id=" . db_escape($db, $project_id);

  // Execute query
  if (mysqli_query($db, $sql)) {
    echo "Page with ID $project_id was deleted successfully.";
    redirect_to(url_for('/admin/')); // Redirect to Projects page
  } else {
    echo "Error deleting record: " . mysqli_error($db);
  }

  // Close database connection
  db_disconnect($db);
?>
