<?php
/**
 * Project: The Mindcraft Project
 * User: Hector Ordonez
 * Description:
 * Controller of the page releaseHistory.
 * Here a historical log of the releases of this project will be displayed.
 * Date: 23/07/13 12:00
 */

namespace application\controllers;

use application\engine\Controller;

class releaseHistory extends Controller
{
    /**
     * Array of Arrays.
     * @var array
     */
    private $_releaseLogHistory;

    /**
     * Array of Strings
     * @var
     */
    private $_developmentVersion;

    /**
     * Controller constructor for the Release History page.
     */
    public function __construct()
    {
        parent::__construct();

        // Setting version under construction
        $this->_setDevelopmentVersion('0.300', '17/02/2013', array (
            '[Feature] - Release fully functional version of MindCraft!!'
        ));
        
        // Setting Historical Log of releases
        $this->_addHistoryLog('0.291', '13/02/2013', array (
            '[Debug] - Fixed issue in Settings page where Username could not be changed.',
            '[Feature] - Documented User model class',
        ));
        $this->_addHistoryLog('0.290', '11/02/2013', array (
            '[Refactor] - Database design modified - Idea, Mission and Routines come to play. Refactored models accordingly',
            '[Feature] - ApplyTime step has been implemented and it is just AWESOME!',
            '[Refactor] - Improved other MindFlow steps, for faster loading and code cleaning,',
            '[Feature] - New visuals for date picker.',
            '[Delete] - Website does not use anymore the Jquery Ui styles.'
        ));
        $this->_addHistoryLog('0.283', '03/02/2013', array (
            '[Feature] - Added new feature to JQuery date-picker; an AfterDisplay event.',
            '[Feature] - Build ApplyTime basic structure',
            '[Feature] - Created MindCraft Calendar awesome style!'
        ));
        $this->_addHistoryLog('0.2823', '01/02/2013', array (
            '[Core Update] - In order to create the customized Calendar, JQuery UI is updated and the CSS removed.'
        ));
        $this->_addHistoryLog('0.2822', '31/01/2013', array (
            '[Debug] - User mail was not being saved on Sign Up.'
        ));
        $this->_addHistoryLog('0.2821', '31/01/2013', array (
            '[Feature] - User last login re-implemented.'
        ));
        $this->_addHistoryLog('0.282', '31/01/2013', array (
            '[Feature] - Minor changes here and there to make the website stable - no ugly stuff like broken links',
            '[Debug] - Profile points to Settings page, meanwhile Profile itself is not developed.',
            '[Feature] - LearnMore points to Release History, meanwhile LearnMore itself is not developed.',
        ));
        $this->_addHistoryLog('0.281', '30/01/2013', array (
            '[Feature] - Implemented new Prioritize step visuals and functionality.',
            '[Refactor] - Changed importance and urgency Idea columns to important and urgent. Changed affected code.',
        ));
        $this->_addHistoryLog('0.280', '30/01/2013', array (
            '[Code improvement] - Minor changes in headers and footers to make source-code look even more awesome.',
            '[Core Update] - Updated GridElements library, which gives customContent all row data instead id.',
            '[Refactor] - All JS using customContent modified to access rowData.id instead rowId.',
            '[Feature] - Implemented new Selection step visuals and functionality. This required some changes in newIdea.',
            '[Refactor] - Because of the changes in newIdea for Selection, BrainStorm step changed so newIdea reads the data coming from the server and only uses what it needs (before it was using all!)',
        ));
        $this->_addHistoryLog('0.279', '29/01/2013', array (
            '[Refactor] - Refactored Prioritize and Apply Time step to use the new GridElements construction. Removed all functionality beyond basic one, as new developments are coming (yay!).',
            '[Debug] - Fixed bug that allowed pending Users to login.'
        ));
        $this->_addHistoryLog('0.278', '29/01/2013', array (
            '[Refactor] - Refactored Selection step to use the new GridElements construction. Not full functionality as postpone will be replaced by Selecting Idea for further working out.',
        ));
        $this->_addHistoryLog('0.277', '29/01/2013', array (
            '[Refactor] - Modified MindFlow in order to adapt it to the changes in CSS and in Grid Elements.',
            '[Refactor] - Refactored BrainStorm step to use the new GridElements construction. Made some improvements, too.',
            '[Debug] - The design requires to extend sometimes the Body width of 1000. Because of that it has been changed to 1050 to allow minor overflows.',
        ));
        $this->_addHistoryLog('0.276', '29/01/2013', array (
            '[Debug] - SignUp Service had default user state as inactive, when should be pending. Changed!',
            '[Debug] - Fixed bug in UserManagement where username input was selecting all text when clicking it, while only should focus. Now does it nicely!',
            '[Visual improvement] - UserManagement selects for role and state were not properly shown in Firefox. Fixed!',
            '[Visual improvement] - SignUp structure changed and added some styles so it looks the same than Index, in all browsers.'
        ));
        $this->_addHistoryLog('0.275', '29/01/2013', array (
            '[Debug] - Fixed issue with Body style, now it is not required absolute in it.',
            '[Visual improvement] - Minor improvements in index and sign up, so they are build and look the same.',
            '[Refactor] - Reorganized CSS Styles. Changed some styles, where the body content was bigger than body height (and therefore making a scroll bar appear)'
        ));
        $this->_addHistoryLog('0.274', '29/01/2013', array (
            '[Refactor] - Refactored UsersManagement to use the new GridElements construction.'
        ));
        $this->_addHistoryLog('0.273', '29/01/2013', array (
            '[Core Update] - Updated GridElements library to Version 2.0, replacing tables for divs.'
        ));
        $this->_addHistoryLog('0.272', '24/01/2013', array (
            '[Refactor] - Reorganized MindFlow - Prioritize goes before ApplyTime.',
            '[Refactor] - Now MindFlow steps request to MindFlow controller the step by post, not by get.',
            '[Feature] - MindFlow now verifies the step requested.',
            '[Refactor] - Added toArray method in Idea Model to make it handier. Modified MindFlowService to use the new functionality.'
        ));
        $this->_addHistoryLog('0.271', '24/01/2013', array (
            '[Refactor] - Refactor MindFlow to contain all libraries in its place. ',
            '[Refactor] - Prepared application for the new architecture regarding ideas, regular ideas and actions.',
            '[Code improvement] - Moved the Jquery UI libraries to the general Controller, so all logged users have it loaded, everywhere. This is done because this UI is pretty useful, not so heavy, and having it there avoids adding it in different specific controllers.',
        ));
        $this->_addHistoryLog('0.266', '24/01/2013', array (
            '[Feature] - Created Admin library for the amazing Admin crew that informs Admins of the existence of Users whose state is pending.',
            '[Code improvement] - Moved the Jquery UI libraries to the general Controller, so all logged users have it loaded, everywhere. This is done because this UI is pretty useful, not so heavy, and having it there avoids adding it in different specific controllers.',
        ));
        $this->_addHistoryLog('0.265', '23/01/2013', array (
            '[Feature] - Modified UserManagement page to include the new columns State and Mail.',
            '[Refactor] - Minor refactor of UserManagement page construction, to use similar methods than in other places. This requires a better refactor, together with the MindFlow tables.'
        ));
        $this->_addHistoryLog('0.2642', '23/01/2013', array (
            '[Feature] - SignUp functionality implemented.',
            '[Database change] - User table modified; now Users can be active or inactive and can have a mail (actually it is a must for not so-cool people like we the Super Admins!). Changed field name to username.',
            '[Refactor] - Modified access to the User field name to username, according to the nature of the field and to avoid confusions - it is NOT the real name!',
            '[Feature] - Changes in Index page to add a bit of magic to the visuals.',
            '[Feature] - Restored functionality to Index and improved - now login does not redirect from PHP but from JS.',
            '[Refactor] - Enhanced the exception control and communication with client side for SignUp and Login. I expected the system to be of use in other parts of the site.'
        ));
        $this->_addHistoryLog('0.2641', '22/01/2013', array (
            '[Feature] - SignUp page has been build! Front-end at least. Functionality pending to be done.',
            '[Visual improvement] - Updated the index, so the key does cool stuff. Functionality pending to be done.',
        ));
        $this->_addHistoryLog('0.263', '17/01/2013', array (
            '[Debug] - Fixed many issues related to case sensitivity.',
            '[Core Update] - Updated TheMindcraftProject Hecnel Framework to Version 3.002, fixing and a Bootstrap issue.',
        ));
        $this->_addHistoryLog('0.262', '16/01/2013', array (
            '[Feature] - MindFlow Functionality completed.',
            '[Debug] - Modified fields related to dates to display them with Spanish format dd/mm/yyyy.',
        ));
        $this->_addHistoryLog('0.261', '13/01/2013', array (
            '[Feature] - MindFlow new design construction for BrainStorm, Selection, ApplyTime and Prioritize. PerForm has no design so remains undone. Pending functionality.'
        ));
        $this->_addHistoryLog('0.260', '08/01/2013', array (
            '[Feature] - Skeleton of new page: MindFlow. This page will contain the core workflow of the website: BrainStorm, WorkOut, Select, ApplyTime, Prioritize and PerForm. Created the basic behavior for different steps selection. Pending on building the content.'
        ));
        $this->_addHistoryLog('0.251', '08/01/2013', array (
            '[Debug] - Fixed visual issue - circular image in main was increasing height in its rotations.'
        ));
        $this->_addHistoryLog('0.250', '07/01/2013', array (
            'Happy new year!',
            '[Core Update] - Updated TheMindcraftProject Hecnel Framework to Version 3.001.',
            '[Refactor] - Absolutely awesome refactor of the whole project due to lots of improvements that new Hecnel brings.',
            '[Refactor] - Library object is replaced by Service object.',
            '[Refactor] - New View engine means changes in related code.',
            '[Refactor] - Validation system disappears, being replaced by a new Form and Input system',
            '[Refactor] - Previous Database-Model system is smashed by php-activerecord, which brings lots of awesomeness to the project.',
            '[Etc] - A high number of issues are here and there. I am not concerned about it because the new design is still on the way and changes are going to be done.',
        ));
        $this->_addHistoryLog('0.245', '11/12/2013', array (
            '[Debug] - Minor issue with the Welcoming message.'
        ));
        $this->_addHistoryLog('0.244', '11/12/2013', array (
            '[Visual improvement] - Added text to the process actions. Minor modifications in the image sizes. '
        ));
        $this->_addHistoryLog('0.243', '11/12/2013', array (
            '[Visual improvement] - Replaced temporal Add image for creating new ideas with new Plus image.'
        ));
        $this->_addHistoryLog('0.242', '10/12/2013', array (
            '[Debug] - Minor fixes in the visuals of the new table design implementation.'
        ));
        $this->_addHistoryLog('0.241', '10/12/2013', array (
            '[Visual improvement] - Added partial rotation and stop sequence to the circle spinning in the main page.'
        ));
        $this->_addHistoryLog('0.24', '10/12/2013', array (
            '[Code improvement] - Added JQuery plugin Transit to the site.',
            '[Visual improvement] - Added movement to the center of the Main page.',
            '[Visual improvement] - Replaced previous images for main page links to new ones without text. Added events to it, so now they get bigger when hovering.'
        ));
        $this->_addHistoryLog('0.23', '10/12/2013', array (
            '[Refactor] - Implemented new table design.',
            '[Refactor] - Removed usage of old images and added two new ones. Modified code to use the new images.'
        ));
        $this->_addHistoryLog('0.22', '09/12/2013', array (
            '[Visual improvement] - New style for Main page.'
        ));
        $this->_addHistoryLog('0.213', '08/12/2013', array (
            '[Visual improvement] - Added new font and colors default standard.',
            '[Etc] - Removed previous font classes from project. Replaced, when defined, new font style.'
        ));
        $this->_addHistoryLog('0.212', '08/12/2013', array (
            '[Debug] - Fixed issue where some scripts where trying to get the body by the old ID. Replaced with the new one.',
            '[Etc] - Some comments had Project: Organizator. Replaced with The Mindcraft Project.',
            '[Etc] - Minor changes in the views in order to tabulate properly the code in Source-View mode.',
            '[Visual improvement] - Changes for the Logged In styles in Header and Footer, for both regular and admin users.'
        ));
        $this->_addHistoryLog('0.211', '08/12/2013', array (
            '[Refactor] - More styles movement. With this release the non-logged styles are organized.'
        ));
        $this->_addHistoryLog('0.21', '08/12/2013', array (
            '[Refactor] - Changed header and footer related files structure.',
            '[Refactor] - Separated header and footer styles in different files.',
            '[Visual improvement] - Finished the minor changes applied in previous update (buggy)'
        ));
        $this->_addHistoryLog('0.20', '22/11/2013', array (
            '[Visual improvement] - Minor changes in Index visuals.'
        ));
        $this->_addHistoryLog('0.19', '22/11/2013', array (
            '[Core Update] - Updated TheMindcraftProject Hecnel Framework to Version 1.84.'
        ));
        $this->_addHistoryLog('0.18', '22/11/2013', array (
            '[Debug] - Added some modifications to prevent issues with Session.',
            '[Feature] - SignUp page skeleton.'
        ));
        $this->_addHistoryLog('0.17', '20/10/2013', array (
            '[Etc] - Reactivation! The Mindcraft Project\'s on the road again!!',
            '[Debug] - Fixed a link that was wrong - learnMore is redirecting to releaseHistory. We do not have a Learn More page yet :(',
            '[Etc] - Modified settings files for development. Of course, Live is not impacted by those changes.'
        ));
        $this->_addHistoryLog('0.161', '22/08/2013', array (
            '[Refactor] - Project name changed. Root folder changed accordingly too. Selfology now is The Mindcraft Project.'
        ));
        $this->_addHistoryLog('0.16', '20/08/2013', array (
            '[Production] - First release to Production!! Check out :) http: http://themindcraftproject.org/. In order to achieve this and as a consequence, the following changes have been done...',
            '[Code improvement] - Added in the htaccess file the config for the live server. It contains the code for both development and live, and needs to be switched when working on one another.'.
            '[Debug] - Index page now logs in and out properly. Before there was a short delay in live',
            '[Debug] - Errors appear in live as the PHP version the hosting uses is case sensitive. Changes in order to have the uppers and the lowers where they must be.',
            '[Code improvement] - Changes in Views so, when User wants to read the source code of a page, the lines tabulates properly.',
            '[Feature] - Removed the welcoming static image for admin footer (Hello Hector) and added dynamic text (Hello userName).'
        ));
        $this->_addHistoryLog('0.151', '07/08/2013', array (
            '[Temporal] - Added SQLExport of the current Database structure plus the Admin Users to allow a fast startup in another machine.'
        ));
        $this->_addHistoryLog('0.15', '07/08/2013', array (
            '[Code improvement] - Removed TODOS that belong to the Framework.',
            '[Temporal] - Added the JQuery Ui CSS for the datepicker. Eventually we need our own CSS version or even our own datepicker library.',
            '[Code improvement] - Removed not required libraries and deprecated functions - setErrorMessage is replaced by setInfoMessage.'
        ));
        $this->_addHistoryLog('0.14', '07/08/2013', array (
            '[Refactor] - Changed the Users Management page to use the new grid. New methods for the User creation, edition and deletion.'
        ));
        $this->_addHistoryLog('0.13', '06/08/2013', array (
            '[Feature] - Added functionality to the Action stage. Now Users can finish or delete their actions. Besides an historical table is shown with the previous finished actions.',
            '[Code improvement] - Deleted non-necessary CSS libraries for JQGrid. Currently only the Users Management section (for admins) is still using (or trying to, as they do not exist anymore!) the JQgrids.'
        ));
        $this->_addHistoryLog('0.12', '06/08/2013', array (
            '[Code improvement] - Moved CSS for the styling of table contents to the gridElements sheet, so it is not required in the sheets that uses it, except in case a redefinition is needed.',
            '[Feature] - Added functionality to the Prioritizing step on the Work Out stage: now Idea\'s priority can be set. It is possible that an Action column might be required; however at this point it is not sure, so the design of the feature is minimal.'
        ));
        $this->_addHistoryLog('0.11', '05/08/2013', array (
            '[Feature] - Added full functionality to the Apply Time step on the Work Out stage. Now it allows to set the date and time of the idea, and to define it as recurrent by setting its frequency.',
            '[Code improvement] - Added new function to the General JavaScript library - isChecked. To use with a Checkbox in order to know whether is checked or not.',
            '[Temporal] - Added a new JQuery library - Timepicker, which allows the time picker in the Apply Time step to show all timing possibilities. In future term a custom function should replace this.',
            '[Refactor] - Refactored uniqueAjaxCall to uniqueUserRequest, as will as well manage requests that have time related needs, like fading outs.'
        ));
        $this->_addHistoryLog('0.10', '04/08/2103', array (
            '[Feature] - Added full functionality to the Selection step on the Work Out stage. Now Work Out allows Create, Edit, Delete and Hold Over Ideas.',
            '[Feature] - Idea concept improved; now it allows the parameter Postponed. This reacts to the action Hold Over in the selection step. Idea Model is refactored and the idea selection for the brainstorm and the selection step of the work out filters by postponed - false. The code is changed in order to make it easier to get the active ideas from the User. This affects all libraries that need to get active ideas from Idea Model.',
            '[Feature] - Extended the login system - now when a User logs into the system a new parameter is updated in the User Model: the last_login. This parameter allows the system to know if the User is logging a different day than the last time and, if that is the case, the postponed ideas will be set to "not postponed".'
        ));
        $this->_addHistoryLog('0.09', '04/08/2013', array (
            '[Feature] - Redesigned the Grid construction system, making it far more flexible and readable.',
            '[Refactor] - Controllers and Libraries related to Work Out stage changed so it works for the basics.',
            '[Temporal] - Workout visual features removed. Visuals are not yet defined so it will remain simple until the visual design is provided.'
        ));
        $this->_addHistoryLog('0.08', '28/07/2013', array (
            '[Feature] - New Grid construction system, used Javascript OOP. Refactored Brainstorm, selection, timing, prioritizing and action grids.',
            '[Visual improvement] - New styles for header, admin and images.',
            '[Visual improvement] - Adjustments here and there to make it look pretty.'
        ));
        $this->_addHistoryLog('0.07', '27/07/2013', array(
            '[Debug] - Content was getting messy when the width of the browser was too narrow. Set min-width in the header, content and footer, so now there is a minimum width.',
            '[Debug] - When not logged in the header and the footer were not suppose to be as in a logged in situation. Created special header and footer for non logged in users and logged in ones. This is a workaround meanwhile the Hecnel Framework does not works a way to use multiple headers and footers.',
            '[Debug] - Fixed UserManagementLibrary; was trying to update the User Id.'
        ));
        $this->_addHistoryLog('0.06', '27/07/2013', array(
            '[Feature] - Added basic visual and functionality features to the Action tool',
            '[New design and architecture concept] - As part of the new architecture designed in V0.05, the Work Out tool now is divided in three sections. Implemented the visual characteristics and navigation.',
            '[Feature] - Refactored the old IdeaToAction tool to the new Selection tool, first part of Work Out section.',
            '[Feature] - Created the basics of the Timing tool, second part of Work Out section.',
            '[Feature] - Created the basics of the Prioritizing tool, third part of Work Out section.',
            '[Feature] - Created the basics of the Settings section so User can change its name and password.',
            '[Visual improvement] - Minor adjustments in the header and content css in order to avoid having the logo upon selectable content.',
            '[Debug] - Fixed relative paths in JS; this was giving issues depending on whether the controller received or not Index as method. Now links will always be absolute.',
            '[Visual improvement] - Moved Left Panel from header to the left of the body.'
        ));
        $this->_addHistoryLog('0.05', '25/07/2013', array(
            '[New design and architecture concept] - Changed names and images to add a new project model. Now the process starts at Brainstorm, IdeaToAction is the "Work Out" of the ideas, and the action list is shown in the Action stage.',
            '[Feature] - Added skeleton of the following new features; Settings, Action and Profile.',
            '[Feature] - Added an Admin Panel.',
            '[Feature] - Added a Users Management tool for Admins.',
            '[Feature] - Implemented the functionality to the "IdeaToAction" old page, which now is called "Work Out".',
            '[Minor improvement] - Made the Logo clickable.'
        ));
        $this->_addHistoryLog('0.04', '24/07/2013', array(
            '[Code improvement] - Multiple minor changes in the comments and structure.',
            '[Visual improvement] - Implemented new visual features, using Zuzanna\'s graphic designs.'
        ));
        $this->_addHistoryLog('0.03', '23/07/2013', array(
            '[Debug] - Fixed little issue with the pagination of the Brainstorm page.',
            '[Feature] - Created skeleton of IdeaToAction page. Its purpose is to manage the already conceived ideas and filter them; decided if they must be done, if user wants to hold them over, and with which priority do them. Functionality pending to be done.',
            '[Feature upgrade] - Upgraded Brainstorm page; now the ideas have date of creation and, when listing the ideas, the system shows the ideas without to-do date and ideas with to-do date as today or previous.'
        ));
        $this->_addHistoryLog('0.02', '23/07/2013', array(
            '[Feature] - Created brainstorm page.',
            '[Code Improvement] - Added JQuery and JQgrid external libraries.'
        ));
        $this->_addHistoryLog('0.01', '23/07/2103', array(
            '[Feature] - New options in the top menu: release History, brainstorm',
            '[Feature] - Created release history page.',
            '[Visual Improvement] - Created Styles for the header, optimizing the code. Right now using only one image for all options, but considering multiple images in near future.'
        ));
        $this->_addHistoryLog('0.00', '23/07/2013', array(
            'Skeleton construction.'
        ));
    }

    /**
     * Release History index page.
     */
    public function index()
    {
        $this->_view->addLibrary('application/views/releaseHistory/css/releaseHistory.css');
        $this->_view->addLibrary('application/views/releaseHistory/js/learnMore.js');

        $this->_view->setParameter('developmentVersion', $this->_getDevelopmentVersion());
        $this->_view->setParameter('historicalLog', $this->_getHistoryLog());

        $this->_view->addChunk('releaseHistory/index');
    }

    /**
     * Adds an history log to its property array.
     * @param string $version
     * @param string $date
     * @param array $changes. Array of changes (strings)
     */
    private function _addHistoryLog($version, $date, $changes)
    {
        $this->_releaseLogHistory[$version] = array(
            'version' => $version,
            'date' => $date,
            'changes' => $changes
        );
    }

    /**
     * Gets the historical log array.
     * @return array
     */
    private function _getHistoryLog()
    {
        return $this->_releaseLogHistory;
    }

    /**
     * Sets the current development version details.
     * @param $version
     * @param $date
     * @param $changes
     */
    private function _setDevelopmentVersion($version, $date, $changes)
    {
        $this->_developmentVersion = array(
            'version' => $version,
            'date' => $date,
            'changes' => $changes
        );
    }

    /**
     * Gets the current development version details.
     * @return array
     */
    private function _getDevelopmentVersion()
    {
        return $this->_developmentVersion;
    }
}