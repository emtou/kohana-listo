<?php
/**
 * Declares default Listo FilterSet view
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
 * @link      https://github.com/emtou/kohana-listo/tree/master/views/listo/filterset/default.php
 */

defined('SYSPATH') OR die('No direct access allowed.');

echo '<div>';
if ($content)
{
  echo $content;
}
echo '</div>';