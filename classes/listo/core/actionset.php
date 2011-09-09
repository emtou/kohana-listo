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
  private function __construct()
  {
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
  public static function factory()
  {
    return new self;
  }


  /**
   * Adds multi and solo actions to the Filto inner Table
   *
   * @param string $alias         Alias of the Filto
   * @param array  $column_keys   Column keys
   * @param array  $column_titles Column titles
   * @param Table  &$table        Table instance
   *
   * @return null
   */
  public function pre_render($alias, $column_keys, $column_titles, & $table)
  {
    if (sizeof($this->_multi_actions) > 0)
    {
      $column_keys = array_reverse(array_merge(array_reverse($column_keys), array('select')));

      $column_titles = array_reverse(
          array_merge(
              array_reverse($column_titles),
              array(
                  form::checkbox(
                      $alias.'_checkall',
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


    if (sizeof($this->_solo_actions) > 0)
    {
      $column_keys[]   = 'action';
      $column_titles[] = 'Actions';

      /**
       * Callback on actions cells
       *
       * @param mixed  $value       value
       * @param int    $index       index
       * @param string $key         key
       * @param array  $body_data   body_data
       * @param array  $user_data   body_data
       * @param array  $row_data    row_data
       * @param array  $column_data column_data
       * @param Table  $table       table
       *
       * @return string HTML cell content
       */
      function actions_callback($value, $index, $key, $body_data, $user_data, $row_data, $column_data, $table)
      {
        $actions = array();
        foreach ($user_data['solo_actions'] as $action)
        {
          $actions[] = $action->render($user_data, $index);
        }
        return implode(' | ', $actions);
      }

      $table->set_callback('actions_callback', 'column', 'action');
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
    $view = View::factory($this->_view);

    $view->set('content', $this->_render_actions());

    $html = $view->render();

    if ($echo)
    {
      echo $html;
    }

    return $html;
  }


} // End Listo_Core_ActionSet