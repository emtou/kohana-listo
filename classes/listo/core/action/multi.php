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
   * @param string       $alias              Alias of the parent Listo
   * @param array        &$column_attributes Column attributes
   * @param array        &$column_keys       Column keys
   * @param array        &$column_titles     Column titles
   * @param Kohana_Table &$table             Table instance
   *
   * @return null
   */
  public static function add_select_column($alias, array & $column_attributes, array & $column_keys, array & $column_titles, Kohana_Table & $table)
  {
    // Set new column key
    $column_keys = array_reverse(array_merge(array_reverse($column_keys), array('select')));

    // Set new column title
    $column_titles = array_reverse(
        array_merge(
            array_reverse($column_titles),
            array(
                form::checkbox(
                    $alias.'_multiaction_checkbox',
                    __('All'),
                    FALSE,
                    array(
                      'alt'  => __('Select all/none')
                    )
                )
            )
        )
    );

    // Set new column callback
    $table->set_callback('Listo_Action_Multi::render_cell_callback', 'column', 'select');

    // Set new column attributes
    foreach (array_keys($column_attributes) as $type)
    {
      array_unshift($column_attributes[$type], NULL);
    }
  }


  /**
   * Javascript code to add to the page
   *
   * @param string $alias           Alias of the parent Listo
   * @param array  $actions_js_code Javascript codes for the actions
   *
   * @return string Javascript code
   */
  public static function global_js_code($alias, array $actions_js_code)
  {
    $js_code = '';

    // Check/Uncheck all boxes
    $js_code .= "
      $(':input[name=".$alias."_multiaction_checkbox]').live(
        'change',
        function()
        {
          $(':input[name^=select]').each(
            function()
            {
              if ($(this).is(':checked'))
              {
                $(this).removeAttr('checked');
              }
              else
              {
                $(this).attr('checked', 'checked');
              }
              $(this).change();
            }
          );
        }
      );

    ";

    // Auto-submit on multi action selection
    $js_code .= "
      $('select[name=multi_actions]').live(
        'change',
        function()
        {
          ";
    foreach ($actions_js_code as $action_alias => $action_js_code)
    {
      $js_code .= "
        if ($('select[name=multi_actions] option:selected').attr('value') == '".$action_alias."')
        {
          ".$action_js_code."
        }
      ";
    }
    $js_code .= "
        }
      );
      ";


    // Action on button click
    $js_code .= "
      $('input[name=doaction]').live(
        'click',
        function()
        {
          $('select[name=multi_actions]').change();
        }
      );";

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
      NULL,
      'white-space:nowrap;vertical-align:middle;'
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
   * @param array &$actions Actions array
   *
   * @return null
   */
  public function add_select_option(array & $actions)
  {
    $actions[$this->_alias] = $this->_label;
  }


  /**
   * Javascrtipt code to invoque on multi action selection
   *
   * @param array &$multi_js_code Array of javascript codes
   *
   * @return javascript code
   */
  public function js_code(array & $multi_js_code)
  {
    $js_code = '';

    switch ($this->_type)
    {
      case Listo_Action::DIRECTROUTE :
        if ($this->_params['method'] == 'GET')
        {
          $multi_js_code[$this->_alias] = "".
            "var url = '".Route::url($this->_params['route'], $this->_params['uri'])."';\n\n".
            "".
            "var selected_values = $('input[name^=select]:checked').map(function(){".
            "  return $(this).val();".
            "}).get().join(',');".
            "if (selected_values != '') {".
            "  url = url.replace(/:selected_values/i, selected_values);".
            "  window.location.replace(url);".
            "}".
            ""
            ;
        }
      break;

      case Listo_Action::FILLFIELD :
        $multi_js_code[$this->_alias] = "".
            "var selected_values = $('input[name^=select]:checked').map(function(){".
            "  return $(this).val();".
            "}).get().join(',');".
            "$('#".$this->_params['fieldid']."').text(selected_values);";
      break;
    }

  }

} // End Listo_Core_Action_Multi