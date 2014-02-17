/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * Description: Prioritize JS Library
 * Date: 11/01/14 14:30
 */

function Prioritize($element, callback) {
    // Variable that contains the Table Object.
    var table;

    // Step content
    var $workspace;

    /***********************************/
    /** Construct                     **/
    /***********************************/

    $workspace = $element;
    $workspace.html(builtStepContent());
    builtGrid(callback);

    /***********************************/
    /** Public functions              **/
    /***********************************/

    this.close = function (afterFadeOut) {
        $workspace.fadeOut(
            function () {
                $workspace.empty();
                afterFadeOut();
            }
        );
    };

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
        table = new Table('prioritizeGrid', {
            colModel: [
                {colIndex: 'id'},
                {colIndex: 'title', classList: ['ftype_contentA']},
                {colIndex: 'important'},
                {colIndex: 'urgent'},
                {colIndex: 'actions', customContent: function (rowData) {
                    var importantValue = (rowData.important) ? 'mark' : '';
                    var urgentValue = (rowData.urgent) ? 'mark' : '';

                    var importantAction = '<div class="action"><a class="mindCraft-ui-button mindCraft-ui-button-important clickable ' + importantValue + '">' + rowData.id + '</a></div>';
                    var urgentAction = '<div class="action"><a class="mindCraft-ui-button mindCraft-ui-button-urgent clickable' + urgentValue + '">' + rowData.id + '</a></div>';

                    return '<div class="actionBox">' + importantAction + urgentAction + '</div>';
                }},
                {colIndex: 'date_creation', classList: ['ftype_contentA', 'centered']}
            ]});
        table.addHeaderElement(headerRow.toHTML());

        loadPrioritize(table, callback);

        $grid.delegate('.mindCraft-ui-button-important', 'click', function () {
            toggleIdeaImportance(jQuery(this));
        });
        $grid.delegate('.mindCraft-ui-button-urgent', 'click', function () {
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
        var marked = $importanceLink.hasClass('mark');

        var data = {
            'id': $importanceLink.html(),
            'important': !(marked)
        };

        // Request to the Server
        jQuery.ajax({
            type: 'post',
            url: url,
            data: data
        }).done(function () {
                // Now, changing the visuals
                if (marked) {
                    $importanceLink.removeClass('mark');
                } else {
                    $importanceLink.addClass('mark');
                }
            }).fail(function (data) {
                setInfoMessage($infoDisplayer, 'error', data.statusText, 2000);
            }
        );
    }

    /**
     * Toggles the Idea urgency.
     *
     */
    function toggleIdeaUrgency($urgencyLink) {
        // Declaring parameters
        var $infoDisplayer = jQuery('#infoDisplayer');
        var url = root_url + 'mindFlow/setIdeaUrgent';
        var marked = $urgencyLink.hasClass('mark');

        var data = {
            'id': $urgencyLink.html(),
            'urgent': !(marked)
        };

        // Request to the Server
        jQuery.ajax({
            type: 'post',
            url: url,
            data: data
        }).done(function () {
                // Now, changing the visuals
                if (marked) {
                    $urgencyLink.removeClass('mark');
                } else {
                    $urgencyLink.addClass('mark');
                }
            }
        ).fail(function (data) {
                setInfoMessage($infoDisplayer, 'error', data.statusText, 2000);
            }
        );
    }
}

/**
 * Prioritize loader
 * @param table
 * @param callback
 */
function loadPrioritize(table, callback) {
    var url = root_url + 'mindFlow/getIdeas';
    var data = {step: 'prioritize'};

    jQuery.ajax({
        type: 'post',
        url: url,
        data: data
    }).done(
        function (dataList) {
            var i, data;
            var jsonObject = jQuery.parseJSON(dataList);

            for (i = 0; i < jsonObject['missions'].length; i++) {
                data = {
                    id: jsonObject['missions'][i]['id'],
                    title: jsonObject['missions'][i]['title'],
                    date_creation: jsonObject['missions'][i]['date_creation'],
                    urgent: jsonObject['missions'][i]['urgent'],
                    important: jsonObject['missions'][i]['important']
                };
                table.addContentData(data);
            }
            for (i = 0; i < jsonObject['routines'].length; i++) {
                data = {
                    id: jsonObject['routines'][i]['id'],
                    title: jsonObject['routines'][i]['title'],
                    date_creation: jsonObject['routines'][i]['date_creation'],
                    urgent: jsonObject['routines'][i]['urgent'],
                    important: jsonObject['routines'][i]['important']
                };
                table.addContentData(data);
            }

            callback();
        }
    ).fail(
        function () {
            setInfoMessage(jQuery('#infoDisplayer'), 'error', 'Data could not be load. Try again later.', 50000);
        }
    );
}