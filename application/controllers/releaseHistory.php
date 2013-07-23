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
        $this->_setDevelopmentVersion('0.01', date("Y-m-d"), array(
            '[Feature] - New options in the top menu: release History, brainstorm',
            '[Feature] - Created release history page.',
            '[Visual Improvement] - Created Styles for the header, optimizing the code. Right now using only one image for all options, but considering multiple images in near future.'
        ));

        // Setting Historical Log of releases
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