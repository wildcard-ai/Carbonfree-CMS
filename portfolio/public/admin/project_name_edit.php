<?php
  require_once('../../private/initialize.php');

  // Check connection
  if (!$db) {
    die(json_encode(["success" => false, "error" => "Connection failed."]));
  }

  // Get input values
  $project_id = $_POST['project_id'] ?? null;
  $project_name = $_POST['project_name'] ?? null;

  // Prepare SQL query
  $sql = "UPDATE projects SET project_name='" . db_escape($db, $project_name) . "'";
  $sql .= " WHERE id=" . db_escape($db, $project_id);

  // Execute SQL query to update record in database
  if (mysqli_query($db, $sql)) {
    echo json_encode(["success" => true, "message" => "Record updated successfully.", "newprojectname" => db_escape($db, $project_name)]);
  } else {
    echo json_encode(["success" => false, "error" => "Error updating record."]);
  }

  // Close database connection
  db_disconnect($db);
?>
