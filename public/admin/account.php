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

<?php $page_title = "Account"; ?>
<?php include(SHARED_PATH . '/admin_header.php'); ?>

<main>
  <section>
  </section>

  <section class="details">
    <header>
      <h2 class="section-title">Login Settings</h2>
    </header>
    <div class="card-group">
      <label class="card <?php if(empty($admin["username"])) { echo "empty";} ?>" data-collapse="toggle">
        <div class="arrow-button-container">
          <button class="button button-light arrow" data-button="edit">Change</button>
        </div>
        <div class="col-fg-1">
          <div class="collapse show" data-collapse="toggle">
            <header class="card-header">Username</header>
            <div class="card-text">
              <?php echo $admin["username"]; ?>
            </div>
          </div>
          <form class="collapse" data-collapse="toggle" data-form="username">
            <div class="input-group">
              <label class="card-header">
                Username
                <input class="form-control" type="text" data-input="focus" name="username" value="<?php echo $admin["username"]; ?>" autocomplete="off" required>
              </label>
              <label class="card-header">
                Current Password
                <input class="form-control" type="password" name="password" value="" autocomplete="off" required>
              </label>
            </div>
            <div class="form-actions">
              <button class="button button-primary" type="submit">Save changes</button>
              <button class="button button-light" data-button="cancel" type="button">Cancel</button>
            </div>
          </form>
        </div>
      </label>

      <label class="card <?php if(empty($admin["hashed_password"])) { echo "empty";} ?>" data-collapse="toggle">
        <div class="arrow-button-container">
          <button class="button button-light arrow" data-button="edit">Change</button>
        </div>
        <div class="col-fg-1">
          <div class="collapse show" data-collapse="toggle">
            <header class="card-header">Password</header>
            <div class="card-text">
              <?php echo "************"; ?>
            </div>
          </div>
          <form class="collapse" data-collapse="toggle" data-form="password">
            <div class="input-group">
              <label class="card-header">
                Current Password
                <input class="form-control" type="password" data-input="focus" name="password" value="" autocomplete="off" required>
              </label>
              <label class="card-header">
                New Password
                <input class="form-control" type="password" name="password" value="" autocomplete="off" required>
              </label>
              <label class="card-header">
                Confirm Password
                <input class="form-control" type="password" name="password" value="" autocomplete="off" required>
              </label>
            </div>
            <div class="form-actions">
              <button class="button button-primary" type="submit">Save changes</button>
              <button class="button button-light" data-button="cancel" type="button">Cancel</button>
            </div>
          </form>
        </div>
      </label>
    </div>
  </section>

  <section class="details">
    <header>
      <h2 class="section-title">Email Settings</h2>
    </header>
    <div class="card-group">
      <label class="card <?php if(empty($admin["email"])) { echo "empty";} ?>" data-collapse="toggle">
        <div class="arrow-button-container">
          <button class="button button-light arrow" data-button="edit">Change</button>
        </div>
        <div class="col-fg-1">
          <div class="collapse show" data-collapse="toggle">
            <header class="card-header">Email</header>
            <div class="card-text">
              <?php echo $admin["email"]; ?>
            </div>
          </div>
          <form class="collapse" data-collapse="toggle" data-form="email">
            <div class="input-group">
              <label class="card-header">
                Email
                <input class="form-control" type="text" data-input="focus" name="email" value="<?php echo $admin["email"]; ?>" autocomplete="off" required>
              </label>
              <label class="card-header">
                Current Password
                <input class="form-control" type="password" name="password" value="" autocomplete="off" required>
              </label>
            </div>
            <div class="form-actions">
              <button class="button button-primary" type="submit">Save changes</button>
              <button class="button button-light" data-button="cancel" type="button">Cancel</button>
            </div>
          </form>
        </div>
      </label>
    </div>
  </section>
</main>
<?php include(SHARED_PATH . '/admin_footer.php'); ?>