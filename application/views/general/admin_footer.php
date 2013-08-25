<?php
/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * View chunk for the admin footer.
 * Date: 25/07/13 02:00
 */
?>

            </div>
            <!-- Closing logged-in Content -->

        </div>
    </div>

    <div id='admin_footer'>
        <div id="adminWelcome">
            <div id='captainAdminHere'></div>
            <div class='font_title' id='helloMe'>Hello, <?php echo $this->myName; ?>!</div>
        </div>
        <div id="adminActions">
            <a class='admin_option usersManagement' href="<?php echo _SYSTEM_BASE_URL; ?>usersManagement"></a>
            <a class='admin_option releaseHistory' href="<?php echo _SYSTEM_BASE_URL; ?>releaseHistory"></a>
        </div>
    </div>

</body>
</html>