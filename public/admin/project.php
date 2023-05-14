<?php
require_once('../../private/initialize.php');
require_login();

$project_id = isset($_GET['id']) ? $_GET['id'] : null;

$project = find_project_by_id($project_id);
$image_set = find_images_by_project_id($project_id);

// Delete Project
if(is_post_request()) {
  // Delete project from database
  $result = delete_project($project_id);
  $_SESSION['message'] = 'Project deleted successfully.';
  redirect_to(url_for('/admin/'));
} else {
  $project = find_project_by_id($project_id);
}

include(SHARED_PATH . '/admin_header.php');

?>

<main>
  <header class="page-header">
    <h2 class="page-title" data-new-project-title="project-name"><?php echo $project["project_name"]; ?></h2>
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
        <div class="project-name-wrapper" data-title-collapse="project-name" data-new-project-title="project-name">
          <?php echo $project["project_name"]; ?>
        </div>
        <div class="project-name-form-wrapper" data-form-collapse="project-name">
          <form data-form-id="project-name">
            <input type="hidden" name="project-id" value="<?php echo $project_id; ?>">
            <input class="project-name-input" type="text" data-input-id="project-name" name="project-name" value="<?php echo $project["project_name"]; ?>" required>
            <div class="button-container">
              <button class="button button-secondary" data-button-save="project-name" type="submit">Save</button>
              <button class="button button-light" data-button-cancel="project-name" type="button">Cancel</button>
            </div>
          </form>
        </div>
      </div>
      <div class="edit-wrapper" data-edit-collapse="project-name">
        <button class="button button-primary" data-button-edit="project-name">Edit</button>
      </div>
    </div>

    <div class="details-wrapper">
      <div class="form-wrapper">
        <div><span>Description</span></div>
        <div class="project-name-wrapper" data-title-collapse="description" data-new-description="description">
          <?php echo $project["description"]; ?>
        </div>
        <div class="project-name-form-wrapper" data-form-collapse="description">
          <form data-form-id="description">
            <input type="hidden" name="project-id" value="<?php echo $project_id; ?>">
            <input class="project-name-input" type="text" data-input-id="description" name="description" value="<?php echo $project["description"]; ?>" required>
            <div class="button-container">
              <button class="button button-secondary" data-button-save="description" type="submit">Save</button>
              <button class="button button-light" data-button-cancel="description" type="button">Cancel</button>
            </div>
          </form>
        </div>
      </div>
      <div class="edit-wrapper" data-edit-collapse="description">
        <button class="button button-primary" data-button-edit="description">Edit</button>
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
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Delete this Project?</h5>
        <button class="close" data-dismiss="modal"><span>×</span></button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete this project and its images? This action cannot be undone.</p>
      </div>
      <div class="modal-footer">
        <button class="button button-light" data-dismiss="modal">Cancel</button>
        <form method="post" action="<?php echo url_for('admin/project.php?id=' . h(u($project['id']))); ?>">
          <button class="button button-danger" type="submit">Delete</button>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include(SHARED_PATH . '/admin_footer.php'); ?>