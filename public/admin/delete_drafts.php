<?php
require_once('../../private/initialize.php');

// Establish your database connection here if needed

// Retrieve the value of isDraft from the request payload
$isDraft = isset($_POST['isDraft']) ? $_POST['isDraft'] : null;


// Perform the deletion query
// Replace 'your_table_name' with the actual name of your table
// Replace 'your_is_draft_column' with the actual name of your is_draft column
$query = "DELETE FROM images WHERE is_draft = 1";
$result = mysqli_query($db, $query); // Assuming you are using MySQLi

if ($result) {
  $response = ['message' => 'Drafts deleted successfully'];
} else {
  $response = ['message' => 'Error deleting drafts'];
}

// Send the response as JSON
header('Content-Type: application/json');
echo json_encode($response);
db_disconnect($db);
?>
