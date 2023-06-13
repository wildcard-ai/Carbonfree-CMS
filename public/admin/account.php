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
  $admin = find_admin_by_id($id);
}

?>

<?php $page_title = "Account"; ?>
<?php include(SHARED_PATH . '/admin_header.php'); ?>

<main>
  <section>
  </section>

  <section class="details">
    <header>
      <h2 class="section-title">Sign-in Settings</h2>
    </header>
    <div class="card-group">
      <label class="card <?php if(empty($admin["email"])) { echo "empty";} ?>" data-collapse-id="email">
        <div class="arrow-button-container">
          <button class="button button-light arrow" data-edit-button="email" data-collapse-target="email">Change</button>
        </div>
        <div class="col-fg-1">
          <header class="card-header">Password</header>
          <div class="collapse show card-text" data-collapse-id="email" data-update="email">
            <?php echo "************"; ?>
          </div>
          <form class="collapse" data-form-id="email" data-collapse-id="email" data-collapse-target="email">
            <div class="input-group">
              <input type="hidden" name="project-id" value="<?php echo $id; ?>">
              <input class="form-control" type="text" data-input-id="email" name="email" value="<?php echo $admin["email"]; ?>" autocomplete="off" required>
            </div>
            <div class="form-actions">
              <button class="button button-primary" type="submit">Save changes</button>
              <button class="button button-light" data-cancel-button="email" data-collapse-target="email" type="button">Cancel</button>
            </div>
          </form>
        </div>
      </label>

      <label class="card <?php if(empty($admin["email"])) { echo "empty";} ?>" data-collapse-id="email">
        <div class="arrow-button-container">
          <button class="button button-light arrow" data-edit-button="email" data-collapse-target="email">Change</button>
        </div>
        <div class="col-fg-1">
          <header class="card-header">Sign-in Email</header>
          <div class="collapse show card-text" data-collapse-id="email" data-update="email">
            <?php echo $admin["email"]; ?>
          </div>
          <form class="collapse" data-form-id="email" data-collapse-id="email" data-collapse-target="email">
            <div class="input-group">
              <input type="hidden" name="project-id" value="<?php echo $id; ?>">
              <input class="form-control" type="text" data-input-id="email" name="email" value="<?php echo $admin["email"]; ?>" autocomplete="off" required>
            </div>
            <div class="form-actions">
              <button class="button button-primary" type="submit">Save changes</button>
              <button class="button button-light" data-cancel-button="email" data-collapse-target="email" type="button">Cancel</button>
            </div>
          </form>
        </div>
      </label>
    </div>
  </section>
</main>
<?php include(SHARED_PATH . '/admin_footer.php'); ?>