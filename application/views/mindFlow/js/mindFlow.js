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
    moveFlow();

    // Adding Events to the page

    // Flow menu click
    $flowMenu.find('a').click(function () {
        var selectedStep = jQuery(this).parent().attr('id');
        if (currentStep == selectedStep) {
            return;
        }
        currentStep = selectedStep;

        moveFlow();
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

function logThis(msg) {
    jQuery('#log').append(msg);
}

function moveFlow() {
    var $pastFlow = jQuery('#pastFlow');
    var $futureFlow = jQuery('#futureFlow');
    var stepSize = 170;
    var fullSize = 1000;
    var multiplier;

    logThis('Moving Flow to step ' + currentStep);
    
    switch (currentStep)
    {
        case 'step1':
            multiplier = 0;
            break;
        case 'step2':
            multiplier = 1;
            break;
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