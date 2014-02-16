/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * General JS Library.
 * Contains config and general startup script.
 * Date: 25/07/13 1:30
 */

/**
 * Definition of the Root Url to use in links.
 * @type {string}
 */
var root_url = 'http://localhost/projects/themindcraftproject/';
// var root_url = 'http://www.themindcraftproject.org/';

/**
 * On Document Ready...
 */
jQuery().ready(function () {
    /**
     * Sets Logo to be a link to the main page.
     */
    jQuery('#logo').click(function() {
        window.location.href = root_url;
    });
});