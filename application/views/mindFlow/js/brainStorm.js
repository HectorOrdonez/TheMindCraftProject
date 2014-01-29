/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * Description: Brainstorm JS Library
 * Date: 11/01/14 14:30
 */

function BrainStorm($element, callback) {
    // Variable that contains the Grid Object.
    var grid;

    // Step content
    var $workspace;

    // Initializing BrainStorm
    $workspace = $element;
    $workspace.empty();
    $workspace.html(builtStepContent());
    builtGrid(callback);

    /***********************************/
    /** Private functions             **/
    /***********************************/

    /**
     * Step Content for BrainStorm section 
     * @returns {string}
     */
    function builtStepContent() {
        return "<div id='gridWrapper'><table id='brainStorm_grid'></table></div><div class='ftype_errorA' id='errorDisplayer'></div>";
    }

    /**
     * BrainStorm grid constructor
     * @param callback
     */
    function builtGrid(callback) {
        // JQuery variable that stores the grid.
        var $grid = jQuery('#brainStorm_grid');

        // Brainstorm header construction
        var headerRow = new Row(
            {'cells': [
                {'html': 'id', 'classList': ['col_id']},
                {'html': 'title', 'classList': ['col_title', 'ftype_titleC']},
                {'html': '', 'classList': ['col_actions']},
                {'html': 'input date', 'classList': ['col_date_creation', 'ftype_titleC', 'centered']}
            ],
                'classList': ['header']
            });

        // Brainstorm footer construction
        var footerRow = new Row(
            {'cells': [
                {
                    'html': '<a href="#" id="linkNewIdea"></a><form id="formNewIdea" action="' + root_url + 'mindFlow/newIdea"><input type="text" name="title" class="ftype_contentA" id="inputNewIdea" /></form>',
                    'colspan': '3'
                }
            ], 'classList': ['footer']}
        );

        // Brainstorm table construction
        var table = new Table('brainStorm_grid', {
            colModel: [
                {dataIndex: 'id', classList: ['id']},
                {dataIndex: 'title', classList: ['title', 'ftype_contentA']},
                {staticElement: function (rowId) {
                    var cellWrapper = '<div class="cellWrapper">';
                    var actionBox = '<div class="actionBox">';
                    var editAction = '<div class="action"><a class="editAction" id="' + rowId + '"></a></div>';
                    var delAction = '<div class="action"><a class="delAction" id="' + rowId + '"></a></div>';
                    return cellWrapper + actionBox + editAction + delAction + '</div></div>';
                },
                    classList: ['actions']
                },
                {dataIndex: 'date_creation', classList: ['ftype_contentA', 'centered']}
            ]});
        table.addHeaderElement(headerRow.getRow());
        table.addFooterElement(footerRow.getRow());

        // Brainstorm Grid parameters definition
        var gridParameters = {
            'url': root_url + 'mindFlow/getIdeas',
            'extraData': {step: 'brainStorm'},
            'eventEOI': function () {
            },
            'eventDL': function () {
                callback();

                // Initializing page focus on the add input
                jQuery('#inputNewIdea').focus();
            }
        };

        // Brainstorm Grid construction
        grid = new Grid(table, gridParameters);

        // Adding effects to the grid buttons
        jQuery('#linkNewIdea').click(function () {
            submitNewIdea();
        });
        jQuery('#formNewIdea').keypress(function (event) {
            if (event.which == 13) {
                console.log('enter pressed in brainstorm');
                submitNewIdea();
                event.preventDefault();
            }
        });

        // Adding edit and delete triggers
        $grid.delegate('.title', 'click', function () {
            editDialog(jQuery(this).parent().find('.editAction'));
        });
        $grid.delegate('.editAction', 'click', function () {
            editDialog(jQuery(this));
        });
        $grid.delegate('.delAction', 'click', function () {
            deleteIdea(jQuery(this));
        });
    }

    /**
     * Replaces the title string in the row of the given element with an input that allows the user to edit it.
     * @param $clickedAction The clicked thing.
     */
    function editDialog($clickedAction) {
        /**
         * The Clicked action is the <a> link. Its parent is the action div, which parent is the action box, which parent is the action cell.
         */
        var $actionCell = $clickedAction.parent().parent().parent();
        var $ideaRow = $actionCell.parent();
        var ideaId = $clickedAction.html();
        var $titleCell = $ideaRow.children().eq(1);

        // 1 - Save previous links in Action column
        var previousActions = $actionCell.html();

        // 2 - Remove actions temporally
        $actionCell.html('');

        // 3 - Save previous idea's title
        var previousTitle = $titleCell.html();

        // 4 - Replace title column text with input.
        var titleCellContent = '<a href="#" id="linkEditIdea"></a><form id="formEditIdea" action="' + root_url + 'mindFlow/editIdea"><input type="hidden" class="inputEditIdeaId" name="id" value="' + ideaId + '" /><input type="text" name="title" class="inputEditIdeaTitle ftype_contentA" value="' + previousTitle + '"/></form>';
        $titleCell.html(titleCellContent);

        // 5 - Focus user on Input
        $titleCell.find('.inputEditIdeaTitle').focus();
        $titleCell.find('.inputEditIdeaTitle').blur(function () {
            // Restore normality
            $titleCell.html(previousTitle);
            $actionCell.html(previousActions);
        });

        // 6 - Adding Enter event to the form
        jQuery('#formEditIdea').keypress(function (event) {
            if (event.which == 13) {
                event.preventDefault();
                submitEditIdea(jQuery(this), function (newTitle) {
                    $titleCell.html(newTitle);
                    $actionCell.html(previousActions);
                });
            }
        });
    }

    /**
     * Asynchronous request to the server to edit an idea.
     * @param $form
     * @param {Function} successCallback
     */
    function submitEditIdea($form, successCallback) {
        var $errorDisplayer = jQuery('#errorDisplayer');
        var url = $form.attr('action');
        var data = $form.serialize();
        var $inputTitle = $form.find('.inputEditIdeaTitle');

        if ($inputTitle.val() == '') {
            setInfoMessage($errorDisplayer, 'error', 'Title cannot be empty.', 2000);
            return;
        } else if ($inputTitle.val().length > 200) {
            setInfoMessage($errorDisplayer, 'error', 'Title cannot be longer than 200 characters.', 2000);
            return;
        }

        jQuery.ajax({
            type: 'post',
            url: url,
            data: data
        }).done(function () {
                successCallback($inputTitle.val());
            }
        ).fail(function (data) {
                setInfoMessage($errorDisplayer, 'error', data.statusText, 2000);
            }
        );
    }

    /**
     * Asynchronous request to the server to delete an idea.
     * @param $clickedAction The clicked thing.
     */
    function deleteIdea($clickedAction) {
        var $errorDisplayer = jQuery('#errorDisplayer');
        var url = root_url + 'mindFlow/deleteIdea';
        var data = {'id': $clickedAction.html()};

        var $actionCell = $clickedAction.parent().parent().parent();

        jQuery.ajax({
            type: 'post',
            url: url,
            data: data
        }).done(function () {
                grid.table.removeContentId($actionCell.parent().attr('id'));
            }
        ).fail(function (data) {
                setInfoMessage($errorDisplayer, 'error', data.statusText, 2000);
            }
        );
    }

    /**
     * Asynchronous request to the server to create an idea.
     */
    function submitNewIdea() {
        console.log('Submit new BS idea');
        var $form = jQuery('#formNewIdea');
        var $errorDisplayer = jQuery('#errorDisplayer');
        var url = $form.attr('action');
        var data = $form.serialize();
        var $input = jQuery('#inputNewIdea');

        if ($input.val() == '') {
            setInfoMessage($errorDisplayer, 'error', 'Explain a bit more your idea... :) ', 2000);
            return;
        } else if ($input.val().length > 200) {
            setInfoMessage($errorDisplayer, 'error', 'A bit more brief, please? ', 2000);
            return;
        }

        jQuery.ajax({
            type: 'post',
            url: url,
            data: data
        }).done(function (data) {
                grid.table.addContentData(jQuery.parseJSON(data));
                jQuery('#inputNewIdea').val('');
            }
        ).fail(function (data) {
                setInfoMessage($errorDisplayer, 'error', data.statusText, 2000);
            }
        );
    }
}