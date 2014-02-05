/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * Description: ApplyTime JS Library
 * Date: 11/01/14 14:30
 */

var sampleData = {
    ideaId: 125,
    ideaType: 'unique',
    initDate: '14/04/2014',
    fromTime: '14:00',
    tillTime: '15:00',
    weeklyRepetition: null,
    weekdays: null,
    startDate: null,
    finishDate: null
};

function ApplyTime($element, callback) {
    // Variable that contains the Grid Object.
    var grid;

    // Step content
    var $workspace;

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
                new Cell({'html': 'title', 'classList': ['col_title', 'ftype_titleC']}),
                new Cell({'html': '', 'classList': ['col_date_todo']}),
                new Cell({'html': '', 'classList': ['col_time_from']}),
                new Cell({'html': '', 'classList': ['col_time_till']}),
                new Cell({'html': '', 'classList': ['col_actions']})
            ],
                'classList': ['header']
            });

        // ApplyTime Table construction
        var table = new Table('applyTimeGrid', {
            colModel: [
                {colIndex: 'id'},
                {colIndex: 'title', classList: ['ftype_contentA']},
                {colIndex: 'date_todo'},
                {colIndex: 'time_from'},
                {colIndex: 'time_till'},
                {colIndex: 'actions', customContent: function (rowData) {
                    var setTodo = '<div class="action"><a class="setTodoAction"></a></div>';
                    var setRoutine = '<div class="action"><a class="setRoutineAction"></a></div>';

                    return '<div class="actionBox">' + setTodo + setRoutine + '</div>';
                }}
            ]});
        table.addHeaderElement(headerRow.toHTML());

        // ApplyTime Grid parameters definition
        var gridParameters = {
            'url': root_url + 'mindFlow/getIdeas',
            'extraData': {step: 'applyTime'},
            'eventDL': function () {
                callback();
            }
        };

        // ApplyTime Grid construction
        grid = new Grid(table, gridParameters);

        // Add Event Listeners
        $grid.delegate('.setTodoAction', 'click', function () {
            openSetTodoDialog(sampleData);
        });
        $grid.delegate('.setRoutineAction', 'click', function () {
            openSetRoutineDialog('new', sampleData);
        });
        jQuery('#submitTodo').click(function () {
            submitTodo();
        });
        jQuery('#submitRoutine').click(function () {
            submitRoutine();
        });
    }
}

function openSetTodoDialog(data) {
    // Initializing
    var $datePicker = jQuery('#datePicker');
    var $todoElement = jQuery('#setTodoDialogWrapper');
    var $dialogElement = jQuery('#applyTimeDialog');
    var $fromHoursSelector = jQuery('#todoFromHoursSelector');
    var $fromMinSelector = jQuery('#todoFromMinutesSelector');
    var $tillHoursSelector = jQuery('#todoTillHoursSelector');
    var $tillMinSelector = jQuery('#todoTillMinutesSelector');

    // Parsing parameters
    var startDate = null; //getDateFromString(data.initDate);

    // Setting up
    $datePicker.datepicker({
        defaultDate: startDate,
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
    initTimeSelector('hours', $fromHoursSelector, data.fromTime);
    initTimeSelector('minutes', $fromMinSelector, data.fromTime);
    initTimeSelector('hours', $tillHoursSelector, data.tillTime);
    initTimeSelector('minutes', $tillMinSelector, data.tillTime);
    jQuery('#ideaId').val(data.ideaId);
    jQuery('#ideaType').val(data.ideaType);

    // Displaying dialog
    $todoElement.css('display', 'block');
    $dialogElement.fadeIn();

    // Adding Event Listeners
    jQuery('#applyTimeOverlay').click(function () {
        $dialogElement.fadeOut(function () {
            // Unbinding Events
            jQuery('#applyTimeOverlay').unbind('click');
            $dialogElement.find('.inputs div').unbind('click');
            jQuery('#moreOftenAction').unbind('click');

            $todoElement.css('display', 'none');
        });
    });
    $dialogElement.find('.inputs div').click(function () {
        var $element = jQuery(this);
        if ($element.hasClass('hours') == true) {
            switchHoursSelector($element, $element.html());
        } else {
            switchMinutesSelector($element, $element.html());
        }
    });
    jQuery('#moreOftenAction').click(function () {
        data.fromTime = getTime($fromHoursSelector, $fromMinSelector);
        data.tillTime = getTime($tillHoursSelector, $tillMinSelector);

        $todoElement.fadeOut(function () {

            // Unbinding Events
            jQuery('#applyTimeOverlay').unbind('click');
            $dialogElement.find('.inputs div').unbind('click');
            jQuery('#moreOftenAction').unbind('click');

            openSetRoutineDialog('renewed', data);
        });
    });
}

/**
 *
 * @param openingType Indicates if Routine Dialog needs to be open together with ApplyTime dialog or if this one is already opened.
 * @param data Contains the initializing data.
 */
function openSetRoutineDialog(openingType, data) {
    // Initializing
    var $startInputDate = jQuery('#startDate');
    var $finishInputDate = jQuery('#finishDate');
    var $routineElement = jQuery('#setRoutineDialogWrapper');
    var $weeklyRepetitionSelector = jQuery('#weeklyRepetitionSelector');
    var $weekdaysSelector = jQuery('#weekdaysSelectionWrapper');
    var $dialogElement = jQuery('#applyTimeDialog');
    var $fromHoursSelector = jQuery('#routineFromHoursSelector');
    var $fromMinSelector = jQuery('#routineFromMinutesSelector');
    var $tillHoursSelector = jQuery('#routineTillHoursSelector');
    var $tillMinSelector = jQuery('#routineTillMinutesSelector');

    // Parsing parameters
    var startDate = (null == data.startDate) ? new Date() : getDateFromString(data.startDate);
    var finishDate = (null == data.finishDate) ? new Date() : getDateFromString(data.finishDate);

    // Setting up
    $startInputDate.datepicker({
        firstDay: 1,
        defaultDate: startDate,
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
        firstDay: 1,
        defaultDate: finishDate,
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
    initWeeklyRepetitionSelector($weeklyRepetitionSelector, data.weeklyRepetition);
    initWeekdaysSelector($weekdaysSelector, data.weekdays);

    if (openingType == 'new') {
        $routineElement.css('display', 'block');
        $dialogElement.fadeIn();
    } else if (openingType == 'renewed') {
        $routineElement.fadeIn();
    }
    initTimeSelector('hours', $fromHoursSelector, data.fromTime);
    initTimeSelector('minutes', $fromMinSelector, data.fromTime);
    initTimeSelector('hours', $tillHoursSelector, data.tillTime);
    initTimeSelector('minutes', $tillMinSelector, data.tillTime);
    jQuery('#ideaId').val(data.ideaId);
    jQuery('#ideaType').val(data.ideaType);

    $dialogElement.find('.inputs div').click(function () {
        var $element = jQuery(this);
        if ($element.hasClass('hours') == true) {
            switchHoursSelector($element, $element.html());
        } else {
            switchMinutesSelector($element, $element.html());
        }
    });

    // Adding Event Listeners
    jQuery('#applyTimeOverlay').click(function () {
        $dialogElement.fadeOut(function () {
            // Unbinding Events
            jQuery('#applyTimeOverlay').unbind('click');
            $weeklyRepetitionSelector.unbind('click');
            $weekdaysSelector.find('li').unbind('click');
            $dialogElement.find('.inputs div').unbind('click');

            $routineElement.css('display', 'none');
        });
    });
    $weeklyRepetitionSelector.click(function () {
        var $this = jQuery(this);
        switchWeeklyRepetitionSelector($this, $this.html());
    });
    $weekdaysSelector.find('li').click(function () {
        switchWeekdaySelection(jQuery(this));
    });
}

/**
 * String containing the date in format dd/mm/yyyy.
 * Example: 14/04/2014
 * @param string
 */

function getDateFromString(string) {
    var pieces = string.split('/');
    var date = new Date(pieces[2], pieces[1], pieces[0]);
    return date;
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

function getTime($hours, $minutes) {
    if ($hours.html().length != 2 || $minutes.html().length != 2) {
        return null;
    }
    return $hours.html() + ':' + $minutes.html();
}

function submitTodo() {
    // Initialize parameters
    var $ideaId = jQuery('#ideaId');
    var $ideaType = jQuery('#ideaType');
    var $datePicker = jQuery('#datePicker');
    var $fromHoursSelector = jQuery('#todoFromHoursSelector');
    var $fromMinSelector = jQuery('#todoFromMinutesSelector');
    var $tillHoursSelector = jQuery('#todoTillHoursSelector');
    var $tillMinSelector = jQuery('#todoTillMinutesSelector');

    // Collecting data
    var data = {
        ideaId: $ideaId.val(),
        ideaType: $ideaType.val(),
        date_todo: $datePicker.datepicker('getDate'),
        time_from: getTime($fromHoursSelector, $fromMinSelector),
        time_till: getTime($tillHoursSelector, $tillMinSelector)
    };

    // Validate request
    var $infoDisplayer = jQuery('#setTodoInfo');
    try {
        validateUniqueApplyTimeRequest(data.date_todo, data.time_from, data.time_till);
    } catch (err) {
        setInfoMessage($infoDisplayer, 'error', err, 5000);
        return;
    }

    submitApplyTime($infoDisplayer, 'setTodo', data, function () {
        console.log('Set Todo Callback');
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
        console.log('Set Routine Callback');
    });
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

function validateUniqueApplyTimeRequest(date_todo, time_from, time_till) {
    validateTimeFrame(time_from, time_till);
}

function validateRoutineApplyTimeRequest(date_start, date_finish, time_from, time_till, weekdays) {
    validateDateFrame(date_start, date_finish);
    validateTimeFrame(time_from, time_till);
    if (weekdays == '0000000') {
        throw 'At least one weekday must be selected.';
    }
}

function validateTimeFrame(time_from, time_till) {
    if (null != time_from && null != time_till) {
        if (time_from >= time_till) {
            throw 'From time has to be before Till time!';
        }
    } else if (null != time_from || null != time_till) {
        throw 'A time frame needs both from and till being set.';
    }
}

function validateDateFrame(date_start, date_finish) {
    if (null != date_start && !(date_start instanceof Date)) {
        throw 'Start date is in a wrong format.';
    }
    if (null != date_finish && !(date_finish instanceof Date)) {
        throw 'Finish date is in a wrong format.';
    }

    if (null != date_start && null != date_finish) {
        if (date_start > date_finish) {
            throw 'Starting date must be after finishing one!';
        }
    }
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