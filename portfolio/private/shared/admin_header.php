<?php

if(!isset($page_title)) { $page_title = 'Admin Panel'; }

$active_page = basename($_SERVER['PHP_SELF'], '.php');

$new_project = [];
$new_project["project_name"] = '';

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo h($page_title) . ' - ' . $site_name; ?></title>
  <link rel="icon" href="<?php echo url_for('images/favicon.ico'); ?>" type="image/x-icon">
  <link rel="stylesheet" href="<?php echo url_for('admin/css/admin.css'); ?>">
</head>
<body>
<nav>
  <div class="nav">
    <a class="logo" href="<?php echo url_for('admin/'); ?>" title="Admin Panel">Admin Panel</a>
    <label class="button button-secondary create-project-button" for="open-modal"><i class="plus-icon"></i></label>
    <button class="toggle" data-toggle-target="menu"><i class="hamburger-icon"></i></button>
    <div class="menu" data-toggle-id="menu">
      <div class="navbar">
        <a class="item <?= ($active_page == 'index') ? 'active':''; ?>" href="<?php echo url_for('admin'); ?>">Projects</a>
        <a class="item <?= ($active_page == 'password') ? 'active':''; ?>" href="<?php echo url_for('admin/password.php'); ?>">Password</a>
        <button class="button button-secondary create-project-button" id="open-modal" data-modal-target="modal-wrapper"><i class="plus-icon"></i> Create Project</button>
        <a class="button button-light" href="<?php echo url_for('admin/logout.php'); ?>">Log out</a>
      </div>
    </div>
  </div>
  <div class="modal" data-modal-id="modal-wrapper">
    <div class="modal-content">
      <span class="close" data-modal-action="close">&times;</span>
      <h2>Create Project</h2>
      <form data-form-id="create-project-form">
        <!-- Projet Title -->
        <input class="project-name-input" type="text" data-input-id="project-name" name="project_name" required>
        <!-- Visibility -->
        <div class="switch-wrapper">
          <label class="switch">
            <input type="checkbox" id="visible-input" data-input-id="visible-input" name="visible">
            <span class="slider round"></span>
          </label>
          <label class="toggle-label" for="visible-input">Visibility</label>
        </div>
        <div class="modal-actions">
          <button class="button button-primary" type="submit">Create</button>
        </div>
      </form>
    </div>
  </div>
</nav>