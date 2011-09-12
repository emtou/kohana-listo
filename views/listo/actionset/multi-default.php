<?php
/**
 * Declares default Listo ActionSet view
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
 * @link      https://github.com/emtou/kohana-listo/tree/master/views/listo/actionset/default.php
 */

defined('SYSPATH') OR die('No direct access allowed.');

echo '<div class="multi_actions">';
echo '<span class="arrow">↖</span>';
echo '<span class="links">';
  echo 'check';
  echo '/';
  echo 'uncheck';
  echo ' all';
echo '</span>';

echo '<span class="actions">';
echo 'Actions: ';
echo $content;
echo '</span>';

echo '</div>';