/**
 * Project: Selfology
 * User: Hector Ordonez
 * Description: Timing JS Library
 * Date: 25/07/13 21:00
 */

function createTimingGrid() {
    // JQuery variable that stores the grid.
    var $grid = jQuery('#timing_grid');

    // Timing header construction
    var headerRow = new Row(
        {'cells': [
            {'html': 'Id', 'classList': ['col_id']},
            {'html': 'Title', 'classList': ['col_title']},
            {'html': 'Actions', 'classList': ['col_actions']},
            {'html': 'Date Creation', 'classList': ['col_date_creation']}
        ],
            'classList': ['header']
        });

    // Selection table construction
    var table = new Table('timing_grid', {
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
        'url': root_url + 'workout/getIdeas/stepTiming',
        'eventEOI': function () {
            console.log('selection grid initialized.');
        }
    };

    // Workout Grid construction
    workoutGrid = new Grid(table, gridParameters);
}