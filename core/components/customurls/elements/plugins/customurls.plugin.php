<?php
/**
 * CustomURLs customurls plugin
 *
 * Copyright 2011 Benjamin Vauchel <contact@omycode.fr>
 *
 * @author Benjamin Vauchel <contact@omycode.fr>
 * @version Version 1.0.0-rc1
 * 23/08/12
 *
 * CustomURLs is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option) any
 * later version.
 *
 * CustomURLs is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * CustomURLs; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package customurls
 */

/**
 * MODx CustomURLs CustomUrls plugin
 *
 * Description: This extra allows you to define custom alias or URI patterns for your resources. It supports translit and Redirector packages.
 * You can build your patterns from resource fields, TV, snippets and output filters and set some constraints like you'd do with custom forms.
 * Usefull when you want to add id or published date to your URLs.
 * 
 * Example of URL patterns : 
 * 
 * [[+alias]]
 * some-text-before-[[+alias]]
 * [[+id]]-[[+alias]]
 * [[+publishedon:strtotime:date=`%Y-%m-%d`]]/[[+id]]-[[+alias]]
 * [[+tv.mytv]]-[[+id]]
 * [[MySnippet? &id=`[[+id]]`]]
 *
 * Events: OnResourceDuplicate, OnDocFormSave
 *
 * @package customurls
 *
 */

// Load CustomUrls service
$corePath =  $modx->getOption('customurls.core_path',$scriptProperties,$modx->getOption('core_path').'components/customurls/');
$customUrls = $modx->getService('customurls','CustomUrls',$corePath.'model/customurls/',$scriptProperties);
if (!($customUrls instanceof CustomUrls)) return '';

// Retrieve current resource
$eventName = $modx->event->name;
if($eventName == 'OnResourceDuplicate')
{
    $resource = $newResource;
}

// Select the proper URL pattern of the current resource
$customUrl = $customUrls->getCustomUrl($resource);

// If no custom URL pattern defined, exit
if(empty($customUrl))
{
    return '';
}

// Else we generate the custom URL
switch($eventName) 
{
    case 'OnDocFormSave': 
        $customUrls->generateCustomUrl($resource, $customUrl);
        break;
        
    case 'OnResourceDuplicate': 
        $customUrls->generateCustomUrl($resource, $customUrl);
        break;
}