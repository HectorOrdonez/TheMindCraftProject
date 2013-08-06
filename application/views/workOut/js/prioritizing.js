/**
 * Project: Selfology
 * User: Hector Ordonez
 * Description: Prioritizing JS Library
 * Date: 25/07/13 21:00
 */

function createPrioritizingGrid() {
    // JQuery variable that stores the grid.
    var $grid = jQuery('#prioritizing_grid');

    // Prioritizing header construction
    var headerRow = new Row(
        {'cells': [
            {'html': 'Id', 'classList': ['col_id']},
            {'html': 'Title', 'classList': ['col_title']},
            {'html': 'Priority', 'classList': ['col_priority']},
            {'html': 'Date Creation', 'classList': ['col_date_creation']}
        ],
            'classList': ['header']
        });

    // Prioritizing table construction
    var table = new Table('prioritizing_grid', {
        colModel: [
            {dataIndex: 'id', classList: ['id']},
            {dataIndex: 'title', classList: ['title']},
            {dataIndex: 'priority', classList: ['priority']},
            {dataIndex: 'date_creation'}
        ]});
    table.addHeaderElement(headerRow.getRow());

    // Prioritizing Grid parameters definition
    var gridParameters = {
        'url': root_url + 'workout/getIdeas/stepPrioritizing',
        'eventEOI': function () {
            console.log('selection grid initialized.');
        }
    };

    // Workout Grid construction
    workoutGrid = new Grid(table, gridParameters);

    // Adding prioritizing triggers
    $grid.delegate('.priority', 'dblclick', function () {

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
    var url = root_url + 'workout/setPriorityToIdea';
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