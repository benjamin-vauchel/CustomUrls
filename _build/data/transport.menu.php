<?php
/**
* Adds the manager menu entry into package
* (MODX 2.3+ / 3: menus reference namespace + controller directly, modAction no longer exists)
*
* @package customurls
* @subpackage build
*/
$menu= $modx->newObject('modMenu');
$menu->fromArray(array(
    'text' => 'customurls',
    'parent' => 'components',
    'action' => 'home',
    'namespace' => 'customurls',
    'description' => 'customurls.menu_desc',
    'icon' => '',
    'menuindex' => 0,
    'params' => '',
    'handler' => '',
),'',true,true);

return $menu;
