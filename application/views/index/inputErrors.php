<?php
/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * View chunk for the Index when input errors are generated.
 * Date: 23/07/13 13:30
 */
?>

    <div class='inputErrors ftype_errorA'>
<?php foreach ($this->errors as $error) : ?>
        <div class='inputError'><?php echo $error; ?></div>
<?php endforeach; ?>
    </div>
