/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 *
 * Helper to build a table.
 *
 * The 'parameters' parameter contains a different number of properties, some required and some optional.
 *
 * Required Parameters:
 *  colModel - Contains the columns that this table will use in the content section.
 *      colModel - dataIndex - Required parameter in every column. Specifies from which column the data will be loaded.
 *      colModel - staticContent - Required parameter in every column.
 *
 * Optional Parameters:
 *
 /***********************************************************************************************************************
 * Object Table
 *
 * Public methods
 * + addHeaderElement
 * + addContentElement
 * + addFooterElement
 * + addContentData
 * + removeContentId
 *
 * Private methods
 * - validateParameters
 * - initializeBasics
 * - initializeColumnModel
 * - headerLength
 * - contentLength
 * - footerLength
 * - displayError
 *
 ***********************************************************************************************************************
 *
 * Date: 31/07/13 13:00
 */

/**
 * Table constructor.
 * @param {string} tableId - Id of the table html element.
 * @param {Object} parameters - Required and optional parameters for the construction of the table.
 * @constructor
 */
function Table(tableId, parameters) {
    /**
     * Table Element. If not found is initialized as undefined, which will generate an error in the validation.
     * @type {HTMLElement|string}
     */
    var tableLocation = document.getElementById(tableId) || 'undefined';

    /**
     * TBody Element. From here all the rows will hang.
     * @type {HTMLElement}
     */
    var workspace;

    /**
     * List of column models used for the construction of the content rows.
     * @type {Object}
     */
    var colModel = [];

    /**
     * Amount of columns with data expected when adding a row to the content.
     * Columns with staticContent are not considered.
     * @type {number}
     */
    var dataColumns = 0;

    /**
     * Object that contains the header rows.
     * @type {Object}
     */
    var headerRows = {};

    /**
     * Object that contains the content rows.
     * @type {Object}
     */
    var contentRows = {};

    /**
     * Object that contains the footer rows.
     * @type {Object}
     */
    var footerRows = {};

    /*****************************************************************************************************************/
    /** Table construction                                                                                          **/
    /*****************************************************************************************************************/

    try {
        // Validate received parameters
        validateParameters();

        // Initializing Table
        initializeBasics();
        initializeColumnModel();
    }
    catch (error) {
        displayError(error);
    }

    /*****************************************************************************************************************/
    /** Private functions definition                                                                                **/
    /*****************************************************************************************************************/

    /**
     * Private function validateParameters.
     * Validates the parameters received in the constructor in order to verify that this table can be constructed properly.
     */
    function validateParameters() {
        if (tableLocation == 'undefined' || tableLocation.tagName != 'TABLE') {
            throw 'Table construction needs a valid Table Id';
        }

        if (typeof(parameters) == 'undefined') {
            throw 'Table construction needs parameters.';
        }

        if (typeof(parameters['colModel']) == 'undefined') {
            throw 'Table constructions needs the parameter colModel.';
        }
    }

    /**
     * Private function initializeBasics
     * Builds the TBody element, sets it up and appends it to the table element.
     */
    function initializeBasics() {
        var tbody = document.createElement('tbody');
        tbody.setAttribute('id', tableId + '_workspace');
        tbody.classList.add('workspace');
        tableLocation.appendChild(tbody);
        workspace = tbody;
    }

    /**
     * Private function initializeColumnModel
     * Verifies that all columns contain either a dataIndex parameter or a staticElement function.
     * Counts the columns with dataIndex in the property dataColumn.
     */
    function initializeColumnModel() {
        var receivedColModel = parameters['colModel'];

        for (var i = 0; i < receivedColModel.length; i++) {
            colModel[i] = receivedColModel[i];

            if (typeof (colModel[i].dataIndex) == 'undefined') {
                if (typeof (colModel[i].staticElement) == 'undefined') {
                    throw 'A column without dataIndex needs to have a staticContent function defined.';
                }
            } else {
                dataColumns++;
            }
        }
    }

    /**
     * Private function headerLength
     * Returns the current amount of rows that the header contains.
     */
    function headerLength() {
        return Object.keys(headerRows).length;
    }

    /**
     * Private function contentLength
     * Returns the current amount of rows that the content contains.
     */
    function contentLength() {
        return Object.keys(contentRows).length;
    }

    /**
     * Private function footerLength
     * Returns the current amount of rows that the footer contains.
     */
    function footerLength() {
        return Object.keys(footerRows).length;
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
     * Public function addHeaderElement
     * Adds a row in the last position of the header.
     * Sets its Id.
     * Adds it to the headerRows.
     * @param rowElement
     */
    this.addHeaderElement = function (rowElement) {
        // Generate Id
        var headerId = 'header_' + (headerLength() + 1);

        // Set Id of the element
        rowElement.setAttribute('id', headerId);

        // Append header to the workspace
        workspace.appendChild(rowElement);

        // Add row to the contentRows.
        headerRows[headerId] = rowElement;
    };

    /**
     * Public function addContentElement
     * Adds a row in the last position of the content.
     * Sets its Id.
     * Adds it to the contentRows.
     * @param rowElement
     */
    this.addContentElement = function (rowElement) {
        // Generate Id
        var contentId = 'content_' + (contentLength() + 1);

        // Set Id of the element
        rowElement.setAttribute('id', contentId);
        if (contentLength() == 0) {
            if (footerLength() == 0) {
                // In case there are no content rows and the footer is not defined, add the row in the last position of
                // the workspace.
                workspace.appendChild(rowElement);
            } else {
                // In case there are no content rows but there are footer rows defined, add the row before the first
                // footer row.
                workspace.insertBefore(rowElement, footerRows['footer_1']);
            }
        } else {
            // In case there are content rows, search for the last content row and add the row before the next element
            // of the last content row (this means - add the row after the last content row)
            var lastSibling = 'content_' + contentLength();
            workspace.insertBefore(rowElement, contentRows[lastSibling].nextSibling);
        }

        // Add row to the contentRows.
        contentRows[contentId] = rowElement;
    };

    /**
     * Public function addFooterElement
     * Adds a row in the last position of the footer.
     * Sets its Id.
     * Adds it to the footerRows.
     * @param rowElement
     */
    this.addFooterElement = function (rowElement) {
        // Generate Id
        var footerId = 'footer_' + (footerLength() + 1);

        // Set Id of the element
        rowElement.setAttribute('id', footerId);

        // Append header to the workspace
        workspace.appendChild(rowElement);

        // Add row to the contentRows.
        footerRows[footerId] = rowElement;
    };

    /**
     * Public function addContentData
     * Uses the column model defined in the table to generate a content row that contains the data specified by the
     * passed parameter.
     * @param data
     */
    this.addContentData = function (data) {
        // Verify data content.
        if (Object.keys(data).length != dataColumns) {
            throw 'Data received contains a number of cells that does not match with the expected number of cells defined by the colModel.';
        }

        if (typeof(data['id']) == 'undefined') {
            throw 'Data received does not contain an identification column.';
        }

        // Generate cells.
        var cells = [];

        jQuery.each(colModel, function (i, col) {
            var newCell = {};

            if (typeof(col.dataIndex) == 'undefined') {
                newCell.html = col.staticElement(data['id']);
            } else {
                newCell.html = data[col.dataIndex];
            }

            if (typeof (col.classList) != 'undefined') {
                newCell.classList = col.classList;
            }
            cells.push(newCell);
        });

        // Generate row
        var newRow = new Row(
            {'cells': cells,
                'classList': ['content']
            });

        // Add to content.
        this.addContentElement(newRow.getRow());
    };

    /**
     * Public function removeContentId
     * Receives the rowName of a content row.
     * Removes it from the workspace.
     * Removes it from the contentRows.
     * Rearranges the contentRows.
     * @param rowName
     */
    this.removeContentId = function (rowName) {
        // Defining required parameters
        var previousContentLength = contentLength();
        var rowId = parseInt(rowName.substr(8));

        // Removing Child
        workspace.removeChild(contentRows[rowName]);
        delete contentRows[rowName];

        // Renaming rows
        for (var i = rowId; i < previousContentLength; i++) {
            contentRows['content_' + i] = contentRows['content_' + (i + 1)];
            contentRows['content_' + i].setAttribute('id', 'content_' + i);
        }

        // Removing last contentRow, as it is duplicated. This is only required if the deleted row was not the last one in the content.
        if (rowId != previousContentLength) {
            delete contentRows['content_' + i];
        }
    };
}