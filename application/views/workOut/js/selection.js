/**
 * Project: Selfology
 * User: Hector Ordonez
 * Description: Selection JS Library
 * Date: 25/07/13 21:00
 */

function createSelectionGrid() {
    // JQuery variable that stores the grid.
    var $grid = jQuery('#selection_grid');

    // Selection header construction
    var headerRow = new Row(
        {'cells': [
            {'html': 'Id', 'classList': ['col_id']},
            {'html': 'Title', 'classList': ['col_title']},
            {'html': 'Actions', 'classList': ['col_actions']},
            {'html': 'Date Creation', 'classList': ['col_date_creation']}
        ],
            'classList': ['header']
        });

    // Selection Table construction
    var table = new Table('selection_grid', {
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

    // Selection Grid parameters definition
    var gridParameters = {
        'url': root_url + 'workout/getIdeas/stepSelection',
        'eventEOI': function () {
            console.log('selection grid initialized.');
        }
    };

    // Workout Grid construction
    workoutGrid = new Grid(table, gridParameters);
}