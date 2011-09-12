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
  echo '<span class="jslink" id="'.$alias.'_multiaction_checkall">check all</span>';
  echo '/';
  echo '<span class="jslink" id="'.$alias.'_multiaction_uncheckall">uncheck all</span>';
  echo '/';
  echo '<span class="jslink" id="'.$alias.'_multiaction_invertall">invert</span>';

echo '</span>';

echo '<span class="actions">';
echo 'Actions: ';
echo $action_selector;
echo $action_validator;
echo '</span>';

echo '</div>';