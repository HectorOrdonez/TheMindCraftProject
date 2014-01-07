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

    <div id='admin_footer'>
        <div id="adminWelcome">
            <div id='captainAdminHere'></div>
            <div class='' id='helloMe'>Hello, <?php echo $this->getParameter('userName'); ?>!</div>
        </div>
        <div id="adminActions">
            <a class='admin_option usersManagement' href="<?php echo _SYSTEM_BASE_URL; ?>usersManagement"></a>
            <a class='admin_option releaseHistory' href="<?php echo _SYSTEM_BASE_URL; ?>releaseHistory"></a>
        </div>
    </div>

</body>
</html>