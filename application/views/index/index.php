<?php
/**
 * Project: Selfology
 * User: Hector Ordonez
 * Description:
 * View chunk for the Index content.
 * Date: 23/07/13 13:30
 */
?>
<div class='loginBlock'>
    <form id='loginForm' action="<?php echo _SYSTEM_BASE_URL; ?>index/login" method="POST">
        <p>
            <input class='loginInputName' type='text' name='username' placeholder="username"/>
        </p>

        <p>
            <input class='loginInputPass' type='password' name='password' placeholder="password"/>
        </p>

        <div id='loginSubmit'>
            <div/>
    </form>
</div>