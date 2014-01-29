/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 *
 * Helper to build a row for a table.
 *
 * The 'parameters' parameter contains a different number of properties, some required and some optional.
 *
 * Required Parameters:
 *  cells : List of cells that this row contains.
 *
 * Optional Parameters:
 *  classList : array with the classes that this row uses.
 *
 /***********************************************************************************************************************
 * Object Row
 *
 * Public methods
 * + getRow
 *
 * Private methods
 * - validateParameters
 * - initializeRow
 * - displayErrors
 *
 ***********************************************************************************************************************
 *
 * Date: 31/07/13 13:00
 */

/**
 * Row constructor. Needs "parameters" object to be initialized.
 *
 * Required attributes:
 *   cells - Must be an array of objects, each of them definitions
 *
 * Additional optional attributes:
 *  classList - An array of classes that will be attached to this row.
 *
 * @param {Object} parameters
 * @constructor
 */
function Row(parameters) {
    /*****************************************************************************************************************/
    /** Parameters definition                                                                                       **/
    /*****************************************************************************************************************/

    /**
     * Element that contains the row as it has to be append to the desired parent.
     * @type {HTMLElement}
     */
    var html;

    /**
     * List of cells that this row contains.
     * @type {Array}
     */
    var cells = [];

    /**
     * List of classes that the row uses.
     */
    var rowClassList = [];

    /*****************************************************************************************************************/
    /** Row construction                                                                                            **/
    /*****************************************************************************************************************/

    try {
        // Validate received parameters
        validateParameters();

        // Initializing Row
        initializeRow()
    }
    catch (error) {
        displayError(error);
    }

    /*****************************************************************************************************************/
    /** Private functions definition                                                                                **/
    /*****************************************************************************************************************/

    /**
     * Private function validateParameters.
     * Validates the parameters received in the constructor in order to verify that this row can be constructed properly.
     */
    function validateParameters() {
        if (typeof(parameters) == 'undefined') {
            throw 'Row construction needs parameters.';
        }

        if (typeof(parameters['cells']) == 'undefined') {
            throw 'Row construction needs cells.';
        }

        for (var i = 0; i < parameters['cells'].length; i++) {
            if (!(parameters['cells'][i] instanceof Cell)) {
                throw 'Cell list must contain only Cell objects';
            }
        }
    }

    /**
     * Private function initializeRow
     * Builds the TR element and sets it up depending on the parameters received.
     */
    function initializeRow() {
        rowClassList.push('row');

        for (var i = 0; i < parameters['cells'].length; i++) {
            cells.push(parameters['cells'][i]);
        }

        if (typeof(parameters['classList']) != 'undefined') {
            for (i = 0; i < parameters['classList'].length; i++) {
                rowClassList.push(parameters['classList'][i]);
            }
        }
    }

    /**
     * Private function displayError
     * Logs the error.
     * @param msg
     */
    function displayError(msg) {
        if (typeof(msg.stack) != 'undefined') {
            msg = msg.stack;
        }
        console.error(msg);
    }

    /*****************************************************************************************************************/
    /** Public functions definition                                                                                 **/
    /*****************************************************************************************************************/

    /**
     * Returns this row in HTML, ready to be appended.
     * @returns {Element}
     */
    this.toHTML = function () {
        html = document.createElement('div');

        for (var i = 0; i < cells.length; i++) {
            html.appendChild(cells[i].toHTML());
        }

        for (i = 0; i < rowClassList.length; i++) {
            html.classList.add(rowClassList[i]);
        }

        return html;
    };

    /**
     * Returns this row cells.
     * @returns {cells[]}
     */
    this.getCells = function () {
        return cells;
    };
}