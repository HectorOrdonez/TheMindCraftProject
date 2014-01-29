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
                new Cell({'html': 'input date', 'classList': ['col_date_creation', 'ftype_titleC', 'centered']})
            ],
                'classList': ['header']
            });

        // ApplyTime Table construction
        var table = new Table('applyTimeGrid', {
            colModel: [
                {colIndex: 'id'},
                {colIndex: 'title', classList: ['ftype_contentA']},
                {colIndex: 'date_creation', classList: ['ftype_contentA', 'centered']}
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
    }
}