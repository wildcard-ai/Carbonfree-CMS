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

<?php $page_title = "Personalize"; ?>
<?php include(SHARED_PATH . '/admin_header.php'); ?>

<main>
  <section>
  </section>
  <section>
    <header class="section-header">
      <h2 class="section-title">Appearance</h2>
      <p>Use the toggles below to adjust the color and font for the whole of your portfolio. Play around!</p>
    </header>
    <div class="card-group">

      <label class="card <?php if(empty($project["description"])) { echo "empty";} ?>" data-collapse="toggle">
        <div class="arrow-button-container">
          <button class="button <?php echo empty($project["description"]) ? "button-secondary" : "button-primary"; ?> arrow" data-button="edit"><?php echo empty($project["description"]) ? "Add" : "Edit"; ?></button>
        </div>
        <div class="col-fg-1">
          <header class="card-header">Footer Blurb</header>
          <div class="collapse show card-text small" data-collapse="toggle" data-update="description">
            <?php echo empty($project["description"]) ? "Anything your viewers should know about the project." : nl2br($project["description"]); ?>
          </div>
          <form class="collapse" data-form-id="description" data-collapse="toggle">
            <div class="input-group">
              <input type="hidden" name="project-id" value="<?php echo $project_id; ?>">
              <textarea class="form-control" data-input-id="description" data-input="focus" name="description" rows="5"><?php echo $project["description"]; ?></textarea>
            </div>
            <div class="form-actions">
              <button class="button <?php echo empty($project["description"]) ? "button-secondary" : "button-primary"; ?>" data-save-button="description" type="submit"><?php echo empty($project["description"]) ? "Add" : "Save changes"; ?></button>
              <button class="button button-light" data-button="cancel" type="button">Cancel</button>
            </div>
          </form>
        </div>
      </label>

    </div>
  </section>

  <section>
    <header class="section-header">
      <h2 class="section-title">Header</h2>
    </header>
    <div class="card-group">
      <label class="card <?php if(empty($project["url"])) { echo "empty";} ?>" data-collapse="toggle">
        <div class="arrow-button-container">
          <button class="button <?php echo empty($project["url"]) ? "button-secondary" : "button-primary"; ?> arrow" data-button="edit"><?php echo empty($project["url"]) ? "Add" : "Edit"; ?></button>
        </div>
        <div class="col-fg-1">
          <header class="card-header">Title</header>
          <div class="collapse show card-text" data-collapse="toggle" data-update="url">
            <?php echo empty($project["url"]) ? "A bit of text for your portfolio header." : $project["url"]; ?>
          </div>
          <form class="collapse" data-form-id="url" data-collapse="toggle">
            <div class="input-group">
              <input type="hidden" name="project-id" value="<?php echo $project_id; ?>">
              <input class="form-control" type="text" data-input-id="url" data-input="focus" name="url" value="<?php echo $project["url"]; ?>" autocomplete="off">
            </div>
            <div class="form-actions">
              <button class="button <?php echo empty($project["url"]) ? "button-secondary" : "button-primary"; ?>" type="submit"><?php echo empty($project["url"]) ? "Add" : "Save changes"; ?></button>
              <button class="button button-light" data-button="cancel" type="button">Cancel</button>
            </div>
          </form>
        </div>
      </label>

      <label class="card <?php if(empty($project["url"])) { echo "empty";} ?>" data-collapse="toggle">
        <div class="arrow-button-container">
          <button class="button <?php echo empty($project["url"]) ? "button-secondary" : "button-primary"; ?> arrow" data-button="edit"><?php echo empty($project["url"]) ? "Add" : "Edit"; ?></button>
        </div>
        <div class="col-fg-1">
          <header class="card-header">Logo</header>
          <div class="collapse show card-text" data-collapse="toggle" data-update="url">
            <?php echo empty($project["url"]) ? "Use an image in place of your portfolio title." : $project["url"]; ?>
          </div>
          <form class="collapse" data-form-id="url" data-collapse="toggle">
            <div class="input-group">
              <input type="hidden" name="project-id" value="<?php echo $project_id; ?>">
              <input class="form-control" type="text" data-input-id="url" data-input="focus" name="url" value="<?php echo $project["url"]; ?>" autocomplete="off">
            </div>
            <div class="form-actions">
              <button class="button <?php echo empty($project["url"]) ? "button-secondary" : "button-primary"; ?>" type="submit"><?php echo empty($project["url"]) ? "Add" : "Save changes"; ?></button>
              <button class="button button-light" data-button="cancel" type="button">Cancel</button>
            </div>
          </form>
        </div>
      </label>
    </div>
  </section>

  <section>
    <header class="section-header">
      <h2 class="section-title">Footer</h2>
    </header>
    <div class="card-group">

      <label class="card <?php if(empty($project["description"])) { echo "empty";} ?>" data-collapse="toggle">
        <div class="arrow-button-container">
          <button class="button <?php echo empty($project["description"]) ? "button-secondary" : "button-primary"; ?> arrow" data-button="edit"><?php echo empty($project["description"]) ? "Add" : "Edit"; ?></button>
        </div>
        <div class="col-fg-1">
          <header class="card-header">Footer Blurb</header>
          <div class="collapse show card-text small" data-collapse="toggle" data-update="description">
            <?php echo empty($project["description"]) ? "Anything your viewers should know about the project." : nl2br($project["description"]); ?>
          </div>
          <form class="collapse" data-form-id="description" data-collapse="toggle">
            <div class="input-group">
              <input type="hidden" name="project-id" value="<?php echo $project_id; ?>">
              <textarea class="form-control" data-input-id="description" data-input="focus" name="description" rows="5"><?php echo $project["description"]; ?></textarea>
            </div>
            <div class="form-actions">
              <button class="button <?php echo empty($project["description"]) ? "button-secondary" : "button-primary"; ?>" data-save-button="description" type="submit"><?php echo empty($project["description"]) ? "Add" : "Save changes"; ?></button>
              <button class="button button-light" data-button="cancel" type="button">Cancel</button>
            </div>
          </form>
        </div>
      </label>

    </div>
  </section>
</main>
<?php include(SHARED_PATH . '/admin_footer.php'); ?>