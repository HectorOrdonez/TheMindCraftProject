<?php
/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * View chunk for the SignUp content.
 * Date: 22/11/13 20:00
 *
 * @var \application\engine\View $this
 */
?>
<?php $this->printChunk('header'); ?>

<div id='signUpBody'>
    <div class='ftype_contentA'>
        New in here?
        <p id='signUpNow'>
            SignUp now!
        </p>
    </div>

    <div id='signUpBlock'>
        <form id='signUpForm' action="<?php echo _SYSTEM_BASE_URL; ?>signUp/signUp" method="POST">
            <p>
                <input id='signUpInputEmail' class='ftype_contentA' type='text' name='email' placeholder="email address"/>
            </p>

            <p>
                <input id='signUpInputUsername' class='ftype_contentA' type='text' name='username' placeholder="username"/>
            </p>

            <p>
                <input  id='signUpInputPassword' class='ftype_contentA' type='password' name='password' placeholder="password"/>
            </p>

            <div id='signUpSubmitText'>
                <span class='ftype_titleA1'>Sign</span><span class='ftype_titleA2'>Up</span><span
                    class='ftype_titleA3'>!</span>
            </div>
            <div id='signUpSubmitBox'>
                <div id='you'>
                    <img src='<?php echo _SYSTEM_BASE_URL . 'public/images/'; ?>SignUp1.png' alt='SignUp'/>
                </div>
                <div id='signUpped'>
                    <img src='<?php echo _SYSTEM_BASE_URL . 'public/images/'; ?>SignUp2.png' alt='SignUp'/>
                </div>
            </div>
        </form>
    </div>
</div>

<?php $this->printChunk('footer'); ?>
