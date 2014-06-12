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

    <div id='settingsSection' class='ftype_contentB'>

        <div class='settingsTitle'>
            <span class='ftype_titleA1'>Your </span><span class='ftype_titleA2'>profile</span><span class='ftype_titleA3'>!</span>
        </div>

        <div class='setting username'>
            <label>
                A different username?
            </label>

            <span class='current ftype_contentB' data-field='username' id='username_label'><?php echo $this->getParameter('currentUsername'); ?></span>

            <form class='formSetting'>
                <input type='text' class='ftype_contentB' name='username' placeholder='new username'/>
            </form>

            <div class='info' id='username_info'></div>
        </div>

        <div class='setting password'>
            <label>
                A new password?
            </label>

            <span class='current ftype_contentB' id='password_label'> * * * * *</span>

            <form class='formSetting'>
                <input type='password' class='ftype_contentB' name='password' placeholder='new password'/>
            </form>

            <div class='info' id='password_info'></div>
        </div>

    </div>

<?php $this->printChunk('footer'); ?>