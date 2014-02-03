/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * Description: ApplyTime JS Library
 * Date: 11/01/14 14:30
 */

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

        $grid.delegate('.setTodoAction', 'click', function () {
            openSetTodoDialog();
        });
        $grid.delegate('.setRoutineAction', 'click', function () {
            openSetRoutineDialog(jQuery(this));
        });
    }
}

function openSetTodoDialog() {

    // Initializing
    createTodoDialogElement();
    var $dialogElement = jQuery('#setTodoDialogWrapper');
    var $datePicker = jQuery('#datePicker');
    $datePicker.datepicker({
        showOtherMonths: true,
        dayNamesMin: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
        afterDisplay: function() {
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

    // Displaying dialog
    $dialogElement.fadeIn();

    // Adding Event Listeners
    $dialogElement.find('.fullOverlay').click(function () {
        $dialogElement.fadeOut(function () {
            $dialogElement.remove();
        });
    });
}

function openSetRoutineDialog() {

}

function createTodoDialogElement() {
    var dialog = document.createElement('DIV');
    dialog.setAttribute('id', 'setTodoDialogWrapper');
    dialog.innerHTML = '' +
        '<div class="fullOverlay"></div>' +
        '<div id="dialogContent">' +
        '   <div id="datePickerWrapper">' +
        '       <div id="datePicker"></div>' +
        '   </div>' +
        '   <div class="verticalSpace30"></div>' +
        '   <div id="timeSelectionWrapper">' +
        '       <div id="fromSelectionWrapper">' +
        '           <div class="text ftype_contentB">' +
        '               from:' +
        '           </div>' +
        '           <div class="inputs">' +
        '               <input class="hours ftype_contentC" type="text" maxlength="2" />' +
        '               <input class="minutes ftype_contentC" type="text" maxlength="2" />' +
        '           </div>' +
        '       </div>' +
        '       <div id="tillSelectionWrapper">' +
        '           <div class="text ftype_contentB">' +
        '               till:' +
        '           </div>' +
        '           <div class="inputs">' +
        '               <input class="hours ftype_contentC" type="text" maxlength="2" />' +
        '               <input class="minutes ftype_contentC" type="text" maxlength="2" />' +
        '           </div>' +
        '       </div>' +
        '   </div>' +
        '   <div class="verticalSpace30"></div>' +
        '   <div id="moreOftenWrapper">' +
        '       <div class="text ftype_contentC">' +
        '           More often?' +
        '       </div>' +
        '       <div class="action">' +
        '           <a class="setRoutineAction"></a>' +
        '       </div>' +
        '   </div>' +
        '   <div class="verticalSpace30"></div>' +
        '   <div class="ftype_titleC" id="submitApplyTime">Apply Time!</div>' +
        '</div>';

    document.body.appendChild(dialog);
}

