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
  <section>
    <header class="section-header">
      <h2 class="section-title">The Basics</h2>
      <p>Everything on this page will show up on your Profile. All the fields are optional but things look a bit nicer if they're all filled out.</p>
    </header>
    <div class="card-group">

      <label class="card <?php if(empty($project["description"])) { echo "empty";} ?>" data-collapse="toggle">
        <div class="arrow-button-container">
          <button class="button <?php echo empty($project["description"]) ? "button-secondary" : "button-primary"; ?> arrow" data-button="edit"><?php echo empty($project["description"]) ? "Add" : "Edit"; ?></button>
        </div>
        <div class="col-fg-1">
          <header class="card-header">Real Name</header>
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

      <label class="card <?php if(empty($project["url"])) { echo "empty";} ?>" data-collapse="toggle">
        <div class="arrow-button-container">
          <button class="button <?php echo empty($project["url"]) ? "button-secondary" : "button-primary"; ?> arrow" data-button="edit"><?php echo empty($project["url"]) ? "Add" : "Edit"; ?></button>
        </div>
        <div class="col-fg-1">
          <header class="card-header">Profile Image</header>
          <div class="collapse show card-text" data-collapse="toggle" data-update="url">
            <img class="profile-image" src="<?php echo url_for('admin/images/profile_image.png'); ?>">
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
          <header class="card-header">About You</header>
          <div class="collapse show card-text" data-collapse="toggle" data-update="url">
            <?php echo empty($project["url"]) ? "Your passions, your hobbies, the written treasure trove of you. Right on? Write on!" : $project["url"]; ?>
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
          <header class="card-header">Specialties</header>
          <div class="collapse show card-text" data-collapse="toggle" data-update="url">
            <?php echo empty($project["url"]) ? "Add your primary areas of expertise (e.g., Illustration, Interactive Design, Photography, Industrial Design)." : $project["url"]; ?>
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
      <h2 class="section-title">Contact Information</h2>
    </header>
    <div class="card-group">
      <label class="card <?php if(empty($project["url"])) { echo "empty";} ?>" data-collapse="toggle">
        <div class="arrow-button-container">
          <button class="button <?php echo empty($project["url"]) ? "button-secondary" : "button-primary"; ?> arrow" data-button="edit"><?php echo empty($project["url"]) ? "Add" : "Edit"; ?></button>
        </div>
        <div class="col-fg-1">
          <header class="card-header">Email</header>
          <div class="collapse show card-text" data-collapse="toggle" data-update="url">
            <?php echo empty($project["url"]) ? "Chances are that the good people of the internet are going to want to get in touch; this is the email that they'll see." : $project["url"]; ?>
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
          <header class="card-header">Location</header>
          <div class="collapse show card-text" data-collapse="toggle" data-update="url">
            <?php echo empty($project["url"]) ? "Where in the world are you? Fill out only what you want to." : $project["url"]; ?>
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
          <header class="card-header">Phone</header>
          <div class="collapse show card-text" data-collapse="toggle" data-update="url">
            <?php echo empty($project["url"]) ? "If you want folks to ring you up about your work this is the spot for your digits." : $project["url"]; ?>
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
      <h2 class="section-title">Availability</h2>
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