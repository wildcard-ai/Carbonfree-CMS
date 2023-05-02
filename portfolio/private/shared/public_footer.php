<footer>&copy; <?php echo date('Y'); ?> Joanne Dawson</footer>
<script src="<?php echo url_for('js/script.js'); ?>"></script>
</body>
</html>

<?php
  db_disconnect($db);
?>