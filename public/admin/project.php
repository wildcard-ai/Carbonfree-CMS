<?php
require_once('../../private/initialize.php');
require_login();

$project_id = isset($_GET['id']) ? $_GET['id'] : null;

$project = find_project_by_id($project_id);
$image_set = find_images_by_project_id($project_id);

$page_title = h($project["project_name"]);
include(SHARED_PATH . '/admin_header.php');

// Delete Project
if(is_post_request()) {
  // Delete project from database
  $result = delete_project($project_id);
  $_SESSION['message'] = 'Project deleted successfully.';
  redirect_to(url_for('/admin/'));
} else {
  $project = find_project_by_id($project_id);
}

?>

<main>
  <header class="page-header">
    <h2 class="page-title new-project-name"><?php echo $page_title; ?></h2>
  </header>

  <section class="images-section">
    <div class="upload-edit-wrapper">
      <form class="upload-image-form" data-form-id="upload" method="post">
        <input type="hidden" name="project_id" value="<?php echo $project_id; ?>">
        <input type="file" name="file" data-input-id="upload" id="file-input" hidden>
        <label class="button button-secondary" for="file-input">Upload</label>
      </form>
      <button class="button button-primary">Edit</button>
    </div>
    <div class="file-list" data-list-id="upload">
      <?php while($image = mysqli_fetch_assoc($image_set)) { ?>
        <img class="uploaded-image" src="<?php echo url_for($image['path']); ?>">
      <?php } ?>
    </div>
  </section>

  <section class="details-section">
    <div>
      <h2 class="section-heading">Details</h2>
    </div>
    <div class="details-wrapper">
      <div class="form-wrapper">
        <div><span>Project Title</span></div>
        <div class="project-name-wrapper new-project-name" data-title-collapse="project-name">
          <?php echo $page_title; ?>
        </div>
        <div class="project-name-form-wrapper" data-form-collapse="project-name">
          <form data-form-id="project-name" method="post">
            <input type="hidden" name="project-id" value="<?php echo $project_id; ?>">
            <input class="project-name-input" type="text" data-input-id="project-name" name="project-name" value="<?php echo $project["project_name"]; ?>" required>
            <button class="button button-secondary" data-button-save="project-name">Save</button>
            <button class="button button-primary" data-button-cancel="project-name">Cancel</button>
          </form>
        </div>
      </div>
      <div class="edit-wrapper" data-edit-collapse="project-name">
        <button class="button button-primary" data-button-edit="project-name">Edit</button>
      </div>
    </div>
  </section>

  <section class="visibility-section">
    <div>
      <h2 class="section-heading">Visibility</h2>
    </div>

    <div class="details-wrapper">
      <div class="wrapper-centered">
        <span>Visibility</span>
      </div>
      <div class="wrapper-centered">
        <form>
          <input type="hidden" name="project_id" value="<?php echo $project_id; ?>">
          <input data-checkbox-type="visibility" name="visibility-checkbox" class="toggle-switch" type="checkbox" <?php if($project["visible"] == "1") { echo " checked"; } ?>>
        </form>
      </div>
    </div>
  </section>

  <section class="delete-section">
    <div>
      <h2 class="section-heading">Delete this Project</h2>
    </div>

    <div class="details-wrapper">
      <div class="wrapper-centered">
        <span>Delete this project</span>
      </div>
      <div class="wrapper-centered">
        <button class="button button-danger" data-modal-target="modal">Delete</button>
      </div>
    </div>
  </section>

</main>
<div class="modal" data-modal-id="modal-wrapper">
  <div class="modal-content">
    <span class="close" data-modal-action="close">&times;</span>

    <h2>Delete this Project?</h2>
    <p>Are you sure you want to delete this project and its images? This action cannot be undone.</p>
    <div class="modal-actions">
      <button class="button button-light" data-modal-button="close">Cancel</button>
      <form method="post" action="<?php echo url_for('admin/project.php?id=' . h(u($project['id']))); ?>">
        <button class="button button-danger" type="submit">Delete</button>
      </form>
    </div>

  </div>
</div>
<?php include(SHARED_PATH . '/admin_footer.php'); ?>