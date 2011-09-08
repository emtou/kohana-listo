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
  /**
   * Creates and initialises the action
   *
   * Can't be called, the factory() method must be used.
   *
   * @return null
   */
  private function __construct()
  {
  }


  /**
   * Create a chainable instance of the Action
   *
   * @return Listo
   */
  public static function factory()
  {
    return new Listo_Action();
  }

} // End Listo_Core_Action