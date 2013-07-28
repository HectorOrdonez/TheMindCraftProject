/**
 * Project: Selfology
 * User: Hector Ordonez
 * Description: WorkOut JS Library
 * Date: 23/07/13 13:00
 */

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

    // 2 - Hide the current content and empty it.
    triggerVisuals('hide');


    // 3 - Make unique ajax request to fill the stepContent with the selected step content.
    uniqueAjaxCall(function (callback) {
        jQuery.ajax({
            type: 'post',
            url: root_url + '/workOut/loadStepChunk',
            data: {
                'step': selectedStep
            }
        }).done(function (data) {
                $stepContent.html(data);
                triggerVisuals('show');
                callback();
            }).fail(function (data) {
                alert('Something went wrong and the step ' + selectedStep + ' could not be load! Here why: ' + data.statusText);
                callback();
            });
    });
}

/**
 * This function manages the visual effects of the Work Out page when selecting a new step.
 * The triggering may happen in two situations:
 * 1 - Start of the page with a starting step as default or clicked by the user directly (example; user clicks Prioritizing step of Work Out directly)
 * 2 - User clicks 'Next' or another step button in the menu.
 *
 * In the first situation there is no step displayed, therefore there is nothing to close.
 * In the second situation there is content displayed and therefore it must be closed.
 *
 * This function needs the parameter action to work; it can be 'open' or 'close'.
 * When this function is called in the situation 1 with the parameter 'close' it must do nothing.
 */
function triggerVisuals(action) {
    if (action == 'hide') {
        toggleNextButton('hide');
        moveStepPointer();
        toggleStepContent('hide', function () {
        });
    } else {
        loadGrid(
            function () {
                toggleNextButton('show');
                toggleStepContent('show', function () {
                });
            });
    }
}

/**
 * Shows or hides the next button for the transitions between steps.
 * @param action
 */
function toggleNextButton(action) {
    var $nextButton = jQuery('#nextStep');

    if (action == 'hide') {
        $nextButton.animate({'opacity': 0});
    } else {
        $nextButton.animate({'opacity': 1, 'display': 'block'});
    }
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

    // Showing pointer (only on page load)
    if ($stepPointer.css('display') == 'none') {
        $stepPointer.css('display', 'block');
    }
}

/**
 * Shows or hides the step content depending on the passed variable action
 * @param {string} action
 */
function toggleStepContent(action, callback) {
    // Set required parameters
    var $stepContent = jQuery('#stepContent');

    if (action == 'hide') {
        $stepContent.animate({'opacity':'0'}, function () {
            callback();
        });
    } else {
        $stepContent.animate({'opacity':'1'}, function () {
            callback();
        });
    }
}

/**
 * Loads the grid depending on the step pointer position.
 */
function loadGrid(callback) {
    // Gets which grid to load
    var pointerPosition = jQuery('#stepPointer').html();

    switch (pointerPosition) {
        case 'stepSelection':
            createSelectionGrid(callback);
            break;
        case 'stepTiming':
            createTimingGrid(callback);
            break;
        case 'stepPrioritizing':
            createPrioritizingGrid(callback);
            break;
    }
}

function generateActionPlan() {
    uniqueAjaxCall(function (callback) {
        jQuery.ajax({
            type: 'post',
            url: root_url + '/workOut/generateActionPlan',
            data: {
            }
        }).done(function (data) {
                callback();
                window.location = root_url + 'action';
            }).fail(function (data) {
                alert('Something went wrong and your action plan could not be generated! Here is why: ' + data.statusText);
                callback();
            });
    });
}

/**
 */
function setErrorMessage($errorDisplayer, message, timeout) {
    $errorDisplayer.html(message);

    setTimeout(function () {
        $errorDisplayer.fadeOut(function () {
            $errorDisplayer.html('');
            $errorDisplayer.fadeIn();
        });
    }, timeout);
}