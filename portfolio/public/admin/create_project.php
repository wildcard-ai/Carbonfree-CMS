<?php
  require_once('../../private/initialize.php');

  if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
  }

  // Check if form data has been received
  if (isset($_POST["project_name"])) {
    // Get input from form data
    $project_name = $_POST["project_name"];
    $visible = $_POST["visible"];

    // Insert input into database
    $sql = "INSERT INTO projects (project_name, visible) VALUES ('$project_name', '$visible')";
    if (mysqli_query($db, $sql)) {
      // Get the ID of the newly created project
      $id = mysqli_insert_id($db);
      echo url_for("admin/project.php?id=") . $id;
    } else {
      echo "Error: " . $sql . "<br>" . mysqli_error($db);
    }
  }

  // Close database connection
  db_disconnect($db);
?>
