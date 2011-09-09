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
  protected $_actionset     = NULL;     /** Listo_ActionSet instance */
  protected $_column_keys   = array();
  protected $_column_titles = array();
  protected $_filterset     = NULL;     /** Listo_FilterSet instance */
  protected $_js_code       = '';       /** Javascript code to add on final page */
  protected $_params        = array(
    'action_multi_key' => 'id',
  );

  public $alias = '';                   /** Alias of this listo */
  public $table = NULL;                 /** Table instance */


  /**
   * Callback to render zebras on odd rows
   *
   * @param mixed $row   Row data
   * @param int   $index Index of the current row
   *
   * @return string Rendered row
   */
  static function zebra_rows_callback($row, $index)
  {
    if ($index % 2 == 0)
    {
      return new Tr(''.$index, 'zebra');
    }
  }


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
    $this->table = Table::factory();
    $this->table->set_attributes('id="'.$alias.'"', '');


    $this->_actionset = Listo_ActionSet::factory();
    $this->_filterset = Listo_FilterSet::factory();
  }


  /**
   * Makes the final adjustments before rendering
   *
   * @return null;
   */
  protected function _pre_render()
  {
    // Actions : Add columns
    $column_keys   = $this->_column_keys;
    $column_titles = $this->_column_titles;

    $this->_actionset->pre_render(
        $this->alias,
        $this->_column_keys,
        $this->_column_titles,
        $this->table,
        $this->_js_code
    );

    // Add global params to the inner Table
    $this->table->set_user_data('params', $this->_params);

    // Add zebra rows coloring
    $this->table->set_callback('Listo::zebra_rows_callback', 'row');
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
   */
  public function add_action(Listo_Action $action)
  {
    $this->_actionset->add_action($action);

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
    $this->_pre_render();

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
   * Javascript code
   *
   * Call AFTER render() !
   *
   * @return string Javascript code
   */
  public function js_code()
  {
    return $this->_js_code;
  }

  /**
   * Sets the keys of the columns to be shown
   *
   * Chainable method.
   *
   * @param array $keys Keys of the columns
   *
   * @return this
   */
  public function set_column_keys(array $keys)
  {
    $this->_column_keys = $keys;

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
    $this->_column_titles = $titles;

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
   * Sets a internal param
   *
   * Chainable method.
   *
   * @param string $alias Alias of the param
   * @param mixed  $value Value of the param
   *
   * @return this
   */
  public function set_param($alias, $value)
  {
    $this->_params[$alias] = $value;

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