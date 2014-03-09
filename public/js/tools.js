/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * Utilities.
 * Date: 16/02/14 20:10
 */

/**
 * Amount of hour quarters that an hour has. 
 * @type {number}
 */
var quartersPerHour = 4;

/**
 * Boolean global variable that allows us to know in any place if there is an ongoing ajax request.
 * @type {boolean}
 */
var ajaxInProgress = false;

/**
 * String containing the date in format dd/mm/yy.
 * Example: 14/04/2014
 * @param string
 */

function getDateFromString(string) {
    if (typeof(string) == 'undefined') {
        return '';
    }
    var pieces = string.split('/');
    var date = new Date(pieces[2], (pieces[1] - 1), pieces[0]);

    if (date == 'Invalid Date') {
        return '';
    }
    return date;
}
/**
 * String containing the date in format dd/mm/yy.
 * String containing the time in format hh:mm.
 * Example: 14/04/2014 14:00
 * @param date
 * @param time
 */

function getDateTimeFromString(date, time) {
    if (typeof(date) == 'undefined' || typeof(time) == 'undefined') {
        return '';
    }

    var datePieces = date.split('/');
    var timePieces = time.split(':');
    var dateTime = new Date(datePieces[2], (datePieces[1] - 1), datePieces[0], timePieces[0], timePieces[1]);

    if (dateTime == 'Invalid Date') {
        return '';
    }
    return dateTime;
}

/**
 * Receives two times string and verifies:
 * - Both are either empty string or filled.
 * - Time from is after time till
 * @param time_from
 * @param time_till
 */
function validateTimeFrame(time_from, time_till) {
    if ('' != time_from && '' != time_till) {
        if (time_from >= time_till) {
            throw 'From time has to be before Till time!';
        }
    } else if ('' != time_from || '' != time_till) {
        throw 'A time frame needs both from and till being set.';
    }
    
    if ( (timeToQuarters(time_till) - timeToQuarters(time_from)) < quartersPerHour)
    {
        throw 'Defined time frame must be of, at least, one hour.';
    }
    
}

/**
 * Receives two dates and verifies:
 *  - Are either empty string '' or Date instances
 *  - If both are defined, date start must be after date finish.
 *  
 * @param date_start
 * @param date_finish
 */
function validateDateFrame(date_start, date_finish) {
    if ('' != date_start && !(date_start instanceof Date)) {
        throw 'Start date is in a wrong format.';
    }
    if ('' != date_finish && !(date_finish instanceof Date)) {
        throw 'Finish date is in a wrong format.';
    }
    
    if ('' != date_start && '' != date_finish) {
        if (date_start > date_finish) {
            throw 'Starting date must be after finishing one!';
        }
    }
}

/**
 * Calculates giving a time in format 15:30 the amount of quarters it has, and returns it.
 * @param time
 * @returns {number}
 */
function timeToQuarters(time) {
    var timePieces = time.split(':');
    var hour = parseInt(timePieces[0]);
    var minutes = parseInt(timePieces[1]);

    // Base time is 06:00, so counting from there.
    var hourAux = hour - 6;

    // Calculating total amount of minutes
    var totalMinutes = hourAux * 60 + minutes;

    // And finally calculating amount of hour quarters this time has.    
    return (totalMinutes / 15);
}

/**
 * Checks if there is an Ajax request in progress. In that case returns false. Otherwise, runs the function.
 * The function being run needs to call the callback function that is being passed. Otherwise it will not allow further ajax requests.
 *
 * @param {function} uniqueFunction
 */
function uniqueUserRequest(uniqueFunction)
{
    if (ajaxInProgress == true){
        console.error('Please, be patient. A request is being processed.');
    }
    else {
        ajaxInProgress = true;
        uniqueFunction(function(){closeAjaxInProgress()});
    }
}

/**
 * Function that is passed as callback to all uniqueUserRequest requests. Once this method is called as callback, new unique ajax calls can be done.
 */
function closeAjaxInProgress(){
    ajaxInProgress = false;
}

/**
 * This function receives a JQuery element where has to be displayed a message.
 * @param $infoDiv - JQuery Element where the message will be displayed.
 * @param type - Type of the message (success | error | info) that defines the font to be used.
 * @param message - Message to display.
 * @param timeout - Time in mils of second that the message will remain displayed.
 */
var messageTimeout = [];
function setInfoMessage($infoDiv, type, message, timeout) {
    var infoMessageKey = $infoDiv.selector;
    
    if (typeof(messageTimeout[infoMessageKey]) === 'undefined')
    {
        // No info message. Adding types.
        $infoDiv.addClass('ftype_' + type + 'A');
        $infoDiv.html(message);
        
        // Setting Timeout
        var setTimeoutFunction = setTimeout(function () {
            $infoDiv.fadeOut(function () {
                $infoDiv.html('');
                $infoDiv.removeClass('ftype_' + type + 'A');
                $infoDiv.fadeIn();
            });
        }, timeout);
        
        // Caching for further info refreshes
        messageTimeout[infoMessageKey] = {
            'setTimeout': setTimeoutFunction,
            'previousType': type
        };
    } else {
        // Refreshing error message
        clearInterval(messageTimeout[infoMessageKey].setTimeout);
        $infoDiv.removeClass('ftype_' + messageTimeout[infoMessageKey].type + 'A');
        $infoDiv.html('');
        delete messageTimeout[infoMessageKey];
        
        setInfoMessage($infoDiv, type, message, timeout);
    }
}