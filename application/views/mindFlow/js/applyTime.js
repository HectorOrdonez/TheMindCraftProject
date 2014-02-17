/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * Description: ApplyTime JS Library
 * Date: 11/01/14 14:30
 */
var applyTimeData = {};

function ApplyTime($element, callback) {
    // Step content
    var $workspace;
    var $showRoutines;
    var table;

    var missionDialog;
    var routineDialog;

    /***********************************/
    /** Construct                     **/
    /***********************************/

    $workspace = $element;
    $showRoutines = jQuery('#showRoutinesWrapper');
    $workspace.html(builtStepContent());
    $showRoutines.appendTo($workspace);
    $showRoutines.find('span').click(function () {
        toggleShowRoutines($showRoutines);
    });
    builtGrid(callback);

    /***********************************/
    /** Public functions              **/
    /***********************************/

    this.close = function (afterFadeOut) {
        $workspace.fadeOut(
            function () {
                $showRoutines.find('span').unbind('click');
                $workspace.after($showRoutines);
                $showRoutines.hide();
                applyTimeData = {};
                $workspace.empty();
                afterFadeOut();
            }
        );
    };

    /***********************************/
    /** Private functions             **/
    /***********************************/

    /**
     * ApplyTime loader
     * @param table
     * @param callback
     */
    function loadApplyTime(table, callback) {
        var url = root_url + 'mindFlow/getIdeas';
        var data = {step: 'applyTime'};

        jQuery.ajax({
            type: 'post',
            url: url,
            data: data
        }).done(
            function (dataList) {
                var i, data;
                var jsonObject = jQuery.parseJSON(dataList);

                for (i = 0; i < jsonObject['missions'].length; i++) {
                    applyTimeData[jsonObject['missions'][i]['id']] = jsonObject['missions'][i];
                    applyTimeData[jsonObject['missions'][i]['id']].type = 'mission';
                }
                for (i = 0; i < jsonObject['routines'].length; i++) {
                    applyTimeData[jsonObject['routines'][i]['id']] = jsonObject['routines'][i];
                    applyTimeData[jsonObject['routines'][i]['id']].type = 'routine';
                }

                for (var ideaId in applyTimeData) {
                    if (applyTimeData[ideaId].type == 'routine' &&
                        applyTimeData[ideaId].selected == false) {
                        continue;
                    }
                    data = {
                        id: applyTimeData[ideaId].id,
                        type: applyTimeData[ideaId].type,
                        title: applyTimeData[ideaId].title
                    };
                    table.addContentData(data);
                }

                initShowRoutinesOption(jsonObject['routines']);
                callback();
            }
        ).fail(
            function () {
                setInfoMessage(jQuery('#infoDisplayer'), 'error', 'Data could not be load. Try again later.', 50000);
            }
        );
    }

    function initShowRoutinesOption(routineObjects) {
        var $showRoutines = jQuery('#showRoutinesWrapper');
        var allRoutinesShown = true;

        if (0 != routineObjects.length) {
            $showRoutines.show();
        }

        for (var i = 0; i < routineObjects.length; i++) {
            if (routineObjects[i].selected == false) {
                allRoutinesShown = false;
            }
        }

        if (allRoutinesShown) {
            $showRoutines.find('span').first().addClass('mark');
        } else {
            $showRoutines.find('span').first().removeClass('mark');
        }
    }

    /**
     * Toggles the routine vision to shown or hidden.
     * @param $showRoutines
     */
    function toggleShowRoutines($showRoutines) {
        uniqueUserRequest(function (callback) {
            var i;
            var $mark = $showRoutines.find('span').first();
            var toggleTo = ($mark.hasClass('mark')) ? 'hide' : 'show';

            var url = root_url + 'mindFlow/toggleShowRoutines';
            var data = {'to': toggleTo};

            // Request to the Server
            jQuery.ajax({
                type: 'post',
                url: url,
                data: data
            }).done(function () {
                    if (toggleTo == 'show') {
                        for (var ideaId in applyTimeData) {
                            if (applyTimeData[ideaId].type == 'routine' && applyTimeData[ideaId].selected == false) {
                                applyTimeData[ideaId].selected = true;

                                var data = {
                                    id: applyTimeData[ideaId].id,
                                    type: applyTimeData[ideaId].type,
                                    title: applyTimeData[ideaId].title
                                };
                                table.addContentData(data);
                            }
                        }
                        $mark.addClass('mark');
                    } else {
                        for (var ideaId in applyTimeData) {
                            if (applyTimeData[ideaId].type == 'routine' && applyTimeData[ideaId].selected == true) {
                                applyTimeData[ideaId].selected = false;
                                table.removeContentId(findRowByIdeaId($workspace, ideaId));
                            }
                        }
                        $mark.removeClass('mark');
                    }

                    callback();
                }).fail(function (data) {
                    var $infoDisplayer = jQuery('#infoDisplayer');
                    setInfoMessage($infoDisplayer, 'error', data.statusText, 2000);
                    callback();
                });
        });
    }

    /**
     * Step Content for Selection section
     * @returns {string}
     */
    function builtStepContent() {
        return "<div class='mindFlowGrid' id='applyTimeGrid'></div>";
    }

    /**
     * ApplyTime grid constructor
     * @param callback
     */
    function builtGrid(callback) {
        // JQuery variable that stores the grid.
        var $grid = jQuery('#applyTimeGrid');

        // ApplyTime header construction
        var headerRow = new Row(
            {'cells': [
                new Cell({'html': 'id', 'classList': ['col_id']}),
                new Cell({'html': 'type', 'classList': ['col_type']}),
                new Cell({'html': 'title', 'classList': ['col_title', 'ftype_titleC']}),
                new Cell({'html': '', 'classList': ['col_actions']}),
                new Cell({'html': 'time', 'classList': ['col_time', 'ftype_titleC', 'centered']})
            ],
                'classList': ['header']
            });

        // ApplyTime Table construction
        table = new Table('applyTimeGrid', {
            colModel: [
                {colIndex: 'id'},
                {colIndex: 'type'},
                {colIndex: 'title', classList: ['ftype_contentA']},
                {colIndex: 'actions', customContent: function (row) {
                    var setTodo = '<div class="action"><a class="mindCraft-ui-button mindCraft-ui-button-timing multi clickable">' + row.id + '</a></div>';
                    var setRoutine = '<div class="action"><a class="mindCraft-ui-button mindCraft-ui-button-circular multi clickable">' + row.id + '</a></div>';
                    return '<div class="actionBox">' + setTodo + setRoutine + '</div>';
                }},
                {colIndex: 'time', classList: ['centered', 'ftype_contentA'], customContent: function (row) {
                    if (row.type != 'mission') {
                        return 'R';
                    }
                    return applyTimeData[row.id].date_todo + ' ' + applyTimeData[row.id].time_from;
                }}
            ]});
        table.addHeaderElement(headerRow.toHTML());

        // Loading Table
        loadApplyTime(table, callback);

        // Creating Mission Dialog object
        routineDialog = new RoutineDialog();
        missionDialog = new MissionDialog(routineDialog);

        // Add Event Listeners
        $grid.delegate('.mindCraft-ui-button-timing', 'click', function () {
            missionDialog.open(jQuery(this).closest('.row'), getDataFromTable(jQuery(this).html()));
        });
        $grid.delegate('.mindCraft-ui-button-circular', 'click', function () {
            routineDialog.open('new', jQuery(this).closest('.row'), getDataFromTable(jQuery(this).html()));
        });
    }
} // End ApplyTime Object

/**
 * The MissionDialog object
 * @param routineDialog To be opened when clicked on MoreOften button.
 * @constructor
 */
function MissionDialog(routineDialog) {
    var assignedTableRow;
    var $datePicker;
    var $todoElement;
    var $dialogElement;
    var $fromHoursSelector;
    var $fromMinSelector;
    var $tillHoursSelector;
    var $tillMinSelector;
    var $applyTimeOverlay;
    var $submitTodo;
    var $moreOftenAction;
    var self = this;
    var previousData;
    var currentData;
    var $infoDisplayer;

    // Construct Mission Dialog
    initialize();

    function initialize() {
        // Initializing
        $datePicker = jQuery('#datePicker');
        $todoElement = jQuery('#setTodoDialogWrapper');
        $dialogElement = jQuery('#applyTimeDialog');
        $fromHoursSelector = jQuery('#todoFromHoursSelector');
        $fromMinSelector = jQuery('#todoFromMinutesSelector');
        $tillHoursSelector = jQuery('#todoTillHoursSelector');
        $tillMinSelector = jQuery('#todoTillMinutesSelector');
        $applyTimeOverlay = jQuery('#applyTimeOverlay');
        $submitTodo = jQuery('#submitTodo');
        $moreOftenAction = jQuery('#moreOftenAction');
        $infoDisplayer = jQuery('#setTodoInfo');

        // Initialize date picker
        $datePicker.datepicker({
            dateFormat: 'dd/mm/yy',
            firstDay: 1,
            showOtherMonths: true,
            dayNamesMin: ['S', 'M', 'T', 'W', 'T', 'F', 'S'],
            afterDisplay: function () {
                // Making odd cells being... odd!
                var oddHelper = 1;
                jQuery.each($datePicker.find('td'), function (index, element) {
                    if (1 === oddHelper % 2) {
                        jQuery(element).addClass('odd');
                    }
                    oddHelper++;
                });
            }
        });
    }

    function setMissionParameters(data) {
        previousData = data;
        currentData = jQuery.extend({}, previousData);
        currentData.type = 'mission';
        var startDate = getDateFromString(previousData.date_todo);
        $datePicker.datepicker("setDate", startDate);

        initTimeSelector('hours', $fromHoursSelector, previousData.time_from);
        initTimeSelector('minutes', $fromMinSelector, previousData.time_from);
        initTimeSelector('hours', $tillHoursSelector, previousData.time_till);
        initTimeSelector('minutes', $tillMinSelector, previousData.time_till);
    }

    function validate() {
        validateTimeFrame(currentData.time_from, currentData.time_till);
    }

    function bindMissionEvents() {
        // Binding events
        $applyTimeOverlay.click(function () {
            self.close('full');
        });

        $fromHoursSelector.click(function () {
            switchHoursSelector($fromHoursSelector, $fromHoursSelector.html());
            if ($fromHoursSelector.html() == '23') {
                switchHoursSelector($fromHoursSelector, $fromHoursSelector.html());
            }
            $tillHoursSelector.html($fromHoursSelector.html());
            switchHoursSelector($tillHoursSelector, $tillHoursSelector.html());
        });

        $tillHoursSelector.click(function () {
            switchHoursSelector($tillHoursSelector, $tillHoursSelector.html());
        });

        $dialogElement.find('.inputs .minutes').click(function () {
            switchMinutesSelector(jQuery(this), jQuery(this).html());
        });

        $moreOftenAction.click(function () {
            currentData.time_from = getTime($fromHoursSelector, $fromMinSelector);
            currentData.time_till = getTime($tillHoursSelector, $tillMinSelector);
            self.close('partial');
        });
        $submitTodo.click(function () {
            self.submit();
        });
    }

    function unbindMissionEvents() {
        $applyTimeOverlay.unbind('click');
        $dialogElement.find('.inputs div').unbind('click');
        $moreOftenAction.unbind('click');
        $submitTodo.unbind('click');
    }

    /**
     * Opens the dialog
     * @param tableRow Related row, to be updated if MissionDialog updates the idea.
     * @param data Current idea data
     */
    this.open = function (tableRow, data) {
        assignedTableRow = tableRow;

        // Requires idea data
        setMissionParameters(data);

        // Show dialog
        $todoElement.css('display', 'block');
        $dialogElement.fadeIn();

        bindMissionEvents();
    };

    /**
     * Closes the dialog
     * @param closureType
     */
    this.close = function (closureType) {
        if (closureType == 'full') {
            $dialogElement.fadeOut(function () {
                unbindMissionEvents();
                $todoElement.css('display', 'none');
            });
        } else {
            $todoElement.fadeOut(function () {
                unbindMissionEvents();
                routineDialog.open('renewed', assignedTableRow, currentData);
            });
        }
    };

    /**
     * Submits the dialog
     */
    this.submit = function () {
        try {
            // Collects new idea data
            currentData.date_todo = $datePicker.val();
            currentData.time_from = getTime($fromHoursSelector, $fromMinSelector);
            currentData.time_till = getTime($tillHoursSelector, $tillMinSelector);

            // In case no data is changed, close dialog directly.
            if (previousData.date_todo == currentData.date_todo &&
                previousData.time_from == currentData.time_from &&
                previousData.time_till == currentData.time_till) {
                $applyTimeOverlay.click();
                return;
            }

            validate();

            jQuery.ajax({
                type: 'post',
                url: root_url + 'mindFlow/setMissionDateTime',
                data: currentData
            }).done(function () {
                    setDataFromTable(assignedTableRow, currentData);
                    $applyTimeOverlay.click();
                }
            ).fail(function (data) {
                    throw data.statusText;
                }
            );
        } catch (err) {
            this.showError(err);
        }
    };

    /**
     * Shows a mission dialog error
     * @param err
     */
    this.showError = function (err) {
        setInfoMessage($infoDisplayer, 'error', err, 5000);
    };
}

/**
 * The RoutineDialog object
 * @constructor
 */
function RoutineDialog() {
    var assignedTableRow;
    var $startInputDate;
    var $finishInputDate;
    var $routineElement;
    var $weeklyRepetitionSelector;
    var $weekdaysSelector;
    var $dialogElement;
    var $fromHoursSelector;
    var $fromMinSelector;
    var $tillHoursSelector;
    var $tillMinSelector;
    var $applyTimeOverlay;
    var $submitRoutine;
    var self = this;
    var previousData;
    var currentData;
    var $infoDisplayer;

    // Construct Mission Dialog
    initialize();

    function initialize() {
        // Initializing parameters
        $startInputDate = jQuery('#startDate');
        $finishInputDate = jQuery('#finishDate');
        $routineElement = jQuery('#setRoutineDialogWrapper');
        $weeklyRepetitionSelector = jQuery('#weeklyRepetitionSelector');
        $weekdaysSelector = jQuery('#weekdaysSelectionWrapper');
        $dialogElement = jQuery('#applyTimeDialog');
        $fromHoursSelector = jQuery('#routineFromHoursSelector');
        $fromMinSelector = jQuery('#routineFromMinutesSelector');
        $tillHoursSelector = jQuery('#routineTillHoursSelector');
        $tillMinSelector = jQuery('#routineTillMinutesSelector');
        $applyTimeOverlay = jQuery('#applyTimeOverlay');
        $submitRoutine = jQuery('#submitRoutine');
        $infoDisplayer = jQuery('#setRoutineInfo');

        // Initialize date pickers
        $startInputDate.datepicker({
            dateFormat: 'dd/mm/yy',
            firstDay: 1,
            showOtherMonths: true,
            dayNamesMin: ['S', 'M', 'T', 'W', 'T', 'F', 'S'],
            afterDisplay: function () {
                // Making odd cells being... odd!
                var oddHelper = 1;
                jQuery.each(jQuery('#ui-datepicker-div').find('td'), function (index, element) {
                    if (1 === oddHelper % 2) {
                        jQuery(element).addClass('odd');
                    }
                    oddHelper++;
                });
            }
        });
        $finishInputDate.datepicker({
            dateFormat: 'dd/mm/yy',
            firstDay: 1,
            showOtherMonths: true,
            dayNamesMin: ['S', 'M', 'T', 'W', 'T', 'F', 'S'],
            afterDisplay: function () {
                // Making odd cells being... odd!
                var oddHelper = 1;
                jQuery.each(jQuery('#ui-datepicker-div').find('td'), function (index, element) {
                    if (1 === oddHelper % 2) {
                        jQuery(element).addClass('odd');
                    }
                    oddHelper++;
                });
            }
        });
    }

    function setRoutineParameters(data) {
        previousData = data;
        currentData = jQuery.extend({}, previousData);
        currentData.type = 'routine';

        var startDate = getDateFromString(currentData.date_start);
        var finishDate = getDateFromString(currentData.date_finish);
        $startInputDate.datepicker({defaultDate: startDate});
        $finishInputDate.datepicker({defaultDate: finishDate});
        $startInputDate.val(currentData.date_start);
        $finishInputDate.val(currentData.date_finish);

        initWeeklyRepetitionSelector($weeklyRepetitionSelector, currentData.frequency_weeks);
        initWeekdaysSelector($weekdaysSelector, currentData.frequency_days);
        initTimeSelector('hours', $fromHoursSelector, currentData.time_from);
        initTimeSelector('minutes', $fromMinSelector, currentData.time_from);
        initTimeSelector('hours', $tillHoursSelector, currentData.time_till);
        initTimeSelector('minutes', $tillMinSelector, currentData.time_till);
    }

    function validate() {
        validateDateFrame(getDateFromString(currentData.date_start), getDateFromString(currentData.date_finish));
        validateTimeFrame(currentData.time_from, currentData.time_till);
        if (currentData.frequency_days == '0000000') {
            throw 'At least one weekday must be selected.';
        }
    }

    function switchWeeklyRepetitionSelector($element, content) {
        var weeklyRepetitionOptions = ['1', '2', '3', '4'];

        var currentWeeklyRepetitionAmount = weeklyRepetitionOptions.indexOf(content);

        if (currentWeeklyRepetitionAmount < 0) {
            throw 'Something is very wrong in here.';
        }

        if (currentWeeklyRepetitionAmount == (weeklyRepetitionOptions.length - 1)) {
            $element.html(weeklyRepetitionOptions[0]);
        } else {
            $element.html(weeklyRepetitionOptions[currentWeeklyRepetitionAmount + 1]);
        }
    }

    function switchWeekdaySelection($liElement) {
        if ($liElement.hasClass('selected')) {
            $liElement.removeClass('selected ftype_contentB');
        } else {
            $liElement.addClass('selected ftype_contentB');
        }
    }

    function initWeeklyRepetitionSelector($element, content) {
        if (null == content) {
            $element.html('1');
        } else {
            $element.html(content);
        }
    }

    function initWeekdaysSelector($element, content) {
        if (null == content) {
            content = '1111100';
        }

        var initSelection = content.split('');
        var $liElements = $element.find('li');

        for (var i = 0; i < $liElements.length; i++) {
            if (initSelection[i] == '1') {
                jQuery($liElements[i]).addClass('selected ftype_contentB');
            }
        }
    }

    function bindRoutineEvents() {
        $applyTimeOverlay.click(function () {
            self.close();
        });

        $fromHoursSelector.click(function () {
            switchHoursSelector($fromHoursSelector, $fromHoursSelector.html());
            if ($fromHoursSelector.html() == '23') {
                switchHoursSelector($fromHoursSelector, $fromHoursSelector.html());
            }
            $tillHoursSelector.html($fromHoursSelector.html());
            switchHoursSelector($tillHoursSelector, $tillHoursSelector.html());
        });

        $tillHoursSelector.click(function () {
            switchHoursSelector($tillHoursSelector, $tillHoursSelector.html());
        });

        $dialogElement.find('.inputs .minutes').click(function () {
            switchMinutesSelector(jQuery(this), jQuery(this).html());
        });

        $weeklyRepetitionSelector.click(function () {
            var $this = jQuery(this);
            switchWeeklyRepetitionSelector($this, $this.html());
        });
        $weekdaysSelector.find('li').click(function () {
            switchWeekdaySelection(jQuery(this));
        });
        $submitRoutine.click(function () {
            self.submit();
        });
    }

    function unbindRoutineEvents() {
        $applyTimeOverlay.unbind('click');
        $dialogElement.find('.inputs div').unbind('click');
        $weeklyRepetitionSelector.unbind('click');
        $weekdaysSelector.find('li').unbind('click');
        $submitRoutine.unbind('click');
    }


    /**
     * Opens the dialog
     * @param openingType When RoutineDialog is opened by MissionDialog, the overlay is already there.
     * @param tableRow Related row, to be updated if MissionDialog updates the idea.
     * @param data Current idea data
     */
    this.open = function (openingType, tableRow, data) {
        assignedTableRow = tableRow;

        setRoutineParameters(data);

        bindRoutineEvents();

        // Show dialog
        if (openingType == 'new') {
            $routineElement.css('display', 'block');
            $dialogElement.fadeIn();
        } else {
            $routineElement.fadeIn();
        }
    };


    /**
     * Closes the dialog
     */
    this.close = function () {
        $dialogElement.fadeOut(function () {
            unbindRoutineEvents();
            $routineElement.css('display', 'none');
        });
    };


    /**
     * Submits the dialog
     */
    this.submit = function () {
        try {
            // Collects new idea data
            currentData.frequency_days = getWeekdays($weekdaysSelector);
            currentData.frequency_weeks = $weeklyRepetitionSelector.html();
            currentData.date_start = $startInputDate.val();
            currentData.date_finish = $finishInputDate.val();
            currentData.time_from = getTime($fromHoursSelector, $fromMinSelector);
            currentData.time_till = getTime($tillHoursSelector, $tillMinSelector);

            // In case no data is changed, close dialog directly.
            if (previousData.frequency_days == currentData.frequency_days &&
                previousData.frequency_weeks == currentData.frequency_weeks &&
                previousData.date_start == currentData.date_start &&
                previousData.date_finish == currentData.date_finish &&
                previousData.time_from == currentData.time_from &&
                previousData.time_till == currentData.time_till) {
                $applyTimeOverlay.click();
                return;
            }

            validate();

            jQuery.ajax({
                type: 'post',
                url: root_url + 'mindFlow/setRoutineDateTime',
                data: currentData
            }).done(function () {
                    setDataFromTable(assignedTableRow, currentData);
                    $applyTimeOverlay.click();
                }
            ).fail(function (data) {
                    throw data.statusText;
                }
            );
        } catch (err) {
            this.showError(err);
        }
    };

    /**
     * Shows a mission dialog error
     * @param err
     */
    this.showError = function (err) {
        setInfoMessage($infoDisplayer, 'error', err, 5000);
    };
}

/**
 * Builds a weekdays string.
 * @param $element
 * @returns {string}
 */
function getWeekdays($element) {
    var string = '';
    $element.find('li').each(function () {
        if (jQuery(this).hasClass('selected')) {
            string += '1';
        } else {
            string += '0';
        }
    });

    return string;
}


/**
 * Type can be either hours or minutes.
 * Initializes the time selector with given init value.
 * @param type
 * @param $element
 * @param initValue
 */
function initTimeSelector(type, $element, initValue) {
    if (null == initValue || initValue.length < 5) {
        $element.html('');
        return;
    }
    if (type == 'hours') {
        $element.html(initValue.substr(0, 2));
    } else {
        $element.html(initValue.substr(3, 2));
    }
}

/**
 * Switches hour selector to next position
 * @param $element The element to be switched
 * @param content The current content
 */
function switchHoursSelector($element, content) {
    var hours = [null, '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23'];

    if ('' == content) {
        content = null;
    }

    var currentHour = hours.indexOf(content);

    if (currentHour < 0) {
        throw 'Something is very wrong in here.';
    }

    if (currentHour == (hours.length - 1)) {
        $element.html(hours[0]);
    } else {
        $element.html(hours[currentHour + 1]);
    }
}

/**
 * Switches minutes selector to next position
 * @param $element The element to be switched
 * @param content The current content
 */
function switchMinutesSelector($element, content) {
    var minutes = [null, '00', '15', '30', '45'];

    if ('' == content) {
        content = null;
    }

    var currentMinutes = minutes.indexOf(content);

    if (currentMinutes < 0) {
        throw 'Something is very wrong in here.';
    }
    if (currentMinutes == (minutes.length - 1)) {
        $element.html(minutes[0]);
    } else {
        $element.html(minutes[currentMinutes + 1]);
    }
}

/**
 * Expects two jQuery elements which html content are hours and minutes.
 * Builds a time string [00:00], if possible. If not, returns empty string.
 * @param $hours
 * @param $minutes
 * @returns {string}
 */
function getTime($hours, $minutes) {
    // In case neither hours nor minutes are defined, no time is requested.
    if ($hours.html().length == 0 && $minutes.html().length == 0) {
        return '';
    }

    // As one of them are defined, they have to be properly defined. Otherwise, exception is thrown.
    if ($hours.html().length != 2 || $minutes.html().length != 2) {
        throw 'Time frame is not properly defined.';
    }

    return $hours.html() + ':' + $minutes.html();
}

function getDataFromTable(ideaId) {
    return applyTimeData[ideaId];
}

function setDataFromTable(tableRow, data) {
    // Changing visual table
    var $row = jQuery(tableRow);
    $row.find('.col_type').html(data.type);
    if (data.type == 'mission') {
        $row.find('.col_time').html(data.date_todo + ' ' + data.time_from);
    } else {
        $row.find('.col_time').html('R');
    }

    // Changing data
    applyTimeData[data.id] = data;
}

function findRowByIdeaId($location, ideaId) {
    var rowId;

    var colIds = $location.find('.cell.col_id');
    jQuery(colIds).each(function (colId) {
        if (jQuery(this).html() == ideaId) {
            rowId = jQuery(this).closest('.row').attr('id');
        }
    });

    if (typeof(rowId) == 'undefined') {
        throw 'Not found';
    }
    return rowId;
}