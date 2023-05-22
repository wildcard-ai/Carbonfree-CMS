<?php

require_once('../private/initialize.php');

$id = $_GET['id'] ?? redirect_to(url_for('/'));

$image_set = find_images_by_project_id($id);
$project = find_project_by_id($id);

?>

<?php $page_title = $project["project_name"] . ' - ' . $site_name; ?>
<?php include(SHARED_PATH . '/public_header.php'); ?>

<main class="projects">
  <h1><?php echo $project["project_name"]; ?></h1>
  <section class="images">
    <?php while($image = mysqli_fetch_assoc($image_set)) { ?>
      <img src="<?php echo url_for($image["path"]); ?>">
    <?php } ?>
    
    <?php mysqli_free_result($image_set); ?>
  </section>
  <section class="details">
    <div class="details-label">
      Description
    </div>
    <div>
      <?php echo nl2br($project["description"]); ?>
    </div>
  </section>
</main>

<?php include(SHARED_PATH . '/public_footer.php'); ?>