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
            action.showOnPool($unlistedLoc);
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
            action.showOnDay($yesterdayLoc);
            return;
        }

        if (actionDateTime < tomorrow) {
            action.setAsDoable();
            action.showImportance();
            action.showUrgency();
            action.showRoutineAction();
            action.showOnDay($todayLoc);
            return;
        }

        if (actionDateTime < afterTomorrow) {
            action.showOnDay($tomorrowLoc);
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

    /**
     * Appends the action html to the given day location and, after, sets the position.
     * @param $location
     */
    this.showOnDay = function ($location) {
        jQuery(actionHTMLElement).appendTo($location);
        setPosition();
    };

    /**
     * Appends the action html to the action pool.
     * @param $pool
     */
    this.showOnPool = function ($pool) {
        jQuery(actionHTMLElement).appendTo($pool);
    };

    /**
     * Requests the action done state to be toggled to the Server.
     */
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

    /**
     * Adds a checkbox to the Action to toggle its done status.
     */
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

    /**
     * Makes the importance icon to be shown, if this action is important.
     */
    this.showImportance = function () {
        if (self.important) {
            makeImportant();
        }
    };

    /**
     * Makes the urgency icon to be shown, if this action is urgency.
     */
    this.showUrgency = function () {
        if (self.urgent) {
            makeUrgent();
        }
    };
    
    /**
     * Makes the routine icon to be shown, if this action is part of a routine.
     */
    this.showRoutineAction = function () {
        if (self.routine_id) {
            makeRoutineAction();
        }
    };

    /**
     * Destroys the action element.
     */
    this.destroy = function () {
        jQuery(actionHTMLElement).remove();
    };

    /**
     * Requests this action to be deleted to the Server.
     */
    this.delete = function () {
        var $infoDisplayer = jQuery('#infoDisplayer');
        var url = root_url + 'mindFlow/deleteAction';
        var data = {
            'id': self.id
        };

        jQuery.ajax({
            type: 'post',
            url: url,
            data: data
        }).done(function () {
                jQuery(actionHTMLElement).fadeOut(function(){
                    self.destroy();
                });
            }
        ).fail(function (data) {
                setInfoMessage($infoDisplayer, 'error', data.statusText, 2000);
            }
        );
    };

    /***********************************/
    /** Private functions             **/
    /***********************************/

    /**
     * Builds and return the action html.
     * @returns {HTMLElement}
     */
    function buildActionHTML() {
        // Action element
        var actionElement = document.createElement('div');
        actionElement.className = 'perFormAction';
        actionElement.innerHTML = "" +
            "<div class='perFormActionId'></div>" +
            "<div class='actionExtras'></div>" +
            "<div class='actionDelete'></div>" +
            "<div class='actionTitle'><div class='titleTextWrapper'></div></div>";
        
        // Title element 
        var titleElement = document.createElement('p');
        titleElement.className = 'ftype_contentB';
        titleElement.innerHTML = self.title;
        
        // Delete element
        var deleteElement = document.createElement('span');
        deleteElement.className = 'mindCraft-ui-button mindCraft-ui-button-delete clickable';
        
        // Coupling components
        jQuery(actionElement).find('.titleTextWrapper').append(titleElement);
        jQuery(actionElement).find('.actionDelete').append(deleteElement);
        
        // Attaching listeners
        jQuery(actionElement).find('.actionTitle').click(function(){
            jQuery(deleteElement).parent().toggle();
            toggleFullTextDisplay();
        });
        
        jQuery(deleteElement).click(function(){
            self.delete();
        });
        
        return actionElement;
    }
    
    function toggleFullTextDisplay()
    {
        jQuery(actionHTMLElement).toggleClass('fullText');
    }

    /**
     * Positions this action in the day.
     */
    function setPosition () {
        var timeFromQuarters = timeToQuarters(self.time_from);
        var timeTillQuarters = timeToQuarters(self.time_till);
        var timeDuration = timeTillQuarters - timeFromQuarters;

        jQuery(actionHTMLElement).css('top', baseHeight + timeFromQuarters * heightPerUnitPosition);
        jQuery(actionHTMLElement).css('height', timeDuration * heightPerUnitPosition);
    }

    /**
     * Displays the routine icon.
     */
    function makeRoutineAction() {
        var routineElement = document.createElement('span');
        routineElement.className = 'mindCraft-ui-button mindCraft-ui-button-circular';
        jQuery(actionHTMLElement).find('.actionExtras').append(routineElement);
    }

    /**
     * Displays the important icon.
     */
    function makeImportant() {
        var importantElement = document.createElement('span');
        importantElement.className = 'mindCraft-ui-button mindCraft-ui-button-important mark';
        jQuery(actionHTMLElement).find('.actionExtras').append(importantElement);
    }

    /**
     * Displays the urgent icon.
     */
    function makeUrgent() {
        var urgentElement = document.createElement('span');
        urgentElement.className = 'mindCraft-ui-button mindCraft-ui-button-urgent mark';
        jQuery(actionHTMLElement).find('.actionExtras').append(urgentElement);
    }
}