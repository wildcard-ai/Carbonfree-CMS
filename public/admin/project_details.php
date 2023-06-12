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

  if (isset($_POST['url'])) {
    $project['url'] = $_POST['url'] ?? null;
  }

  if (isset($_POST['client'])) {
    $project['client'] = $_POST['client'] ?? null;
  }

  if (isset($_POST['project_type'])) {
    $project['project_type'] = $_POST['project_type'] ?? null;
  }

  // Prepare SQL query
  $result = update_project_by_id($db, $project);

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

    if (isset($project['url'])) {
      $successMessages['message'] = "Project url updated successfully.";
      if (empty($project['url'])) {
        $successMessages['data'] = "If your project has external assets you can link to them here. The site will open up in a new window.";
      } else {
        $successMessages['data'] = $project['url'];
      }
    }

    if (isset($project['client'])) {
      $successMessages['message'] = "Project client updated successfully.";
      if (empty($project['client'])) {
        $successMessages['data'] = "Who you did the work for.";
      } else {
        $successMessages['data'] = $project['client'];
      }
    }

    if (isset($project['project_type'])) {
      $successMessages['message'] = "Project type updated successfully.";
      if (empty($project['project_type'])) {
        $successMessages['data'] = "Separate types with commas (e.g Illustration, Website).";
      } else {
        $successMessages['data'] = $project['project_type'];
      }
    }
    
    echo json_encode(["success" => true, "message" => $successMessages['message'], "newText" => $successMessages['data']]);
  } else {
    echo json_encode($result);
  }

  // Close database connection
  db_disconnect($db);
?>