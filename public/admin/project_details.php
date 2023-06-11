<?php
  require_once('../../private/initialize.php');

  // Check connection
  if (!$db) {
    die(json_encode(["success" => false, "error" => "Connection failed."]));
  }

  // Get input values
  $project = [];
  $project['id'] = $_POST['project_id'] ?? null;

  if (isset($_POST['project_name'])) {
    $project['project_name'] = $_POST['project_name'] ?? null;
  }
  
  if (isset($_POST['description'])) {
    $project['description'] = $_POST['description'] ?? null;
  }

  // Prepare SQL query
  $result = update_project($db, $project);

  // Execute SQL query to update record in database
  if ($result === true) {
    $successMessages = [];
    
    if (isset($project['project_name'])) {
      $successMessages['message'] = "Project name updated successfully.";
      $successMessages['data'] = $project['project_name'];
    }
    
    if (isset($project['description'])) {
      $successMessages['message'] = "Project description updated successfully.";
      if (empty($project['description'])) {
        $successMessages['data'] = "Anything your viewers should know about the project.";
      } else {
        $successMessages['data'] = $project['description'];
      }
    }
    
    echo json_encode(["success" => true, "message" => $successMessages['message'], "newText" => $successMessages['data']]);
  } else {
    echo json_encode($result);
  }

  // Close database connection
  db_disconnect($db);
?>