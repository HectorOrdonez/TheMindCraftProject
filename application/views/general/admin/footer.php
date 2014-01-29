<?php
/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * View chunk for the admin footer.
 * Date: 25/07/13 02:00
 *
 * @var \application\engine\View $this
 */
?>

</div>
<!-- Closing logged-in Content -->

<div id='footer'>
</div>

<div id='admin_footer'>
    <div id="adminWelcome">
        <div id='captainAdminHere'></div>
        <div class='' id='helloMe'>
            <span class='ftype_titleA1'>Hello, </span><span class='ftype_titleA2'><?php echo $this->getParameter('userName'); ?></span><span class='ftype_titleA3'>!</span>
        </div>
    </div>
    <div id="adminActions">
        <a class='admin_option usersManagement' href="<?php echo _SYSTEM_BASE_URL; ?>usersManagement"></a>
        <a class='admin_option releaseHistory' href="<?php echo _SYSTEM_BASE_URL; ?>releaseHistory"></a>
    </div>
</div>

</body>
</html>