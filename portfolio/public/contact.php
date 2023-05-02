<?php

require_once('../private/initialize.php');
?>

<?php $page_title = "Contact" . ' - ' . $site_name; ?>
<?php include(SHARED_PATH . '/public_header.php'); ?>

<main class="contact">
  <div class="table-wrapper">


    <form method="post" action="send_email.php">
      <div class="form-contact-parent">

        <div class="form-contact">
          <label for="name">Name:</label>
          <input type="text" id="name" name="name" required>
        </div>

        <div class="form-contact">
          <label for="email">Email:</label>
          <input type="email" id="email" name="email" required>
        </div>

        <div class="form-contact message">
          <label for="message">Message:</label>
          <textarea rows="5" cols="33" id="message" name="message" required></textarea>
        </div>
      </div>
      <div class="form-submit">
        <input type="submit" value="Sumbit">
      </div>
    </form>

    <div class="address">
      <h4>Address</h4>
      <p>Seattle, Washington</p>
    </div>
  </div>
</main>

<?php include(SHARED_PATH . '/public_footer.php'); ?>