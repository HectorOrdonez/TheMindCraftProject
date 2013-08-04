/**
 * Project: Selfology
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
 * + getCell
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
     * Element that contains the cell as it has to be append to the desired parent.
     * @type {HTMLElement}
     */
    var cell;

    /**
     * List of classes that the cell uses.
     * @type {Array}
     */
    var classList;

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
            throw 'Parameter html is required..';
        }
    }

    /**
     * Private function initializeCell
     * Builds the TD element and sets it up depending on the parameters received.
     */
    function initializeCell() {
        cell = document.createElement('TD');
        cell.classList.add('cell');
        cell.innerHTML = parameters['html'];

        if (typeof(parameters['classList']) != 'undefined') {
            var classList = parameters['classList'];
            for (var i = 0; i < classList.length; i++) {
                cell.classList.add(classList[i]);
            }
        }

        if (typeof(parameters['colspan']) != 'undefined') {
            cell.setAttribute('colspan', parameters['colspan']);
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
    /** Public functions definition                                                                                **/
    /*****************************************************************************************************************/

    /**
     * Returns the cell property which contains the built TD element.
     * @returns {Element}
     */
    this.getCell = function () {
        return cell;
    };
}
