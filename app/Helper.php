<?php
namespace App;

class Helper {

	/**
	* Return "ago" time.
	*/
    public static function diffForHumans($datetime)
    {
        return Self::ago(strtotime($datetime));
    }

	/**
	 * time since last post
	 * @author Mad Jack
	 * @web https://gist.github.com/kimolalekan/bf56932b632aab2f8e22/cbac92228a71b41d33ae72f0a9cc17b752b711cd
	 * @param  {int} $time passed time in seconds as param
	 * @return {datetime}             Return formated date and time
	 */
	public static function ago($time) {

		$diff = time() - (int)$time;

		if ($diff == 0) {
			return 'Just now';
		}

		$intervals = array(
			1 => array('year', 31556926),
			$diff < 31556926 => array('month', 2628000),
			$diff < 2629744 => array('week', 604800),
			$diff < 604800 => array('day', 86400),
			$diff < 86400 => array('hour', 3600),
			$diff < 3600 => array('minute', 60),
			$diff < 60 => array('second', 1)
		);

		$value = floor($diff/$intervals[1][1]);
		$ago = $value.' '.$intervals[1][0].($value > 1 ? 's' : '');

		$days = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');

		$day = $days[date('w', $time)];

		if ($ago == '1 day') {
			return 'Yesterday at '.date('H:i', $time);
		}
		elseif ($ago == '2 days' || $ago == '3 days' || $ago == '4 days' || $ago == '5 days' || $ago == '6 days' || $ago == '7 days') {
			return $day.' at '.date('H:i', $time);
		}
		elseif ($value <= 59 && $intervals[1][0] == 'second' ||  $intervals[1][0] == 'minute' ||  $intervals[1][0] == 'hour') {
			return $ago.' ago';
		}
		else {
			return date('M', $time).' '.date('d', $time).', '.date('Y', $time).' at '.date('H:i', $time);
		}
}

}