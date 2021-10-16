<?php
/**
 * @ Chess League Manager (CLM) Termine Modul 
 * @Copyright (C) 2008-2021 CLM Team.  All rights reserved
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.chessleaguemanager.de
*/
 // no direct access
defined('_JEXEC') or die('Restricted access');
if(!defined("DS")){define('DS', DIRECTORY_SEPARATOR);} // fix for Joomla 3.2
require_once (JPATH_SITE . DIRECTORY_SEPARATOR . "components" . DIRECTORY_SEPARATOR . "com_clm" . DIRECTORY_SEPARATOR . "clm" . DIRECTORY_SEPARATOR . "index.php");

// angemeldet
require_once (dirname(__FILE__).DS.'helper.php');

 
$par_liste 			= $params->def('liste', 0);
$par_anzahl 		= $params->def('anzahl', 5);
$par_datum			= $params->def('datum', 1);
$par_datum_link 	= $params->def('datumlink', 1);
$par_name 			= $params->def('name', 1);
$par_typ 			= $params->def('typ', 1);
$par_termin_link 	= $params->def('terminlink', 1);

$runden	= modCLMTermineHelper::getRunde($params);

require(JModuleHelper::getLayoutPath('mod_clm_termine'));