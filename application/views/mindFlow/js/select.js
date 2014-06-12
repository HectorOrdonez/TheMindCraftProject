/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * Description: Select JS Library
 * Date: 11/01/14 14:30
 */

function Select($element, callback) {    
    // Variable that contains the table Object.
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
     * Step Content for Select section
     * @returns {string}
     */
    function builtStepContent() {
        return "<div class='mindFlowGrid' id='selectGrid'></div>";
    }

    /**
     * Selection grid constructor
     * @param callback
     */
    function builtGrid(callback) {
        // JQuery variable that stores the grid.
        var $grid = jQuery('#selectGrid');

        // Select header construction
        var headerRow = new Row(
            {'cells': [
                new Cell({'html': 'id', 'classList': ['col_id']}),
                new Cell({'html': 'title', 'classList': ['col_title', 'ftype_contentA']}),
                new Cell({'html': '', 'classList': ['col_selected']}),
                new Cell({'html': '', 'classList': ['col_actions']}),
                new Cell({'html': 'input date', 'classList': ['col_date_creation', 'ftype_contentA', 'centered']})
            ],
                'classList': ['header']
            });

        // Select footer construction
        var footerRow = new Row(
            {'cells': [
                new Cell({
                    'html': '<a href="#" id="linkNewIdea"></a><form id="formNewIdea"><input type="text" name="title" class="ftype_contentB" id="inputNewIdea" maxlength="100" /></form>',
                    'classList': ['newIdeaCell']
                })
            ], 'classList': ['footer']}
        );

        // Select Table construction
        table = new Table('selectGrid', {
            colModel: [
                {colIndex: 'id'},
                {colIndex: 'title', classList: ['ftype_contentB']},
                {colIndex: 'selected'},
                {colIndex: 'actions', customContent: function (rowData) {
                    var selectValue = (rowData.selected) ? 'mark': '';

                    var selectAction = '<div class="action"><a class="mindCraft-ui-button mindCraft-ui-button-select multi clickable ' + selectValue + '">' + rowData.id + '</a></div>';
                    var editAction = '<div class="action"><a class="mindCraft-ui-button mindCraft-ui-button-edit multi clickable">' + rowData.id + '</a></div>';
                    var delAction = '<div class="action"><a class="mindCraft-ui-button mindCraft-ui-button-delete multi clickable">' + rowData.id + '</a></div>';

                    return '<div class="actionBox">' + selectAction + editAction + delAction + '</div>';
                }},
                {colIndex: 'date_creation', classList: ['ftype_contentB', 'centered']}
            ]});

        table.addHeaderElement(headerRow.toHTML());
        table.addFooterElement(footerRow.toHTML());

        loadSelect(table, function () {
            callback();
            jQuery('#inputNewIdea').focus();
        });

        // Adding effects to the grid buttons
        jQuery('#linkNewIdea').click(function () {
            submitNewIdea();
        });
        jQuery('#formNewIdea').keypress(function (event) {
            if (event.which == 13) {
                event.preventDefault();
                submitNewIdea();
            }
        });

        // Adding edit and delete triggers
        $grid.delegate('.content .col_title', 'click', function () {
            editDialog(jQuery(this));
        });
        $grid.delegate('.mindCraft-ui-button-edit', 'click', function () {
            editDialog(jQuery(this).parent().parent().parent().parent().find('.col_title'));
        });
        $grid.delegate('.mindCraft-ui-button-delete', 'click', function () {
            deleteIdea(jQuery(this));
        });
        $grid.delegate('.mindCraft-ui-button-select', 'click', function () {
            selectIdea(jQuery(this));
        });
    }

    /**
     * Replaces the title string in the row of the given element with an input that allows the user to edit it.
     * @param $titleCell The Title Cell
     */
    function editDialog($titleCell) {
        // Checking if this cell is already open
        if ($titleCell.hasClass('open')) {
            return;
        }

        // Setting this cell as open to avoid multiple "opening" requests
        $titleCell.addClass('open');

        // Declaring parameters
        var ideaId = $titleCell.parent().find('.col_id').html();        // The idea id for the request
        var $actionCell = $titleCell.parent().find('.col_actions');     // This row actions cell
        var previousActions = $actionCell.html();                       // The actions cell content
        var previousTitle = $titleCell.html();                          // Previous title, to restore in case no changes are required.
        var editableContent = '' +
            '<form id="formEditIdea">' +
            '<input type="text" name="title" id="inputEditIdeaTitle" value="" maxlength="100"/>' +
            '</form>';                                                  // The cell content to offer User the edit input

        // Removing actions temporally 
        $actionCell.html('');

        // Now, changing the visuals
        $titleCell.html(editableContent);
        var $inputEditIdeaTitle = jQuery('#inputEditIdeaTitle');
        $inputEditIdeaTitle.focus();
        $inputEditIdeaTitle.val(previousTitle);

        // Event Listeners
        $inputEditIdeaTitle.blur(function () {
            // Restore normality
            $titleCell.html(previousTitle);
            $titleCell.removeClass('open');
            $actionCell.html(previousActions);
        });
        jQuery('#formEditIdea').keypress(function (event) {
            if (event.which == 13) {
                event.preventDefault();
                submitEditIdea(ideaId, $inputEditIdeaTitle.val(), function (newTitle) {
                    $titleCell.html(newTitle);
                    $titleCell.removeClass('open');
                    $actionCell.html(previousActions);
                });
            }
        });
    }

    /**
     * Asynchronous request to the server to edit an idea.
     * @param {String} ideaId
     * @param {String} newIdeaTitle
     * @param {Function} callback
     */
    function submitEditIdea(ideaId, newIdeaTitle, callback) {
        // Declaring parameters
        var $infoDisplayer = jQuery('#infoDisplayer');
        var url = root_url + 'mindFlow/editIdea';
        var data = {
            id: ideaId,
            title: newIdeaTitle
        };

        // Validating input title
        if (newIdeaTitle == '') {
            setInfoMessage($infoDisplayer, 'error', 'Title cannot be empty.', 2000);
            return;
        }
        if (newIdeaTitle.length > 100) {
            setInfoMessage($infoDisplayer, 'error', 'Title cannot be longer than 200 characters.', 2000);
            return;
        }

        jQuery.ajax({
            type: 'post',
            url: url,
            data: data
        }).done(function () {
                callback(newIdeaTitle);
            }
        ).fail(function (data) {
                setInfoMessage($infoDisplayer, 'error', data.statusText, 2000);
            }
        );
    }

    /**
     * Asynchronous request to the server to delete an idea.
     * @param $element Element to destroy.
     */
    function deleteIdea($element) {
        var $infoDisplayer = jQuery('#infoDisplayer');
        var url = root_url + 'mindFlow/deleteIdea';
        var data = {
            'id': $element.html()
        };

        jQuery.ajax({
            type: 'post',
            url: url,
            data: data
        }).done(function () {
                table.removeContentId($element.closest('.row').attr('id'));
            }
        ).fail(function (data) {
                setInfoMessage($infoDisplayer, 'error', data.statusText, 2000);
            }
        );
    }

    /**
     * Asynchronous request to the server to create an idea.
     */
    function submitNewIdea() {
        var $infoDisplayer = jQuery('#infoDisplayer');
        var url = root_url + 'mindFlow/newIdea';
        var $input = jQuery('#inputNewIdea');
        var data = {title: $input.val()};

        if ($input.val() == '') {
            setInfoMessage($infoDisplayer, 'error', 'Explain a bit more your idea... :) ', 2000);
            return;
        } else if ($input.val().length > 100) {
            setInfoMessage($infoDisplayer, 'error', 'A bit more brief, please? ', 2000);
            return;
        }

        jQuery.ajax({
            type: 'post',
            url: url,
            data: data
        }).done(function (rawData) {
                var data = jQuery.parseJSON(rawData);
                var newRow = {
                    id: data.id,
                    title: data.title,
                    date_creation: data.date_creation,
                    selected: data.selected
                };
                table.addContentData(newRow);

                /**
                 * Scrolling down the workspace, if necessary.
                 */
                var workspace = jQuery($workspace).find('.workspace').get(0);
                workspace.scrollTop = workspace.scrollHeight;

                // Resetting the input
                $input.val('');
            }
        ).fail(function (data) {
                setInfoMessage($infoDisplayer, 'error', data.statusText, 2000);
            }
        );
    }

    /**
     * Selects or un-selects this idea.
     * @param $selectLink
     */
    function selectIdea($selectLink) {
        // Declaring parameters
        var $infoDisplayer = jQuery('#infoDisplayer');
        var url = root_url + 'mindFlow/setIdeaSelection';
        var marked = ($selectLink.hasClass('mark'));

        var data = {
            'id': $selectLink.html(),
            'selected': !marked
        };

        // Request to the Server
        jQuery.ajax({
            type: 'post',
            url: url,
            data: data
        }).done(function () {
                // Now, changing the visuals
                if (marked) {
                    $selectLink.removeClass('mark');
                } else {
                    $selectLink.addClass('mark');
                }
            }
        ).fail(function (data) {
                setInfoMessage($infoDisplayer, 'error', data.statusText, 2000);
            }
        );
    }
}

/**
 * Select loader
 * @param table
 * @param callback
 */
function loadSelect(table, callback) {
    var url = root_url + 'mindFlow/getIdeas';
    var data = {step: 'select'};

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
                    selected: jsonObject['missions'][i]['selected']
                };
                table.addContentData(data);
            }
            for (i = 0; i < jsonObject['routines'].length; i++) {
                data = {
                    id: jsonObject['routines'][i]['id'],
                    title: jsonObject['routines'][i]['title'],
                    date_creation: jsonObject['routines'][i]['date_creation'],
                    selected: jsonObject['routines'][i]['selected']
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