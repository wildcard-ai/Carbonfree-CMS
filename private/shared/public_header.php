<?php if(!isset($page_title)) { $page_title = 'Test Site'; } ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $page_title; ?></title>
  <link rel="icon" href="<?php echo url_for('images/favicon.ico'); ?>">
  <meta name="theme-color" content="#365de3" />
  <link rel="stylesheet" href="<?php echo url_for('css/main.css'); ?>">
  <script src="<?php echo url_for('js/navbar.js'); ?>" defer></script>
</head>
<body>
<header>
  <a href="<?php echo url_for('/'); ?>"><?php echo $site_name; ?></a>
</header>

<nav class="navbar">
  <a class="navbar-brand" href="<?php echo url_for('/'); ?>"><?php echo $site_name; ?></a>
  <button class="navbar-toggler" data-toggle="collapse" data-target="#navbarSupportedContent"><i class="navbar-toggler-icon"></i></button>
  <div class="navbar-collapse collapse" id="navbarSupportedContent">
    <div class="navbar-nav">
      <a class="nav-link<?php echo (is_page('index')) ? ' active':''; ?>" href="<?php echo url_for('/'); ?>">Work</a>
      <a class="nav-link<?php echo (is_page('about')) ? ' active':''; ?>" href="<?php echo url_for('/about.php'); ?>">About</a>
      <a class="nav-link<?php echo (is_page('contact')) ? ' active':''; ?>" href="<?php echo url_for('/contact.php'); ?>">Contact</a>
    </div>
  </div>
</nav>