<?php
/**
 * Declares Listo_Core_Filter_Select helper
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
 * @link      https://github.com/emtou/kohana-listo/tree/master/classes/listo/core/filter/select.php
 */

defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Provides Listo_Core_Filter_Select helper
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
 * @link      https://github.com/emtou/kohana-listo/tree/master/classes/listo/core/filter/select.php
 */
class Listo_Core_Filter_Select extends Listo_Filter
{
  protected $_alias    = '';
  protected $_label    = '';
  protected $_options  = array();
  protected $_selected = '';


  /**
   * Creates and initialises the filter
   *
   * @param string $alias Alias of the filter
   *
   * @return this
   */
  public function __construct($alias)
  {
    $this->_alias = $alias;
  }


  /**
   * Renders the filter
   *
   * @return rendered filter in HTML
   */
  public function render()
  {
    $html = '';

    $html .= Form::select(
        'filter_select_'.$this->_alias,
        $this->_options,
        $this->_selected,
        array(
          'onchange' => "location.href='?".$this->_alias."='+this.value;",
        )
    );

    return $html;
  }


  /**
   * Registers options fot the filter
   *
   * Chainable method.
   *
   * @param array $options Options to register
   *
   * @return this
   */
  public function set_options(array $options)
  {
    $this->_options = $options;

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

} // End Listo_Core_Filter_Select