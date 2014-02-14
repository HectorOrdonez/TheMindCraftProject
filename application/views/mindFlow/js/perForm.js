/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * Description: PerForm JS Library
 * Date: 13/02/14 23:00
 */
var baseHeight = 30;
var heightPerUnitPosition = 7.5;

function PerForm($element, callback) {
    // Step content
    var $workspace;
    var $perFormLayout;

    // PerForm column locations
    var $unlistedLoc;
    var $yesterdayLoc;
    var $todayLoc;
    var $tomorrowLoc;

    // Date helpers
    var yesterday;
    var today;
    var tomorrow;
    var afterTomorrow;

    // Info displayer
    var $infoDisplayer = jQuery('#infoDisplayer');

    // Action list container
    var actionList = [];

    /***********************************/
    /** Construct                     **/
    /***********************************/

    $workspace = $element;
    $perFormLayout = jQuery('#perFormLayout');
    $unlistedLoc = jQuery('#perFormUnlisted');
    $yesterdayLoc = jQuery('#perFormYesterday');
    $todayLoc = jQuery('#perFormToday');
    $tomorrowLoc = jQuery('#perFormTomorrow');

    initDates();
    buildLayout();

    loadData();

    /***********************************/
    /** Public functions              **/
    /***********************************/

    this.close = function (afterFadeOut) {
        $workspace.fadeOut(function () {
            $perFormLayout.hide();
            jQuery(actionList).each(function (index, action) {
                action.destroy();
            });
            $workspace.after($perFormLayout);
            $workspace.empty();
            afterFadeOut();
        });
    };

    /***********************************/
    /** Private functions             **/
    /***********************************/
    function initDates() {
        yesterday = new Date();
        today = new Date();
        tomorrow = new Date();
        afterTomorrow = new Date();

        // Removing time from today date
        today.setHours(6, 0, 0);

        yesterday.setDate(today.getDate() - 1);
        yesterday.setHours(6, 0, 0);
        tomorrow.setDate(today.getDate() + 1);
        tomorrow.setHours(6, 0, 0);
        afterTomorrow.setDate(today.getDate() + 2);
        afterTomorrow.setHours(0, 0, 0);
    }

    function buildLayout() {
        var $perFormLayout = jQuery('#perFormLayout');
        $perFormLayout.appendTo($workspace);
        $perFormLayout.show();
    }

    function loadData() {
        var url = root_url + 'mindFlow/getActions';

        jQuery.ajax({
            type: 'post',
            url: url
        }).done(
            function (dataList) {
                var i;
                var jsonObject = jQuery.parseJSON(dataList);

                for (i = 0; i < jsonObject.length; i++) {
                    var newAction = new Action(jsonObject[i]);
                    actionList.push(newAction);
                    setActionLocation(newAction);
                }

                callback();
            }
        ).fail(
            function () {
                setInfoMessage(jQuery('#infoDisplayer'), 'error', 'Data could not be load. Try again later.', 50000);
            }
        );
    }

    function setActionLocation(action) {
        var actionDateTime = getDateTimeFromString(action.date_todo, action.time_from);

        if (actionDateTime == '') {
            action.setAsDoable();
            action.showOn($unlistedLoc);
            return;
        }

        if (actionDateTime < yesterday) {
            var errMsg = 'This action [' + action.id + '] is set to be done in the past beyond yesterday';
            setInfoMessage($infoDisplayer, 'error', errMsg, 5000);
            console.error(errMsg);
            return;
        }

        if (actionDateTime < today) {
            action.setAsDoable();
            action.setPosition();
            action.showOn($yesterdayLoc);
            return;
        }

        if (actionDateTime < tomorrow) {
            action.setAsDoable();
            action.setPosition();
            action.showOn($todayLoc);
            return;
        }

        if (actionDateTime < afterTomorrow) {
            action.setPosition();
            action.showOn($tomorrowLoc);
            return;
        }

        var errMsg = 'This action [' + action.id + '] is set to be done in the future beyond tomorrow.';
        setInfoMessage($infoDisplayer, 'error', errMsg, 5000);
        console.error(errMsg);
    }
}

function Action(data) {
    /***********************************/
    /** Construct                     **/
    /***********************************/

    var self = this;
    this.id = data.id;
    this.routine_id = data.routine_id;
    this.title = data.title;
    this.date_creation = data.date_creation;
    this.date_todo = data.date_todo;
    this.time_from = data.time_from;
    this.time_till = data.time_till;
    this.date_done = data.date_done;
    this.important = data.important;
    this.urgent = data.urgent;
    var actionHTMLElement = buildActionHTML();

    /***********************************/
    /** Public functions              **/
    /***********************************/
    this.showOn = function ($location) {
        jQuery(actionHTMLElement).appendTo($location);
    };

    this.toggleDone = function () {
        if (self.date_done == '') {
            self.date_done = 'something';
            jQuery(actionHTMLElement).find('.perFormAction-button-done').addClass('done');
        } else {
            self.date_done = '';
            jQuery(actionHTMLElement).find('.perFormAction-button-done').removeClass('done');
        }
    };

    this.destroy = function () {
        jQuery(actionHTMLElement).remove();
    };

    this.setAsDoable = function () {
        var doableElement = document.createElement('span');
        doableElement.className = 'image perFormAction-button-done';
        jQuery(actionHTMLElement).find('.actionDo').append(doableElement);
        jQuery(doableElement).click(function () {
            self.toggleDone();
        });
    };

    this.setPosition = function() {
        var timeFromQuarters = timeToQuarters(self.time_from);
        var timeTillQuarters = timeToQuarters(self.time_till);
        var timeDuration = timeTillQuarters - timeFromQuarters;
        
        jQuery(actionHTMLElement).css('top', timeFromQuarters * heightPerUnitPosition);
        jQuery(actionHTMLElement).css('height', timeDuration * heightPerUnitPosition);
    };

    /***********************************/
    /** Private functions             **/
    /***********************************/

    function buildActionHTML() {
        var actionElement = document.createElement('div');
        actionElement.className = 'perFormAction';
        actionElement.innerHTML = "" +
            "<div class='perFormActionId'></div>" +
            "<div class='actionDo'></div>" +
            "<div class='perFormActionTitle'><span class='ftype_contentC'>" + self.title + "    </span></div>" +
            "<div class='actionExtras'>" +
            "</div>";

        if (self.important) {
            makeImportant(actionElement);
        }

        if (self.urgent) {
            makeUrgent(actionElement);
        }

        if (self.routine_id) {
            makePartOfRoutine(actionElement);
        }

        return actionElement;
    }

    function makePartOfRoutine(actionElement) {
        var routineElement = document.createElement('span');
        routineElement.className = 'image perFormAction-extra-routine';
        jQuery(actionElement).find('.actionExtras').append(routineElement);
    }

    function makeImportant(actionElement) {
        var importantElement = document.createElement('span');
        importantElement.className = 'image perFormAction-extra-important';
        jQuery(actionElement).find('.actionExtras').append(importantElement);
    }

    function makeUrgent(actionElement) {
        var urgentElement = document.createElement('span');
        urgentElement.className = 'image perFormAction-extra-urgent';
        jQuery(actionElement).find('.actionExtras').append(urgentElement);
    }
}


/************************************************************************************************/
/**     UTILITIES                                                                              **/
/************************************************************************************************/

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

function timeToQuarters(time)
{
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