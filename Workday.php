<?php

class Workday {

  public function getNumberOfDaysBetweenTwoDays($fromdate, $todate) {
    $this->isValidDate($fromdate);
    $this->isValidDate($todate);

    $fromdateObject = new DateTime($fromdate);
    $todateObject = new DateTime($todate);

    if ($todateObject <= $fromdateObject) {
      throw new DomainException('Todate should be greater than Fromdate'); 
    }

    return $todateObject->diff($fromdateObject, true)->format('%a');
  }

  public function getNumberOfWeekendsBetweenTwodays($fromdate, $todate) {
    $numberOfWeekEnds = 0;
    $this->isValidDate($fromdate);
    $this->isValidDate($todate);

    $numberOfDaysBetweenTwoDays = $this->getNumberOfDaysBetweenTwoDays($fromdate, $todate);
    $numberOfWeekEnds = (int)($numberOfDaysBetweenTwoDays / 7) * 2;
    
    $remainingDaysFromLastWeek = (int)($numberOfDaysBetweenTwoDays % 7);

    if ($remainingDaysFromLastWeek > 0) {
      $startDateBasedOnRemainingDaysFromLastWeek = $this->getTheDateForGivenDaysBeforeADate($todate, $remainingDaysFromLastWeek);

      while($startDateBasedOnRemainingDaysFromLastWeek < date('Y-m-d', strtotime($todate))) {
        if (date('N', strtotime($startDateBasedOnRemainingDaysFromLastWeek)) >= 6) {
          $numberOfWeekEnds++;
        }

        $startDateBasedOnRemainingDaysFromLastWeek = date('Y-m-d', strtotime($startDateBasedOnRemainingDaysFromLastWeek . " +1 days"));
      }
    }
    return $numberOfWeekEnds;
  }

  public function getTheDateForGivenDaysBeforeADate($date, $numberOfDays) {
    $this->isValidDate($date);
    
    if (!is_int($numberOfDays) || ($numberOfDays < 0)) {
      throw new DomainException('Number of days should be a valid integer');
    }

    $dateObject = date("Y-m-d", strtotime($date . " -$numberOfDays days"));
    return $dateObject;
  }

  public function getNumberOfWorkingDaysBetweenTwoDays($fromdate, $todate, $holidayArray = array()) {
    return $this->getNumberOfDaysBetweenTwoDays($fromdate, $todate) - $this->getNumberOfWeekendsBetweenTwodays($fromdate, $todate) - $this->getNumberOfHolidaysOnWorkingDays($holidayArray, $fromdate, $todate);
  }

  public function getNumberOfHolidaysOnWorkingDays($holidayArray, $fromdate, $todate) {
    $numberOfHolidaysOnWorkingDays = 0;

    foreach ($holidayArray as $holiday) {
      $this->isValidDate($holiday);

      if (strtotime($fromdate) > strtotime($holiday) || strtotime($todate) < strtotime($holiday)) {
        continue;
      }
      
      if (date('N', strtotime($holiday)) < 6) {
        $numberOfHolidaysOnWorkingDays++;
      }
    }

    return $numberOfHolidaysOnWorkingDays;
  }

  private function isValidDate($date) {
    try {
      $dateObject = new DateTime($date);
    } catch (Exception $e) {
      throw new InvalidArgumentException($e->getMessage(), $e->getCode(), $e);
    }

    return true;
  }
}
