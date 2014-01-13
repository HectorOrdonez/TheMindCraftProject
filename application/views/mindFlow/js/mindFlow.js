/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * @date 8/01/14 15:00
 */

var currentStep = '';

jQuery(document).ready(function () {
    // Init parameters
    var $flowMenu = jQuery('#flowMenu');
    currentStep = jQuery('#initStep').html();

    uniqueUserRequest(function (callback) {
        loadCurrentStep(callback);
        moveFlowToCurrentStep();
        callback();
    });

    // Adding Events to the page
    // Flow menu click
    $flowMenu.find('a').click(function () {
        var selectedStep = jQuery(this).parent().attr('id');
        if (currentStep == selectedStep) {
            return;
        }

        uniqueUserRequest(function (callback) {
            currentStep = selectedStep;
            loadCurrentStep(callback);
            moveFlowToCurrentStep();
        });
    });

    // Big options hovering
    $flowMenu.find('.flowOption img').hover(function () {
        jQuery(this).transition({
            scale: 1.33,
            duration: 150,
            queue: false
        });
    }, function () {
        jQuery(this).transition({
            scale: 1,
            duration: 150,
            queue: false
        });
    });
});

function loadCurrentStep(callback) {
    // Init parameters
    var MindFlowGrid;
    var $stepContent = jQuery('#stepContent');
    var afterLoadCallback = function () {
        $stepContent.fadeIn();
        callback();
    };
    var doAfterFadeOut = function () {
        switch (currentStep) {
            case 'step1':
                MindFlowGrid = new BrainStorm($stepContent, afterLoadCallback);
                break;
            case 'step2':
            case 'step21':
                MindFlowGrid = new Selection($stepContent, afterLoadCallback);
                break;
            case 'step22':
                MindFlowGrid = new ApplyTime($stepContent, afterLoadCallback);
                break;
            case 'step23':
                MindFlowGrid = new Prioritize($stepContent, afterLoadCallback);
                break;
            case 'step3':
                MindFlowGrid = new Prioritize();
                //generateActionPlan(afterLoadCallback);
                break;
        }
    };

    // Execute
    $stepContent.fadeOut(function () {
        doAfterFadeOut()
    });
}

/**
 * Function called when flow line needs to be moved to the current step.
 */
function moveFlowToCurrentStep() {

    var $pastFlow = jQuery('#pastFlow');
    var $futureFlow = jQuery('#futureFlow');
    var stepSize = 170;
    var fullSize = 1000;
    var multiplier;

    switch (currentStep) {
        case 'step1':
            multiplier = 0;
            break;
        case 'step2':
            currentStep = 'step21';
        case 'step21':
            multiplier = 2;
            break;
        case 'step22':
            multiplier = 3;
            break;
        case 'step23':
            multiplier = 4;
            break;
        case 'step3':
            multiplier = 5;
            break;
    }

    $pastFlow.animate({
        width: multiplier * stepSize
    });

    $futureFlow.animate({
        width: fullSize - ( (1 + multiplier) * stepSize)
    });
}