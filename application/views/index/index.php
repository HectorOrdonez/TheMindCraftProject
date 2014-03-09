<?php
/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * View chunk for the Index content.
 * Date: 23/07/13 13:30
 *
 * @var \application\engine\View $this
 */
?>
<?php $this->printChunk('header'); ?>

    <div id='loginBlock'>
        <form id='loginForm' action="<?php echo _SYSTEM_BASE_URL; ?>index/login" method="POST">
            <label>
                <input id='loginInputUsername' class='ftype_contentB shadowed' type='text' name='username' placeholder="username"/>
            </label>

            <div class='errorTextWrapper'>
                <p class='errorText' id='usernameError'></p>
            </div>

            <label>
                <input id='loginInputPassword' class='ftype_contentB shadowed' type='password' name='password' placeholder="password"/>
            </label>

            <div class='errorTextWrapper'>
                <p class='errorText' id='passwordError'></p>
            </div>

            <div class='verticalSpace30'></div>

            <div id='loginSubmitBox'>
                <div id='loginImageBox'>
                    <div id='key'>
                        <img src='<?php echo _SYSTEM_BASE_URL . 'public/images/'; ?>LogIn1.png' alt='Log In!'/>
                    </div>
                    <div id='lock'>
                        <div id='lockFront'>
                            <img src='<?php echo _SYSTEM_BASE_URL . 'public/images/'; ?>LogIn2.png' alt='Log In!'/>
                        </div>
                        <div id='lockBack'>
                            <img src='<?php echo _SYSTEM_BASE_URL . 'public/images/'; ?>LogIn3.png' alt='Log In!'/>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div id='generalError'>
        
    </div>

    <div id='loginConfirmation'>
        <div class='ftype_successA'>
            Starting session ...
        </div>
    </div>

<?php $this->printChunk('footer'); ?>