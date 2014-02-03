/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * Description: Prioritize JS Library
 * Date: 11/01/14 14:30
 */

function Prioritize($element, callback) {
    /**
     * Constant that defines important and urgent class and values
     */
    /** @type {string} */
    const importantClass = 'important';
    /** @type {string} */
    const notImportantClass = 'notImportant';
    /** @type {string} */
    const urgentClass = 'urgent';
    /** @type {string} */
    const notUrgentClass = 'notUrgent';
    /** @type {number} */
    const importantState= 1;
    /** @type {number} */
    const notImportantState = 0;
    /** @type {number} */
    const urgentState= 1;
    /** @type {number} */
    const notUrgentState = 0;

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
        return "<div class='mindFlowGrid' id='prioritizeGrid'></div>";
    }

    /**
     * Prioritize grid constructor
     * @param callback
     */
    function builtGrid(callback) {
        // JQuery variable that stores the grid.
        var $grid = jQuery('#prioritizeGrid');

        // Prioritize header construction
        var headerRow = new Row(
            {'cells': [
                new Cell({'html': 'id', 'classList': ['col_id']}),
                new Cell({'html': 'title', 'classList': ['col_title', 'ftype_titleC']}),
                new Cell({'html': '', 'classList': ['col_important']}),
                new Cell({'html': '', 'classList': ['col_urgent']}),
                new Cell({'html': '', 'classList': ['col_actions']}),
                new Cell({'html': 'input date', 'classList': ['col_date_creation', 'ftype_titleC', 'centered']})
            ],
                'classList': ['header']
            });

        // ApplyTime Table construction
        var table = new Table('prioritizeGrid', {
            colModel: [
                {colIndex: 'id'},
                {colIndex: 'title', classList: ['ftype_contentA']},
                {colIndex: 'important'},
                {colIndex: 'urgent'},
                {colIndex: 'actions', customContent: function (rowData) {
                    var importantValue = (rowData.important) ? importantClass : notImportantClass;
                    var urgentValue = (rowData.urgent) ? urgentClass : notUrgentClass;

                    var importantAction = '<div class="action"><a class="importantAction ' + importantValue + '">' + rowData.id + '</a></div>';
                    var urgentAction = '<div class="action"><a class="urgentAction ' + urgentValue + '">' + rowData.id + '</a></div>';

                    return '<div class="actionBox">' + importantAction + urgentAction + '</div>';
                }},
                {colIndex: 'date_creation', classList: ['ftype_contentA', 'centered']}
            ]});
        table.addHeaderElement(headerRow.toHTML());

        // ApplyTime Grid parameters definition
        var gridParameters = {
            'url': root_url + 'mindFlow/getIdeas',
            'extraData': {step: 'prioritize'},
            'eventDL': function () {
                callback();
            }
        };

        // Prioritize Grid construction
        grid = new Grid(table, gridParameters);

        $grid.delegate('.importantAction', 'click', function () {
            toggleIdeaImportance(jQuery(this));
        });
        $grid.delegate('.urgentAction', 'click', function () {
            toggleIdeaUrgency(jQuery(this));
        });
    }

    /**
     * Toggles the Idea importance.
     *
     */
    function toggleIdeaImportance($importanceLink) {
        // Declaring parameters
        var $infoDisplayer = jQuery('#infoDisplayer');
        var url = root_url + 'mindFlow/setIdeaImportant';
        var ideaId = $importanceLink.html();
        var ideaCurrentState = ($importanceLink.hasClass(importantClass))? importantClass : notImportantClass;
        var ideaDesiredState = ($importanceLink.hasClass(importantClass))? notImportantClass : importantClass;

        var data = {
            'id': $importanceLink.html(),
            'important': !($importanceLink.hasClass(importantClass))
        };

        // Now, changing the visuals
        $importanceLink.removeClass(ideaCurrentState);
        $importanceLink.addClass(ideaDesiredState);

        // Request to the Server
        jQuery.ajax({
            type: 'post',
            url: url,
            data: data
        }).done(function () {
            }
        ).fail(function (data) {
                setInfoMessage($infoDisplayer, 'error', data.statusText, 2000);

                // Restoring previous visuals
                $importanceLink.removeClass(ideaDesiredState);
                $importanceLink.addClass(ideaCurrentState);
            }
        );
    }

    /**
     * Toggles the Idea urgency.
     *
     */
    function toggleIdeaUrgency ($urgencyLink) {
        // Declaring parameters
        var $infoDisplayer = jQuery('#infoDisplayer');
        var url = root_url + 'mindFlow/setIdeaUrgent';
        var ideaId = $urgencyLink.html();
        var ideaCurrentState = ($urgencyLink.hasClass(urgentClass))? urgentClass : notUrgentClass;
        var ideaDesiredState = ($urgencyLink.hasClass(urgentClass))? notUrgentClass : urgentClass;

        var data = {
            'id': $urgencyLink.html(),
            'urgent': !($urgencyLink.hasClass(urgentClass))
        };

        // Now, changing the visuals
        $urgencyLink.removeClass(ideaCurrentState);
        $urgencyLink.addClass(ideaDesiredState);
        
        // Request to the Server
        jQuery.ajax({
            type: 'post',
            url: url,
            data: data
        }).done(function () {
            }
        ).fail(function (data) {
                setInfoMessage($infoDisplayer, 'error', data.statusText, 2000);

                // Restoring previous visuals
                $urgencyLink.removeClass(ideaDesiredState);
                $urgencyLink.addClass(ideaCurrentState);
            }
        );
    }
}