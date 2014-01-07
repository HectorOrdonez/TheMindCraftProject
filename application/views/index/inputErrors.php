<?php
/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * View chunk for the Index when input errors are generated.
 * Date: 23/07/13 13:30
 *
 * @var \application\engine\View $this
 */
?>
<?php $this->printChunk('header'); ?>

<div class='loginBlock'>
    <form id='loginForm' action="<?php echo _SYSTEM_BASE_URL; ?>index/login" method="POST">
        <p>
            <input class='loginInputName ftype_contentA' type='text' name='username' placeholder="username"/>
        </p>

        <p>
            <input class='loginInputPass ftype_contentA' type='password' name='password' placeholder="password"/>
        </p>

        <div id='loginSubmit'></div>
    </form>
</div>

<div class='inputErrors ftype_errorA'>
    <?php foreach ($this->getParameter('errors') as $error) : ?>
        <div class='inputError'><?php echo $error->getError()->getMessage(); ?></div>
    <?php endforeach; ?>
</div>

<?php $this->printChunk('footer'); ?>
