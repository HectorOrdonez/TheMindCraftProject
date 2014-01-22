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
    var $key = jQuery('#key');
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
    
    callback();
}