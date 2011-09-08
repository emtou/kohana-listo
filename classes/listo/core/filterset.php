<?php
/**
 * Declares Listo_Core_FilterSet helper
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
 * @link      https://github.com/emtou/kohana-listo/tree/master/classes/listo/core/filterset.php
 */

defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Provides Listo_Core_FilterSet helper
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
 * @link      https://github.com/emtou/kohana-listo/tree/master/classes/listo/core/filterset.php
 */
class Listo_Core_FilterSet
{
  protected $_filters = array(); /** Array of Listo_Filter instances */
  protected $_multi   = FALSE;
  protected $_view    = 'listo/filterset/default';


  /**
   * Creates and initialises the filterset
   *
   * Can't be called, the factory() method must be used.
   *
   * @return null
   */
  private function __construct()
  {
  }


  /**
   * Renders the filterset
   *
   * @return rendered filterset in HTML
   *
   * @see Listo_Filter::render()
   */
  protected function _render_filters()
  {
    $html = '';

    foreach ($this->_filters as $filter)
    {
      $html .= $filter->render();
    }

    return $html;
  }


  /**
   * Registers a filter in the filter set
   *
   * Chainable method.
   *
   * @param Listo_Filter $filter Filter to register
   *
   * @return this
   */
  public function add_filter($filter)
  {
    array_push($this->_filters, $filter);

    return $this;
  }


  /**
   * Create a chainable instance of the Filterset
   *
   * @return Listo
   */
  public static function factory()
  {
    return new self;
  }



  /**
   * Renders the filter set
   *
   * @param bool $echo Should the output be echoed
   *
   * @return rendered filter set in HTML
   *
   * @see Listo::_render_actions()
   * @see Listo::_render_filters()
   * @see Table::render()
   */
  public function render($echo = FALSE)
  {
    $view = View::factory($this->_view);

    $view->set('content', $this->_render_filters());

    $html = $view->render();

    if ($echo)
    {
      echo $html;
    }

    return $html;
  }


} // End Listo_Core_FilterSet