<?php
/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * View chunk for the Settings content.
 * Date: 25/07/13 01:30
 *
 * @var \application\engine\View $this
 */
?>
<?php $this->printChunk('header'); ?>

    <div id='settingsSection' class='ftype_contentA'>

        <div class='settingsTitle'>
            Settings.
        </div>

        <div class='setting name'>
            <label>
                A different name?
            </label>

            <div class='current ftype_contentA' id='name_label'><?php echo $this->getParameter('currentUsername'); ?></div>

            <form class='formSetting'>
                <input type='text' class='ftype_contentA' name='name' placeholder='new username'/>
            </form>

            <a href='#' class='change' id='name_change'></a>

            <div class='info' id='name_info'></div>
        </div>

        <div class='setting password'>
            <label>
                A new password?
            </label>

            <div class='current ftype_contentA' id='password_label'> * * * * *</div>

            <form class='formSetting'>
                <input type='password' class='ftype_contentA' name='password' placeholder='new password'/>
            </form>

            <a href='#' class='change' id="password_change"></a>

            <div class='info' id='password_info'></div>
        </div>

    </div>

<?php $this->printChunk('footer'); ?>