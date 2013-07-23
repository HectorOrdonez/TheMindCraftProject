<?php
/**
 * Project: Selfology
 * User: Hector Ordonez
 * Description: 
 * Date: 23/07/13 12:06
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
     * Index constructor.
     */
    public function __construct()
    {
        parent::__construct();

        // Setting version under construction
        $this->_setDevelopmentVersion('0.03', date('d-m-Y'), array(
            '[Debug] - Fixed little issue with the pagination of the Brainstorm page.',
            '[Feature] - Created skeleton of IdeaToAction page. Its purpose is to manage the already conceived ideas and filter them; decided if they must be done, if user wants to hold them over, and with which priority do them. Functionality pending to be done.',
            '[Feature upgrade] - Upgraded Brainstorm page; now the ideas have date of creation and, when listing the ideas, the system shows the ideas without to-do date and ideas with to-do date as today or previous.'
        ));

        // Setting Historical Log of releases
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
     * About main page.
     */
    public function index()
    {
        $this->_view->addLibrary('css','application/views/releaseHistory/css/releaseHistory.css');

        $this->_view->setParameter('developmentVersion', $this->_getDevelopmentVersion());
        $this->_view->setParameter('historicalLog', $this->_getHistoryLog());

        $this->_view->addChunk('releaseHistory/index');
    }

    /**
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

    private function _getHistoryLog()
    {
        return $this->_releaseLogHistory;
    }

    private function _setDevelopmentVersion($version, $date, $changes)
    {
        $this->_developmentVersion = array(
            'version' => $version,
            'date' => $date,
            'changes' => $changes
        );
    }

    private function _getDevelopmentVersion()
    {
        return $this->_developmentVersion;
    }
}