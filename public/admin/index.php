<?php
  require_once('../../private/initialize.php');
  require_login();

  if(is_post_request()) {

    $project = [];
    $project['project_name'] = $_POST['project_name'] ?? '';
    $project['visible'] = $_POST['visible'] ?? '';

    $result = insert_project($project);
    if($result === true) {
      $new_id = mysqli_insert_id($db);
      redirect_to(url_for('/admin/project.php?id=' . $new_id));
    } else {
      $errors = $result;
    }
  
  }

  $project_set = find_all_projects();

?>

<?php $page_title = "Projects"; ?>
<?php include(SHARED_PATH . '/admin_header.php'); ?>

<main>

  <header class="page-header">
    <h2 class="page-title">Projects</h2>
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
          <img class="thumbnail" data-thumbnail-id="<?php echo $project['id']; ?>" src="<?php echo $project_cover; ?>">
          <div class="project-title"><?php echo h($project['project_name']); ?></div>
        </a>
        <form class="thumbnail-form" data-form-type="thumbnail">
          <input type="hidden" name="project_id" data-project-id="thumbnail" value="<?php echo $project['id']; ?>">
          <input type="file" name="file" data-file-type="thumbnail" id="file-<?php echo $project['id']; ?>" hidden>
        </form>
        <div class="tmb-btn-container" data-button-type="thumbnail">
          <label class="button button-primary thumbnail-button" for="file-<?php echo $project['id']; ?>">Change</label>
        </div>
      </div>
    <?php } ?>
  </section>
</main>
<label class="button button-secondary create-project-button create-project-float" data-modal-target="modal"><i class="plus-icon"></i></label>
<!-- Modal -->
<div class="modal" data-modal-id="modal-wrapper">
  <div class="modal-content">
    <span class="close" data-modal-action="close">&times;</span>
    
    <h2>Create Project</h2>
    <form data-form-id="create-project-form" action="<?php echo url_for('/admin/index.php'); ?>" method="post">
      <!-- Projet Title -->
      <input class="project-name-input" type="text" name="project_name" required>
      <!-- Visibility -->
      <div>
        <input type="hidden" name="visible" value="0">
        <input class="toggle-switch" id="visibility-toggle-switch" type="checkbox" name="visible" value="1">
        <label class="toggle-switch-label" for="visibility-toggle-switch">Visibility</label>
      </div>
      <div class="modal-actions">
        <button class="button button-primary" type="submit">Create</button>
      </div>
    </form>

  </div>
</div>
<?php include(SHARED_PATH . '/admin_footer.php'); ?>