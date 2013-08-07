<?php
/**
 * Project: Hecnel Framework
 * User: Hector Ordonez
 * Description:
 * Date: 11/06/13 12:00
 */

namespace engine;

class View
{
    /**
     * Title of the Page.
     * @var string
     */
    protected $_title = 'Hecnel Framework';

    /**
     * List of js files that the page needs to load
     * @var array
     */
    protected $_js = array();

    /**
     * List of css files that the page needs to load
     * @var array
     */
    protected $_css = array();

    /**
     * Path of the header to be rendered.
     * @var string
     */
    protected $_header = 'application/views/general/header.php';

    /**
     * Path of the footer to be rendered.
     * @var string
     */
    protected $_footer = 'application/views/general/footer.php';

    /**
     * Array of view chunks to be rendered. The set order defines the render order.
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
     * The handling of these arrays is normally done in the Header. In case the general Header is not called, another
     * view has to render them.
     *
     * Notice that if the first four characters are http, the library is considered external and the BASE_URL of the system won't be added to the String.
     *
     * @param string $type
     * @param string $libraryPath
     */
    public function addLibrary($type, $libraryPath)
    {
        if (substr($libraryPath, 0, 4) !== "http") {
            $libraryPath = _SYSTEM_BASE_URL . $libraryPath;
        }

        if ($type == 'css') {
            $this->_css[] = $libraryPath;
        } elseif ($type == 'js') {
            $this->_js[] = $libraryPath;
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
    public function setTitle ($title)
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
     * Sets the Header chunk.
     * @param string $chunk
     */
    public function setHeaderChunk($chunk)
    {
        $this->_header = $chunk;
    }

    /**
     * Sets the Footer chunk.
     * @param string $chunk
     */
    public function setFooterChunk($chunk)
    {
        $this->_footer = (string) $chunk;
    }

    /**
     * Adds a view chunk. As an optional parameter, position can be sent. In that
     * case the view chunk is injected in that position.
     * @param string $chunk
     * @param int $pos
     */
    public function addChunk($chunk, $pos = NULL)
    {
        if (!isset($pos))
        {
            $this->_chunks[] = $chunk;
        }
        else
        {
            $prevChunks = array_slice($this->_chunks, 0, $pos);
            $postChunks = array_slice($this->_chunks, $pos);
            $prevChunks[] = $chunk;
            $this->_chunks= array_merge($prevChunks, $postChunks);
        }
    }

    /**
     * Returns the Title.
     * @return string
     */
    private function _getTitle()
    {
        return $this->_title;
    }

    /**
     * Returns the array of Css libraries.
     * @return array
     */
    private function _getCss()
    {
        return $this->_css;
    }

    /**
     * Returns the array of Js libraries.
     * @return array
     */
    private function _getJs()
    {
        return $this->_js;
    }

    /**
     * Returns the array of Meta tags.
     * @return array
     */
    private function _getMeta()
    {
        return $this->_meta;
    }

    /**
     * Renders the chunks of the View.
     */
    public function render()
    {
        if (strlen($this->_header) > 0) {
            require $this->_header;
        }

        foreach ($this->_chunks as $chunk)
        {
            require 'application/views/' . $chunk . '.php';
        }

        if (strlen($this->_footer) > 0) {
            require $this->_footer;
        }
    }
}