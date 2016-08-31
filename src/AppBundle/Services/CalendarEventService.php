<?php
/**
 * Created by PhpStorm.
 * User: aschuermann
 * Date: 23.03.2016
 * Time: 18:42
 */
namespace AppBundle\Services;

use AppBundle\Entity\AbstractAppointment;
use AppBundle\Entity\CalendarEvent;
use AppBundle\Entity\Repository\AbstractAppointmentRepository;
use AppBundle\Entity\Repository\AppointmentRepository;

class CalendarEventService {

    /**
     * @var AppointmentRepository
     */
    private $appointmentRepository;

    /**
     * @var AbstractAppointmentRepository
     */
    private $abstractAppointmentRepository;

    /**
     * Will be filled once via getter
     * @var array
     */
    private $numbersToWeekDays = null;

    /**
     * @var array
     */
    private $WeekDaysToNumbers = [
        'Sunday' => 0,
        'Monday' => 1,
        'Tuesday' => 2,
        'Wednesday' => 3,
        'Thursday' => 4,
        'Friday' => 5,
        'Saturday' => 6
    ];

    public function __construct(AppointmentRepository $appointmentRepository, AbstractAppointmentRepository $abstractAppointmentRepository)
    {
        $this->appointmentRepository = $appointmentRepository;
        $this->abstractAppointmentRepository = $abstractAppointmentRepository;
    }

    public function getEventsForPeriod($periodStart, $periodEnd)
    {
        $abstractAppointments = $this->abstractAppointmentRepository->getAbstractAppointmentsForPeriod($periodStart, $periodEnd);
        $abstractEvents = $this->createEventsFromAbstractAppointments($abstractAppointments, $periodStart, $periodEnd);

        return $abstractEvents;
    }

    /**
     * @param AbstractAppointment[] $abstractAppointments
     * @param \DateTime $start
     * @param \DateTime $end
     * @return array
     */
    public function createEventsFromAbstractAppointments($abstractAppointments, \DateTime $start, \DateTime $end)
    {
        $events = [];
        /**
         * @var string $key
         * @var AbstractAppointment $abstractAppointment
         */
        foreach ($abstractAppointments as $key => $abstractAppointment) {
            $event = null;
            switch ($abstractAppointment->getRepeatType()) {
                case AbstractAppointment::REPEAT_TYPE_WEEKLY:
                    if($abstractAppointment->getValidUntilLimit()) {
                        $lastValidEvent = $this->getLastValidDay($abstractAppointment);
                        if ($lastValidEvent > $end) {
                            continue;
                        }
                        /** Create Events */
                        for($i = 0;$i < $abstractAppointment->getValidUntilLimit(); $i++) {
                            
                        }
                    }
                    break;
                case AbstractAppointment::REPEAT_TYPE_YEARLY:
                    if($abstractAppointment->getValidUntilLimit()) {
                        /** @TODO: calculate weather or not date is within reach */
/*                        $lastValidDate = new\DateTime($abstractAppointment->getValidFrom());
                        $lastValidDate->add(new \DateInterval($abstractAppointment->getValidUntilLimit() . ' years'));
*/

                    }
                    break;
                default:
                    break;
            }
            if ($event) {
                $events[] = $event;
            }
        }

        return $events;
    }

    /**
     * @param AbstractAppointment $abstractAppointment
     * @return \DateTime
     */
    public function getLastValidDay(AbstractAppointment $abstractAppointment)
    {
        switch($abstractAppointment->getRepeatType()) {
            case AbstractAppointment::REPEAT_TYPE_WEEKLY:
                if ($abstractAppointment->getValidUntilLimit()) {

                    $startDateDayOfWeek = $abstractAppointment->getValidFrom()->format('w');
                    $weekDaysAfterStart = $this->getWeekDaysAfterThis($abstractAppointment, $startDateDayOfWeek);
                    $repeatLastWeekDay = $this->getLastDayOfWeekDays($abstractAppointment);
                    $weekDaysCount = $this->countWeekDays($abstractAppointment);
                    $limit = $abstractAppointment->getValidUntilLimit();
                    $lastValidDay = new \DateTime($abstractAppointment->getValidFrom()->format('c'));

                    if($weekDaysAfterStart > $limit) {
                        for($i = $this->getNumberForWeekDay($startDateDayOfWeek);$i <= $this->getNumberForWeekDay($repeatLastWeekDay) && $limit > 0; $i++) {
                            if ($this->isRepeatWeekDay($abstractAppointment, $i)) {
                                $limit--;

                            }
                        }
                        $lastValidDay->modify($this->getWeekDayForNumber($i));

                        return $lastValidDay;
                    } elseif($weekDaysAfterStart == $limit) {
                        $lastValidDay->modify($repeatLastWeekDay);
                        return $lastValidDay;
                    } else {
                        $limit -= $weekDaysAfterStart;
                    }

                    $fullWeeks = round($limit / $weekDaysCount);
                    $remainderOfFullWeeks = $limit % $weekDaysCount;

                    if($remainderOfFullWeeks == 0 && $fullWeeks == 0) {
                        die(__FILE__.':'.__LINE__.': this should not happen');
                    } elseif ($remainderOfFullWeeks == 0 && $fullWeeks > 0) {
                        $lastValidDay->modify('+' . $fullWeeks * 7 . ' days');
                        return $lastValidDay;
                    } elseif ($remainderOfFullWeeks > 0 && $fullWeeks > 0) {
                        $limit -= ($fullWeeks * $weekDaysCount);
                        $lastValidDay->modify('+' . $fullWeeks * 7 . ' days');
                        for($i = 0;$i <= 6 && $limit > 0; $i++) {
                            if ($this->isRepeatWeekDay($abstractAppointment, $i)) {
                                $limit--;
                            }
                        }
                        $lastValidDay->modify($this->getWeekDayForNumber($i));
                        return $lastValidDay;
                    }

                    die(__FILE__.':'.__LINE__.': this should also not happen');
                }
                elseif ($abstractAppointment->getValidTill()) {
                    /** @TODO calculate last WeekDay */
                    return $abstractAppointment->getValidTill();
                }

                break;
            default:
                break;
        }

        return null;
    }

    public function getWeekDaysAfterThis(AbstractAppointment $abstractAppointment, $weekDay)
    {
        if(!is_numeric($weekDay)) {
            $weekDay = $this->getNumberForWeekDay($weekDay);
        }
        $count = 0;

        for($i = 6;$i >= $weekDay;$i--) {
            if ($this->isRepeatWeekDay($abstractAppointment, $i)) {
                $count++;
            }
        }

        return $count;
    }

    /**
     * @param AbstractAppointment $abstractAppointment
     * @param $weekDay
     * @return int
     * @deprecated
     */
    public function getWeekDaysBeforeThis(AbstractAppointment $abstractAppointment, $weekDay)
    {
        if(!is_numeric($weekDay)) {
            $weekDay = $this->getNumberForWeekDay($weekDay);
        }
        $count = 0;

        for($i = 0;$i <= $weekDay;$i++) {
            if ($this->isRepeatWeekDay($abstractAppointment, $i)) {
                $count++;
            }
        }

        return $count;
    }

    public function countWeekDays(AbstractAppointment $abstractAppointment)
    {
        $count = 0;
        for($i = 6;$i >= 0;$i--) {
            if ($this->isRepeatWeekDay($abstractAppointment, $i)) {
                $count++;
            }
        }

        return $count;
    }

    public function getLastDayOfWeekDays(AbstractAppointment $abstractAppointment)
    {
        for($i = 6;$i >= 0;$i--) {
            if ($this->isRepeatWeekDay($abstractAppointment, $i)) {
                return $this->getWeekDayForNumber($i);
            }
        }

        return $abstractAppointment->getValidFrom()->format('l');
    }

    /**
     * @param AbstractAppointment $abstractAppointment
     * @return string
     * @deprecated
     */
    public function getFirstDayOfWeekDays(AbstractAppointment $abstractAppointment)
    {
        for($i = 0;$i < 6;$i++) {
            if ($this->isRepeatWeekDay($abstractAppointment, $i)) {
                return $this->getWeekDayForNumber($i);
            }
        }

        return $abstractAppointment->getValidFrom()->format('l');
    }

    /**
     * @param AbstractAppointment $abstractAppointment
     * @return array
     * @deprecated
     */
    public function getRepeatWeekDays(AbstractAppointment $abstractAppointment)
    {
        $weekDays = [];
        for($i = 0;$i < 6;$i++) {
            if ($this->isRepeatWeekDay($abstractAppointment, $i)) {
                $weekDays[$i] = $this->getWeekDayForNumber($i);
            }
        }

        return count($weekDays) > 0 ? $weekDays : [$abstractAppointment->getValidFrom()->format('l')];
    }

    public function isRepeatWeekDay(AbstractAppointment $abstractAppointment, $weekDay)
    {
        if(is_numeric($weekDay)) {
            $weekDay = $this->getWeekDayForNumber($weekDay);
        }

        return (
            ( $weekDay == 'Sunday' && $abstractAppointment->getWeekdaySunday() )
            || ( $weekDay == 'Sunday' && $abstractAppointment->getWeekdaySunday() )
            || ( $weekDay == 'Monday' && $abstractAppointment->getWeekdayMonday() )
            || ( $weekDay == 'Tuesday' && $abstractAppointment->getWeekdayTuesday() )
            || ( $weekDay == 'Wednesday' && $abstractAppointment->getWeekdayWednesday() )
            || ( $weekDay == 'Thursday' && $abstractAppointment->getWeekdayThursday() )
            || ( $weekDay == 'Friday' && $abstractAppointment->getWeekdayFriday() )
            || ( $weekDay == 'Saturday' && $abstractAppointment->getWeekdaySaturday() )
        );
    }

    /**
     * @return array
     */
    public function getWeekDaysToNumbers()
    {
        return $this->WeekDaysToNumbers;
    }

    /**
     * @return array
     */
    public function getNumbersToWeekDays()
    {
        if(!$this->numbersToWeekDays) {
            $this->numbersToWeekDays = array_flip($this->WeekDaysToNumbers);
        };
        return $this->numbersToWeekDays;
    }

    public function getWeekDayForNumber($number) {
        return $this->getNumbersToWeekDays()[$number];
    }

    public function getNumberForWeekDay($weekDay) {
        return $this->getWeekDaysToNumbers()[$weekDay];
    }

}