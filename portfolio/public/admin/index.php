<?php

  require_once('../../private/initialize.php');
  require_login();

  $project_set = find_all_projects();

  //Create project
  if(is_post_request()) {
    $project = [];
    $project['project_name'] = $_POST['project_name'] ?? '';

    insert_project($project);
    redirect_to(url_for('admin/'));
  }

?>

<?php $page_title = "Projects"; ?>
<?php include(SHARED_PATH . '/admin_header.php'); ?>

<?php echo display_errors($errors); ?>
<?php echo display_session_message(); ?>

<main>

  <header class="page-header">
    <h2 class="page-title">Projects</h2>
    <!-- Visibility -->
    <div>
      <label class="toggle-label" for="edit-thumbnails">Edit Thumbnails</label>
      <div class="switch-container">
        <input id="edit-thumbnails" class="switch" type="checkbox" data-switch-type="thumbnail">
      </div>
    </div>
  </header>

  <?php
    if (!empty($_GET['data'])) {
      $data = json_decode($_GET['data']);
      $isValid = $data && property_exists($data, 'message');
      $message = $isValid ? $data->message : 'Invalid data format.';
      echo '<div class="alert alert-primary">' . $message . '</div>';
    }
  ?>

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
            $project_cover = url_for('admin/images/no-thumbnail.jpg');
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

<script src="<?php echo url_for('admin/js/thumbnail.js'); ?>"></script>
<?php include(SHARED_PATH . '/admin_footer.php'); ?>