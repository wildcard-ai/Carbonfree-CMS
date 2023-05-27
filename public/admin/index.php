<?php
  require_once('../../private/initialize.php');
  require_login();

  if(is_post_request()) {

    $new_project = [];
    $new_project['project_name'] = $_POST['project_name'] ?? '';
    $new_project['visible'] = $_POST['visible'] ?? '';

    $result = insert_project($new_project);
    if($result === true) {
      $new_id = mysqli_insert_id($db);
      redirect_to(url_for('/admin/project.php?id=' . $new_id));
    } else {
      $errors = $result;
    }
  
  } else {
    // display the blank form
    $new_project = [];
    $new_project["project_name"] = '';
    $new_project["visible"] = '';
  }

  $project_set = find_all_projects();

?>

<?php $page_title = "Projects"; ?>
<?php include(SHARED_PATH . '/admin_header.php'); ?>

<main>

  <header class="page-header">
    <h2 class="page-title separator">Projects</h2>
    <button class="button button-secondary create-project-button" data-modal-target="modal"><i class="plus-icon"></i> Create Project</button>
    <!-- Visibility -->
    <div class="ml-auto">
      <label class="toggle-switch-label" for="thumbnail-toggle-switch">Edit Thumbnails</label>
      <input id="thumbnail-toggle-switch" class="toggle-switch" type="checkbox" data-switch-type="thumbnail">
    </div>
  </header>

  <?php echo display_errors($errors); ?>
  <?php echo display_session_message(); ?>

  <section class="projects-list">
    <?php while($project = mysqli_fetch_assoc($project_set)) { ?>
      <?php
        $cover = find_project_by_id($project['id']);
        $image = find_first_image_by_project_id($project['id']);
        if (!empty($cover['cover_path'])) {
            $project_cover = url_for($cover['cover_path']);
        } elseif (!empty($image['path'])) {
            $project_cover = url_for($image['path']);
        } else {
            $project_cover = url_for('images/no-thumbnail.jpg');
        }
      ?>
      <div class="project">
        <a class="project-link" href="<?php echo url_for('/admin/project.php?id=' . h(u($project['id']))); ?>">
          <div class="thumbnail-container">
            <img class="thumbnail" data-thumbnail-id="<?php echo $project['id']; ?>" src="<?php echo $project_cover; ?>" loading="lazy">
          </div>
          <div class="project-title-container">
            <div class="project-title"><?php echo h($project['project_name']); ?></div>
          </div>
        </a>
        <div class="tmb-btn-container" data-button-type="thumbnail">
          <form data-form-type="thumbnail">
            <input type="hidden" name="project_id" data-project-id="thumbnail" value="<?php echo $project['id']; ?>">
            <input type="file" name="file" data-file-type="thumbnail" id="file-<?php echo $project['id']; ?>" hidden>
          </form>
          <label class="button button-primary thumbnail-button" for="file-<?php echo $project['id']; ?>">Change</label>
        </div>
      </div>
    <?php } ?>
  </section>
</main>
<label class="create-project-button create-project-float" data-modal-target="modal"><i class="plus-icon"></i></label>
<!-- Modal -->
<dialog class="modal" data-dialog="modal">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title">Create Project</h5>
      <button class="close" data-dismiss="modal"><span>×</span></button>
    </div>
    <div class="modal-body">
      <form id="create-project" data-form-id="create-project-form" action="<?php echo url_for('/admin/index.php'); ?>" method="post">
        <!-- Projet Title -->
        <div class="input-group">
          <input class="form-control" data-input-id="create-project-form" type="text" name="project_name" value="<?php echo h($new_project['project_name']); ?>" autocomplete="off">
        </div>
        <!-- Visibility -->
        <div>
          <input type="hidden" name="visible" value="0">
          <input class="toggle-switch" id="visibility-toggle-switch" type="checkbox" name="visible" value="1"<?php if($new_project['visible'] == 1) { echo " checked"; } ?>>
          <label class="toggle-switch-label" for="visibility-toggle-switch">Visibility</label>
        </div>
      </form>
    </div>
    <div class="modal-footer">
      <button form="create-project" data-button-id="create-project-form" class="button button-secondary" type="submit">Create</button>
    </div>
  </div>
</dialog>
<?php include(SHARED_PATH . '/admin_footer.php'); ?>