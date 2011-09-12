<?php
/**
 * Declares Listo_Core_ActionSet helper
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
 * @link      https://github.com/emtou/kohana-listo/tree/master/classes/listo/core/actionset.php
 */

defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Provides Listo_Core_ActionSet helper
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
 * @link      https://github.com/emtou/kohana-listo/tree/master/classes/listo/core/actionset.php
 */
class Listo_Core_ActionSet
{
  protected $_alias         = '';
  protected $_multi         = FALSE;
  protected $_multi_actions = array(); /** Array of Listo_Action instances */
  protected $_solo_actions  = array(); /** Array of Listo_Action instances */
  protected $_multi_view    = 'listo/actionset/multi-default';


  /**
   * Creates and initialises the actionset
   *
   * Can't be called, the factory() method must be used.
   *
   * @return null
   */
  private function __construct($alias)
  {
    $this->_alias = $alias;
  }


  /**
   * Renders the action set
   *
   * @return rendered action set in HTML
   */
  protected function _render_multi_actions()
  {
    $html = '';

    $actions     = array();
    $actions['NOACTION'] = __('-- Select an action --');

    foreach ($this->_multi_actions as $action)
    {
      $action->add_select_option($actions, $js_code);
    }

    $html .= Form::select('multi_actions', $actions, 'NOACTION');

    return $html;
  }


  /**
   * Registers an action in the action set
   *
   * Chainable method.
   *
   * @param Listo_Action $action Action to register
   *
   * @return this
   */
  public function add_action($action)
  {
    if ($action instanceof Listo_Action_Solo)
    {
      array_push($this->_solo_actions, $action);
    }
    elseif ($action instanceof Listo_Action_Multi)
    {
      array_push($this->_multi_actions, $action);
    }

    return $this;
  }


  /**
   * Create a chainable instance of the ActionSet
   *
   * @return Listo
   */
  public static function factory($alias)
  {
    return new self($alias);
  }


  /**
   * Adds multi and solo actions to the Filto inner Table
   *
   * @param string $alias         Alias of the Filto
   * @param array  $column_keys   Column keys
   * @param array  $column_titles Column titles
   * @param Table  &$table        Table instance
   * @param string &$js_code      Javascript code
   *
   * @return null
   */
  public function pre_render($alias, $column_keys, $column_titles, & $table, & $js_code)
  {
    if (sizeof($this->_multi_actions) > 0)
    {
      $js_code .= Listo_Action_Multi::js_code($alias);

      Listo_Action_Multi::add_select_column($alias, $column_keys, $column_titles, $table);
    }


    if (sizeof($this->_solo_actions) > 0)
    {
      $column_keys[]   = 'action';
      $column_titles[] = 'Actions';

      $table->set_callback('Listo_Action_Solo::render_cell_callback', 'column', 'action');
      $table->set_user_data('solo_actions', $this->_solo_actions);
    }

    $table->set_column_filter($column_keys);
    $table->set_column_titles($column_titles);

  }


  /**
   * Renders the action set
   *
   * @param bool $echo Should the output be echoed
   *
   * @return rendered action set in HTML
   *
   * @see Listo_ActionSet::_render_actions()
   */
  public function render($echo = FALSE)
  {
    $multi_view = View::factory($this->_multi_view);

    $multi_view->set('content', $this->_render_multi_actions());
    $multi_view->set('alias',   $this->_alias);

    $html = $multi_view->render();

    if ($echo)
    {
      echo $html;
    }

    return $html;
  }


} // End Listo_Core_ActionSet