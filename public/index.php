<?php

require_once('../private/initialize.php');

$project_set = find_all_projects(['visible' => true]);

?>

<?php $page_title = $site_name; ?>
<?php include(SHARED_PATH . '/public_header.php'); ?>

<main class="work">

  <?php while($project = mysqli_fetch_assoc($project_set)) { ?>
    <?php
      $cover = find_project_by_id($project['id']);
      $image = find_first_image_by_project_id($project['id']);
      if(!isset($cover["cover_path"]) and !isset($image["path"])) {
        $project_cover = url_for("admin/images/no-thumbnail.jpg");
      } elseif (!isset($cover["cover_path"])) {
        $project_cover = $image["path"];
      } else{
        $project_cover = $cover["cover_path"];
      }
    ?>
    <div class="project">
      <a class="project-link" href="<?php echo url_for('/project.php?id=' . h(u($project['id']))); ?>">
        <img class="thumbnail" src="<?php echo url_for($project_cover); ?>">
        <div class="project-title"><?php echo h($project["project_name"]); ?></div>
      </a>
    </div>
  <?php } ?>

</main>

<?php include(SHARED_PATH . '/public_footer.php'); ?>