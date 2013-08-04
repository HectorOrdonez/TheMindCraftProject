/**
 * Project: Selfology
 * User: Hector Ordonez
 * Description:
 * Description: Brainstorm JS Library
 * Date: 23/07/13 13:00
 */

/**
 * Global variable that will contain the grid.
 * @type {Grid}
 */
var brainstormGrid;

jQuery().ready(function () {
    // JQuery variable that stores the grid.
    var $grid = jQuery('#brainstorm_grid');

    // Brainstorm header construction
    var headerRow = new Row(
        {'cells': [
            {'html': 'Id', 'classList': ['col_id']},
            {'html': 'Title', 'classList': ['col_title']},
            {'html': 'Actions', 'classList': ['col_actions']},
            {'html': 'Date Creation', 'classList': ['col_date_creation']}
        ],
            'classList': ['header']
        });

    // Brainstorm footer construction
    var footerRow = new Row(
        {'cells': [
            {
                'html': '<a href="#" id="linkNewIdea" class="font_normal"></a><form id="formNewIdea" action="' + root_url + 'brainstorm/createIdea"><input type="text" name="title" class="inputNewIdea" /></form>',
                'colspan': '3'
            }
        ], 'classList': ['footer']}
    );

    // Brainstorm table construction
    var table = new Table('brainstorm_grid', {
        colModel: [
            {dataIndex: 'id', classList: ['id']},
            {dataIndex: 'title', classList: ['title']},
            {staticElement: function (rowId) {
                var editAction = '<a class="editAction">' + rowId + '</a>';
                var delAction = '<a class="delAction"> ' + rowId + '</a>';
                return editAction + delAction;
            },
                classList: ['actions']
            },
            {dataIndex: 'date_creation'}
        ]});
    table.addHeaderElement(headerRow.getRow());
    table.addFooterElement(footerRow.getRow());

    // Brainstorm Grid parameters definition
    var gridParameters = {
        'url': root_url + 'brainstorm/getIdeas',
        'eventEOI': function () {
        }
    };

    // Brainstorm Grid construction
    brainstormGrid = new Grid(table, gridParameters);

    // Adding effects to the grid buttons
    jQuery('#linkNewIdea').click(function () {
        submitNewIdea();
    });
    jQuery('#formNewIdea').keypress(function (event) {
        if (event.which == 13) {
            submitNewIdea();
            event.preventDefault();
        }
    });

    // Adding edit and delete triggers
    $grid.delegate('.title', 'dblclick', function () {
        editDialog(jQuery(this).parent().find('.editAction'));
    });
    $grid.delegate('.editAction', 'click', function () {
        editDialog(jQuery(this))
    });
    $grid.delegate('.delAction', 'click', function () {
        deleteIdea(jQuery(this))
    });

    // Initializing page focus on the add input
    jQuery('.inputNewIdea').focus();
});

/**
 * Replaces the title string in the row of the given element with an input that allows the user to edit it.
 * @param $element
 */
function editDialog($element) {
    var $actionCell = $element.parent();
    var $ideaRow = $element.parent().parent();
    var ideaId = $element.html();
    var $titleCell = $ideaRow.children().eq(1);

    // 1 - Save previous links in Action column
    var previousActions = $actionCell.html();

    // 2 - Remove actions temporally
    $actionCell.html('');

    // 3 - Save previous idea's title
    var previousTitle = $titleCell.html();

    // 4 - Replace title column text with input.
    var titleCellContent = '<a href="#" id="linkEditIdea" class="font_normal"></a><form id="formEditIdea" action="' + root_url + 'brainstorm/editIdea"><input type="hidden" class="inputEditIdeaId" name="id" value="' + ideaId + '" /><input type="text" name="title" class="inputEditIdeaTitle" value="' + previousTitle + '"/></form>';
    $titleCell.html(titleCellContent);

    // 5 - Focus user on Input
    $titleCell.find('.inputEditIdeaTitle').focus();
    $titleCell.find('.inputEditIdeaTitle').blur(function () {
        // Restore to normality
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
        setErrorMessage($errorDisplayer, 'Title cannot be empty.', 2000);
        return;
    } else if ($inputTitle.val().length > 200) {
        setErrorMessage($errorDisplayer, 'Title cannot be longer than 200 characters.', 2000);
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
            setErrorMessage($errorDisplayer, data.statusText, 2000);
        }
    );
}

/**
 * Asynchronous request to the server to delete an idea.
 * @param $element
 */
function deleteIdea($element) {
    var $errorDisplayer = jQuery('#errorDisplayer');
    var url = root_url + 'brainstorm/deleteIdea';
    var data = {'id': $element.html()};

    jQuery.ajax({
        type: 'post',
        url: url,
        data: data
    }).done(function () {
            brainstormGrid.table.removeContentId($element.parent().parent().attr('id'));
        }
    ).fail(function (data) {
            setErrorMessage($errorDisplayer, data.statusText, 2000);
        }
    );
}
/**
 * Asynchronous request to the server to create an idea.
 */
function submitNewIdea() {
    var $form = jQuery('#formNewIdea');
    var $errorDisplayer = jQuery('#errorDisplayer');
    var url = $form.attr('action');
    var data = $form.serialize();
    var $input = jQuery('.inputNewIdea');

    if ($input.val() == '') {
        setErrorMessage($errorDisplayer, 'That was a good idea.', 2000);
        return;
    } else if ($input.val().length > 200) {
        setErrorMessage($errorDisplayer, 'go away.', 2000);
        return;
    }

    jQuery.ajax({
        type: 'post',
        url: url,
        data: data
    }).done(function (data) {
            brainstormGrid.table.addContentData(jQuery.parseJSON(data));
            jQuery('.inputNewIdea').val('');
        }
    ).fail(function (data) {
            setErrorMessage($errorDisplayer, data.statusText, 2000);
        }
    );
}

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