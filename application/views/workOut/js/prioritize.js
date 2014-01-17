/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * Description: Prioritize JS Library
 * Date: 11/01/14 14:30
 */

function Prioritize($element, callback) {
    // Variable that contains the Grid Object.
    var grid;

    // Step content
    var $workspace;

    // Initializing Prioritize
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
        return "<div id='gridWrapper'><table id='prioritize_grid'></table></div><div class='ftype_errorA' id='errorDisplayer'></div>";
    }

    /**
     * Prioritize grid constructor
     * @param callback
     */
    function builtGrid(callback) {
        // JQuery variable that stores the grid.
        var $grid = jQuery('#prioritize_grid');

        // Prioritize header construction
        var headerRow = new Row(
            {'cells': [
                {'html': 'id', 'classList': ['col_id']},
                {'html': 'title', 'classList': ['col_title', 'ftype_titleC']},
                {'html': '', 'classList': ['col_priority']},
                {'html': 'input date', 'classList': ['col_date_creation', 'ftype_titleC', 'centered']}
            ],
                'classList': ['header']
            });

        // ApplyTime Table construction
        var table = new Table('prioritize_grid', {
            colModel: [
                {dataIndex: 'id', classList: ['id']},
                {dataIndex: 'title', classList: ['title', 'ftype_contentA']},
                {dataIndex: 'priority', classList: ['priority', 'ftype_contentA']},
                {dataIndex: 'date_creation', classList: ['ftype_contentA', 'centered']}
            ]});
        table.addHeaderElement(headerRow.getRow());

        // ApplyTime Grid parameters definition
        var gridParameters = {
            'url': root_url + 'mindFlow/getIdeas/Prioritize',
            'eventDL': function () {
                callback();
            }
        };

        // ApplyTime Grid construction
        grid = new Grid(table, gridParameters);

        // Adding edit and delete triggers
        $grid.delegate('.priority', 'click', function () {
            openEditPriorityDialog(jQuery(this).parent());
        });
    }

    /**
     * Replaces the priority value of the row with a select with options from 1 to 10.
     * If the User modifies its value an ajax request will be sent to modify the idea's priority to the selected one.
     * @param $element - Represents the idea row.
     */
    function openEditPriorityDialog($element) {
        uniqueUserRequest(function (allowNewUniqueRequests) {
            var ideaId = $element.children().eq(0).html();

            // 3 - Save previous idea's priority
            var priorityCell = $element.children().eq(2);
            var priorityCellPreviousValue = priorityCell.html();

            // 4 - Replace priority column text with input.
            var priorityCellContent = '' +
                '<select class="prioritySelector">' +
                '   <option>1</option>' +
                '   <option>2</option>' +
                '   <option>3</option>' +
                '   <option>4</option>' +
                '   <option>5</option>' +
                '   <option>6</option>' +
                '   <option>7</option>' +
                '   <option>8</option>' +
                '   <option>9</option>' +
                '   <option>10</option>' +
                '</select>';
            priorityCell.html(priorityCellContent);
            priorityCell.find('select').val(priorityCellPreviousValue);

            // 5 - Focus user on Input
            priorityCell.find('select').focus();
            priorityCell.find('select').blur(function () {
                // Check if Priority has changed
                var newPrio = priorityCell.find('select').val();
                if (newPrio != priorityCellPreviousValue) {
                    submitSetPriority(ideaId, newPrio, function () {
                        priorityCell.html(newPrio);
                        allowNewUniqueRequests();
                    });

                } else {
                    priorityCell.html(priorityCellPreviousValue);
                    allowNewUniqueRequests();
                }
            });
        });
    }

    /**
     * Asynchronous request to the server to set a different priority value to an idea.
     */
    function submitSetPriority(ideaId, newPrio, callback) {
        var $infoDisplayer = jQuery('#infoDisplayer');
        var url = root_url + 'mindFlow/prioritizeIdea';
        var data = {
            'id': ideaId,
            'priority': newPrio
        };

        jQuery.ajax({
            type: 'post',
            url: url,
            data: data
        }).done(function () {
                callback();
            }
        ).fail(function (data) {
                setInfoMessage($infoDisplayer, 'error', data.statusText, 2000);
            }
        );
    }
}