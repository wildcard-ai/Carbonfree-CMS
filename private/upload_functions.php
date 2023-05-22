<?php

function create_directory_if_not_exists($path) {
  if (!is_dir($path)) {
    mkdir($path);
  }
}

function get_file_extension($file_name) {
  return strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
}

function is_valid_extension($file_ext, $extensions) {
  return in_array($file_ext, $extensions);
}

function is_valid_file_size($file_size, $limit) {
  return $file_size <= $limit;
}

function move_uploaded_file_to_destination($file_tmp, $destination) {
  move_uploaded_file($file_tmp, $destination);
}

function generate_unique_filename($file_ext) {
  return uniqid() . "." . $file_ext;
}

function generate_upload_path($filename, $path) {
  return $path . $filename;
}

function generate_upload_url($filename) {
  return "uploads/" . $filename;
}

?>