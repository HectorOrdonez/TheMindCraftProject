/**
 * Project: Selfology
 * User: Hector Ordonez
 * Description:
 * Description: Action JS Library
 * Date: 25/07/13 01:30
 */

jQuery().ready(function () {
    var gridWrapper = jQuery('#gridWrapper');
    gridWrapper.fadeOut();

    var grid = new Grid({
        location: 'grid_location',
        url: root_url + 'action/getActions',
        columns: [
            {display: '', name: 'id', index: 'id'},
            {display: 'title', name: 'title', index: 'title'},
            {display: 'input date', name: 'date_creation', index: 'date_creation'}
        ],
        specialOptions: {headerClass: 'font_title'},
        afterComplete: function () {
            console.log('After Complete.');
        },
        afterRender: function () {
            console.log('After Render!');
            gridWrapper.fadeIn();
        }
    });

    grid.createGrid();
});

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