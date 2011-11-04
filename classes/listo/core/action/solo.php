<?php
/**
 * Declares Listo_Core_Action_Solo helper
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
 * @link      https://github.com/emtou/kohana-listo/tree/master/classes/listo/core/action/solo.php
 */

defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Provides Listo_Core_Action_Solo helper
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
 * @link      https://github.com/emtou/kohana-listo/tree/master/classes/listo/core/action/solo.php
 */
class Listo_Core_Action_Solo extends Listo_Action
{
  protected $_alias         = '';
  protected $_disable_rules = array();
  protected $_label         = '';
  protected $_options       = array();
  protected $_selected      = '';


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
    $actions = array();
    foreach ($user_data['solo_actions'] as $action)
    {
      $actions[] = $action->render($user_data, $index);
    }
    return new Td(
      implode('', $actions),
      NULL,
      'white-space:nowrap;vertical-align:middle;'
    );
  }


  /**
   * Creates and initialises the filter
   *
   * @param string $alias Alias of the filter
   * @param string $label Label of the filter
   *
   * @return this
   */
  public function __construct($alias, $label)
  {
    $this->_alias = $alias;
    $this->_label = $label;
  }


  /**
   * Add a disable rule
   *
   * Examples :
   *  - ':value(id)', '=', 1
   *  - ':value(date)', '=', '2010-05-03'
   *
   * Chainable method
   *
   * @param string $subject  subject the rule applies to
   * @param string $operator operator of the subject and the what
   * @param mixed  $what     what
   *
   * @return $this
   */
  public function disable_if($subject, $operator, $what)
  {
    $this->_disable_rules[] = array($subject, $operator, $what);

    return $this;
  }


  /**
   * Renders the action in "one" mode
   *
   * @param mixed $user_data User data from the table module
   * @param int   $index     Index of the current row
   *
   * @return html
   */
  public function render($user_data, $index)
  {
    if ($this->_type == Listo_Action::DIRECTROUTE)
    {
      if ($this->test_disable($user_data, $index))
        return '';

      $uri = array();

      foreach ($this->_params['uri'] as $key => $value)
      {
        if (preg_match('/^:value\(([^)]+)\)$/D', $value, $subpatterns))
        {
          $uri[$key] = $user_data['data'][$index]->{$subpatterns[1]};
        }
        else
        {
          $uri[$key] = $value;
        }
      }

      return HTML::anchor(
                Route::url($this->_params['route'], $uri),
                $this->_label
            );
    }

    return 'BAD_ACTION';
  }


  /**
   * Set the label for the action
   *
   * Chainable method.
   *
   * @param string $label Label of the action
   *
   * @return this
   */
  public function set_label($label)
  {
    $this->_label = $label;

    return $this;
  }


  /**
   * Registers the selected option
   *
   * Chainable method.
   *
   * @param string $selected Option name to set as selected
   *
   * @return this
   */
  public function set_selected($selected)
  {
    $this->_selected = $selected;

    return $this;
  }


  /**
   * Test if a disable rule fires
   *
   * Currently, the only rule operators are '=' and '!='
   *
   * @param mixed $user_data User data from the table module
   * @param int   $index     Index of the current row
   *
   * @return bool disable ?
   *
   * @throws Listo_Exception Can't test action disabling: unknown rule operator :operator
   */
  public function test_disable($user_data, $index)
  {
    foreach ($this->_disable_rules as $rule)
    {
      $submatches = array();
      if (preg_match('/^:value\((\w+)\)$/D', $rule[0], $submatches))
      {
        $field_alias = $submatches[1];
        switch ($rule[1])
        {
          case '=' :
            if ($user_data['data'][$index]->{$field_alias} == $rule[2])
              return TRUE;
          break;

          case '!=' :
            if ($user_data['data'][$index]->{$field_alias} != $rule[2])
              return TRUE;
          break;

          case 'match' :
            if (preg_match($rule[2], $user_data['data'][$index]->{$field_alias}))
              return TRUE;
          break;

          case '!match' :
            if ( ! preg_match($rule[2], $user_data['data'][$index]->{$field_alias}))
              return TRUE;
          break;

          default :
            throw new Listo_Exception(
              'Can\'t test action disabling: unknown rule operator :operator',
              array(':operator' => $rule[1])
            );
        }
      }
    }

    return FALSE;
  }

} // End Listo_Core_Action_Solo