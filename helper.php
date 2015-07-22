<?php
// no direct access
defined('_JEXEC') or die('Restricted access');
class modCLMTermineHelper {
	public static function getRunde(&$params) {
		global $mainframe;
		$db = JFactory::getDBO();
		$par_liste = $params->def('liste', 0);
		if ($par_liste == 0) {
			$now = date("Y-m-d");
		} else {
			$now = date("Y-01-01");
		}
		$query = " (SELECT 'liga' AS source, li.datum AS datum, li.datum AS enddatum, li.sid, li.name, li.nr, li.liga AS typ_id, t.id, t.name AS typ, t.durchgang AS durchgang, t.published, t.runden AS ligarunde" . " , t.ordering, li.startzeit AS starttime " . " FROM #__clm_runden_termine AS li LEFT JOIN #__clm_liga AS t ON t.id = li.liga " . " WHERE t.published != '0' AND  datum >= '" . $now . "' )" . " UNION ALL" . " (SELECT 'termin', e.startdate AS datum,  e.enddate AS enddatum, '1', e.name, '1', '', e.id, e.address AS typ, '1', e.published, 'event' AS ligarunde " . " , e.ordering, starttime " . " FROM #__clm_termine AS e " . " WHERE e.published != '0' AND  e.startdate >= '" . $now . "' )" . " UNION ALL" . " (SELECT 'turnier', tu.datum AS datum, tu.datum AS enddatum, tu.sid, tu.name, tu.nr, tu.turnier AS typ_id, b.id, b.name AS typ, tu.dg AS durchgang, b.published, '' " . " , b.ordering, tu.startzeit AS starttime " . " FROM #__clm_turniere_rnd_termine AS tu LEFT JOIN #__clm_turniere AS b ON b.id = tu.turnier " . " WHERE b.published != '0' AND  datum >= '" . $now . "' )" . " ORDER BY datum ASC, IF(starttime = '00:00:00','24:00:00',starttime) ASC, ABS(ordering) ASC, ABS(typ_id) ASC, ABS(nr) ASC ";
		$db->setQuery($query);
		$runden = $db->loadObjectList();;
		return $runden;
	}
	public static function monthBack($timestamp) {
		return mktime(0, 0, 0, date("m", $timestamp) - 1, date("d", $timestamp), date("Y", $timestamp));
	}
	public static function yearBack($timestamp) {
		return mktime(0, 0, 0, date("m", $timestamp), date("d", $timestamp), date("Y", $timestamp) - 1);
	}
	public static function monthForward($timestamp) {
		return mktime(0, 0, 0, date("m", $timestamp) + 1, date("d", $timestamp), date("Y", $timestamp));
	}
	public static function yearForward($timestamp) {
		return mktime(0, 0, 0, date("m", $timestamp), date("d", $timestamp), date("Y", $timestamp) + 1);
	}
	public static function getCalender($date, $headline = array('Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa', 'So'), $event, $datum_stamp) {
		$sum_days = date('t', $date);
		$month = date('m',$date);
		if ($month == "01") {
			$LastMonthSum = date('t',mktime(0,0,0,'12',1,(date('Y',$date)-1)));
		} else { 
			$LastMonthSum = date('t',mktime(0,0,0,(date('m',$date)-1),1,date('Y',$date)));
		}
		$linkname_tl = "index.php?option=com_clm&amp;view=termine&amp;Itemid=1";
		foreach ($headline as $key => $value) {
			echo "<div class=\"tag kal_top\">" . $value . "</div>\n";
		}
		$istamp = 0;
		for ($i = 1;$i <= $sum_days;$i++) {
			$day_name = date('D', mktime(0, 0, 0, date('m', $date), $i, date('Y', $date)));
			$day_number = date('w', mktime(0, 0, 0, date('m', $date), $i, date('Y', $date)));
			$stamp = mktime(0, 0, 0, date('m', $date), $i, date('Y', $date));
			// Letzter Monat
			if ($i == 1) {
				$s = array_search($day_name, array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'));
				for ($b = $s;$b > 0;$b--) {
					$x = $LastMonthSum - $b + 1;
					echo "<div class=\"tag before\">" . sprintf("%02d", $x) . "</div>\n";
				}
			}
			// Aktueller Tag
			if (date('Ymd') == date('Ymd', $stamp)) {
				echo "<div id=\"" . $stamp . "\" class=\"tag heute\">" . "<a href=\"" . $linkname_tl . "&amp;start=" . date('Y-m', $date) . "-01";
				echo "\" ><span title=\"" . "heute" . "\" class=\"CLMTooltip\">" . sprintf("%02d", $i) . "</span></a>" . "</div>\n";
			}
			// Termin !
			elseif (array_key_exists($stamp, $event)) {
				while ($datum_stamp[$istamp] < $stamp) {
					$istamp++;
				}
				echo "<div id=\"" . $stamp . "\" class=\"tag event\">";
				if ((!isset($datum_stamp[$istamp + 1])) OR ($datum_stamp[$istamp] != $datum_stamp[$istamp + 1])) {
					echo "<a href=\"" . $event[$stamp][0];
					echo "\" ><span title=\"" . $event[$stamp][1] . "\" class=\"CLMTooltip\">" . sprintf("%02d", $i) . "</span></a>";
				} else {
					echo "<a href=\"" . $linkname_tl . "&amp;start=" . date('Y-m-d', $stamp);
					echo "\" ><span title=\"" . "mehrere Termine am Tag!" . "\" class=\"CLMTooltip\">" . sprintf("%02d", $i) . "</span></a>";
				}
				echo "</div>\n";
			}
			// Normaler Tag
			else {
				echo "<div id=\"" . $stamp . "\" class=\"tag normal\">" . sprintf("%02d", $i) . "</div>\n";
			}
			// Nächster Monat
			if ($i == $sum_days) {
				$next_sum = (6 - array_search($day_name, array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun')));
				for ($c = 1;$c <= $next_sum;$c++) {
					echo "<div class=\"tag after\"> " . sprintf("%02d", $c) . " </div>\n";
				}
			}
		}
	}
}
