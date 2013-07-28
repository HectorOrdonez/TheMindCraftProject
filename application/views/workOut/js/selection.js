/**
 * Project: Selfology
 * User: Hector Ordonez
 * Description: Selection JS Library
 * Date: 25/07/13 21:00
 */

function createSelectionGrid(callback) {
    var grid = new Grid({
        location: 'grid_location',
        url: root_url + 'workout/getIdeas/stepSelection',
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
            callback();
        }
    });

    grid.createGrid();

}