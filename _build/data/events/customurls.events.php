<?php
/**
 * Array of plugin events for Mycomponent package
 *
 * @package customurls
 * @subpackage build
 */
$events = array();

/* Note: These must not be existing System Events!

 * This example is not used by default in the build.
 * It shows how to add custom System Events
 * for your plugin. See the commented out plugin section
 * of built.transport.php */


$events['OnDocFormSave']= $modx->newObject('modPluginEvent');
$events['OnDocFormSave']->fromArray(array(
    'event' => 'OnDocFormSave',
    'priority' => 0,
    'propertyset' => 0,
),'',true,true);

$events['OnResourceDuplicate']= $modx->newObject('modPluginEvent');
$events['OnResourceDuplicate']->fromArray(array(
    'event' => 'OnResourceDuplicate',
    'priority' => 0,
    'propertyset' => 0,
),'',true,true);

return $events;