/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description: Settings JS Library
 * Date: 25/07/13 01:30
 */

jQuery().ready(function () {
    jQuery('.change').click(function () {
        var $input = jQuery(this).parent().find('form').find('input');

        updateSetting($input);
    });

    jQuery('.formSetting').keypress(function (event) {
        if (event.which == 13) {
            var $input = jQuery(this).find('input');
            updateSetting($input);

            event.preventDefault();
        }
    });
});

/**
 * Function that runs an unique ajax call to update the setting that the user modified.
 * In order to make sure that only one request at a time is done, the uniqueUserRequest will verify that there is no pending Ajax request.
 * While this function is running, other unique ajax calls cannot be done.
 * Once this function ends, the callback is executed, which will allow further unique ajax calls.
 *
 * @param $input JQueried input
 */
function updateSetting($input) {
    var type = $input.attr('name');
    var value = $input.val();
    
    uniqueUserRequest(function (callback) {
        var $infoDiv = jQuery('#' + type + '_info');
        var $labelDiv = jQuery('#' + type + '_label');
        var $change = jQuery('#' + type + '_change');
        $change.addClass('processing');

        jQuery.ajax({
            type: 'post',
            url: root_url + 'settings/updateSetting',
            data: {
                'type': type,
                'newValue': value
            }
        }).done(function () {
                if (type != 'password') {
                    jQuery('span[data-field="username"]').html(value);
                }
                $input.val('');
                $change.removeClass('processing');
                callback();
                setInfoMessage($infoDiv, 'success', ':-)', 1000);
            }).fail(function (data) {
                $change.removeClass('processing');
                callback();
                setInfoMessage($infoDiv, 'error', data.statusText, 2000);
            });
    });
}