/**
 * Project: Selfology
 * User: Hector Ordonez
 * Description:
 * Library that contains general functions used in the application related to JQGrids.
 * Date: 23/07/13 13:30
 */

/**
 * Function that initializes the Date Edition in JQGrid.
 * @param elem
 */
function initDateEdit (elem) {
    jQuery(elem).datepicker({
        dateFormat: "yy-mm-dd",
        changeYear: true,
        changeMonth: true,
        showWeek: true,
        yearRange: '1950: 2000'
    });

}
/**
 * Function that initializes the Date Search column in JQGrid.
 * @param elem
 */
function initDateSearch (elem) {
    setTimeout(function () {
        jQuery(elem).datepicker({
            dateFormat: "yy-mm-dd"
        });
    }, 100);
}

/**
 * Function for resize the grid according to the width of the resized window
 * @param grid_id string - jqGrid id used in current page
 * @param div_id string - parent div_id according to whom it will need to resize
 * @param width string - width of the grid that has been set during initialize the grid setup
 * @returns void
 * @todo Build a global Jquery related functions, sort of Jquery Tool Box.
 */
function resizeJQGridWidth(grid_id, div_id, width) {
    jQuery(window).bind('resize',function () {
        var $grid = jQuery('#' + grid_id);
        $grid.setGridWidth(width, true); //Back to original width
        $grid.setGridWidth($('#' + div_id).width(), true); //Resized to new width as per window
    }).trigger('resize');
}