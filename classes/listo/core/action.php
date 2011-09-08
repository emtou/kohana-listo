<?php
/**
 * Declares Listo_Core_Action helper
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
 * @link      https://github.com/emtou/kohana-listo/tree/master/classes/listo/core/action.php
 */

defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Provides Listo_Core_Action helper
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
 * @link      https://github.com/emtou/kohana-listo/tree/master/classes/listo/core/action.php
 */
class Listo_Core_Action
{
  const DIRECTROUTE = 'Listo_Action::DIRECTROUTE';

  protected $_params = array();
  protected $_type   = '';

  public $alias = '';
  public $one   = TRUE;
  public $label = '';


  /**
   * Creates and initialises the action
   *
   * @param string $alias Alias of the action
   * @param string $label Label of the action
   *
   * Can't be called, the factory() method must be used.
   *
   * @return null
   */
  private function __construct($alias, $label)
  {
    $this->alias = $alias;
    $this->label = $label;
  }


  /**
   * Create a chainable instance of the Action
   *
   * @param string $alias Alias of the action
   * @param string $label Label of the action
   *
   * @return Listo
   */
  public static function factory($alias, $label)
  {
    return new Listo_Action($alias, $label);
  }


  /**
   * Renders the action in "one" mode
   *
   * @param mixed $user_data User data from the table module
   * @param int   $index     Index of the current row
   *
   * @return html
   */
  public function render_one($user_data, $index)
  {
    if ($this->_type == Listo_Action::DIRECTROUTE)
    {
      $uri = array();

      foreach ($this->_params['uri'] as $key => $value)
      {
        if (preg_match('/^:value\(([^)]+)\)$/', $value, $subpatterns))
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
                $this->label
            );
    }

    return 'BAD_ACTION';
  }


  /**
   * Sets the action to be used on one element
   *
   * Chainable method.
   *
   * @return this
   */
  public function set_one()
  {
    $this->one = TRUE;

    return $this;
  }

  /**
   * Sets the concrete action
   *
   * Chainable method.
   *
   * @param string $type   Type of action
   * @param array  $params Params for the action
   *
   * @return this
   */
  public function set_action($type, array $params = array())
  {
    $this->_params = $params;
    $this->_type   = $type;

    return $this;
  }

} // End Listo_Core_Action