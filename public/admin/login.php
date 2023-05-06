<?php
require_once('../../private/initialize.php');

handle_logout();
already_logged_in();

$errors = [];
$username = '';
$password = '';

if(is_post_request()) {

  $username = $_POST['username'] ?? '';
  $password = $_POST['password'] ?? '';

  // Validations
  if(is_blank($username)) {
    $errors[] = "Username cannot be blank.";
  }
  if(is_blank($password)) {
    $errors[] = "Password cannot be blank.";
  }

  // if there were no errors, try to login
  if(empty($errors)) {
    // Using one variable ensures that msg is the same
    $login_failure_msg = "Log in was unsuccessful.";
    
    $admin = find_admin_by_username($username);
    if($admin) {
      if(password_verify($password, $admin['hashed_password'])) {
        // password matches
        log_in_admin($admin);
        redirect_to(url_for('/admin/'));
      } else {
        // username found, but password does not match
        $errors[] = $login_failure_msg;
      }
    } else {
      // no username was found
      $errors[] = $login_failure_msg;
    }

  }

}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - DC3D</title>
    <link rel="shortcut icon" href="<?php echo url_for('images/favicon.ico'); ?>">
    <link rel="stylesheet" href="<?php echo url_for('admin/css/login.css'); ?>">
  </head>
  <body>
    <div class="login-box">
      <h1>Login</h1>
      <?php echo display_errors($errors); ?>
      <form action="<?php echo url_for('/admin/login.php'); ?>" method="post">
        <label for="username">Username</label>
        <input class="input" type="text" id="username" name="username" value="<?php echo h($username); ?>" required>
        <label for="password">Password</label>
        <input class="input" type="password" id="password" name="password" value="" required>
        <input class="button button-primary login" type="submit" value="Login">
      </form>
    </div>
  </body>
</html>