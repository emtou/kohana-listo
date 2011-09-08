<?php
/**
 * Declares Listo_Core_Filter helper
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
 * @link      https://github.com/emtou/kohana-listo/tree/master/classes/listo/core/filter.php
 */

defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Provides Listo_Core_Filter helper
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
 * @link      https://github.com/emtou/kohana-listo/tree/master/classes/listo/core/filter.php
 */
class Listo_Core_Filter
{
  const NOFILTER = 'ListoFilter::NOFILTER';
  const SELECT   = 'Select';
  const TEXT     = 'Text';


  /**
   * Creates and initialises the filter
   *
   * Can't be called, the factory() method must be used.
   *
   * @return null
   */
  public function __construct()
  {
    throw new Kohana_User_Exception('Listo_Filter can\'t be instantiated');
  }


  /**
   * Create a chainable instance of the Filter
   *
   * @param string $type  Type of the filter
   * @param string $alias Alias of the filter
   *
   * @return Listo
   */
  public static function factory($type, $alias)
  {
    $class = 'Listo_Filter_'.$type;

    if ( ! class_exists($class))
    {
      throw new Kohana_Exception('Listo Filter class type not found: :type', array(':type' => $type));

    }

    return new $class($alias);
  }

} // End Listo_Core_Filter