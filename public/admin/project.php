<?php
require_once('../../private/initialize.php');
require_login();

$project_id = isset($_GET['id']) ? $_GET['id'] : null;

$project = find_project_by_id($project_id);
$image_set = find_images_by_project_id($project_id);

$page_title = h($project["project_name"]);
include(SHARED_PATH . '/admin_header.php');

?>

<main>
  <header class="page-header">
    <h2 class="page-title new-project-name"><?php echo $page_title; ?></h2>
  </header>
  <section class="images-section">
    <div class="upload-edit-wrapper">
      <form class="upload-image-form" id="upload-image-form" method="post">
        <input type="hidden" name="project_id" value="<?php echo $project_id; ?>">
        <input type="file" name="file" id="file-input" hidden>
        <label class="button button-secondary" for="file-input">Upload</label>
      </form>
      <button class="button button-primary">Edit</button>
    </div>
    <div class="file-list" id="file-list">
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
        <div class="project-name-wrapper new-project-name">
          <?php echo $page_title; ?>
        </div>
        <div class="project-name-form-wrapper">
          <form id="edit-project-name-form" method="post">
            <input type="hidden" name="project-id" value="<?php echo $project_id; ?>">
            <input class="project-name-input" type="text" id="project-name-input" name="project-name" value="<?php echo $project["project_name"]; ?>" required>
            <button class="button button-secondary" id="save-project-name">Save</button>
            <button class="button button-primary" id="cancel-project-name">Cancel</button>
          </form>
        </div>
      </div>
      <div class="edit-wrapper">
        <button class="button button-primary" id="edit-project-name">Edit</button>
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
          <input id="visibility-checkbox" name="visibility-checkbox" class="toggle-switch" type="checkbox" data-switch-type="thumbnail" <?php if($project["visible"] == "1") { echo " checked"; } ?>>
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
        <form data-form-id="delete-project-form" data-confirm-message="Are you sure you want to delete this project? This action cannot be undone.">
          <input type="hidden" name="project_id" value="<?php echo $project_id; ?>">
          <button class="button button-danger" type="submit">Delete</button>
        </form>
      </div>
    </div>
  </section>
</main>
<?php include(SHARED_PATH . '/admin_footer.php'); ?>