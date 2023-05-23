<?php
require_once('../../private/initialize.php');
require_login();

if(isset($_GET['id'])) {
  $project_id = $_GET['id'];
  $project = find_project_by_id($project_id);
  $image_set = find_images_by_project_id($project_id);
  if(!$project) {
    header("HTTP/1.0 404 Not Found");
    include(SHARED_PATH . '/404.html');
    die();
  }
} else {
  redirect_to(url_for('/admin/'));
}

// Delete Project
if(is_post_request()) {
  // Delete project from database
  $result = delete_project($project_id);
  $_SESSION['message'] = 'Project deleted successfully.';
  redirect_to(url_for('/admin/'));
}

include(SHARED_PATH . '/admin_header.php');

?>

<main>
  <header class="page-header">
    <h2 class="page-title" data-update="project-name"><?php echo $project["project_name"]; ?></h2>
  </header>

  <section>
    <div class="upload-toolbar">
      <form class="collapse show" method="post" enctype="multipart/form-data" data-form-id="upload" data-project-id="<?php echo $project_id; ?>">
        <input type="file" name="files[]" data-input-id="upload" id="file-input" hidden multiple>
        <label tabindex="0" class="button button-secondary" for="file-input">Upload</label>
      </form>
      <button class="button button-primary collapse show" data-edit-button="image" data-edit-button-toggled="false">Edit</button>
      <span class="selected-count collapse" data-selected-count="image"></span>
      <button class="button button-danger collapse" data-delete-button="image">Delete</button>
      <button class="button button-primary collapse" data-select-all-button="image">Select All</button>
    </div>
    <div class="image-list" data-list-id="upload">
      <?php while($image = mysqli_fetch_assoc($image_set)) { ?>
        <label class="image-container">
          <img class="uploaded-image" src="<?php echo url_for($image['path']); ?>">
          <input class="image-checkbox collapse" type="checkbox" data-checkbox="image" data-image-id="<?php echo $image['id']; ?>" disabled>
        </label>
      <?php } ?>
    </div>
  </section>

  <section>
    <header>
      <h2 class="section-title">Details</h2>
    </header>
    <div class="card">
      <div class="col-fg-1">
        <header class="card-header">Project Title</header>
        <div class="collapse show" data-collapse-id="project-name" data-update="project-name">
          <?php echo $project["project_name"]; ?>
        </div>
        <form class="collapse" data-form-id="project-name" data-collapse-id="project-name" data-collapse-target="project-name">
          <div class="input-group">
            <input type="hidden" name="project-id" value="<?php echo $project_id; ?>">
            <input type="text" data-input-id="project-name" name="project-name" value="<?php echo $project["project_name"]; ?>" required>
          </div>
          <div class="form-actions">
            <button class="button button-primary" type="submit">Save</button>
            <button class="button button-light" data-cancel-button="project-name" data-collapse-target="project-name" type="button">Cancel</button>
          </div>
        </form>
      </div>
      <div class="collapse show" data-collapse-id="project-name">
        <button class="button button-primary" data-edit-button="project-name" data-collapse-target="project-name">Edit</button>
      </div>
    </div>

    <div class="card">
      <div class="col-fg-1">
        <header class="card-header">Description</header>
        <div class="collapse show" data-collapse-id="description" data-update="description">
          <?php echo empty($project["description"]) ? "No description" : nl2br($project["description"]); ?>
        </div>
        <form class="collapse" data-form-id="description" data-collapse-id="description" data-collapse-target="description">
          <div class="input-group">
            <input type="hidden" name="project-id" value="<?php echo $project_id; ?>">
            <textarea data-input-id="description" name="description" rows="8" cols="50"><?php echo $project["description"]; ?></textarea>
          </div>
          <div class="form-actions">
            <button class="button button-primary" data-save-button="description" type="submit">Save</button>
            <button class="button button-light" data-cancel-button="description" data-collapse-target="description" type="button">Cancel</button>
          </div>
        </form>
      </div>
      <div class="collapse show" data-collapse-id="description">
        <button class="button button-primary" data-edit-button="description" data-collapse-target="description">Edit</button>
      </div>
    </div>
  </section>

  <section>
    <header>
      <h2 class="section-title">Visibility</h2>
    </header>

    <div class="card">
      <header class="wrapper-centered card-header">
        Visibility
      </header>
      <form class="wrapper-centered">
        <input type="hidden" name="project_id" value="<?php echo $project_id; ?>">
        <input data-checkbox-type="visibility" name="visibility-checkbox" class="toggle-switch" type="checkbox" <?php if($project["visible"] == "1") { echo " checked"; } ?>>
      </form>
    </div>
  </section>

  <section>
    <header>
      <h2 class="section-title">Delete this Project</h2>
    </header>

    <div class="card">
      <header class="wrapper-centered card-header">
        Delete this project
      </header>
      <div class="wrapper-centered">
        <button class="button button-danger" data-modal-target="modal">Delete</button>
      </div>
    </div>
  </section>

</main>

<dialog class="modal" data-dialog="modal">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title">Delete project</h5>
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

<?php include(SHARED_PATH . '/admin_footer.php'); ?>