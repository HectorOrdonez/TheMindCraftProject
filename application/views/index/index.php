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
                <input id='loginInputUsername' class='ftype_contentA' type='text' name='username' placeholder="username"/>
            </label>

            <div class='errorText'>
                <div id='usernameError'></div>
            </div>

            <label>
                <input id='loginInputPassword' class='ftype_contentA' type='password' name='password' placeholder="password"/>
            </label>

            <div class='errorText'>
                <div id='passwordError'></div>
            </div>

            <div class='verticalSpace30'></div>

            <div id='loginSubmitBox'>

                <div id='loginSubmitText'>
                    <span class='ftype_titleA1'>Log</span><span class='ftype_titleA2'>In</span><span
                        class='ftype_titleA3'>!</span>
                </div>

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
        <div class='ftype_successA' id='confirmationTitle'>
            Starting session ...
        </div>
    </div>

<?php $this->printChunk('footer'); ?>