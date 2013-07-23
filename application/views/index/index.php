<div class='loginBlock'>
    <form action="<?php echo _SYSTEM_BASE_URL; ?>index/login" method="POST">
        <p>
            <label>
                User name
            </label>
            <input type='text' name='username'/>
        </p>

        <p>
            <label>
                Password
            </label>
            <input type='password' name='password'/>
        </p>

        <input type='submit' id='submit'/>
    </form>
</div>