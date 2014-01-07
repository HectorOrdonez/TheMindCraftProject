<?php
/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * View chunk for the UsersManagement content.
 * Date: 25/07/13 01:30
 *
 * @var \application\engine\View $this
 */
?>
<?php $this->printChunk('header'); ?>

    <h1>
        Users Management
    </h1>
    <div id='gridWrapper'>
        <table id='usersManagement_grid'>
        </table>
    </div>
    <div id='infoDisplayer'>
    </div>

<?php $this->printChunk('footer'); ?>