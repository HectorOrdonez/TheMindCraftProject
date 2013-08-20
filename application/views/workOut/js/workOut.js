/**
 * Project: Selfology
 * User: Hector Ordonez
 * Description: WorkOut JS Library
 * Date: 23/07/13 13:00
 */

/**
 * Global variable that will contain the current grid.
 * @type {Grid}
 */
var workOutGrid;

jQuery().ready(function () {
    // Element definitions
    var $stepPointer = jQuery('#stepPointer');

    // Setting events
    jQuery('.stepSelector').click(function () {
        if ($stepPointer.html() != jQuery(this).attr('id')) {
            changeStep(jQuery(this).attr('id'));
        }
    });
    jQuery('#nextStep').click(function () {
        switch ($stepPointer.html()) {
            case 'stepSelection':
                changeStep('stepTiming');
                break;
            case 'stepTiming':
                changeStep('stepPrioritizing');
                break;
            case 'stepPrioritizing':
                generateActionPlan();
                break;
        }
    });

    // Initializing step
    var startingStep = $stepPointer.html();
    changeStep(startingStep);
});

/**
 * This function requests to the server the View Chunk related to this step.
 * Afterwards the js script that initializes the related step functionality is called.
 * @param {string} selectedStep
 */
function changeStep(selectedStep) {
    // Element definitions
    var $stepContent = jQuery('#stepContent');
    var $stepPointer = jQuery('#stepPointer');

    // 1 - Set stepPointer to selected step
    $stepPointer.html(selectedStep);

    // 2 - Move Pointer to set position
    moveStepPointer();

    // 3 - Make unique ajax request to fill the stepContent with the selected step content.
    uniqueUserRequest(function (callback) {
        jQuery.ajax({
                type: 'post',
                url: root_url + '/workOut/loadStepChunk',
                data: {
                    'step': selectedStep
                }
            }
        ).done(function (data) {
                $stepContent.html(data);
                loadGrid();
                callback();
            }
        ).fail(function (data) {
                callback();
            }
        );
    });
}

/**
 * Moves the Step Pointer to the related position, depending on its content.
 */
function moveStepPointer() {
    // Set required parameters
    var $stepPointer = jQuery('#stepPointer');
    var pointerPosition = $stepPointer.html();
    var elemWidth = $stepPointer.width();

    // Moving pointer
    switch (pointerPosition) {
        case 'stepSelection':
            $stepPointer.animate({'left': 0 });
            break;
        case 'stepTiming':
            $stepPointer.animate({'left': elemWidth });
            break;
        case 'stepPrioritizing':
            $stepPointer.animate({'left': elemWidth * 2 });
            break;
    }
}

/**
 * Loads the grid depending on the step pointer position.
 */
function loadGrid() {
    // Gets which grid to load
    var pointerPosition = jQuery('#stepPointer').html();

    switch (pointerPosition) {
        case 'stepSelection':
            createSelectionGrid();
            break;
        case 'stepTiming':
            createTimingGrid();
            break;
        case 'stepPrioritizing':
            createPrioritizingGrid();
            break;
    }
}

/**
 * Function triggered when User wants to go to the next step of Prioritizing. The set ideas will turn into actions and, after, User will be redirected to the action page.
 */
function generateActionPlan() {
    uniqueUserRequest(function (callback) {
        jQuery.ajax({
                type: 'post',
                url: root_url + '/workOut/generateActionPlan',
                data: {
                }
            }
        ).done(function () {
                callback();
                window.location = root_url + 'action';
            }
        ).fail(function (data) {
                console.error('Something went wrong and your action plan could not be generated! Here is why: ' + data.statusText);
                callback();
            }
        );
    });
}