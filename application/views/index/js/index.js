/**
 * Project: Selfology
 * User: Hector Ordonez
 * Description:
 * Description: Index JS Library
 * Date: 23/07/13 10:00
 */
jQuery().ready(function () {
    jQuery('.loginInputName').focus();

    jQuery('#loginSubmit').click(function () {
        jQuery('#loginForm').submit();
    });

    jQuery('#loginForm').keypress(function (event) {
        if (event.which == 13) {
            jQuery('#loginForm').submit();
        }
    });
});