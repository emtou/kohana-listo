<?php
/**
 * Declares Listo_Core_Sort helper
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
 * @link      https://github.com/emtou/kohana-listo/tree/master/classes/listo/core/sort.php
 */

defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Provides Listo_Core_Sort helper
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
 * @link      https://github.com/emtou/kohana-listo/tree/master/classes/listo/core/sort.php
 */
class Listo_Core_Sort
{
  /**
   * Creates and initialises the sort
   *
   * Can't be called, the factory() method must be used.
   *
   * @return null
   */
  private function __construct()
  {
  }


  /**
   * Create a chainable instance of the Sort
   *
   * @return Listo
   */
  public static function factory()
  {
    return new self;
  }

} // End Listo_Core_Sort