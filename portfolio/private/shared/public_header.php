<?php if(!isset($page_title)) { $page_title = 'Test Site'; } ?>

<?php $active_page = basename($_SERVER['PHP_SELF'], '.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $page_title; ?></title>
  <link rel="icon" href="<?php echo url_for('images/favicon.ico'); ?>" type="image/x-icon">
  <link rel="stylesheet" href="<?php echo url_for('css/main.css'); ?>">
</head>
<body>
<header>
  <a href="<?php echo url_for('/'); ?>"><?php echo $site_name; ?></a>
</header>

<nav>
  <a class="logo" href="<?php echo url_for('/'); ?>"><?php echo $site_name; ?></a>
  <button class="toggle" data-toggle-target="menu"><i class="hamburger-icon"></i></button>
  <div class="menu" data-toggle-id="menu">
    <div class="navbar">
      <a class="item<?php echo ($active_page == 'index') ? ' active':''; ?>" href="<?php echo url_for('/'); ?>">Work</a>
      <a class="item<?php echo ($active_page == 'about') ? ' active':''; ?>" href="<?php echo url_for('/about.php'); ?>">About</a>
      <a class="item<?php echo ($active_page == 'contact') ? ' active':''; ?>" href="<?php echo url_for('/contact.php'); ?>">Contact</a>
    </div>
  </div>
</nav>