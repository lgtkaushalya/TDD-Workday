<?php
require_once('Workday.php');

class WorkdayTest extends PHPUnit_Framework_TestCase {
  
  public function testGetNumberOfDaysBetweenTwoDaysInSameMonth() {
    $workday = new Workday();
    $this->assertEquals(30, $workday->getNumberOfDaysBetweenTwoDays('2016-08-01', '2016-08-31'));
  }

  public function testGetNumberOfDaysBetweenTwoDaysInDifferentMonths() {
    $workday = new Workday();
    $this->assertEquals(26, $workday->getNumberOfDaysBetweenTwoDays('2016-08-10', '2016-09-05'));
  }

  public function testGetNumberOfDaysBetweenTwoDaysInDifferentYears() {
      $workday = new Workday();
      $this->assertEquals(26, $workday->getNumberOfDaysBetweenTwoDays('2016-12-10', '2017-01-05'));
  }

  public function testGetNumberOfDaysBetweenTwoDaysInALeapYear() {
        $workday = new Workday();
        $this->assertEquals(24, $workday->getNumberOfDaysBetweenTwoDays('2016-02-10', '2016-03-05'));
  }

  /**
   * @expectedException InvalidArgumentException 
   */
  public function testGetNumberOfDaysBetweenTwoDaysWithIvalidFromDate() {
    $workday = new Workday();
    $workday->getNumberOfDaysBetweenTwoDays('2016-22-10', '2016-03-05');
  }

  /**
   * @expectedException InvalidArgumentException 
   */
  public function testGetNumberOfDaysBetweenTwoDaysWithIvalidToDate() {
    $workday = new Workday();
    $workday->getNumberOfDaysBetweenTwoDays('2016-01-10', '2016-13-05');
  }

  /**
   * @expectedException InvalidArgumentException 
   */
  public function testGetNumberOfDaysBetweenTwoDaysWithIvalidDateString() {
    $workday = new Workday();
    $workday->getNumberOfDaysBetweenTwoDays('Invalid', 'Invalid');
  }

  /**
   * @expectedException DomainException 
   */
  public function testGetNumberOfDaysBetweenTwoDaysWithFromDateHigherThanToDate() {
    $workday = new Workday();
    $workday->getNumberOfDaysBetweenTwoDays('2016-10-10', '2016-10-08');
  }

  /**
   * @expectedException DomainException 
   */
  public function testGetNumberOfDaysBetweenTwoDaysWithFromDateEqualToDate() {
    $workday = new Workday();
    $workday->getNumberOfDaysBetweenTwoDays('2016-10-10', '2016-10-10');
  }

  public function testGetNumberOfWeekendsBetweenTwodaysFromMidWeektoMidWeek() {
    $workday = new Workday();
    $this->assertEquals(8, $workday->getNumberOfWeekendsBetweenTwodays('2016-08-01', '2016-08-31'));
  }

  public function testGetNumberOfWeekendsBetweenTwodaysFromMidWeektoEndOfWeek() {
    $workday = new Workday();
    $this->assertEquals(9, $workday->getNumberOfWeekendsBetweenTwodays('2016-08-01', '2016-09-04'));
  }

  public function testGetNumberOfWeekendsBetweenTwodaysInDifferentYears() {
    $workday = new Workday();
    $this->assertEquals(18, $workday->getNumberOfWeekendsBetweenTwodays('2015-12-01', '2016-02-04'));
  }

  public function testGetTheDateForGivenDaysBeforeADateInSameMonth() {
    $workday = new Workday();
    $this->assertEquals('2016-08-29', $workday->getTheDateForGivenDaysBeforeADate('2016-09-04', 6));
  }

  public function testGetTheDateForGivenDaysBeforeADateInALeapYear() {
    $workday = new Workday();
    $this->assertEquals('2016-02-27', $workday->getTheDateForGivenDaysBeforeADate('2016-03-04', 6));
  }

  public function testGetTheDateForGivenDaysBeforeADateInTwoYears() {
    $workday = new Workday();
    $this->assertEquals('2015-12-29', $workday->getTheDateForGivenDaysBeforeADate('2016-01-04', 6));
  }

  /**
   * @expectedException InvalidArgumentException
   */
  public function testGetTheDateForGivenDaysBeforeADateWithInvalidDate() {
    $workday = new Workday();
    $workday->getTheDateForGivenDaysBeforeADate('2016-21-04', 6);
  }

  /**
   * @expectedException DomainException
   */
  public function testGetTheDateForGivenDaysBeforeADateWithInvalidNumberOfDays() {
    $workday = new Workday();
    $workday->getTheDateForGivenDaysBeforeADate('2016-10-04', "Invalid");
  }

  public function testGetNumberOfWorkingDaysBetweenTwoDaysForSameMonth() {
    $workday = new Workday();
    $this->assertEquals(22, $workday->getNumberOfWorkingDaysBetweenTwoDays('2016-08-01', '2016-08-31'));
  }

  public function testGetNumberOfWorkingDaysBetweenTwoDaysForWeekend() {
    $workday = new Workday();
    $this->assertEquals(0, $workday->getNumberOfWorkingDaysBetweenTwoDays('2016-08-21', '2016-08-22'));
  }

  public function testGetNumberOfWorkingDaysBetweenTwoDaysExcludingHolidays() {
    $holidayArray = array('2016-08-03', '2016-08-14', '2016-08-20', '2016-08-23');
    $workday = new Workday();
    $this->assertEquals(20, $workday->getNumberOfWorkingDaysBetweenTwoDays('2016-08-01', '2016-08-31', $holidayArray));
  }

  public function testGetNumberOfWorkingDaysBetweenTwoDaysExcludingHolidaysInDifferentMonths() {
    $holidayArray = array('2016-07-26', '2016-08-03', '2016-08-14', '2016-08-20', '2016-08-23');
    $workday = new Workday();
    $this->assertEquals(20, $workday->getNumberOfWorkingDaysBetweenTwoDays('2016-08-01', '2016-08-31', $holidayArray));
  }

  public function testGetNumberOfHolidaysOnWorkingDays() {
    $holidayArray = array('2016-08-03', '2016-08-14', '2016-08-20', '2016-08-23');
    $workday = new Workday();
    $this->assertEquals(2, $workday->getNumberOfHolidaysOnWorkingDays($holidayArray, '2016-08-01', '2016-08-31'));
  }
}
