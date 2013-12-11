/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * Description: Main JS Library
 * Date: 22/08/13 10:00
 */
jQuery().ready(function () {
    initSpinning();
    
    jQuery('#processActions').find('a').hover(function () {
        jQuery(this).transition({
            scale: 1.2,
            duration: 200
        });
    }, function () {
        jQuery(this).transition({
            scale: 1,
            duration: 200
        });
    });
});

/**
 * This function initializes the spinning of the circle.
 */
function initSpinning()
{
    var $spin = jQuery('#spinningCircle');
    turnCircle(0);

    function turnCircle(degree)
    {
        // Setting rotation to requested degree.
        $spin.css({ WebkitTransform: 'rotate(' + degree + 'deg)'});
        $spin.css({ '-moz-transform' : 'rotate(' + degree + 'deg)'});

        // Checking what do do now 
        // If current degree is a third movement, waits before next movement starts.
        // Else, calls itself again for next degree after a short time.
        if (degree % 120 == 0)
        {
            setTimeout(function() {
                turnCircle(++degree);
            }, 2000);
        } else {
            setTimeout(function() {
                turnCircle(++degree);
            }, 10);
        }
    }
}