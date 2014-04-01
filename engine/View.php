<?php
/**
 * Project: Hecnel Framework
 * User: Hector Ordonez
 * Description:
 * View engine class.
 * Its purpose is to define the behavior of the Views of the application and to provide general methods.
 * @date: 11/06/13 12:00
 */

namespace engine;

use engine\drivers\Exception;
use engine\drivers\Exceptions\ViewException;

/**
 * Class View
 * @package engine
 */
class View
{
    /**
     * Default error messages.
     */
    const MAIN_CHUNK_ALREADY_SET = "Main chunk can not be set as it is already set.";
    const CHUNK_NOT_SET = "Requested chunk %s is not set.";

    /**
     * Title of the Page.
     * @var string
     */
    protected $_title = '';

    /**
     * List of js files that the page needs to load
     * @var array
     */
    protected $_js = array();

    /**
     * List of css files that the page needs to load.
     * @var array
     */
    protected $_css = array();

    /**
     * Array of view chunks that can be displayed.
     * @var array
     */
    protected $_chunks = array();

    /**
     * Array of Meta tags to be added in the Header. Each value of the array contains an array of data.
     * Example:
     * $_meta = array (
     *      'name' => array (
     *          'name' => 'MyName',
     *          'content' => 'MyNameContent'>
     *      'keywords' => array (
     *          'name' => 'keywords',
     *          'content' => 'hoygan, reconchuda, lareputamadre'
     *      'http->equiv => 'Content-Script-Type',
     *      'content' => 'text/javascript'
     * );
     *
     * Notice that the index of each position of an array is a custom name that defines that type of Meta tag.
     * The controller defines a default meta construction, but if a child needs a different Meta then it needs to
     * add a new meta for that index.
     * @var array
     */
    protected $_meta = array();

    /**
     * View Constructor.
     */
    public function __construct()
    {
    }

    /**
     * Adds a CSS or JS library to the appropriate array in the View.
     * Notice that if the first four characters are http, the library is considered external and the BASE_URL of the system won't be added to the String.
     *
     * @param string $libraryPath
     */
    public function addLibrary($libraryPath)
    {
        if (substr($libraryPath, 0, 4) !== "http") {
            $libraryPath = _SYSTEM_BASE_URL . $libraryPath;
        }
        
        if(substr($libraryPath, -2, 2) == 'js'){
            $this->_js[] = $libraryPath;
        } else {
            $this->_css[] = $libraryPath;
        }
    }

    /**
     * Adds a meta tag to the specified index.
     * If a Meta tag already exists with that index, it is overridden.
     * @param string $index
     * @param array $meta
     */
    public function setMeta($index, $meta)
    {
        $this->_meta[$index] = $meta;
    }

    /**
     * Sets the title of the page.
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->_title = $title;
    }

    /**
     * Sets a parameter to the specified key in the view.
     * @param string $key
     * @param mixed $value
     */
    public function setParameter($key, $value)
    {
        $this->$key = $value;
    }

    /**
     * Gets a parameter with the specified key in the view.
     * @param string $key
     * @return mixed $value
     */
    public function getParameter($key)
    {
        return $this->$key;
    }

    /**
     * Returns the Title.
     * @return string
     */
    public function getTitle()
    {
        return $this->_title;
    }

    /**
     * Returns the array of Css libraries.
     * @return array
     */
    public function getCss()
    {
        return $this->_css;
    }

    /**
     * Returns the array of Js libraries.
     * @return array
     */
    public function getJs()
    {
        return $this->_js;
    }

    /**
     * Returns the array of Meta tags.
     * @return array
     */
    public function getMeta()
    {
        return $this->_meta;
    }

    /**
     * Adds a view chunk. The chunk name gives the chunk a key to be accessed when requested by printChunk and tha path
     * specifies the View where to search it when printing.
     *
     * Notice that main chunk is special - it is the one that will be printed when rendering the view, and it should
     * be in charge of printing what is required.
     * Because of this, replacing main chunk with another one once it is set is not allowed.
     *
     * @param string $path Chunk path.
     * @param string $name Chunk name.
     * @throws ViewException
     */
    public function addChunk($path, $name = 'main')
    {
        if ('main' == $name and isset($this->_chunks[$name])) {
            throw new ViewException(sprintf(self::MAIN_CHUNK_ALREADY_SET), Exception::FATAL_EXCEPTION);
        }

        $this->_chunks[$name] = $path;
    }

    /**
     * Prints a chunks.
     * 
     * Notice that in case main chunk is requested and it is not defined, it does NOT triggers an exception.
     * That allows controllers to avoid using views in, for example, an Ajax request.
     * 
     * @param string $name
     * @throws ViewException
     */
    public function printChunk($name = 'main')
    {
        if (!isset($this->_chunks[$name])) {
            // No exception if no view is required.
            if ('main' == $name) return;
            
            throw new ViewException(sprintf(self::CHUNK_NOT_SET, $name), Exception::FATAL_EXCEPTION);
        }
        
        require _SYSTEM_ROOT_PATH . join(DIRECTORY_SEPARATOR, array('application', 'views', $this->_chunks[$name] .'.php'));
    }
}