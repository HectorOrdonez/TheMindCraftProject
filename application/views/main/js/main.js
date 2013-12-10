/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * Description: Main JS Library
 * Date: 22/08/13 10:00
 */
jQuery().ready(function () {
    jQuery('#processActions a').hover(function () {
        console.log('hover!');
        jQuery(this).transition({
            scale: 1.2,
            duration: 200
        });
    }, function () {
        console.log('no hover!');
        jQuery(this).transition({
            scale: 1,
            duration: 200
        });
    });
    
    var $spin = jQuery('#spinningCircle');
    
    spinForever(0);
    
    function spinForever(degree)
    {
        var turn = Math.floor(degree / 360);
        
        if ( (turn % 2) != 0)
        {
            $spin.css({ WebkitTransform: 'rotate(' + degree + 'deg)'});
            $spin.css({ '-moz-transform' : 'rotate(' + degree + 'deg)'});
        }
        
        setTimeout(function() {
            spinForever(++degree);
        }, 5);
    }
});
