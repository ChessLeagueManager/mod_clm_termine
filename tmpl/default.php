<?php
/**
 * @ Chess League Manager (CLM) Component 
 * @Copyright (C) 2011-2023 CLM Team.  All rights reserved
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.chessleaguemanager.de
 * @author Fjodor Schäfer
 * @email ich@vonfio.de
*/
defined('_JEXEC') or die('Restricted access'); 
jimport( 'joomla.html.parameter' );

$option	= clm_core::$load->request_string( 'option','' );
if ($option == '') {
    // URL zusammenstellen
    $url = (empty($_SERVER['HTTPS'])) ? 'http' : 'https';
    $url .= $_SERVER['HTTP_HOST'];
    $url .= $_SERVER['REQUEST_URI']; // $url enthält jetzt die komplette URL
	$pcomponent = strpos($url,'/component/');
	if ($pcomponent !== false) {
		if (substr($url, $pcomponent + 10, 5) == '/clm/') 
			$option = "com_clm";
	}
}
$view	= clm_core::$load->request_string( 'view','' );
$saison	= clm_core::$load->request_string( 'saison','');
$liga	= clm_core::$load->request_string( 'liga','');
$turnier	= clm_core::$load->request_int( 'turnier','');
$dg		= clm_core::$load->request_string( 'dg','' );
$runde	= clm_core::$load->request_string( 'runde','');
$snr	= clm_core::$load->request_string( 'snr','');
$zps	= clm_core::$load->request_string( 'zps','');
$typeid	= clm_core::$load->request_string( 'typeid','');
$atyp	= clm_core::$load->request_string( 'atyp','');
$itemid	= clm_core::$load->request_string( 'Itemid','');
$start	= clm_core::$load->request_string( 'start','');
$categoryid	= clm_core::$load->request_string( 'categoryid','');
$spRang	= clm_core::$load->request_string( 'spRang','');
$tlnr	= clm_core::$load->request_string( 'tlnr','');
$mglnr	= clm_core::$load->request_string( 'mglnr','');
$PKZ	= clm_core::$load->request_string( 'PKZ','');
$layout	= clm_core::$load->request_string( 'layout','');
$format	= clm_core::$load->request_string( 'format','');
$id	= clm_core::$load->request_string( 'id','');
$orderby	= clm_core::$load->request_string( 'orderby','');
$ext_view	= clm_core::$load->request_string( 'ext_view','');
$source	= clm_core::$load->request_string( 'source','');
$nr	= clm_core::$load->request_string( 'nr','');

//echo "<br>Itemid: $itemid ";

$href_string = '';
if ($option != '') $href_string .= '&option='.$option; 
if ($view != '' AND $view != 'categories') $href_string .= '&view='.$view; 
if ($saison != '') $href_string .= '&saison='.$saison; 
if ($turnier != '') $href_string .= '&turnier='.$turnier; 
if ($liga != '') $href_string .= '&liga='.$liga; 
if ($dg != '') $href_string .= '&dg='.$dg; 
if ($runde != '') $href_string .= '&runde='.$runde; 
if ($snr != '') $href_string .= '&snr='.$snr; 
if ($zps != '') $href_string .= '&zps='.$zps; 
if ($typeid != '') $href_string .= '&typeid='.$typeid; 
if ($atyp != '') $href_string .= '&atyp='.$atyp; 
if ($itemid != '') $href_string .= '&Itemid='.$itemid; 
if ($start != '') $href_string .= '&start='.$start; 
if ($categoryid != '') $href_string .= '&categoryid='.$categoryid; 
if ($spRang != '') $href_string .= '&spRang='.$spRang; 
if ($tlnr != '') $href_string .= '&tlnr='.$tlnr; 
if ($mglnr != '') $href_string .= '&mglnr='.$mglnr; 
if ($PKZ != '') $href_string .= '&PKZ='.$PKZ; 
if ($layout != '') $href_string .= '&layout='.$layout; 
if ($format != '') $href_string .= '&format='.$format; 
if ($id != '') $href_string .= '&id='.$id; 
if ($orderby != '') $href_string .= '&orderby='.$orderby; 
if ($ext_view != '') $href_string .= '&ext_view='.$ext_view; 
if ($source != '') $href_string .= '&source='.$source; 
if ($nr != '') $href_string .= '&nr='.$nr; 
//echo "<br>href: $href_string ";

if ($href_string == '&view=categories')  { 
	echo "<br>href_string:".$href_string; die('hhh');
	$href_string = ''; }

	$document = JFactory::getDocument();

	$cssDir = JURI::base().'modules'.DS.'mod_clm_termine';

	//$document->addStyleSheet( $cssDir.DS.'mod_clm_termine.css', 'text/css', null, array() );
	$document->addStyleSheet( $cssDir.DS.'mod_clm_termine.css' );   // Joomla 4

if ($par_liste == 0 OR $par_liste == 2) { 
	if ($par_liste == 0) {
		?> <ul class="menu"> <?php 	
	} else {
		?> <div style="height:<?php echo $par_height; ?>px;overflow:auto;font-size:100%;">
		   <table>
		<?php $par_anzahl = count($runden); 	
	}
		
$arrWochentag = array( 
		"Monday" => JText::_('MOD_CLM_TERMINE_T01'), 
		"Tuesday" => JText::_('MOD_CLM_TERMINE_T02'), 
		"Wednesday" => JText::_('MOD_CLM_TERMINE_T03'), 
		"Thursday" => JText::_('MOD_CLM_TERMINE_T04'), 
		"Friday" => JText::_('MOD_CLM_TERMINE_T05'), 
		"Saturday" => JText::_('MOD_CLM_TERMINE_T06'), 
		"Sunday" => JText::_('MOD_CLM_TERMINE_T07') );
$count = 0; 
if ($start == '' OR $start == '1') $start = date("Y-m-d");

for ($t = 0; $t < $par_anzahl; $t++) {
	if (!isset($runden[$t])) break;
	if ($runden[$t]->datum >= $start) { 
 
		// Veranstaltung verlinken
		if ($runden[$t]->source == 'termin') { 
			$categoryid_link = $categoryid;
			if ($categoryid_link == '') $categoryid_link = '0';
			$linkname = "index.php?option=com_clm&amp;view=termine&amp;nr=". $runden[$t]->id ."&amp;layout=termine_detail&amp;categoryid=".$categoryid_link; 
		} elseif ($runden[$t]->ligarunde != 0) { 
			//$linkname = "index.php?option=com_clm&amp;view=runde&amp;saison=" . $runden[$t]->sid . "&amp;liga=" .  $runden[$t]->typ_id ."&amp;runde=" . $runden[$t]->nr ."&amp;dg=" . $runden[$t]->durchgang;
			if (($runden[$t]->durchgang > 1) AND ($runden[$t]->nr > $runden[$t]->ligarunde)) {
				if ($runden[$t]->nr > (3 * $runden[$t]->ligarunde)) $dg = 4;
				elseif ($runden[$t]->nr > (2 * $runden[$t]->ligarunde)) $dg = 3;
				else $dg = 2;
				$linkname = "index.php?option=com_clm&amp;view=runde&amp;saison=" . $runden[$t]->sid . "&amp;liga=" .  $runden[$t]->typ_id 
				."&amp;runde=" . ($runden[$t]->nr - (($dg - 1) * $runden[$t]->ligarunde)) ."&amp;dg=". $dg;
			} else { 
				$linkname = "index.php?option=com_clm&amp;view=runde&amp;saison=" . $runden[$t]->sid . "&amp;liga=" .  $runden[$t]->typ_id ."&amp;runde=" . $runden[$t]->nr ."&amp;dg=1";
			}
		} else {
			$linkname = "index.php?option=com_clm&amp;view=turnier_runde&amp;runde=" . $runden[$t]->nr . "&amp;turnier=" . $runden[$t]->typ_id; }
		$linkname .= "&amp;start=". $runden[$t]->datum;             
		// Datumsberechnungen
		$datum[$t] = strtotime($runden[$t]->datum);
		$datum_arr[$t] = explode("-",$runden[$t]->datum);
		
		$datum_link = '<a href="'. $linkname;
		if ($itemid <>'') { $datum_link .= '&Itemid='.$itemid; }
		$datum_link .= '">'. $arrWochentag[date("l",$datum[$t])]. ',&nbsp;' . $datum_arr[$t][2].'.'.$datum_arr[$t][1].'.'.$datum_arr[$t][0];
		if ($runden[$t]->enddatum > $runden[$t]->datum) { 
			$enddatum[$t] = strtotime($runden[$t]->enddatum);
			$enddatum_arr[$t] = explode("-",$runden[$t]->enddatum); 
			$datum_link .= ' - '. $arrWochentag[date("l",$enddatum[$t])]. ',&nbsp;' . $enddatum_arr[$t][2].'.'.$enddatum_arr[$t][1].'.'.$enddatum_arr[$t][0]; 
		} else {
			$enddatum[$t] = '';
		}
		$datum_link .= "</a>\n";
						
     
	if ($par_liste == 0) {
		echo '<li class="kalenderli">'; 	
	} else {
		echo '<tr class="kalenderli"><td>'; 	
	}
	
		if ($par_datum == 1) { // Parameter prüfen: Datum
			if ((isset($datum[$t-1])) AND ($datum[$t] == $datum[$t-1]) AND (isset($enddatum[$t-1])) AND ($enddatum[$t] == $enddatum[$t-1])) { echo ''; }      //klkl
				else { 
					if ($par_datum_link == 1) { // Parameter prüfen: Datum verlinken
						echo $datum_link;
					} else {  
						echo $arrWochentag[date("l",$datum[$t])]. ",&nbsp;" . $datum_arr[$t][2].".".$datum_arr[$t][1].".".$datum_arr[$t][0]; 
							if ($runden[$t]->enddatum > $runden[$t]->datum) { //klkl
							echo ' - '.$arrWochentag[date("l",$enddatum[$t])]. ',&nbsp;' . $enddatum_arr[$t][2].'.'.$enddatum_arr[$t][1].'.'.$enddatum_arr[$t][0]; }
					} 	
				 } 
		} else { } 
		if (($par_name == 1) OR ($par_typ == 1) AND (($runden[$t]->name <>'') AND ($runden[$t]->typ <>'')) ) {
			
			if ($par_termin_link == 1 ) {
				echo '<a href="'. $linkname;
				if ($itemid <>'') { echo "&Itemid=".$itemid; }
				echo '">';
			}
				if ($runden[$t]->starttime != '00:00:00') { echo "&nbsp;&nbsp;".substr($runden[$t]->starttime,0,5); } // Starttime 								
				if (($par_name == 1) OR ($par_typ == 1) AND ($runden[$t]->typ <>'') ) { echo "&nbsp;&nbsp;"; }
				if ($par_name == 1) { echo $runden[$t]->name ."\n"; } // Parameter prüfen: Terminname 				
				if (($par_name == 1) AND ($par_typ == 1) AND ($runden[$t]->typ <>'') ) { echo "&nbsp;-&nbsp;"; }
				if ($par_typ == 1) { echo $runden[$t]->typ ."\n"; }  // Parameter prüfen: Ort / Liga / Turnier
			if ($par_termin_link == 1 ) {
				echo "</a>\n";
			}
			
			echo '<br />';
	 	} else { 
			echo '<br />'; 
		}
		
	if ($par_liste == 0) {
		echo '</li>'; 	
	} else {
		echo '</tr></td>'; 	
	}

}
} 

	if ($par_liste == 0) {
		?></ul><?php 	
	} else {
		?></table>
		  </div>
		  <br>
		<?php 	
	}

} else { 

// Termine als Timestamp zu einem Array machen
$datum_stamp	= array ();
$datumend_stamp	= array ();
// Termin Details
$event_desc		= array ();
for ( $a = 0; $a < count ($runden); $a++ ) {

	// Veranstaltung verlinken
	if ($runden[$a]->source == 'termin') { 
 		$linkname = "index.php?option=com_clm&amp;view=termine&amp;nr=". $runden[$a]->id ."&amp;layout=termine_detail"; 
 	} elseif ($runden[$a]->ligarunde != 0) { 
 		//$linkname = "index.php?option=com_clm&amp;view=runde&amp;saison=". $runden[$a]->sid ."&amp;liga=".  $runden[$a]->typ_id ."&amp;runde=". $runden[$a]->nr ."&amp;dg=". $runden[$a]->durchgang; 
//		if (($runden[$a]->durchgang > 1) AND ($runden[$a]->nr > $runden[$a]->runden))
//			$linkname = "index.php?option=com_clm&amp;view=runde&amp;saison=" . $runden[$a]->sid . "&amp;liga=" .  $runden[$a]->typ_id ."&amp;runde=" . ($runden[$a]->nr - $runden[$a]->runden) ."&amp;dg=2";
		if (($runden[$a]->durchgang > 1) AND ($runden[$a]->nr > $runden[$a]->ligarunde)) {
			$i_ligarunde = (integer) $runden[$a]->ligarunde;
			if ($runden[$a]->nr > (3 * $i_ligarunde)) $dg = 4;
			elseif ($runden[$a]->nr > (2 * $i_ligarunde)) $dg = 3;
			else $dg = 2;
			$linkname = "index.php?option=com_clm&amp;view=runde&amp;saison=" . $runden[$a]->sid . "&amp;liga=" .  $runden[$a]->typ_id 
			."&amp;runde=" . ($runden[$a]->nr - (($dg - 1) * $i_ligarunde)) ."&amp;dg=". $dg;
		} else { 
			$linkname = "index.php?option=com_clm&amp;view=runde&amp;saison=" . $runden[$a]->sid . "&amp;liga=" .  $runden[$a]->typ_id ."&amp;runde=" . $runden[$a]->nr ."&amp;dg=1";
		}
 	} else {
 		$linkname = "index.php?option=com_clm&amp;view=turnier_runde&amp;runde=". $runden[$a]->nr ."&amp;turnier=". $runden[$a]->typ_id; 
	}
	$linkname .= "&amp;start=". $runden[$a]->datum; 
	$title			= $runden[$a]->name;
	$ende			= strtotime($runden[$a]->enddatum); 
	$anfang 		= strtotime($runden[$a]->datum);
		
	
	$datum_stamp[] 		= 	strtotime($runden[$a]->datum); 
	$event_desc[]		= 	array ($linkname , $title, $anfang, $ende  );  
	while ($ende > $anfang) {
		$anfang = mktime(0, 0, 0, date("m",$anfang)  , date("d",$anfang)+1, date("Y",$anfang));
		$datum_stamp[] 		= 	$anfang; 
		$event_desc[]		= 	array ($linkname , $title, $anfang, $ende  );  
	}
}
array_multisort ($datum_stamp, $event_desc);

// Mehrdimensionaler Array mit allen Information. Das Timestamp ist der Key
$event	= array_combine ($datum_stamp, $event_desc);

//if( isset($_REQUEST['timestamp'])) { $date = $_REQUEST['timestamp']; }
if( isset($_REQUEST['timestamp'])) { $date = $_REQUEST['timestamp']; $start = ''; }
else { $date = time(); }
if ($start != '' AND $start != '1') {
	$start_arr = explode("-",$start);
    $date = mktime(0,0,0,$start_arr[1],$start_arr[2],$start_arr[0]);
}

$arrMonth = array(
    "January" => JText::_('MOD_CLM_TERMINE_M01'),
    "February" => JText::_('MOD_CLM_TERMINE_M02'),
    "March" => JText::_('MOD_CLM_TERMINE_M03'),
    "April" => JText::_('MOD_CLM_TERMINE_M04'),
    "May" => JText::_('MOD_CLM_TERMINE_M05'),
    "June" => JText::_('MOD_CLM_TERMINE_M06'),
    "July" => JText::_('MOD_CLM_TERMINE_M07'),
    "August" => JText::_('MOD_CLM_TERMINE_M08'),
    "September" => JText::_('MOD_CLM_TERMINE_M09'),
    "October" => JText::_('MOD_CLM_TERMINE_M10'),
    "November" => JText::_('MOD_CLM_TERMINE_M11'),
    "December" => JText::_('MOD_CLM_TERMINE_M12')
);
    
//$headline = array('Mo','Di','Mi','Do','Fr','Sa','So');
$headline = array( 
		JText::_('MOD_CLM_TERMINE_K01'), 
		JText::_('MOD_CLM_TERMINE_K02'), 
		JText::_('MOD_CLM_TERMINE_K03'), 
		JText::_('MOD_CLM_TERMINE_K04'), 
		JText::_('MOD_CLM_TERMINE_K05'), 
		JText::_('MOD_CLM_TERMINE_K06'), 
		JText::_('MOD_CLM_TERMINE_K07') );

$linkname_tl = "index.php?option=com_clm&amp;view=termine&amp;Itemid=1"; 
$htext = $arrMonth[date('F',$date)].' '.date('y',$date);

?>

<center>
<div class="kalender">
    <div class="kal_pagination">
        <a href="index.php?timestamp=<?php echo modCLMTermineHelper::yearBack($date).$href_string; ?>" class="last">&laquo;</a> 
        <a href="index.php?timestamp=<?php echo modCLMTermineHelper::monthBack($date).$href_string; ?>" class="last">&lsaquo;</a> 
        <span><a title="<?php echo 'Termine '.$htext; ?>" href="<?php echo $linkname_tl.'&amp;start='.date('Y-m',$date).'-01'; ?>"><?php echo $htext ?></a></span>
        <a href="index.php?timestamp=<?php echo modCLMTermineHelper::monthForward($date).$href_string; ?>" class="next">&rsaquo;</a>
        <a href="index.php?timestamp=<?php echo modCLMTermineHelper::yearForward($date).$href_string; ?>" class="next">&raquo;</a>
        <div class="clear"></div>
    </div>
    <?php modCLMTermineHelper::getCalender($date,$event,$datum_stamp,$headline); ?>
    <div class="clear"></div>
</div>
</center>
<?php } ?>
 
