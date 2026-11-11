<?php
/**
 * DateTimeHelper.php
 *
 * Date and Time Helper class for date and time related functions
 *
 * PHP version 5 >= 5.3.0
 *
 * @package BitTS
 * @author  Erwin Beukhof
 * @version 30 october 2026
 */

namespace BitTS\Support;

class DateTimeHelper {

  /**
   * Function to convert old fashioned time and date formats to new versions.
   */
  private static function convertFormat($format) {
    return str_replace(array('%d','%e','%A',  '%a','%B',  '%b', '%h', '%m','%Y',  '%y','%H','%I','%M','%S','%u'),
                       array('dd','d', 'EEEE','E', 'MMMM','MMM','MMM','MM','yyyy','yy','HH','hh','mm','ss','cc'),
                       $format);
  }

  // Formatted time string
  public static function strftime($format, $timestamp = null) {
    /*** string strftime ( string format [, int timestamp] ) ***/
    global $LANGUAGE_ARRAY_MONTH;
    global $LANGUAGE_ARRAY_DAY;

    if (isset($timestamp)) {
      //$tepstrftime = strftime($format, $timestamp);
      //$tepstrftime = datefmt_format_object(DateTime::createFromTimestamp($timestamp), self::convertFormat($format)); // php >= 8.4
      $tepstrftime = datefmt_format_object(\DateTime::createFromFormat('U', (string) $timestamp), self::convertFormat($format)); // php < 8.4
    } else {
      //$tepstrftime = strftime($format);
      $tepstrftime = datefmt_format_object(new \DateTime(), self::convertFormat($format));
    }
    if (isset($LANGUAGE_ARRAY_MONTH))
      $tepstrftime = strtr($tepstrftime, $LANGUAGE_ARRAY_MONTH);
    if (isset($LANGUAGE_ARRAY_DAY))
      $tepstrftime = strtr($tepstrftime, $LANGUAGE_ARRAY_DAY);

    return $tepstrftime;
  }

  // Formatted period from formatted datetime
  public static function dateToPeriod($format = null, $date = null) {
    if (isset($format) && isset($date)) {
      //return strftime('%Y-%m', self::dateToUTS($format, $date));
      //return datefmt_format_object(DateTime::createFromTimestamp(self::dateToUTS($format, $date)), 'yyyy-MM'); // php >= 8.4
      return datefmt_format_object(\DateTime::createFromFormat('U', (string) self::dateToUTS($format, $date)), 'yyyy-MM'); // php < 8.4
    } else {
      return datefmt_format_object(new \DateTime(), 'yyyy-MM');
    }
  }

  // Formatted datetime from formatted period
  public static function getPeriodStartDate($period = null) {
    if ($period == null)
      $period = self::dateToPeriod();
  	$year = (int)substr($period, 0, 4);
  	$month = (int)substr($period, 5, 2);
    return $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-01';
  }

  public static function getPeriodEndDate($period = null) {
    if ($period == null)
      $period = self::dateToPeriod();
    $year = (int)substr($period, 0, 4);
    $month = (int)substr($period, 5, 2);
    //return strftime('%Y-%m-%d', mktime(0, 0, 0, $month + 1, 0, $year));
    //return datefmt_format_object(DateTime::createFromTimestamp(mktime(0, 0, 0, $month + 1, 0, $year)), 'yyyy-MM-dd'); // >= php 8.4
    return datefmt_format_object(\DateTime::createFromFormat('U', (string) mktime(0, 0, 0, $month + 1, 0, $year)), 'yyyy-MM-dd'); // php < 8.4
  }

  // Test date by format
  public static function testDate($format, $date) {
    // Available old formats:
    // '%Y-%m-%d'
    // '%d-%m-%Y'
    // '%m-%d-%Y'
    // Available new formats:
    // 'yyyy-MM-dd'
    // 'dd-MM-yyyy'
    // 'MM-dd-yyyy'
    $date_array = explode('-', $date);
    if (sizeof($date_array) != 3) {
      return false;
    }

    switch (self::convertFormat($format)) {
      case 'yyyy-MM-dd':
        $year = (int)$date_array[0];
        $month = (int)$date_array[1];
        $day = (int)$date_array[2];
        break;
      case 'dd-MM-yyyy':
        $day = (int)$date_array[0];
        $month = (int)$date_array[1];
        $year = (int)$date_array[2];
        break;
      case 'MM-dd-yyyy':
        $month = (int)$date_array[0];
        $day = (int)$date_array[1];
        $year = (int)$date_array[2];
        break;
      default:
        return false;
    }

    if ($year<1970 || $year>2099)
      return false;

    if ($day<1 || $day>31)
      return false;

    if ($month==2 && ((!self::isLeapYear($year) && $day>28) || (self::isLeapYear($year) && $day>29)))
      return false;

    if (($month==4 || $month==6 || $month==9 || $month==11) && $day>30)
      return false;

    // All seems to be in order
    return true;
  }

  ////
  // Check if year is a leap year
  private static function isLeapYear($year) {
    if ($year % 100 == 0) {
      if ($year % 400 == 0) return true;
    } else {
      if (($year % 4) == 0) return true;
    }
    return false;
  }

  // UNIX timestamp from formatted datetime
  public static function dateToUTS($format, $date, $offset_in_days = 0) {
    // Available old formats:
    // '%Y-%m-%d'
    // '%d-%m-%Y'
    // '%m-%d-%Y'
    // Available new formats:
    // 'yyyy-MM-dd'
    // 'dd-MM-yyyy'
    // 'MM-dd-yyyy'
    $format_ok=false;
    $date_array = explode('-', $date);
    if (sizeof($date_array) != 3) {
      return false;
    }

    switch (self::convertFormat($format)) {
      case 'yyyy-MM-dd':
        $year = (int)$date_array[0];
        $month = (int)$date_array[1];
        $day = (int)$date_array[2];
        $format_ok=true;
        break;
      case 'dd-MM-yyyy':
        $day = (int)$date_array[0];
        $month = (int)$date_array[1];
        $year = (int)$date_array[2];
        $format_ok=true;
        break;
      case 'MM-dd-yyyy':
        $month = (int)$date_array[0];
        $day = (int)$date_array[1];
        $year = (int)$date_array[2];
        $format_ok=true;
        break;
    }

    $day += $offset_in_days;

    return ($format_ok?mktime(0, 0, 0, $month, $day, $year):0);
  }

  // Return previous/next period
  public static function getNextPeriod($period, $increase = 1) {
  	$year = (int)substr($period, 0, 4);
  	$month = (int)substr($period, 5, 2);
  	
  	$month += $increase;
  	while ($month > 12) {
  	  $year += 1;
  	  $month -= 12;
  	}
  	while ($month < 1) {
  	  $year -= 1;
  	  $month += 12;
  	}
    return (string)$year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT);
  }

} // class GeneralUtil