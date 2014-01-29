/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 *
 * Helper to build a cell for a table.
 *
 * The 'parameters' parameter contains a different number of properties, some required and some optional.
 *
 * Required Parameters:
 *  html : The html content of the cell.
 *
 * Optional Parameters:
 *  classList : array with the classes that this cell uses.
 *  colspan : colspan attribute to add to the cell.
 *
 /***********************************************************************************************************************
 * Object Cell
 *
 * Public methods
 * + toHTML
 *
 * Private methods
 * - validateParameters
 * - initializeCell
 * - displayErrors
 *
 ***********************************************************************************************************************
 *
 * Date: 31/07/13 13:00
 */

/**
 * Cell constructor.
 * @param {Object} parameters - Required and optional parameters for the construction of the cell.
 * @constructor
 */
function Cell(parameters) {

    /*****************************************************************************************************************/
    /** Parameters definition                                                                                       **/
    /*****************************************************************************************************************/

    /**
     * The HTML that goes inside the cell.
     * @type {String}
     */
    var cellContent = '';

    /**
     * List of classes that the cell uses.
     * @type {Array}
     */
    var cellClassList = [];

    /*****************************************************************************************************************/
    /** Cell construction                                                                                           **/
    /*****************************************************************************************************************/

    try {
        // Validate received parameters
        validateParameters();

        // Initializing Cell
        initializeCell()
    }
    catch (error) {
        displayError(error);
    }

    /*****************************************************************************************************************/
    /** Private functions definition                                                                                **/
    /*****************************************************************************************************************/

    /**
     * Private function validateParameters.
     * Validates the parameters received in the constructor in order to verify that this cell can be constructed properly.
     */
    function validateParameters() {
        if (typeof(parameters) == 'undefined') {
            throw 'No parameters were passed.';
        }

        if (typeof(parameters['html']) == 'undefined') {
            throw 'Parameter html is required.';
        }
    }

    /**
     * Private function initializeCell
     * Builds the TD element and sets it up depending on the parameters received.
     */
    function initializeCell() {
        cellContent = parameters['html'];

        if (typeof(parameters['classList']) != 'undefined') {
            cellClassList = parameters['classList'];
        }

        if (typeof(parameters['colspan']) != 'undefined') {
            cellColspan = parameters['colspan'];
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
     * Adds a class to the classList.
     */
    this.addClass = function (className) {
        cellClassList.push(className);
    };

    this.addClassList = function (classList) {
        for (var i = 0; i < classList.length; i++) {
            cellClassList.push(classList[i]);
        }
    };

    /**
     * Returns the cell's content.
     */
    this.getContent = function () {
        return cellContent;
    };

    /**
     * Generates the html and returns it.
     * @returns {Element}
     */
    this.toHTML = function () {
        var html = document.createElement('div');
        html.classList.add('cell');
        html.innerHTML = cellContent;

        for (var i = 0; i < cellClassList.length; i++) {
            html.classList.add(cellClassList[i]);
        }
        return html;
    };
}
