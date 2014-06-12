/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * Description: Admin JS Library, only and exclusive for the amazing Admin crew!
 * Date: 23/01/14 19:30
 */

/**
 * Here the Admin sleeps;
 *      one Admin to rule them all,
 *      one Admin to find them,
 *      one Admin to bring them all and in this project bind them,
 *      in the land of MindCraft where the Ideas lie.
 * @type Admin
 * @todo This object should use data stored in Cookies - because things like 'noPendingUsersWarning' should be kept, page to page.
 *       Besides, this would avoid the Admin to check for pending Users at every page load.
 *       As an optional step to consider, the system should redefine the values every day - so in case the user sets noPendingUsersWarning to true, it would be reset.
 *
 *
 *
 */
var admin = null;

jQuery().ready(function () {
    admin = new Admin();
});

/**
 * Admin Object
 * @constructor
 */
function Admin() {
    /**
     * This constant defines how often the Admin will check whether or not there are pending users.
     * @type {number}
     */
    const checkPendingUsersEvery = (60 * 60) * 1000; // Amount in miliseconds. 60 (1 minute) * 60 (1 hour) * 1000 (seconds)
    /**
     * Whether or not the Admin wants to be told when there are pending users.
     * @type bool
     */
    var noPendingUsersWarning;

    /**
     * JQuery object that contains the div in which the admin messages have to be displayed
     * @type {*}
     */
    var $adminInfo = jQuery('#adminInfoDisplayer');


    /*****************************************************************************************************************/
    /** Admin construction                                                                                          **/
    /*****************************************************************************************************************/

    setTimeout(function () {
        checkForPendingUsers();
    }, 5000);

    /*****************************************************************************************************************/
    /** Private functions definition                                                                                **/
    /*****************************************************************************************************************/

    /**
     * Private function validateParameters
     * Validates the parameters received in the constructor in order to verify that this table can be constructed properly.
     */
    function checkForPendingUsers() {
        if (noPendingUsersWarning == true) return; // If noPendingUsersWarning is set to true, exit.

        var url = root_url + '/usersManagement/countPendingUsers';

        jQuery.ajax({
            type: 'post',
            url: url
        }).done(function (rawData) {
                var data = jQuery.parseJSON(rawData);
                if (data.pendingUsersAmount > 0) {
                    setInfoMessage($adminInfo, 'info', data.pendingUsersAmount + ' new user(s) pending...', 50000);
                }
            }
        );

        setTimeout(function () {
            checkForPendingUsers();
        }, checkPendingUsersEvery);
    }
}