<?php
/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * View chunk for the non-logged users footer.
 * Date: 27/07/13 19:15
 *
 * @var \application\engine\View $this
 */
?>
</div>
<!-- Closing non-Logged Content -->

<div id='footer'>
</div>

<!-- JS Libraries -->
<?php foreach ($this->getJs() as $js) : ?><script src='<?php echo $js; ?>'></script>
<?php endforeach; ?>

</body>
</html>