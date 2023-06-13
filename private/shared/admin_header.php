<?php

if(!isset($page_title)) { $page_title = 'Admin Panel'; }

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?php echo h($page_title) . ' - ' . $site_name; ?></title>
  <link rel="icon" href="<?php echo url_for('images/favicon.ico'); ?>">
  <link rel="stylesheet" href="<?php echo url_for('admin/css/admin.css'); ?>">
  <?php
    echo load_script('index', 'modal');
    echo load_script('index', 'script');
    echo load_script('project', 'project');
    echo load_script('index', 'display');
  ?>
</head>
<body>
<nav>
  <div class="navbar">
    <a class="nav-link<?php echo (is_page( 'index' )) ? ' active':''; ?>" href="<?php echo url_for('admin'); ?>">Projects</a>
    <a class="nav-link<?php echo (is_page( 'about' )) ? ' active':''; ?>" href="<?php echo url_for('admin/about.php'); ?>">Profile</a>
    <a class="nav-link<?php echo (is_page( 'personalize' )) ? ' active':''; ?>" href="<?php echo url_for('admin/personalize.php'); ?>">Personalize</a>
    <a class="nav-link ml-auto" href="<?php echo url_for('/'); ?>" target="_blank">View portfolio</a>
    <div class="separator"></div>
    <div class="dropdown">
      <button class="dropdown-button"><i class="gear-icon"></i></button>
      <div class="dropdown-content collapse">
        <a class="nav-link<?php echo (is_page( 'account' )) ? ' active':''; ?>" href="<?php echo url_for('admin/account.php'); ?>">Account</a>
        <a class="nav-link" href="<?php echo url_for('admin/login.php?action=logout'); ?>">Log out</a>
      </div>
    </div>
  </div>
</nav>