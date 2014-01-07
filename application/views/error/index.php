<?php
/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * View chunk for the Error content.
 * Date: 23/07/13 13:00
 *
 * @var \application\engine\View $this
 */
?>
<?php $this->printChunk('header'); ?>

    <div>
        <?php echo $this->getParameter('msg'); ?>

    </div>

<?php $this->printChunk('footer'); ?>