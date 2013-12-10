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
            <div class='ftype_titleA1'>
                Welcome
            </div>
            <div class='ftype_titleA2'>
                <?php echo $this->userName; ?>
            </div>
        </div>
            
        </div>
        <div id='mainMenu'>
            <div id='spinningCircle'>
            </div>

            <div id='processActions'>
                <a class='option brainstorm' href="<?php echo _SYSTEM_BASE_URL; ?>brainstorm"></a>
                <a class='option workOut' href="<?php echo _SYSTEM_BASE_URL; ?>workOut"></a>
                <a class='option perform' href="<?php echo _SYSTEM_BASE_URL; ?>perform"></a>
            </div>
        </div>