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
  <script src="<?php echo url_for('admin/js/navbar.js'); ?>" defer></script>
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
  <div class="nav">
    <a class="logo" href="<?php echo url_for('admin/'); ?>" title="Admin Panel">Admin Panel</a>
    <button class="menu-toggler" data-toggle="collapse"><i class="hamburger-icon"></i></button>
    <div class="menu closed" data-menu="collapse">
      <div class="navbar">
        <a class="item<?php echo (is_page( 'index' )) ? ' active':''; ?>" href="<?php echo url_for('admin'); ?>">Projects</a>
        <a class="item<?php echo (is_page( 'password' )) ? ' active':''; ?>" href="<?php echo url_for('admin/password.php'); ?>">Password</a>
        <a class="item logout" href="<?php echo url_for('admin/login.php?action=logout'); ?>">Log out</a>
      </div>
    </div>
  </div>
</nav>