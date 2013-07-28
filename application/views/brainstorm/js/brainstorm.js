/**
 * Project: Selfology
 * User: Hector Ordonez
 * Description:
 * Description: Brainstorm JS Library
 * Date: 23/07/13 13:00
 */

jQuery().ready(function () {
    var gridWrapper = jQuery('#gridWrapper');
    gridWrapper.fadeOut();

    var grid = new Grid({
        location: 'grid_location',
        url: root_url + 'brainstorm/getIdeas',
        columns: [
            {display: '', name: 'id', index: 'id'},
            {display: 'title', name: 'title', index: 'title'},
            {display: 'input date', name: 'date_creation', index: 'date_creation'}
        ],
        specialOptions: {headerClass: 'font_title'},
        afterComplete: function () {
            console.log('After Complete.');
            var bottomCell = new Cell('<a href="#" id="linkNewIdea" class="font_normal"></a><form id="formNewIdea" action="brainstorm/createIdea"><input type="text" name="title" class="inputNewIdea" /></form>', { colspan: '2'}
            );

            var bottomRow = new Row({});
            bottomRow.addCell(bottomCell);
            grid.table.addRow(bottomRow);
        },
        afterRender: function () {
            console.log('After Render!');
            gridWrapper.fadeIn();

            // Adding effects to the grid buttonsç
            jQuery('#linkNewIdea').click(function () {
                submitNewIdea();
            });
            jQuery('#formNewIdea').keypress(function (event) {
                if (event.which == 13) {
                    submitNewIdea();
                    event.preventDefault();
                }
            });

            // Initializing page focus on the add input
            jQuery('.inputNewIdea').focus();
        }
    });

    grid.createGrid();
});


function submitNewIdea() {
    console.log('Submitting new Idea!');

    var $form = jQuery('#formNewIdea');
    var $errorDisplayer = jQuery('#errorDisplayer');
    var url = jQuery($form).attr('action');
    var data = jQuery($form).serialize();

    if (jQuery('.inputNewIdea').val() == '') {
        setErrorMessage($errorDisplayer, 'That was a good idea.', 2000);
        return;
    } else if (jQuery('.inputNewIdea').val().length > 200) {
        setErrorMessage($errorDisplayer, 'go away.', 2000);
        return;
    }

    jQuery.ajax({
        type: 'post',
        url: url,
        data: data
    }).done(function (data) {
            console.log('Idea added successfully');
            var jsonObject = jQuery.parseJSON(data);

            var cell_newTitle = new Cell(jsonObject['title'], {});
            var cell_newDateCreation = new Cell(jsonObject['date_creation'], {});

            var row = new Row({});

            row.addCell(cell_newTitle);
            row.addCell(cell_newDateCreation);

            jQuery('#grid_location tr:last').before(row.toHTML());
            console.log('emptying the madafuker');
            jQuery('.inputNewIdea').val('');
        }
    ).fail(function (data) {
            setErrorMessage($errorDisplayer, data.statusText, 2000);
        }
    );
}

/**
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