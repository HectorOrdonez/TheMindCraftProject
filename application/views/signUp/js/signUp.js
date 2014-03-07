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
        'mail': formArray[0].value,
        'username': formArray[1].value,
        'password': formArray[2].value
    };

    jQuery.ajax({
        type: 'post',
        url: url,
        data: formData,
        statusCode: {
            // Error code 400 means User did something wrong
            400: function (data) {
                var errors = jQuery.parseJSON(data.responseText);
                jQuery.each(errors, function (key, brokenRule) {
                    var errorMessage;
                    switch (brokenRule)
                    {
                        case 'set':
                            errorMessage = 'This field is required';
                            break;
                        case 'minLength':
                            errorMessage = 'too short ' + key;
                            break;
                        case 'maxLength':
                            errorMessage = 'too long ' + key;
                            break;
                        case 'inUse':
                            errorMessage = 'this ' + key + ' is already in use';
                            break;
                        default:
                            errorMessage = 'Value not accepted.';
                            
                    }
                    setInfoMessage(jQuery('#' + key + 'Error'), 'error', errorMessage, 3000);
                });
                callback();
            },
            // Error code 500 means, probably, that we did something wrong (Oh noes!%!??!)
            500: function (data) {
                setInfoMessage(jQuery('#generalError'), 'error', data.responseText, 5000);
                callback();
            }
        }
    }).done(function () {
            jQuery('#signUpBody').fadeOut(function () {
                jQuery('#signUpConfirmation').fadeIn();
            });
        }
    );
}