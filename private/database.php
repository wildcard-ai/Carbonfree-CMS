<?php

require_once('db_credentials.php');

// Connect to the database
function db_connect() {
  $connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
  return $connection;
}

// Disconnect from the database
function db_disconnect($connection) {
  if(isset($connection)) {
    mysqli_close($connection);
  }
  
}

function db_escape($connection, $string) {
  return mysqli_real_escape_string($connection, $string);
}

// Check connection
function confirm_result_set($result_set) {
  if (!$result_set) {
    exit("Database query failed.");
  }
}