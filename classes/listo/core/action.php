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
  const FILLFIELD   = 'Listo_Action::FILLFIELD';
  const MULTI       = 'Listo_Action_Multi';
  const SOLO        = 'Listo_Action_Solo';

  protected $_params = array();
  protected $_type   = '';


  /**
   * Creates and initialises the action
   *
   * Can't be called, the factory() method must be used.
   *
   * @return null
   */
  private function __construct()
  {
    throw Kohana_Exception('Can\'t instanciate Filto_Action directly.');
  }


  /**
   * Create a chainable instance of the Action
   *
   * @param string $type  Type of the action
   * @param string $alias Alias of the action
   * @param string $label Label of the action
   *
   * @return Listo
   */
  public static function factory($type, $alias, $label)
  {
    return new $type($alias, $label);
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