<?php
/**
 * Project: Selfology
 * User: Hector Ordonez
 * Date: 23/07/13 10:00
 */
?>
<div class='inputErrors'>
    <?php foreach ($this->errors as $error) : ?>
        <div class='inputError'>
            <?php echo $error; ?>
        </div>
    <?php endforeach; ?>
</div>