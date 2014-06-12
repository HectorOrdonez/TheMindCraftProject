<?php
/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * View chunk for the logged users footer.
 * Date: 23/07/13 13:00
 *
 * @var \application\engine\View $this
 */
?>
</div>
<!-- Closing logged-in Content -->

<div id='footer'>
</div>

<!-- JS Libraries -->
<?php foreach ($this->getJs() as $js) : ?><script src='<?php echo $js; ?>'></script>
<?php endforeach; ?>

</body>
</html>