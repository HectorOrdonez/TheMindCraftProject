/**
 * Project: Selfology
 * User: Hector Ordonez
 * Description:
 *
 * Helper to build a grid of dynamic data.
 *
 * The 'parameters' parameter contains a different number of properties, some required and some optional.
 *
 * Required Parameters:
 *  url - Where to request the data.
 *
 * Optional Parameters:
 *  extraData - Additional data to send in the data request.
 *  eventDL - Function to call when data is loaded.
 *  eventEOI - Function to call when end of initialization.
 *
 /***********************************************************************************************************************
 * Object Grid
 *
 * Public methods
 *
 * Private methods
 * - validateParameters
 * - initializeGrid
 * - getData
 * - triggerConstruction
 * - displayError
 * - fillWhenReady
 * - fillTable
 *
 ***********************************************************************************************************************
 *
 * Date: 31/07/13 13:00
 */

/**
 * Grid constructor.
 * @param {Table} table - Table object where the data will be loaded.
 * @param {Object} parameters - Required and optional parameters for the construction of the grid.
 * @constructor
 */
function Grid(table, parameters) {
    /*****************************************************************************************************************/
    /** Static parameters definition                                                                                **/
    /*****************************************************************************************************************/

    /**
     * Number of seconds that the Grid will wait when requesting an ajax call before considering it failed.
     * @type {number}
     */
    Grid.MAX_AJAX_WAITING_TIME = 3;

    /*****************************************************************************************************************/
    /** Parameters definition                                                                                       **/
    /*****************************************************************************************************************/

    /**
     * Url where the Grid will request the data.
     * @type {string}
     */
    var dataSource;

    /**
     * Event Data Loaded. Optional function to be called when the data is loaded.
     * This function will be called after successful data loads and before eventEOI.
     * @type {Function}
     */
    var eventDL;

    /**
     * Event End Of Initialization. Optional function to be called after the initialization.
     * This function will be called after successful and unsuccessful data loads, after eventDL.
     *
     * Notice that in case of an unsuccessful data load, the eventDL will not be called.
     * @type {Function}
     */
    var eventEOI;

    /**
     * Additional parameters to send in the ajax call to the server.
     * @type {Object}
     */
    var extraData;
    /**
     * Boolean that informs the Grid if the ajax request answered with an error.
     * @type {boolean}
     */
    /**
     * List of data received from Ajax call.
     * @type {Array}
     */
    var gridData = [];

    /**
     * Boolean that informs the Grid that the ajax request was successful.
     * @type {boolean}
     */
    var gridDataReady = false;

    /**
     * Boolean that informs the Grid that the ajax request was unsuccessful.
     * @type {boolean}
     */
    var loadingError = false;

    /**
     * The Grid itself. For accessing public parameters from private functions.
     * @type {Grid}
     */
    var self = this;

    /**
     * Public Table container
     * @type {Table}
     */
    this.table = null;

    /*****************************************************************************************************************/
    /** Grid construction                                                                                           **/
    /*****************************************************************************************************************/

    try {
        // Validate received parameters
        validateParameters();

        // Initializing Grid
        initializeGrid();

        // Requesting data to server.
        getData();

        // Triggers construction of the grid when data is ready
        triggerConstruction();
    }
    catch (error) {
        displayError(error);
    }

    /*****************************************************************************************************************/
    /** Private functions definition                                                                                **/
    /*****************************************************************************************************************/

    /**
     * Private function validateParameters.
     * Validates the parameters received in the constructor in order to verify that this grid can be constructed properly.
     */
    function validateParameters() {
        // Validating Table
        if (!(table instanceof Table)) {
            throw 'Error in grid construction: expected Table parameter to be of Table type.';
        }

        // Validating Grid Parameters
        if (typeof (parameters['url']) == 'undefined') {
            throw 'Required parameter [url] not defined.';
        }
    }

    /**
     * Private function initializeGrid
     * Initializes all the parameters required for the grid construction.
     */
    function initializeGrid() {
        self.table = table;
        dataSource = parameters['url'];
        extraData = parameters['extraData'] || {};
        eventDL = parameters['eventDL'] || function () {};
        eventEOI = parameters['eventEOI'] || function () {};
    }

    /**
     * Private function getData
     * Makes an ajax request to the dataSource.
     * If successful, fills the gridData array with the response. This response needs to be in JSON format.
     * If unsuccessful fills the gridData with the error message.
     */
    function getData() {
        jQuery.ajax({
            type: 'post',
            url: dataSource,
            data: extraData
        }).done(
            function (dataList) {
                var jsonObject = jQuery.parseJSON(dataList);

                jQuery.each(jsonObject, function (index, data) {
                    gridData[gridData.length] = data;
                });
                gridDataReady = true;
            }
        ).fail(
            function (data) {
                loadingError = true;
                displayError('Could not load the data. Error received: ' + data.statusText);
            }
        );
    }

    /**
     * Private function fillWhenReady.
     * Recursive function that calls itself every second to check if the ajax request has been answered and, if so,
     * calls the initialRender.
     *
     * In case the waiting exceeds the MAX_AJAX_WAITING_TIME the function dataUnavailable is triggered.
     *
     * @param i - Number of times this function is called.
     */
    function fillWhenReady(i) {
        setTimeout(function () {
            if (gridDataReady == true) {
                fillTable();
            }
            else if (loadingError != true) {
                if (i < Grid.MAX_AJAX_WAITING_TIME) {
                    fillWhenReady(i + 1);
                } else {
                    throw 'Communication with Server failed.';
                }
            }
        }, 1000);
    }

    /**
     * Private function fillTable.
     * Called when the grid has defined completely a table.
     * Calls the function afterComplete if defined.
     * Then calls the render function of the table object.
     * Then calls the function afterRender if defined.
     */
    function fillTable() {
        for (var i = 0; i < gridData.length; i++) {
            table.addContentData(gridData[i]);
        }
        eventDL();
        eventEOI();
    }

    /**
     * Function triggerConstruction
     * Adds the grid data to the table contents, if it is ready.
     * If it is not ready the function startWhenReady will be called.
     */
    function triggerConstruction() {
        if (gridDataReady == true) {
            fillTable();
        } else {
            fillWhenReady(0);
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
}