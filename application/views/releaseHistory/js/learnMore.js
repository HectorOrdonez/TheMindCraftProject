/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * Description: LearnMore JS Library
 * Date: 31/01/14 10:00
 */
jQuery().ready(function () {
    // Event listeners
    jQuery('#showMe').click(function(){
        jQuery('#learnMore').fadeOut(function(){
            jQuery('#websiteHistory').fadeIn(); 
        });
    });
});