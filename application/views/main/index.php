<?php
/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * View chunk for the Main content.
 * Date: 23/07/13 13:30
 */
?>
        <div id='welcome'>
            <span class='ftype_titleA1'>Welcome </span><span class='ftype_titleA2'><?php echo $this->userName; ?></span><span class='ftype_titleA3'>!</span>
        </div>
            
        </div>
        <div id='mainMenu'>
            <div id='spinningCircle'>
            </div>

            <div id='processActions'>
                <div class='action brainStorm'>
                    <a href="<?php echo _SYSTEM_BASE_URL; ?>brainStorm">
                        <img src='<?php echo _SYSTEM_BASE_URL . 'public/images/'; ?>BrainStorm.png' alt='BrainStorm' />
                    </a>
                    <div class='text'>
                        <span class='ftype_titleA1'>Brain</span><span class='ftype_titleA2'>Storm</span><span class='ftype_titleA3'>!</span>
                    </div>
                </div>
                <div class='action workOut'>
                    <a href="<?php echo _SYSTEM_BASE_URL; ?>workOut">
                        <img src='<?php echo _SYSTEM_BASE_URL . 'public/images/'; ?>WorkOut.png' alt='WorkOut' />
                    </a>
                    <div class='text'>
                        <span class='ftype_titleA1'>Work</span><span class='ftype_titleA2'>Out</span><span class='ftype_titleA3'>!</span>
                    </div>
                </div>
                <div class='action perForm'>
                    <a href="<?php echo _SYSTEM_BASE_URL; ?>perForm">
                        <img src='<?php echo _SYSTEM_BASE_URL . 'public/images/'; ?>PerForm.png' alt='PerForm' />
                    </a>
                    <div class='text'>
                        <span class='ftype_titleA1'>Per</span><span class='ftype_titleA2'>Form</span><span class='ftype_titleA3'>!</span>
                    </div>
                </div>
            </div>
        </div>