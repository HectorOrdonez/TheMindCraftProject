/**
 * Project: Selfology
 * User: Hector Ordonez
 * Description:
 * General JS Library. Why, for general purposes!
 * Date: 25/07/13 1:30
 */

var root_url = 'http://192.168.192.13/projects/selfology';

jQuery().ready(function () {
    jQuery('#logo').click(function() {
        window.location.href = root_url;
    });
});
