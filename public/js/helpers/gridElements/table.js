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
 *      colModel[colIndex] - Required parameter in every column. Specifies from which column the data will be loaded.
 *      colModel[customContent] - Optional parameter in every column. Specifies a function that receives the row Id in order to build a custom cell content.
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
     * All table container. Here header, workspace and footer will be appended.
     * @type {HTMLElement}
     */
    var workbench;
    /**
     * The content container Element. Content rows will be appended here.
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
        jQuery(rowElement).insertBefore(workspace);

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
            workspace.appendChild(rowElement);
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
        jQuery(rowElement).insertAfter(workspace);

        // Add row to the contentRows.
        footerRows[footerId] = rowElement;
    };

    /**
     * Public function addContentData
     * Uses the column model defined in the table to generate a content row that contains the data specified by the
     * passed parameter.
     * @param data Object
     */
    this.addContentData = function (data) {
        // Verify data content.
        if (Object.keys(data).length != dataColumns) {
            throw 'Data received contains a number of cells that does not match with the expected number of cells defined by the colModel.';
        }

        if (typeof(data.id) == 'undefined') {
            throw 'Data received does not contain an identification column.';
        }

        // Generate cells.
        var cells = [];
        jQuery.each(colModel, function (i, col) {
            var newCell;
            if (typeof(col.customContent) != 'undefined') {
                newCell = new Cell({html: col.customContent(data)});
            } else {
                newCell = new Cell({html: data[col.colIndex]});
            }

            // Adding to the cell the column related class
            newCell.addClass('col_' + col.colIndex);

            // Adding to the cell the user-specified classes for this column.
            if (typeof (col.classList) != 'undefined') {
                newCell.addClassList(col.classList);
            }
            cells.push(newCell);
        });

        // Generate row
        var newRow = new Row(
            {'cells': cells,
                'classList': ['content']
            });

        // Add to content.
        this.addContentElement(newRow.toHTML());
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

    /*****************************************************************************************************************/
    /** Private functions definition                                                                                **/
    /*****************************************************************************************************************/

    /**
     * Private function validateParameters.
     * Validates the parameters received in the constructor in order to verify that this table can be constructed properly.
     */
    function validateParameters() {
        /** Checking that the location for the table has the right markup **/
        if (tableLocation == 'undefined' || tableLocation.tagName != 'DIV') {
            throw 'Table construction needs a valid div Id';
        }

        /** Checking that the Table object received parameters **/
        if (typeof(parameters) == 'undefined') {
            throw 'Table construction needs parameters.';
        }

        /** Checking that those parameters contain the column model **/
        if (typeof(parameters['colModel']) == 'undefined') {
            throw 'Table construction needs the parameter colModel.';
        }

        /** Checking that the column model contains colIndex or is a staticElement **/
        /** Checking as well that the column model contains one id column, required for the content management **/
        var modelHasId = false;
        for (var i = 0; i < parameters['colModel'].length; i++) {

            if (typeof (parameters['colModel'][i].colIndex) == 'undefined') {
                throw 'Table construction requires all columns to have colIndex defined.';
            } else {
                if (parameters['colModel'][i].colIndex == 'id') {
                    if (modelHasId == true) {
                        throw 'Table construction requires only one Id column.';
                    }
                    modelHasId = true;
                }
            }
        }
        if (modelHasId == false) {
            throw 'Table construction requires an Id column.';
        }
    }

    /**
     * Private function initializeBasics
     * Builds the TBody element, sets it up and appends it to the table element.
     */
    function initializeBasics() {
        workbench = document.createElement('div');
        workbench.setAttribute('id', tableId + '_workbench');
        workbench.classList.add('workbench');
        tableLocation.appendChild(workbench);
        
        workspace = document.createElement('div');
        workspace.setAttribute('id', tableId + '_workspace');
        workspace.classList.add('workspace');
        
        workbench.appendChild(workspace);
    }

    /**
     * Private function initializeColumnModel
     * Verifies that all columns contain either a colIndex.
     */
    function initializeColumnModel() {
        colModel = parameters['colModel'];

        for (var i = 0; i < colModel.length; i++) {

            // dataColumns only increases if customContent is not defined, as that column will not receive data by 
            // parameter; it will be calculated instead with the customContent provided function
            if (typeof (colModel[i].customContent) == 'undefined') {
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
}