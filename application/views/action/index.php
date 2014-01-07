<?php
/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * View chunk for the Action content.
 * Date: 25/07/13 01:30
 *
 * @var \application\engine\View $this
 */
?>
<?php $this->printChunk('header'); ?>

    <h1>
        Action!
    </h1>

    <div id='gridWrapper'>
        <table id='action_grid'>
        </table>
    </div>
    <div class='ftype_errorA' id='errorDisplayer'>
    </div>

    <div id='oldGridWrapper'>
        <table id='oldAction_grid'>
        </table>
    </div>

<?php $this->printChunk('footer'); ?>