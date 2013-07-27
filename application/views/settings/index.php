<?php
/**
 * Project: Selfology
 * User: Hector Ordonez
 * Description:
 * View chunk for the Settings content.
 * Date: 25/07/13 01:30
 */
?>

    <div id='settingsSection' class='font_normal'>

        <div class='settingsTitle font_title'>
            Settings.
        </div>

        <div class='setting name font_subTitle'>
            <label>
                A different name?
            </label>

            <div class='current font_normal' id='name_label'><?php echo $this->currentUsername; ?></div>

            <form class='formSetting'>
                <input type='text' class='font_normal' name='name' placeholder='new username'/>
            </form>

            <a href='#' class='change' id='name_change'></a>

            <div class='info' id='name_info'></div>
        </div>

        <div class='setting password font_subTitle'>
            <label>
                A new password?
            </label>

            <div class='current font_normal' id='password_label'> * * * * *</div>

            <form class='formSetting'>
                <input type='password' class='font_normal' name='password' placeholder='new password'/>
            </form>

            <a href='#' class='change' id="password_change"></a>

            <div class='info' id='password_info'></div>
        </div>

    </div>
