/**
 * Project: Selfology
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
 * Row constructor.
 * @param {Object} parameters - Required and optional parameters for the construction of the row.
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
    var row;

    /**
     * List of cells that this row contains.
     * @type {Array}
     */
    var cells = [];

    /**
     * List of classes that the cell uses.
     * @type {Array}
     */
    var classList;

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
    function validateParameters()
    {
        if (typeof(parameters) == 'undefined') {
            throw 'Row construction needs parameters.';
        }

        if (typeof(parameters['cells']) == 'undefined') {
            throw 'Row construction needs cells.';
        }
    }

    /**
     * Private function initializeRow
     * Builds the TR element and sets it up depending on the parameters received.
     */
    function initializeRow()
    {
        row = document.createElement('TR');
        row.classList.add('row');

        jQuery.each(parameters['cells'], function(i, cellParameters){
            var newCell = new Cell(cellParameters);
            cells.push(newCell);
            row.appendChild(newCell.getCell());
        });

        if (typeof(parameters['classList']) != 'undefined')
        {
            var classList = parameters['classList'];
            for(var i=0; i < classList.length; i++ )
            {
                row.classList.add(classList[i]);
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
    /** Public functions definition                                                                                **/
    /*****************************************************************************************************************/

    /**
     * Returns the row property which contains the built TR element.
     * @returns {Element}
     */
    this.getRow = function () {
        return row;
    };
}
