<?php
/**
 * DokuWiki Plugin Authdomain Limitation (Action Component)
 *
 * Options for the Authdomain Limitation plugin
 *
 * @author basteyyy <sebastian@34n.de>
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 */

$meta['_domainWhiteList']    = array('string','_caution' => 'danger');
$meta['_domainlistErrorMEssage']    = array('string');
$meta['_emailRegex'] = array('string');
$meta['checksAnd'] = array('onoff');