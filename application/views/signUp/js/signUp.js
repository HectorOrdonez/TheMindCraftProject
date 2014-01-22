/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * Description: SignUp JS Library
 * Date: 22/01/14 15:30
 */
var youAlone = '75px';
var youWithUs = '30px';

jQuery().ready(function () {
    // Initializing parameters
    var $signUpSubmitText = jQuery('#signUpSubmitText');
    var $signUpForm = jQuery('#signUpForm');

    // On page start
    jQuery('#signUpInputEmail').focus();

    // Adding Event listeners
    $signUpSubmitText.click(function () {
        userSignUpRequest();
    });

    $signUpForm.keypress(function (event) {
        if (event.which == 13) {
            userSignUpRequest()
        }
    });
});

/**
 * Called when User clicks SignUp or Enter.
 * Will request a Sign Up to the Server with Ajax.
 */
function userSignUpRequest() {
    var $you = jQuery('#you');

    uniqueUserRequest(function (callback) {
        $you.animate({
            'right': youWithUs
        }, {complete: function () {
            signUp(function () {
                callback();
                $you.animate({
                    'right': youAlone
                });
            });
        }});

    });
}

/**
 * @todo Add functionality and error manipulation here!
 * @param callback
 */
function signUp(callback) {
    var $signUpForm = jQuery('#signUpForm');
    var url = $signUpForm.attr('action');
    var formArray = $signUpForm.serializeArray();
    var formData = {
        'email': formArray[0].value,
        'username': formArray[1].value,
        'password': formArray[2].value
    };

    jQuery.ajax({
        type: 'post',
        url: url,
        data: formData
    }).done(function () {
            // @todo Congrats, you've been registered. You'll find a mail and bla.
            callback();
        }
    ).fail(function (data) {
            // @todo Show error elements, wherever they are (more than one might be shown!)
            callback();
        }
    );
}