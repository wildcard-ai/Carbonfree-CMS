<?php

require_once('../private/initialize.php');

?>

<?php $page_title = "About" . ' - ' . $site_name; ?>
<?php include(SHARED_PATH . '/public_header.php'); ?>

<main class="about">
  <img class="myphoto" src="<?php echo url_for('images/myphoto.jpg'); ?>">
  <h1>Joanne Dawson</h1>
  <p>2D Artist <i class="location-icon"></i> Seattle, Washington</p>
</main>

<?php include(SHARED_PATH . '/public_footer.php'); ?>