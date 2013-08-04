/**
 * Project: Selfology
 * User: Hector Ordonez
 * Description: Prioritizing JS Library
 * Date: 25/07/13 21:00
 */

function createPrioritizingGrid(){
    // JQuery variable that stores the grid.
    var $grid = jQuery('#prioritizing_grid');

    // Prioritizing header construction
    var headerRow = new Row(
        {'cells': [
            {'html': 'Id', 'classList': ['col_id']},
            {'html': 'Title', 'classList': ['col_title']},
            {'html': 'Actions', 'classList': ['col_actions']},
            {'html': 'Date Creation', 'classList': ['col_date_creation']}
        ],
            'classList': ['header']
        });

    // Prioritizing table construction
    var table = new Table('prioritizing_grid', {
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

    // Prioritizing Grid parameters definition
    var gridParameters = {
        'url': root_url + 'workout/getIdeas/stepPrioritizing',
        'eventEOI': function () {
            console.log('selection grid initialized.');
        }
    };

    // Workout Grid construction
    workoutGrid = new Grid(table, gridParameters);
}