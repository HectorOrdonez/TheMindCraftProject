/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * Description: Index JS Library
 * Date: 23/07/13 10:00
 */
var keyInside = '-30px';
var keyOutside = '30px';

jQuery().ready(function () {
    // Initializing parameters
    var $loginSubmitText = jQuery('#loginSubmitText');
    var $loginUpForm = jQuery('#loginForm');

    // On page start
    jQuery('#loginInputUsername').focus();

    // Adding Event listeners
    $loginSubmitText.click(function () {
        userLoginRequest();
    });

    $loginUpForm.keypress(function (event) {
        if (event.which == 13) {
            userLoginRequest();
        }
    });
});

/**
 * Called when User clicks LogIn or Enter.
 * Will request a Login to the Server with Ajax.
 */
function userLoginRequest() {
    uniqueUserRequest(function (callback) {

        var $key = jQuery('#key');
        $key.animate({
            'right': keyInside
        }, {complete: function () {
            login(function () {
                callback();
                jQuery('#key').animate({
                    'right': keyOutside
                });
            }); // Login request to server
        }}); // End Key Animation
    }); // End UniqueUserRequest
}

/**
 * @todo Add functionality and error manipulation here!
 * @param callback
 */
function login(callback) {
    var loginForm = jQuery('#loginForm');
    var url = loginForm.attr('action');
    var formArray = loginForm.serializeArray();
    var formData = {
        'username': formArray[0].value,
        'password': formArray[1].value
    };

    jQuery.ajax({
        type: 'post',
        url: url,
        data: formData,
        statusCode: {
            // Error code 400 means User did something wrong
            400: function (data) {
                var errors = jQuery.parseJSON(data.responseText);
                jQuery.each(errors, function (key, value) {
                    setInfoMessage(jQuery('#' + key + 'Error'), 'error', value, 3000);
                });
                callback();
            },
            // Error code 401 means User login is invalid
            401: function (data) {
                setInfoMessage(jQuery('#generalError'), 'error', data.responseText, 4000);
                callback();
            },
            // Error code 500 means, probably, that we did something wrong (Oh noes!%!??!)
            500: function (data) {
                setInfoMessage(jQuery('#generalError'), 'error', data.responseText, 5000);
                callback();
            }
        }
    }).done(function () {
            jQuery('#loginBlock').fadeOut(function () {
                jQuery('#loginConfirmation').fadeIn(function () {
                    setTimeout(function () {
                        window.location = root_url + '/main';
                    }, 1000);
                });
            });
        }
    );
}