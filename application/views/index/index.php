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
            <p>
                <input id='loginInputUsername' class='ftype_contentA' type='text' name='username' placeholder="username"/>
            </p>

            <p>
                <input id='loginInputPassword' class='ftype_contentA' type='password' name='password' placeholder="password"/>
            </p>
            
            <div class='verticalSpace30'></div>

            <div id='loginSubmitText'>
                <span class='ftype_titleA1'>Log</span><span class='ftype_titleA2'>In</span><span
                    class='ftype_titleA3'>!</span>
            </div>
            <div id='loginSubmitBox'>
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

            <div id='loginSubmitBox'></div>
        </form>
    </div>

<?php $this->printChunk('footer'); ?>