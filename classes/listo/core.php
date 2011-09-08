<?php
/**
 * Declares Listo_Core helper
 *
 * PHP version 5
 *
 * @group listo
 *
 * @category  Listo
 * @package   Listo
 * @author    mtou <mtou@charougna.com>
 * @copyright 2011 mtou
 * @license   http://www.debian.org/misc/bsd.license BSD License (3 Clause)
 * @link      https://github.com/emtou/kohana-listo/tree/master/classes/listo/core.php
 */

defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Provides Listo_Core helper
 *
 * PHP version 5
 *
 * @group listo
 *
 * @category  Listo
 * @package   Listo
 * @author    mtou <mtou@charougna.com>
 * @copyright 2011 mtou
 * @license   http://www.debian.org/misc/bsd.license BSD License (3 Clause)
 * @link      https://github.com/emtou/kohana-listo/tree/master/classes/listo/core.php
 */
class Listo_Core
{
  protected $_multi     = FALSE; /** Multi mode */
  protected $_filterset = NULL;  /** Listo_FilterSet instance */


  public $alias = '';            /** Alias of this listo */
  public $table = NULL;          /** Table instance */


  /**
   * Creates and initialises the listo
   *
   * Can't be called, the factory() method must be used.
   *
   * @param string $alias Alias of this listo
   *
   * @return null
   */
  private function __construct($alias)
  {
    $this->alias = $alias;
    $this->table = Table::factory(array());

    $this->_filterset = Listo_FilterSet::factory();
  }


  /**
   * Renders the actions
   *
   * @return rendered actions in HTML
   *
   * @todo code this member
   */
  protected function _render_actions()
  {
    $html = '';
    return $html;
  }


  /**
   * Renders the filterset
   *
   * @return rendered filterset in HTML
   *
   * @see Listo_FilterSet::render()
   */
  protected function _render_filters()
  {
    $html = '';

    $html .= $this->_filterset->render();

    return $html;
  }


  /**
   * Registers an action
   *
   * Chainable method.
   *
   * @param Listo_Action $action Action to be registered
   *
   * @return this
   *
   * @todo code this member
   */
  public function add_action(Listo_Action $action)
  {
    return $this;
  }


  /**
   * Registers a filter to the filterset
   *
   * Chainable method.
   *
   * @param Listo_Filter $filter Filter to be registered
   *
   * @return this
   */
  public function add_filter(Listo_Filter $filter)
  {
    $this->_filterset->add_filter($filter);

    return $this;
  }


  /**
   * Registers an sorting option
   *
   * Chainable method.
   *
   * @param Listo_Sort $sort Sorting option to be registered
   *
   * @return this
   *
   * @todo code this member
   */
  public function add_sort($sort)
  {
    return $this;
  }


  /**
   * Registers a callback on the table
   *
   * Chainable method.
   *
   * @param string $callback_name Name of the callback function
   * @param string $type          Type of the callback
   * @param mixed  $keys          Column keys to apply callback to
   *
   * @return this
   *
   * @see Table::set_callback()
   */
  public function add_table_callback($callback_name, $type = 'body', $keys = NULL)
  {
    $this->table->set_callback($callback_name, $type, $keys);

    return $this;
  }


  /**
   * Create a chainable instance of the Listo class
   *
   * @param string $alias Alias of this listo
   *
   * @return Listo
   */
  public static function factory($alias)
  {
    return new self($alias);
  }


  /**
   * Renders the listo
   *
   * @param bool $echo Should the output be echoed
   *
   * @return rendered listo in HTML
   *
   * @see Listo::_render_actions()
   * @see Listo::_render_filters()
   * @see Table::render()
   */
  public function render($echo = FALSE)
  {
    /**
     * Callback to render zebras on odd rows
     *
     * @param mixed $row   Row data
     * @param int   $index Index of the current row
     *
     * @return string Rendered row
     */
    function listo_format_zebra_row($row, $index)
    {
      if ($index % 2 == 0)
      {
        return new Tr(''.$index, 'zebra');
      }
    }

    $this->table->set_callback('listo_format_zebra_row', 'row');

    $view = View::factory('listo/default');

    $view->set('actions', $this->_render_actions());
    $view->set('alias',   $this->alias);
    $view->set('filters', $this->_render_filters());
    $view->set('table',   $this->table->render());

    $html = $view->render();

    if ($echo)
    {
      echo $html;
    }

    return $html;
  }


  /**
   * Sets the columns to be shown
   *
   * Chainable method.
   *
   * @param array $keys Keys of the columns
   *
   * @return this
   */
  public function set_column_filter(array $keys)
  {
    if ($this->_multi)
    {
      $keys = array_reverse(array_merge(array_reverse($keys), array('select')));
    }

    $this->table->set_column_filter($keys);

    return $this;
  }


  /**
   * Sets the titles of the columns
   *
   * Chainable method.
   *
   * @param array $titles Titles of the columns
   *
   * @return this
   */
  public function set_column_titles(array $titles)
  {
    if ($this->_multi)
    {
      $titles = array_reverse(
          array_merge(
              array_reverse($titles),
              array(
                  form::checkbox(
                      $this->alias.'_checkall',
                      __('All'),
                      FALSE,
                      array(
                        'onclick' => 'javascript:function(){
                                      };"',
                        'alt'  => __('Select all/none')
                      )
                  )
              )
          )
      );
    }

    $this->table->set_column_titles($titles);

    return $this;
  }


  /**
   * Sets the data to be shown by the listo
   *
   * Chainable method.
   *
   * @param mixed $data Data to be kept for showing
   *
   * @return this
   */
  public function set_data($data)
  {
    if ($data instanceof Jelly_Model)
    {
      $data = array($data);
    }

    $this->table->set_body_data($data);
    $this->table->set_user_data('data', $data);

    return $this;
  }


  /**
   * Sets the name of the view to be used to render the FilterSet
   *
   * Chainable method.
   *
   * @param string $view Name of the view
   *
   * @return this
   *
   * @todo code this member
   */
  public function set_filterview($view)
  {
    return $this;
  }


  /**
   * Sets multimode on
   *
   * Chainable method.
   *
   * @return this
   *
   * @todo where is this add_checkbox callback defined ?
   */
  public function set_multi()
  {
    $this->_multi = TRUE;

    $this->table->set_callback('add_checkbox', 'column', 'select');

    return $this;
  }


  /**
   * Sets the name of the view to be used to render the Filto
   *
   * Chainable method.
   *
   * @param string $view Name of the view
   *
   * @return this
   *
   * @todo code this member
   */
  public function set_view($view)
  {
    return $this;
  }

} // End Listo_Core