<?php

function url_for($script_path) {
  // add the leading '/' if not present
  if($script_path[0] != '/') {
    $script_path = "/" . $script_path;
  }
  return WWW_ROOT . $script_path;
}

function u($string="") {
  return urlencode($string);
}

function h($string="") {
  return htmlspecialchars($string);
}

function redirect_to($location) {
  header("Location: " . $location);
  exit;
}

function is_post_request() {
  return $_SERVER['REQUEST_METHOD'] == 'POST';
}

function display_errors($errors=array()) {
  $output = '';
  if(!empty($errors)) {
    $output .= "<div class=\"error-message\">";
    foreach($errors as $error) {
      $output .= h($error);
    }
    $output .= "</div>";
  }
  return $output;
}

function get_and_clear_session_message() {
  if(isset($_SESSION['message']) && $_SESSION['message'] != '') {
    $msg = $_SESSION['message'];
    unset($_SESSION['message']);
    return $msg;
  }
}

function display_session_message() {
  $msg = get_and_clear_session_message();
  if(!is_blank($msg)) {
    return '<div class="alert alert-primary">' . h($msg) . '</div>';
  }
}

function is_page($page) {
  return $page == basename($_SERVER['PHP_SELF'], '.php');
}

function load_script($page, $js_file) {
  if (is_page($page)) {
    return '<script src="' . url_for('admin/js/' . $js_file . '.js') . '" defer></script>';
  }
}


?>