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

    var missionDialog;
    var routineDialog;

    // Initializing ApplyTime
    $workspace = $element;
    $workspace.empty();
    $workspace.html(builtStepContent());
    builtGrid(callback);

    /***********************************/
    /** Private functions             **/
    /***********************************/

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
        var table = new Table('applyTimeGrid', {
            colModel: [
                {colIndex: 'id'},
                {colIndex: 'type'},
                {colIndex: 'title', classList: ['ftype_contentA']},
                {colIndex: 'actions', customContent: function (row) {
                    var setTodo = '<div class="action"><a class="setTodoAction">' + row.id + '</a></div>';
                    var setRoutine = '<div class="action"><a class="setRoutineAction">' + row.id + '</a></div>';
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
        $grid.delegate('.setTodoAction', 'click', function () {
            missionDialog.open(jQuery(this).closest('.row'), getDataFromTable(jQuery(this).html()));
        });
        $grid.delegate('.setRoutineAction', 'click', function () {
            routineDialog.open('new', jQuery(this).closest('.row'), getDataFromTable(jQuery(this).html()));
        });
    }

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

                    data = {
                        id: jsonObject['missions'][i]['id'],
                        type: 'mission',
                        title: jsonObject['missions'][i]['title']
                    };

                    table.addContentData(data);
                }
                for (i = 0; i < jsonObject['routines'].length; i++) {
                    applyTimeData[jsonObject['routines'][i]['id']] = jsonObject['routines'][i];
                    applyTimeData[jsonObject['routines'][i]['id']].type = 'routine';

                    data = {
                        id: jsonObject['routines'][i]['id'],
                        type: 'routine',
                        title: jsonObject['routines'][i]['title']
                    };
                    table.addContentData(data);
                }
                callback();
            }
        ).fail(
            function () {
                setInfoMessage(jQuery('#infoDisplayer'), 'error', 'Data could not be load. Try again later.', 50000);
            }
        );
    }

    function submitTodo(triggeringElement) {
        // Initialize parameters
        var $ideaId = jQuery('#ideaId');
        var $ideaType = jQuery('#ideaType');
        var $datePicker = jQuery('#datePicker');
        var $fromHoursSelector = jQuery('#todoFromHoursSelector');
        var $fromMinSelector = jQuery('#todoFromMinutesSelector');
        var $tillHoursSelector = jQuery('#todoTillHoursSelector');
        var $tillMinSelector = jQuery('#todoTillMinutesSelector');

        // Validate request
        var $infoDisplayer = jQuery('#setTodoInfo');
        try {
            // Collecting data
            var data = {
                id: $ideaId.val(),
                type: $ideaType.val(),
                date_todo: $datePicker.val(),
                time_from: getTime($fromHoursSelector, $fromMinSelector),
                time_till: getTime($tillHoursSelector, $tillMinSelector),
                date_start: '',
                date_finish: '',
                frequency_days: '',
                frequency_weeks: ''
            };

            validateUniqueApplyTimeRequest(data.date_todo, data.time_from, data.time_till);
        } catch (err) {
            setInfoMessage($infoDisplayer, 'error', err, 5000);
            return;
        }

        submitApplyTime($infoDisplayer, 'setTodo', data, function () {
            jQuery('#applyTimeOverlay').click();
            data.type = 'idea';
            setDataFromTable(triggeringElement, data);
        });
    }

    function submitRoutine() {
        // Initialize parameters
        var $ideaId = jQuery('#ideaId');
        var $ideaType = jQuery('#ideaType');
        var $startInputDate = jQuery('#startDate');
        var $finishInputDate = jQuery('#finishDate');
        var $fromHoursSelector = jQuery('#routineFromHoursSelector');
        var $fromMinSelector = jQuery('#routineFromMinutesSelector');
        var $tillHoursSelector = jQuery('#routineTillHoursSelector');
        var $tillMinSelector = jQuery('#routineTillMinutesSelector');
        var $weeklyRepetitionSelector = jQuery('#weeklyRepetitionSelector');
        var $weekdaysSelector = jQuery('#weekdaysSelectionWrapper');

        // Collecting data
        var data = {
            ideaId: $ideaId.val(),
            ideaType: $ideaType.val(),
            date_start: $startInputDate.datepicker('getDate'),
            date_finish: $finishInputDate.datepicker('getDate'),
            time_from: getTime($fromHoursSelector, $fromMinSelector),
            time_till: getTime($tillHoursSelector, $tillMinSelector),
            weeklyRepetition: $weeklyRepetitionSelector.html(),
            weekdays: getWeekdays($weekdaysSelector)
        };

        // Validate request
        var $infoDisplayer = jQuery('#setRoutineInfo');
        try {
            validateRoutineApplyTimeRequest(data.date_start, data.date_finish, data.time_from, data.time_till, data.weekdays);
        } catch (err) {
            setInfoMessage($infoDisplayer, 'error', err, 5000);
        }

        submitApplyTime($infoDisplayer, 'setRoutine', data, function () {
        });
    }
} // End ApplyTime Object

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


function submitApplyTime($infoDiv, requestType, data, callback) {
    var url = root_url + 'mindFlow/' + ((requestType == 'setTodo') ? 'setIdeaTodo' : 'setIdeaRoutine');

    jQuery.ajax({
        type: 'post',
        url: url,
        data: data
    }).done(function () {
            setInfoMessage($infoDiv, 'success', 'Idea changed.', 2000);
            callback();
        }
    ).fail(function (data) {
            setInfoMessage($infoDiv, 'error', data.statusText, 2000);
        }
    );
}

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
        $dialogElement.find('.inputs div').click(function () {
            var $element = jQuery(this);
            if ($element.hasClass('hours') == true) {
                switchHoursSelector($element, $element.html());
            } else {
                switchMinutesSelector($element, $element.html());
            }
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


    this.open = function (tableRow, data) {
        assignedTableRow = tableRow;

        // Requires idea data
        setMissionParameters(data);

        // Show dialog
        $todoElement.css('display', 'block');
        $dialogElement.fadeIn();

        bindMissionEvents();
    };

    this.close = function (closureType) {
        if (closureType == 'full') {
            $dialogElement.fadeOut(function () {
                unbindMissionEvents();
                $todoElement.css('display', 'none');
            });
        } else {
            $todoElement.fadeOut(function () {
                unbindMissionEvents();
                routineDialog.open('renewed', currentData);
            });
        }
    };

    this.submit = function () {
        try {
            // Collects new idea data
            currentData.date_todo = $datePicker.val();
            currentData.time_from = getTime($fromHoursSelector, $fromMinSelector);
            currentData.time_till = getTime($tillHoursSelector, $tillMinSelector);

            // In case no data is changed, close dialog directly.
            if (previousData === currentData) {
                $applyTimeOverlay.click();
                return;
            }

            // Validate            
            validate();

            var url = root_url + 'mindFlow/setMissionDateTime';

            jQuery.ajax({
                type: 'post',
                url: url,
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

    this.showError = function (err) {
        setInfoMessage($infoDisplayer, 'error', err, 5000);
    };
}

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
        $finishInputDate.datepicker({defaultDate: startDate});
        $startInputDate.val(currentData.date_start);
        $finishInputDate.val(currentData.date_finish);

        initWeeklyRepetitionSelector($weeklyRepetitionSelector, currentData.weeklyRepetition);
        initWeekdaysSelector($weekdaysSelector, currentData.weekdays);
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
        $dialogElement.find('.inputs div').click(function () {
            var $element = jQuery(this);
            if ($element.hasClass('hours') == true) {
                switchHoursSelector($element, $element.html());
            } else {
                switchMinutesSelector($element, $element.html());
            }
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

    this.close = function () {
        $dialogElement.fadeOut(function () {
            unbindRoutineEvents();
            $routineElement.css('display', 'none');
        });
    };

    this.submit = function () {
        try {
            // Collects new idea data
            currentData.date_start = $startInputDate.val();
            currentData.date_finish = $finishInputDate.val();
            currentData.time_from = getTime($fromHoursSelector, $fromMinSelector);
            currentData.time_till = getTime($tillHoursSelector, $tillMinSelector);

            // In case no data is changed, close dialog directly.
            if (previousData === currentData) {
                $applyTimeOverlay.click();
                return;
            }
            
            validate();
            
            var url = root_url + 'mindFlow/setRoutineDateTime';

            jQuery.ajax({
                type: 'post',
                url: url,
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

    this.showError = function (err) {
        setInfoMessage($infoDisplayer, 'error', err, 5000);
    };
}
/************************************************************************************************/
/**     UTILITIES                                                                              **/
/************************************************************************************************/

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

function validateTimeFrame(time_from, time_till) {
    if ('' != time_from && '' != time_till) {
        if (time_from >= time_till) {
            throw 'From time has to be before Till time!';
        }
    } else if ('' != time_from || '' != time_till) {
        throw 'A time frame needs both from and till being set.';
    }
}

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