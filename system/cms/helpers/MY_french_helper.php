<?php defined('BASEPATH') OR exit('No direct script access allowed.');

/**
 * PyroCMS French Helper
 * 
 * This uses Codeigniter's helpers/date_helper.php and then translate the date text values in french
 *
 * @author      Radja LOMAS
 * @copyright   Copyright (c) 2014 Radja LOMAS
 */


if (!function_exists('translate_date'))
{
	/**   A FINIR ...................................................

	 * Translates a date into french
	 * @require date helper
	 * @param int $unix The UNIX timestamp
	 * @param string $format The date format to use.
	 * @return string The formatted date in french.

	function translate_date($unix, $format = '')
	{
	setlocale(LC_TIME, 'fra_fra');
	
		if ($unix == '' || !is_numeric($unix))
		{
			$unix = strftime($unix);
		}

		if (!$format)
		{
			$format = Settings::get('format_date');
		}
		
		$date = format_date($unix, $format) ;

		
		return $date ;
	}
	 */
}