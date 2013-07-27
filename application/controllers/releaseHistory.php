<?php
/**
 * Project: Selfology
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
        $this->_setDevelopmentVersion('0.06', date('d-m-Y'), array(
            '[Feature] - Added basic visual and functionality features to the Action tool',
            '[New design and architecture concept] - As part of the new architecture designed in V0.05, the Work Out tool now is divided in three sections. Implemented the visual characteristics and navigation.',
            '[Feature] - Refactored the old IdeaToAction tool to the new Selection tool, first part of Work Out section.',
            '[Feature] - Created the basics of the Timing tool, second part of Work Out section.',
            '[Feature] - Created the basics of the Prioritizing tool, second part of Work Out section.',
            '[Feature] - Created the basics of the Settings section so User can change its name and password.',
            '[Visual improvement] - Minor adjustments in the header and content css in order to avoid having the logo upon selectable content.',
            '[Debug] - Fixed relative paths in JS; this was giving issues depending on whether the controller received or not Index as method. Now links will always be absolute.',
            '[Visual improvement] - Moved Left Panel from header to the left of the body.'
        ));

        // Setting Historical Log of releases
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