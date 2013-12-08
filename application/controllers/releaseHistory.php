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

class ReleaseHistory extends Controller
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
        $this->_setDevelopmentVersion('0.21', '08/12/2013', array (
            '[Refactor] - Changed header and footer related files structure.',
            '[Refactor] - Separated header and footer styles in different files.',
            '[Visual improvement] - Finished the minor changes applied in previous update (buggy)'
        ));

        // Setting Historical Log of releases
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
        $this->_view->addLibrary('css', 'application/views/releaseHistory/css/releaseHistory.css');

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