/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * Description: PerForm JS Library
 * Date: 13/02/14 23:00
 */
var baseHeight = 60;
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
    $unlistedLoc = jQuery('#perFormPool');
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
        today.setHours(5, 59, 0);

        yesterday.setDate(today.getDate() - 1);
        yesterday.setHours(5, 59, 0);
        tomorrow.setDate(today.getDate() + 1);
        tomorrow.setHours(5, 59, 0);
        afterTomorrow.setDate(today.getDate() + 2);
        afterTomorrow.setHours(5, 59, 0);
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
        var errMsg;
        
        if (actionDateTime == '') {
            action.setAsDoable();
            action.showImportance();
            action.showUrgency();
            
            action.showOn($unlistedLoc);
            return;
        }

        if (actionDateTime < yesterday) {
            errMsg = 'This action [' + action.id + '] is set to be done in the past beyond yesterday';
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
            action.showImportance();
            action.showUrgency();
            action.showRoutineAction();
            
            action.setPosition();
            action.showOn($todayLoc);
            return;
        }

        if (actionDateTime < afterTomorrow) {
            action.setPosition();
            action.showOn($tomorrowLoc);
            return;
        }

        errMsg = 'This action [' + action.id + '] is set to be done in the future beyond tomorrow.';
        setInfoMessage($infoDisplayer, 'error', errMsg, 5000);
        console.error(errMsg);
    }
}

/**
 * Action object.
 *
 * Public methods
 *  showOn($location) - Appends this Action html in the indicated place.
 *  toggleDone() - Requests to server to toggle this action done state. Changes the related Mark state.
 *  setAsDoable() - Allows this action to be set as done or undone, and therefore shows a button in its left side.
 *  setPosition - Requests this Action to be set in the column depending on its time_from and time_till.
 *  destroy - Removes this action html.
 *
 *  Private methods
 *      buildActionHTML - Builds the basic Action HTML with startup data.
 *      makePartOfRoutine - Indicates this Action as part of a routine, showing the Routine image at right.
 *      makeImportant - Indicates this Action as important, showing the Important image at right.
 *      makeUrgent - Indicates this Action as urgent, showing the Urgent image at right.
 *
 * @param data Related Action data.
 * @constructor
 */
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
        uniqueUserRequest(function (callback) {
            var url = root_url + 'mindFlow/toggleActionDoneState';
            var data = {'id': self.id};

            // Request to the Server
            jQuery.ajax({
                type: 'post',
                url: url,
                data: data
            }).done(function (data) {
                    var jsonObject = jQuery.parseJSON(data);
                    self.date_done = jsonObject['date_done'];
                    // Toggling succeed in server-side. Proceeding in client-side.
                    var $actionDoneButton = jQuery(actionHTMLElement).find('.mindCraft-ui-button-checkbox');

                    if ($actionDoneButton.hasClass('mark')) {
                        $actionDoneButton.removeClass('mark');
                    } else {
                        $actionDoneButton.addClass('mark');
                    }
                    callback();
                }).fail(function (data) {
                    var $infoDisplayer = jQuery('#infoDisplayer');
                    setInfoMessage($infoDisplayer, 'error', data.statusText, 2000);
                    callback();
                });
        });
    };

    this.setAsDoable = function () {
        var actionDo = document.createElement('div');
        actionDo.className = 'actionDo';
            
        var doableElement = document.createElement('span');
        var doableClasses = 'mindCraft-ui-button mindCraft-ui-button-checkbox clickable';
        doableElement.className = (self.date_done == '') ? doableClasses : doableClasses + ' mark';
        
        actionDo.appendChild(doableElement);
        
        jQuery(actionDo).insertBefore(jQuery(actionHTMLElement).find('.perFormActionId'));
        
        jQuery(doableElement).click(function () {
            self.toggleDone();
        });
    };
    this.showImportance = function () {
        if (self.important) {
            makeImportant();
        }
    };
    this.showUrgency = function () {
        if (self.urgent) {
            makeUrgent();
        }
    };
    this.showRoutineAction = function () {
        if (self.routine_id) {
            makeRoutineAction();
        }
    };

    this.setPosition = function () {
        var timeFromQuarters = timeToQuarters(self.time_from);
        var timeTillQuarters = timeToQuarters(self.time_till);
        var timeDuration = timeTillQuarters - timeFromQuarters;

        jQuery(actionHTMLElement).css('top', baseHeight + timeFromQuarters * heightPerUnitPosition);
        jQuery(actionHTMLElement).css('height', timeDuration * heightPerUnitPosition);
    };

    this.destroy = function () {
        jQuery(actionHTMLElement).remove();
    };

    /***********************************/
    /** Private functions             **/
    /***********************************/

    function buildActionHTML() {
        var actionElement = document.createElement('div');
        actionElement.className = 'perFormAction';
        actionElement.innerHTML = "" +
            "<div class='perFormActionId'></div>" +
            "<div class='actionExtras'></div>" + 
            "<div class='actionTitle'><div class='titleTextWrapper'><span class='ftype_contentB'>" + self.title + "    </span></div></div>";
        
        return actionElement;
    }

    function makeRoutineAction() {
        var routineElement = document.createElement('span');
        routineElement.className = 'mindCraft-ui-button mindCraft-ui-button-circular';
        jQuery(actionHTMLElement).find('.actionExtras').append(routineElement);
    }

    function makeImportant() {
        var importantElement = document.createElement('span');
        importantElement.className = 'mindCraft-ui-button mindCraft-ui-button-important';
        jQuery(actionHTMLElement).find('.actionExtras').append(importantElement);
    }

    function makeUrgent() {
        var urgentElement = document.createElement('span');
        urgentElement.className = 'mindCraft-ui-button mindCraft-ui-button-urgent';
        jQuery(actionHTMLElement).find('.actionExtras').append(urgentElement);
    }
}