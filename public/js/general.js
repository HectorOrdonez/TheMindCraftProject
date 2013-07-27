/**
 * Project: Selfology
 * User: Hector Ordonez
 * Description:
 * General JS Library. Why, for general purposes!
 * Date: 25/07/13 1:30
 */

/**
 * Definition of the Root Url to use in links.
 * @type {string}
 */
var root_url = 'http://192.168.192.13/projects/selfology/';

/**
 * Boolean global variable that allows us to know in any place if there is an ongoing ajax request.
 * @type {boolean}
 */
var ajaxInProgress = false;

/**
 * Checks if there is an Ajax request in progress. In that case returns false. Otherwise, runs the function.
 * The function being run needs to call the callback function that is being passed. Otherwise it will not allow further ajax requests.
 *
 * @param {function} uniqueFunction
 */
function uniqueAjaxCall(uniqueFunction)
{
    if (ajaxInProgress == true){
        alert('Please, be patient. A request is being processed.');
    }
    else {
        ajaxInProgress = true;
        uniqueFunction(function(){closeAjaxInProgress()});
    }
}

/**
 * Function that is passed as callback to all uniqueAjaxCall requests. Once this method is called as callback, new unique ajax calls can be done.
 */
function closeAjaxInProgress(){
    ajaxInProgress = false;
}

/**
 * On Document Ready...
 */
jQuery().ready(function () {
    /**
     * Sets Logo to be a link to the main page.
     */
    jQuery('#logo').click(function() {
        window.location.href = root_url;
    });
});