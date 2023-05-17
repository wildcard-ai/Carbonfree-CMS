<?php

if(!isset($page_title)) { $page_title = 'Admin Panel'; }

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo h($page_title) . ' - ' . $site_name; ?></title>
  <link rel="icon" href="<?php echo url_for('images/favicon.ico'); ?>">
  <link rel="stylesheet" href="<?php echo url_for('admin/css/admin.css'); ?>">
  <script src="<?php echo url_for('js/navbar.js'); ?>" defer></script>
  <?php
    echo load_script('index', 'modal');
    echo load_script('index', 'newproject');
    echo load_script('project', 'modal');
    echo load_script('index', 'thumbnail');
    echo load_script('project', 'project');
  ?>
</head>
<body>
<nav>
  <div class="navbar">
    <a class="navbar-brand" href="<?php echo url_for('admin/'); ?>" title="Admin Panel">Admin Panel</a>
    <button class="navbar-toggler" data-toggle="collapse" data-target="#navbarSupportedContent"><i class="navbar-toggler-icon"></i></button>
    <div class="navbar-collapse collapse" id="navbarSupportedContent">
      <div class="navbar-nav">
        <a class="nav-link<?php echo (is_page( 'index' )) ? ' active':''; ?>" href="<?php echo url_for('admin'); ?>">Projects</a>
        <a class="nav-link<?php echo (is_page( 'password' )) ? ' active':''; ?>" href="<?php echo url_for('admin/password.php'); ?>">Password</a>
        <a class="nav-link logout" href="<?php echo url_for('admin/login.php?action=logout'); ?>">Log out</a>
      </div>
    </div>
  </div>
</nav>