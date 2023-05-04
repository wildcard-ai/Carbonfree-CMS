<?php

require_once('../../private/initialize.php');

require_login();

// Admin id
$id = '1';

if(is_post_request()) {
  $admin = [];
  $admin['id'] = $id;
  $admin['username'] = $_POST['username'] ?? '';
  $admin['email'] = $_POST['email'] ?? '';
  $admin['password'] = $_POST['password'] ?? '';
  $admin['confirm_password'] = $_POST['confirm_password'] ?? '';

  $result = update_admin($admin);
  if($result === true) {
    $_SESSION['message'] = 'Account updated.';
    redirect_to(url_for('/admin/administrator'));
  } else {
    $errors = $result;
  }
} else {
  $admin = find_admin_by_id($id);
}

?>

<?php $page_title = "Account"; ?>
<?php include(SHARED_PATH . '/admin_header.php'); ?>

<main>
  <header class="page-header">
    <h2 class="page-title">Account</h2>
  </header>

  <section class="pw-section">
    <?php echo display_errors($errors); ?>
    <?php echo display_session_message(); ?>

    <form action="<?php echo url_for('admin/administrator/'); ?>" method="post">
      <p>
        Passwords should be at least 8 characters and include at least one uppercase letter, lowercase letter, number, and symbol.
      </p>
      <div class="pw-wrapper">
        <label>Username:</label>
        <input type="text" name="username" value="">
      </div>

      <div class="pw-wrapper">
        <label>Email:</label>
        <input type="text" name="email" value="">
      </div>

      <div class="pw-wrapper">
        <label>Password:</label>
        <input type="password" name="password" value="">
      </div>

      <div class="pw-wrapper">
        <label>Confirm Password:</label>
        <input type="password" name="confirm_password" value="">
      </div>

      <div>
        <button class="button button-primary" type="submit">Save</button>
      </div>
    </form>
  </section>
</main>
<?php include(SHARED_PATH . '/admin_footer.php'); ?>