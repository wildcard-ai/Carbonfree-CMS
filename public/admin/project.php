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

<div class="viewer-card-container">

    <div class="viewer-heading">
      <div class="center-viewer">
        <div class="page-title"><?php echo $project["project_name"]; ?></div>
        <button class="button button-secondary" data-modal-target="image">Manage images</button>
      </div>
    </div>
    <div class="viewer-body" data-viewer="images">
      
      <?php while($thumb = mysqli_fetch_assoc($image_set)) { ?>

        <img class="viewer-image" alt="<?php echo isset($thumb["caption"]) ? $thumb["caption"] : ""; ?>" src="<?php echo url_for($thumb["path"]); ?>" data-image-id="<?php echo $thumb['id']; ?>">

      <?php } ?>
    </div>

</div>

<main>

  <section class="details">
    <header>
      <h2 class="section-title">Details</h2>
    </header>
    <div class="card-group">
      <label class="card <?php if(empty($project["project_name"])) { echo "empty";} ?>" data-collapse-id="project-name">
        <div class="arrow-button-container">
          <button class="button <?php echo empty($project["project_name"]) ? "button-secondary" : "button-primary"; ?> arrow" data-edit-button="project-name" data-collapse-target="project-name">Edit</button>
        </div>
        <div class="col-fg-1">
          <header class="card-header">Title</header>
          <div class="collapse show card-text" data-collapse-id="project-name" data-update="project-name">
            <?php echo $project["project_name"]; ?>
          </div>
          <form class="collapse" data-form-id="project-name" data-collapse-id="project-name" data-collapse-target="project-name">
            <div class="input-group">
              <input type="hidden" name="project-id" value="<?php echo $project_id; ?>">
              <input class="form-control" type="text" data-input-id="project-name" name="project-name" value="<?php echo $project["project_name"]; ?>" autocomplete="off" required>
            </div>
            <div class="form-actions">
              <button class="button <?php echo empty($project["project_name"]) ? "button-secondary" : "button-primary"; ?>" type="submit"><?php echo empty($project["project_name"]) ? "Add" : "Save changes"; ?></button>
              <button class="button button-light" data-cancel-button="project-name" data-collapse-target="project-name" type="button">Cancel</button>
            </div>
          </form>
        </div>
      </label>

      <label class="card <?php if(empty($project["description"])) { echo "empty";} ?>" data-collapse-id="description">
        <div class="arrow-button-container">
          <button class="button <?php echo empty($project["description"]) ? "button-secondary" : "button-primary"; ?> arrow" data-edit-button="description" data-collapse-target="description"><?php echo empty($project["description"]) ? "Add" : "Edit"; ?></button>
        </div>
        <div class="col-fg-1">
          <header class="card-header">Description</header>
          <div class="collapse show card-text small" data-collapse-id="description" data-update="description">
            <?php echo empty($project["description"]) ? "Anything your viewers should know about the project." : nl2br($project["description"]); ?>
          </div>
          <form class="collapse" data-form-id="description" data-collapse-id="description" data-collapse-target="description">
            <div class="input-group">
              <input type="hidden" name="project-id" value="<?php echo $project_id; ?>">
              <textarea class="form-control" data-input-id="description" name="description" rows="5"><?php echo $project["description"]; ?></textarea>
            </div>
            <div class="form-actions">
              <button class="button <?php echo empty($project["description"]) ? "button-secondary" : "button-primary"; ?>" data-save-button="description" type="submit"><?php echo empty($project["description"]) ? "Add" : "Save changes"; ?></button>
              <button class="button button-light" data-cancel-button="description" data-collapse-target="description" type="button">Cancel</button>
            </div>
          </form>
        </div>
      </label>

      <label class="card <?php if(empty($project["url"])) { echo "empty";} ?>" data-collapse-id="url">
        <div class="arrow-button-container">
          <button class="button <?php echo empty($project["url"]) ? "button-secondary" : "button-primary"; ?> arrow" data-edit-button="url" data-collapse-target="url"><?php echo empty($project["url"]) ? "Add" : "Edit"; ?></button>
        </div>
        <div class="col-fg-1">
          <header class="card-header">Project Web Address (URL)</header>
          <div class="collapse show card-text small" data-collapse-id="url" data-update="url">
            <?php echo empty($project["url"]) ? "If your project has external assets you can link to them here. The site will open up in a new window." : $project["url"]; ?>
          </div>
          <form class="collapse" data-form-id="url" data-collapse-id="url" data-collapse-target="url">
            <div class="input-group">
              <input type="hidden" name="project-id" value="<?php echo $project_id; ?>">
              <input class="form-control" type="text" data-input-id="url" name="url" value="<?php echo $project["url"]; ?>" autocomplete="off">
            </div>
            <div class="form-actions">
              <button class="button <?php echo empty($project["url"]) ? "button-secondary" : "button-primary"; ?>" type="submit"><?php echo empty($project["url"]) ? "Add" : "Save changes"; ?></button>
              <button class="button button-light" data-cancel-button="url" data-collapse-target="url" type="button">Cancel</button>
            </div>
          </form>
        </div>
      </label>

      <label class="card <?php if(empty($project["client"])) { echo "empty";} ?>" data-collapse-id="client">
        <div class="arrow-button-container">
          <button class="button <?php echo empty($project["client"]) ? "button-secondary" : "button-primary"; ?> arrow" data-edit-button="client" data-collapse-target="client"><?php echo empty($project["client"]) ? "Add" : "Edit"; ?></button>
        </div>
        <div class="col-fg-1">
          <header class="card-header">Client</header>
          <div class="collapse show card-text small" data-collapse-id="client" data-update="client">
            <?php echo empty($project["client"]) ? "Who you did the work for." : $project["client"]; ?>
          </div>
          <form class="collapse" data-form-id="client" data-collapse-id="client" data-collapse-target="client">
            <div class="input-group">
              <input type="hidden" name="project-id" value="<?php echo $project_id; ?>">
              <input class="form-control" type="text" data-input-id="client" name="client" value="<?php echo $project["client"]; ?>" autocomplete="off">
            </div>
            <div class="form-actions">
              <button class="button <?php echo empty($project["client"]) ? "button-secondary" : "button-primary"; ?>" type="submit"><?php echo empty($project["client"]) ? "Add" : "Save changes"; ?></button>
              <button class="button button-light" data-cancel-button="client" data-collapse-target="client" type="button">Cancel</button>
            </div>
          </form>
        </div>
      </label>

      <label class="card <?php if(empty($project["project_type"])) { echo "empty";} ?>" data-collapse-id="project-type">
        <div class="arrow-button-container">
          <button class="button <?php echo empty($project["project_type"]) ? "button-secondary" : "button-primary"; ?> arrow" data-edit-button="project-type" data-collapse-target="project-type"><?php echo empty($project["project_type"]) ? "Add" : "Edit"; ?></button>
        </div>
        <div class="col-fg-1">
          <header class="card-header">Project Type</header>
          <div class="collapse show card-text small" data-collapse-id="project-type" data-update="project-type">
            <?php echo empty($project["project_type"]) ? "Separate types with commas (e.g Illustration, Website)." : $project["project_type"]; ?>
          </div>
          <form class="collapse" data-form-id="project-type" data-collapse-id="project-type" data-collapse-target="project-type">
            <div class="input-group">
              <input type="hidden" name="project-id" value="<?php echo $project_id; ?>">
              <input class="form-control" type="text" data-input-id="project-type" name="project-type" value="<?php echo $project["project_type"]; ?>" autocomplete="off">
            </div>
            <div class="form-actions">
              <button class="button <?php echo empty($project["project_type"]) ? "button-secondary" : "button-primary"; ?>" type="submit"><?php echo empty($project["project_type"]) ? "Add" : "Save changes"; ?></button>
              <button class="button button-light" data-cancel-button="project-type" data-collapse-target="project-type" type="button">Cancel</button>
            </div>
          </form>
        </div>
      </label>
    </div>
  </section>

  <section>
    <header>
      <h2 class="section-title">Visibility</h2>
    </header>

    <div class="card-group">
      <div class="single-card">
        <div class="visibility-switch" data-checkbox-type="visibility" data-id="<?php echo $project_id; ?>">
          <div data-switch="visible" class="visible<?php if($project["visible"] == "1") { echo " checked"; } ?>">
            <div class="visibility-pic"></div>
            <div class="visibility-text">Visible</div>
          </div>
          <div data-switch="hidden" class="hidden<?php if($project["visible"] == "0") { echo " checked"; } ?>">
            <div class="visibility-pic"></div>
            <div class="visibility-text">Hidden</div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section>
    <header>
      <h2 class="section-title">Delete</h2>
    </header>

    <div class="card-group">
      <label class="card">
        <div class="arrow-button-container">
          <button class="button button-danger arrow" data-button="delete" data-collapse-target="delete">Delete</button>
        </div>
        <div class="collapse show wrapper-centered card-text delete" data-collapse-id="delete">
          this project
        </div>
        <div class="collapse" data-collapse-id="delete">
          <p class="delete-message">This cannot be undone, are you sure?</p>
          <div class="form-actions">
            <form method="post" action="<?php echo url_for('admin/project.php?id=' . h(u($project['id']))); ?>">
              <button class="button button-danger" type="submit">Yes, Delete</button>
            </form>
            <button class="button button-light" data-cancel-button="delete" data-collapse-target="delete" type="button">Cancel</button>
          </div>
        </div>
      </label>
    </div>
  </section>

</main>

<form  method="post" enctype="multipart/form-data" data-form-id="upload" data-project-id="<?php echo $project_id; ?>">
  <input type="file" name="files[]" data-input-id="upload" id="file-input" hidden multiple>
</form>

<dialog class="modal manage-images" data-dialog="image">
  <div class="modal-content">

    <div class="toolbar-header">
      <div class="upload-toolbar" data-upload-buttons="toolbar">
        <h5 class="modal-title">Manage Images</h5>
        <label tabindex="0" class="button button-secondary ml-auto" for="file-input">Add photos</label>
        <button class="button button-light" data-dismiss="image">Cancel</button>
        <button class="button button-primary" data-done-button="images">Done</button>
      </div>
      <div class="delete-toolbar collapse" data-delete-buttons="toolbar">
        <span class="selected-count" data-selected-count="image"></span>
        <button class="button button-danger" data-delete-button="image">Delete</button>
        <button class="button button-primary select-all" data-select-all-button="image">Select All</button>
      </div>
    </div>

    <div class="image-list" data-list-id="upload">

    </div>

  </div>
</dialog>

<?php include(SHARED_PATH . '/admin_footer.php'); ?>