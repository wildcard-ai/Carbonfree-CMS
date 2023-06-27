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
    redirect_to(url_for('/admin/password.php'));
  } else {
    $errors = $result;
  }
} else {
  $admin = find_admin();
}

?>

<?php $page_title = "Profile"; ?>
<?php include(SHARED_PATH . '/admin_header.php'); ?>

<main>
  <section>

  </section>
</main>
<?php include(SHARED_PATH . '/admin_footer.php'); ?>