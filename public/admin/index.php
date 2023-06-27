<?php
  require_once('../../private/initialize.php');
  require_login();

  if(is_post_request()) {

    $new_project = [];
    $new_project['project_name'] = $_POST['project_name'] ?? '';

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
  }

  $project_set = find_all_projects();
  $display_options = getDisplayOptions();
?>

<?php $page_title = "Projects"; ?>
<?php include(SHARED_PATH . '/admin_header.php'); ?>

<main>

  <div class="display-options collapse" data-box="display">
    <button class="close-display" data-dismiss="display"><span>×</span></button>
    <div class="display-options-container">
      <div class="display-heading">
        Display Options
      </div>
      <div class="control-vertical">
        <div class="view-group">
          <label class="vertical-align" for="text-position">Columns</label>

          <div>
            <button class="plus-circle" data-button="minus" <?php if($display_options['column_number'] == 1) { echo "disabled"; } ?>>
              <i class="minus-solid-icon"></i>
            </button>
              <span class="column-number"><?php echo $display_options['column_number']; ?></span>
            <button class="plus-circle" data-button="plus" <?php if($display_options['column_number'] == 3) { echo "disabled"; } ?>>
              <i class="plus-solid-icon"></i>
            </button>
          </div>
        </div>
        <div class="view-group">
          <label class="vertical-align" for="text-position">Text position</label>

          <select class="form-select" id="text-position" data-select="text">
            <option value="below" <?php if($display_options['text_position'] == 'below') { echo "selected"; } ?>>Below</option>
            <option value="inside" <?php if($display_options['text_position'] == 'inside') { echo "selected"; } ?>>Inside</option>
            <option value="hidden" <?php if($display_options['text_position'] == 'hidden') { echo "selected"; } ?>>Hidden</option>
          </select>
        </div>
      </div>
      <div class="control">
        <label class="vertical-align" for="thumbnail-toggle-switch">Edit thumbnails</label>
        <input id="thumbnail-toggle-switch" class="toggle-switch" type="checkbox" data-switch="thumbnail">
      </div>
    </div>
  </div>

  <header class="page-header">
    <button class="button button-secondary create-project-button button-big" data-modal-target="modal">Add a project</button>
    <button class="ml-auto button button-muted collapse show button-big" data-button="display"><i class="paintbrush-icon"></i> Display Options</button>
  </header>

  <?php echo display_errors($errors); ?>
  <?php echo display_session_message(); ?>

  <section class="projects-section">

    <div class="projects-list<?php if($display_options['column_number'] == 2) { echo " two-col"; } elseif($display_options['column_number'] == 3) { echo " three-col"; } else { echo ""; } ?>" data-column="projects-list">
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
              <img class="thumbnail" data-id="<?php echo $project['id']; ?>" src="<?php echo $project_cover; ?>" loading="lazy">
            </div>
            <div class="project-title-container <?php echo 'text-' . $display_options['text_position']; ?>" data-text="position">
              <div><?php echo h($project['project_name']); ?></div>
            </div>
          </a>
          <div class="tmb-btn-container" data-button-type="thumbnail">
            <div class="thumb-uploading" data-status="thumbnail">Uploading...</div>
            <form data-form-type="thumbnail">
              <input type="hidden" name="project_id" data-project-id="thumbnail" value="<?php echo $project['id']; ?>">
              <input type="file" name="file" data-file-type="thumbnail" id="file-<?php echo $project['id']; ?>" hidden>
            </form>
            <label class="button button-primary thumbnail-button" for="file-<?php echo $project['id']; ?>"><i class="image-icon"></i> change</label>
          </div>
        </div>
      <?php } ?>
    </div>

  </section>

</main>

<!-- Modal -->
<dialog class="modal" data-dialog="modal">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title">New project makerizer!</h5>
    </div>
    <div class="modal-body">
      <form id="create-project" data-form-id="create-project-form" action="<?php echo url_for('/admin/index.php'); ?>" method="post">
        <!-- Projet Title -->
        <div class="input-group">
          <label for="project-title">Title</label>
          <input id="project-title" class="form-control title" data-input-id="create-project-form" type="text" name="project_name" value="<?php echo h($new_project['project_name']); ?>" autocomplete="off">
        </div>
      </form>
    </div>
    <div class="modal-footer">
      <button class="button button-light button-big" data-dismiss="modal">Cancel</button>
      <button form="create-project" data-button-id="create-project-form" class="button button-secondary button-big" type="submit">Create your project</button>
    </div>
  </div>
</dialog>
<?php include(SHARED_PATH . '/admin_footer.php'); ?>