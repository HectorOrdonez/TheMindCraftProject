/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * Description: Action JS Library
 * Date: 02/08/13 13:00
 */

/**
 * Global variable that will contain the grid with the current list of actions.
 * @type {Grid} actionGrid
 */
var actionGrid;

/**
 * Global variable that will contain the grid with the finished list of actions.
 * @type {Grid} oldActionGrid
 */
var oldActionGrid;


jQuery().ready(function () {
    createPendingActionsGrid();
    createFinishedActionsGrid();
});

/**
 * Creates the list of actions that the User still has to do.
 */
function createPendingActionsGrid() {
    // JQuery variable that stores the grid.
    var $grid = jQuery('#action_grid');

    // Action header construction
    var headerRow = new Row(
        {'cells': [
            {'html': 'Id', 'classList': ['col_id']},
            {'html': 'Title', 'classList': ['col_title']},
            {'html': 'Priority', 'classList': ['col_priority']},
            {'html': 'Actions', 'classList': ['col_actions']},
            {'html': 'Date Creation', 'classList': ['col_date_creation']}
        ],
            'classList': ['header']
        });
    // Action table construction
    var table = new Table('action_grid', {
        colModel: [
            {dataIndex: 'id', classList: ['id']},
            {dataIndex: 'title', classList: ['title']},
            {dataIndex: 'priority', classList: ['priority']},
            {staticElement: function (rowId) {
                var finishAction = '<a class="finishAction">' + rowId + '</a>';
                var deleteAction = '<a class="deleteAction">' + rowId + '</a>';
                return finishAction + deleteAction;
            },
                classList: ['actions']
            },
            {dataIndex: 'date_creation'}
        ]});
    table.addHeaderElement(headerRow.getRow());

    // Action Grid parameters definition
    var gridParameters = {'url': root_url + 'action/getActions'};

    // Action Grid construction
    actionGrid = new Grid(table, gridParameters);

    // Adding edit and delete triggers
    $grid.delegate('.finishAction', 'click', function () {
        finishAction(jQuery(this));
    });
    $grid.delegate('.deleteAction', 'click', function () {
        deleteAction(jQuery(this));
    });
}

/**
 * Creates the grid with the finished actions, which is for historical purposes.
 */
function createFinishedActionsGrid() {
    // Action header construction
    var oldHeaderRow = new Row(
        {'cells': [
            {'html': 'Id', 'classList': ['col_id']},
            {'html': 'Title', 'classList': ['col_title']}
        ],
            'classList': ['header']
        });

    // Action table construction
    var oldTable = new Table('oldAction_grid', {
        colModel: [
            {dataIndex: 'id', classList: ['id']},
            {dataIndex: 'title', classList: ['title']}
        ]});
    oldTable.addHeaderElement(oldHeaderRow.getRow());

    // Action Grid parameters definition
    var oldGridParameters = {'url': root_url + 'action/getOldActions'};

    // Action Grid construction
    oldActionGrid = new Grid(oldTable, oldGridParameters);
}

/**
 * After confirmation does an asynchronous request to the server to finish the selected action.
 * Once the server confirms that the action is finished, the action is moved from the active list of actions remaining
 * to the finished actions list.
 * @param $element
 */
function finishAction($element) {
    var actionId = $element.html();
    var actionTitle = $element.parent().parent().children().eq(1).html();
    var rowId = $element.parent().parent().attr('id');

    var userResponse = confirm('Finish action?');

    if (userResponse == true) {
        var $infoDisplayer = jQuery('#infoDisplayer');
        var url = root_url + 'action/finishAction';
        var data = {'id': actionId};

        jQuery.ajax({
                type: 'post',
                url: url,
                data: data
            }
        ).done(function () {
                actionGrid.table.removeContentId(rowId);
                oldActionGrid.table.addContentData({'id': actionId, 'title': actionTitle});
            }
        ).fail(function (data) {
                setInfoMessage($infoDisplayer, 'error', data.statusText, 2000);
            }
        );
    }
}

/**
 * After confirmation does an asynchronous request to the server to delete the selected action.
 * @param $element
 */
function deleteAction($element) {
    var actionId = $element.html();
    var rowId = $element.parent().parent().attr('id');

    var userResponse = confirm('Delete action?');

    if (userResponse == true) {
        var $infoDisplayer = jQuery('#infoDisplayer');
        var url = root_url + 'action/deleteAction';
        var data = {'id': actionId};

        jQuery.ajax({
                type: 'post',
                url: url,
                data: data
            }
        ).done(function () {
                actionGrid.table.removeContentId(rowId);
            }
        ).fail(function (data) {
                setInfoMessage($infoDisplayer, 'error', data.statusText, 2000);
            }
        );
    }
}