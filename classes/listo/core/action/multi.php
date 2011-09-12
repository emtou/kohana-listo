<?php
/**
 * Declares Listo_Core_Action_Multi helper
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
 * @link      https://github.com/emtou/kohana-listo/tree/master/classes/listo/core/action/multi.php
 */

defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Provides Listo_Core_Action_Multi helper
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
 * @link      https://github.com/emtou/kohana-listo/tree/master/classes/listo/core/action/multi.php
 */
class Listo_Core_Action_Multi extends Listo_Action
{
  protected $_alias    = '';
  protected $_label    = '';
  protected $_options  = array();
  protected $_selected = '';


  /**
   * Adds a first checkbox column in Table
   *
   * @param string       $alias          Alias of the parent Listo
   * @param array        &$column_keys   Column keys
   * @param array        &$column_titles Column titles
   * @param Kohana_Table &$table         Table instance
   *
   * @return null
   */
  public static function add_select_column($alias, array & $column_keys, array & $column_titles, Kohana_Table & $table)
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
                      'alt'  => __('Select all/none')
                    )
                )
            )
        )
    );

    $table->set_callback('Listo_Action_Multi::render_cell_callback', 'column', 'select');
  }


  /**
   * Javascript code to add to the page
   *
   * @param string $alias Alias of the parent Listo
   *
   * @return string Javascript code
   */
  public static function js_code($alias)
  {
    $js_code = '';

    // Check/Uncheck all boxes
    $js_code .= "
      $(':input[name=".$alias."_checkall]').click(
        function()
        {
          var cases = $('#".$alias."').find(':input[name^=select]');
          if (this.checked)
          {
            cases.attr('checked', true);
          }
          else
          {
            cases.attr('checked', false);
          }
        }
    )";

    return $js_code;
  }


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
  // @codingStandardsIgnoreStart
  public static function render_cell_callback($value, $index, $key, $body_data, $user_data, $row_data, $column_data, $table)
  // @codingStandardsIgnoreEnd
  {
    $multi_key = $user_data['params']['action_multi_key'];

    return new Td(
      Form::checkbox(
          'select['.$index.']',
          $user_data['data'][$index]->{$multi_key},
          FALSE,
          array(
            'class' => 'checkbox',
          )
      ),
      'center'
    );
  }

  /**
   * Creates and initialises the action
   *
   * @param string $alias Alias of the action
   * @param string $label Label of the action
   *
   * @return this
   */
  public function __construct($alias, $label)
  {
    $this->_alias = $alias;
    $this->_label = $label;
  }


  /**
   * Adds selection option to actions array
   *
   * @param array  &$actions Actions array
   * @param string &$js_code Javascript code
   *
   * @return null
   */
  public function add_select_option(array & $actions, & $js_code)
  {
    $actions[$this->_alias] = $this->_label;

    // Auto-submit on multi action selection
    $js_code .= "
      $('select[name=multi_actions] option[value=".$this->_alias."]').select(
        function()
        {
          console.debug(this.value);
        });
    )";
  }

} // End Listo_Core_Action_Multi