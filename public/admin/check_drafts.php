<?php
require_once('../../private/initialize.php');

// Check if there are any drafts
$query = "SELECT COUNT(*) FROM images WHERE is_draft = 1";
$result = mysqli_query($db, $query);
$draftCount = mysqli_fetch_row($result)[0];

// Prepare the response
$response = ['hasDrafts' => ($draftCount > 0)];

// Send the response as JSON
header('Content-Type: application/json');
echo json_encode($response);
db_disconnect($db);
?>
