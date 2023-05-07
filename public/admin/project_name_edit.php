<?php
  require_once('../../private/initialize.php');

  // Check connection
  if (!$db) {
    die(json_encode(["success" => false, "error" => "Connection failed."]));
  }

  // Get input values
  $project = [];
  $project['id'] = $_POST['project_id'] ?? '';
  $project['project_name'] = $_POST['project_name'] ?? '';

  // Prepare SQL query
  $result = update_project($db, $project);

  // Execute SQL query to update record in database
  if ($result === true) {
    echo json_encode(["success" => true, "message" => "Project name updated successfully.", "newProjectName" => $project['project_name']]);
  } else {
    echo json_encode($result);
  }

  // Close database connection
  db_disconnect($db);
?>