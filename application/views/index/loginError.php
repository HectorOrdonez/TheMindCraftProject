<?php
/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * View chunk for the Index when user login attempt fails.
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

<div class='loginError ftype_errorA'><?php echo $this->getParameter('loginError'); ?></div>

<?php $this->printChunk('footer'); ?>

