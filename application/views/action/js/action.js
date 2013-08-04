/**
 * Project: Selfology
 * User: Hector Ordonez
 * Description:
 * Description: Action JS Library
 * Date: 02/08/13 13:00
 */

/**
 * Global variable that will contain the grid.
 * @type {Grid}
 */
var actionGrid;

jQuery().ready(function () {
    // JQuery variable that stores the grid.
    var $grid = jQuery('#baction_grid');

    // Action header construction
    var headerRow = new Row(
        {'cells': [
            {'html': 'Id', 'classList': ['col_id']},
            {'html': 'Title', 'classList': ['col_title']},
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
            {staticElement: function (rowId) {
                return '';
            },
                classList: ['actions']
            },
            {dataIndex: 'date_creation'}
        ]});
    table.addHeaderElement(headerRow.getRow());

    // Action Grid parameters definition
    var gridParameters = {
        'url': root_url + 'action/getActions',
        'eventEOI': function () {
        }
    };

    // Action Grid construction
    actionGrid = new Grid(table, gridParameters);

});

/**
 * Shows an error in the div, hiding and deleting it after the time defined at the timeout parameter.
 * @param $errorDisplayer
 * @param {string} message
 * @param {int} timeout
 */
function setErrorMessage($errorDisplayer, message, timeout) {
    $errorDisplayer.html(message);

    setTimeout(function () {
        $errorDisplayer.fadeOut(function () {
            $errorDisplayer.html('');
            $errorDisplayer.fadeIn();
        });
    }, timeout);
}