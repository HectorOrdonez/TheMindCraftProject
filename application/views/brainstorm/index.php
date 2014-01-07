<?php
/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * View chunk for the Brainstorm content.
 * Date: 23/07/13 13:13
 *
 * @var \application\engine\View $this
 */
?>

<?php $this->printChunk('header'); ?>

<h1>
    Brainstorm!
</h1>
<div id='gridWrapper'>
    <table id='brainstorm_grid'>
    </table>
</div>
<div class='ftype_errorA' id='errorDisplayer'>
</div>

<?php $this->printChunk('footer'); ?>