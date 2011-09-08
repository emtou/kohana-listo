<?php
/**
 * Declares default Listo view
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
 * @link      https://github.com/emtou/kohana-listo/tree/master/views/listo/default.php
 */

defined('SYSPATH') OR die('No direct access allowed.');


if ($filters)
{
  echo '<div name="'.$alias.'_filters">';
  echo $filters;
  echo '</div>';
}

echo $table;

if ($actions)
{
  echo '<div name="'.$alias.'_actions">';
  echo $filters;
  echo '</div>';
}