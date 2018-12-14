<?php
/*
 * Класс обработки публикаций
 * @author Alexey O Klimov
 * @create 08.05.2012
 * @update
 */

class Schedule
{
    // название дней недели
    private $weekNames;
    // Название месяцев
    private $monthName;
    // год
    private $year;
    // основная переменная класса
    public $calendar = array();
    // Пользовательские названия дней недели
    public $daysOfWeekNames;
    // Пользовательские названия дней недели
    public $monthNames;
    // Инфоблок
    public $iblock;
    // Вендор мериприятия
    public $vendor;


    /**
     * Конструктор класса
     * Schedule constructor.
     * @param $iblock
     * @param int $vendor
     */
    public function __construct($iblock, $vendor = 0) {
        $this->__setDayOfWeekNames($this->daysOfWeekNames);
        $this->__setMonthName($this->monthNames);
        $this->calendar = $this->getCalendar();
        $this->setIblock($iblock);
        if ((int)$vendor > 0) {
            $this->setVendor($vendor);
        }
    }

    /**
     * @param mixed $iblock
     */
    public function setIblock($iblock)
    {
        $this->iblock = $iblock;
    }

    /**
     * @return mixed
     */
    public function getIblock()
    {
        return $this->iblock;
    }

    /**
     * @param mixed $vendor
     */
    public function setVendor($vendor)
    {
        $this->vendor = $vendor;
    }

    /**
     * @return mixed
     */
    public function getVendor()
    {
        return $this->vendor;
    }



    /**
     * Установка пользовательских названий для дней недели
     * @param array $day_names
     */
    private function  __setDayOfWeekNames ($day_names = array()) {
        // Название дней недели по умолчанию
        if (!is_array($day_names) or empty($day_names))
            $day_names = array('пн','вт','ср','чт','пт','сб','вс');

        foreach ($day_names as $key => $value)
            $this->weekNames[$key] = $day_names[$key];
    }

    /*
     * Установка пользовательских названий месяцев
     * @param array $month_names
     */
    private function __setMonthName($month_names = array()) {
        // Название дней недели по умолчанию
        if (!is_array($month_names) or empty($month_names))
            $month_names = array(
                1   =>  'Январь',
                2   =>  'Февраль',
                3   =>  'Март',
                4   =>  'Апрель',
                5   =>  'Май',
                6   =>  'Июнь',
                7   =>  'Июль',
                8   =>  'Август',
                9   =>  'Сентябрь',
                10  =>  'Октябрь',
                11  =>  'Ноябрь',
                12  =>  'Декабрь',
            );

        foreach ($month_names as $key => $value)
            $this->monthName[$key] = $month_names[$key];
    }

    /*
     * Вывод названия месяца
     * param n - month number
     */
    private function __getMonthName($n) {
        if (!isset($n) or (int)$n < 1 or (int)$n > 12)
            return false;

        $data = $this->monthName;
        return $data[$n];
    }

    /**
     * Получение текущего месяца
     */
    private function __getCurrentMonth() {
        return date('m');
    }

    /**
     * Получение текущего года
     */
    private function __getCurrentYear() {
        return date('Y');
    }

    /**
     * Получение календаря на заданный месяц
     * @param int $n - номер месяца начиная от сегодняшнего
     * @return array
     */
    public function getCalendar($n=0) {

        // Определяем месяц
        $month = $this->__getCurrentMonth() + $n;

        // Определяем год
        $year = $this->__getCurrentYear();

        // Номер месяца
        $monthNum = (int)date('m', mktime(0, 0, 0, $month, 1, $year));

        // Названия дней недели
        $weekDayNames = $this->weekNames;

        // Вычисляем число дней в заданом месяце
        $dayofmonth = date('t', mktime(0, 0, 0, $month, 1, $year));

        // Вычисляем число дней в предыдущем месяце
        $dayofpremonth = (int)date('t', mktime(0, 0, 0, $month-1, 1, $year));

        // Счётчик для дней месяца
        $day_count = 1;

        // 1. Первая неделя
        $num = 0;
        for($i = 0; $i < 7; $i++)
        {
            // Вычисляем номер дня недели для числа
            $dayofweek = date('w', mktime(0, 0, 0, $month, $day_count, $year));

            // Приводим к числа к формату 1 - понедельник, ..., 6 - суббота
            $dayofweek = $dayofweek - 1;
            if($dayofweek == -1) $dayofweek = 6;

            if($dayofweek == $i)
            {
                // Если дни недели совпадают,
                // заполняем массив $week
                // числами месяца
                $week[$num][$i] = $day_count;
                $day_count++;
            }
            else
            {
                $week[$num][$i] = "";
            }

        }

        // Заполнение даными предыдущего месяца
        $tmp = array_reverse($week[0], TRUE);
        foreach($tmp as $key => $value)
            if ($value == '')
            {
                $tmp[$key] = $dayofpremonth;
                $dayofpremonth--;
            }

        $week[0] = array_reverse($tmp, TRUE);
        unset($tmp);


        // 2. Последующие недели месяца
        while(true)
        {
            $num++;
            for($i = 0; $i < 7; $i++)
            {
                $week[$num][$i] = $day_count;
                $day_count++;
                // Если достигли конца месяца - выходим
                // из цикла
                if($day_count > $dayofmonth) break;
            }

            // Если достигли конца месяца - выходим
            // из цикла
            if($day_count > $dayofmonth) break;
        }


        // Добавляем дни нового месяца
        $tmp = array_pop($week);
        $nextday = 1;
        for($i = 0; $i < 7; $i++)
            if (!isset($tmp[$i]) or $tmp[$i] == '')
            {
                $tmp[$i] = $nextday;
                $nextday++;
            }
        $week[] = $tmp;
        unset($tmp);

        $this->year = date('Y', mktime(0, 0, 0, $month, 1, $year));

        $data = array(
            'n'         => $n,
            'year'     =>  $this->year,
            'monthnum'     =>  $monthNum,
            'month'     =>  $this->__getMonthName($monthNum),
            'prevmonth' =>  ($monthNum-1)%12 == 0 ? 12 : ($monthNum-1)%12,
            'nextmonth' =>  ($monthNum+1)%12 == 0 ? 12 : ($monthNum+1)%12,
            'weekNames' =>  $this->weekNames,
            'week'      =>  $week,
        );

        // Возвращает массив
        return $data;
    }

    /**
     * Получение событий за месяц
     * @param int $month
     * @param int $day_start
     * @param int $day_end
     * @return array
     */
    private function __getActionsMonth ($month, $day_start=0, $day_end=0) {

        $day_s = (int)$day_start > 0 ? $day_start : 1;
        $day_e = (int)$day_end > 0 ? $day_end : 1;

        // Получение UNIX времени текущего месяца
        $time_start = mktime(0,0,0, $month, $day_s, $this->year);

        if ($day_e > 1)
            $time_end = mktime(0,0,0, $month, $day_e, $this->year);
        else
            $time_end = mktime(0,0,0, $month+1, $day_e, $this->year);

        $arFilter = array(
            "IBLOCK_ID" => $this->getIblock(),
            "ACTIVE"    => "Y",
            ">=PROPERTY_START_EVENT_DATE" => date("Y-m-d", $time_start),
            array(
                "LOGIC" => "OR",
                "<=PROPERTY_END_EVENT_DATE" => date("Y-m-d", $time_end),
                "PROPERTY_END_EVENT_DATE" => false
            )
        );

        if ((int)$this->getVendor() > 0) {
            $arFilter["PROPERTY_VENDOR_ITEM"] = $this->getVendor();
        }

        $res = CIBlockElement::GetList(
            array("PROPERTY_START_EVENT_DATE"=>"ASC"),
            $arFilter,
            false,
            false,
            array(
                "ID",
                "NAME",
                "PREVIEW_TEXT",
                "DETAIL_PICTURE",
                "DETAIL_TEXT",
                "DETAIL_PAGE_URL",
                "PROPERTY_START_EVENT_DATE",
                "PROPERTY_END_EVENT_DATE"
            )
        );

        $data = array();
        while($ob = $res->GetNext())
        {

            if (empty($ob["PROPERTY_END_EVENT_DATE_VALUE"])) {
                $ob["PROPERTY_END_EVENT_DATE_VALUE"] = $ob["PROPERTY_START_EVENT_DATE_VALUE"];
            }

            $data[] = $ob;
        }
        



        // Получение событий за месяц
//        $where = ' AND `p`.`type` = 4 AND `p`.`action_date` >= '.$time_start.' AND `p`.`action_date` < '.$time_end;
//        $data = $this->getListShort($where, array('schedule_position' => 'ASC', 'date_add' => 'DESC'), 0, 1);
        
        return $data;
    }


    /**
     * Получение событий
     */
    private function __getScheduleActions() {

        // Получаем массив недель месяца
        $tmp = $this->calendar['week'];
        $count = count($this->calendar['week']);

        // Получение событий за предыдущий месяц
        $fist_week = array_shift($tmp);
        $day_start = $fist_week[0] > 7 ? $fist_week[0] : 0;
        $prev_month = $this->__getActionsMonth($this->calendar['monthnum']-1, $day_start);
        for($j=0;$j<7;$j++)
            if ($this->calendar['week'][0][$j] > 20) {
                $this->calendar['week'][0][$j] = array(
                    'day'   => $this->calendar['week'][0][$j],
                    'another_month' => 1,
                    'today' => 0,
                    'actions' => array(),
                );
                // События
                for ($k=0;$k<count($prev_month);$k++) {
//                    if ($prev_month[$k]['action_day'] == $this->calendar['week'][0][$j]['day'])
//                        $this->calendar['week'][0][$j]['actions'][] = $prev_month[$k];


                    $start_date = (int)CDataBase::FormatDate($prev_month[$k]["PROPERTY_START_EVENT_DATE_VALUE"], "DD.MM.YYYY HH:MI:SS", "DD");
                    $end_date = !empty($prev_month[$k]["PROPERTY_END_EVENT_DATE_VALUE"]) ? (int)CDataBase::FormatDate($prev_month[$k]["PROPERTY_END_EVENT_DATE_VALUE"], "DD.MM.YYYY HH:MI:SS", "DD") : 0;

                    if ($start_date == $this->calendar['week'][0][$j]['day'])
                        $this->calendar['week'][0][$j]['actions'][] = $prev_month[$k];
                    elseif($end_date > 0) {
                        $tmp_start_date = DateTime::createFromFormat('d.m.Y H:i:s', $prev_month[$k]["PROPERTY_START_EVENT_DATE_VALUE"]);
                        $tmp_end_date = DateTime::createFromFormat('d.m.Y H:i:s', $prev_month[$k]["PROPERTY_END_EVENT_DATE_VALUE"]);
                        $dayDiff = $tmp_start_date->diff($tmp_end_date)->format('%a');

                        if ($start_date < $this->calendar['week'][0][$j]['day'] && ($start_date + $dayDiff) >= $this->calendar['week'][0][$j]['day']) {
                            $this->calendar['week'][0][$j]['actions'][] = $prev_month[$k];
                        }

                    }
                }

            }

        // Получение событий за текущий месяц
        $month = $this->__getActionsMonth($this->calendar['monthnum']);

        // Недели месяца
        for ($i=0;$i<$count;$i++) {
            // Первая неделя
            if ($i == 0){
                // Дни недели
                for($j=0;$j<7;$j++)
                    if ($this->calendar['week'][$i][$j] < 10) {
                        $this->calendar['week'][$i][$j] = array(
                            'day'   => $this->calendar['week'][$i][$j],
                            'another_month' => 0,
                            'today' => $this->calendar['week'][$i][$j] == (int)date('d') && $this->calendar['monthnum'] == $this->__getCurrentMonth() ? 1 : 0,
                            'actions' => array()

                        );

                        // События
                        for ($k=0;$k<count($month);$k++) {
//                            if ($month[$k]['action_day'] == $this->calendar['week'][$i][$j]['day'])
//                                $this->calendar['week'][$i][$j]['actions'][] = $month[$k];

                            $start_date = (int)CDataBase::FormatDate($month[$k]["PROPERTY_START_EVENT_DATE_VALUE"], "DD.MM.YYYY HH:MI:SS", "DD");
                            $end_date = !empty($month[$k]["PROPERTY_END_EVENT_DATE_VALUE"]) ? (int)CDataBase::FormatDate($month[$k]["PROPERTY_END_EVENT_DATE_VALUE"], "DD.MM.YYYY HH:MI:SS", "DD") : 0;

                            if ($start_date == $this->calendar['week'][$i][$j]['day'])
                                $this->calendar['week'][$i][$j]['actions'][] = $month[$k];
                            elseif($end_date > 0) {
                                $tmp_start_date = DateTime::createFromFormat('d.m.Y H:i:s', $month[$k]["PROPERTY_START_EVENT_DATE_VALUE"]);
                                $tmp_end_date = DateTime::createFromFormat('d.m.Y H:i:s', $month[$k]["PROPERTY_END_EVENT_DATE_VALUE"]);

                                $dayDiff = $tmp_start_date->diff($tmp_end_date)->format('%a');

                                if ($start_date < $this->calendar['week'][$i][$j]['day'] && ($start_date + $dayDiff) >= $this->calendar['week'][$i][$j]['day']) {
                                    $this->calendar['week'][$i][$j]['actions'][] = $month[$k];
                                }

                            }
                        }

                    }
            }
            // Последняя неделя
            elseif ($i == $count-1) {
                // Дни недели

                for($j=0;$j<7;$j++)
                    if ($this->calendar['week'][$i][$j] > 20) {
                        $this->calendar['week'][$i][$j] = array(
                            'day'   => $this->calendar['week'][$i][$j],
                            'another_month' => 0,
                            'today' => $this->calendar['week'][$i][$j] == (int)date('d') && $this->calendar['monthnum'] == $this->__getCurrentMonth() ? 1 : 0,
                            'actions' => array(),
                        );
                        // События
                        for ($k=0;$k<count($month);$k++) {
//                            if ($month[$k]['action_day'] == $this->calendar['week'][$i][$j]['day'])
//                                $this->calendar['week'][$i][$j]['actions'][] = $month[$k];


                            $start_date = (int)CDataBase::FormatDate($month[$k]["PROPERTY_START_EVENT_DATE_VALUE"], "DD.MM.YYYY HH:MI:SS", "DD");
                            $end_date = !empty($month[$k]["PROPERTY_END_EVENT_DATE_VALUE"]) ? (int)CDataBase::FormatDate($month[$k]["PROPERTY_END_EVENT_DATE_VALUE"], "DD.MM.YYYY HH:MI:SS", "DD") : 0;

                            if ($start_date == $this->calendar['week'][$i][$j]['day'])
                                $this->calendar['week'][$i][$j]['actions'][] = $month[$k];
                            elseif($end_date > 0) {
                                $tmp_start_date = DateTime::createFromFormat('d.m.Y H:i:s', $month[$k]["PROPERTY_START_EVENT_DATE_VALUE"]);
                                $tmp_end_date = DateTime::createFromFormat('d.m.Y H:i:s', $month[$k]["PROPERTY_END_EVENT_DATE_VALUE"]);
                                $dayDiff = $tmp_start_date->diff($tmp_end_date)->format('%a');

                                if ($start_date < $this->calendar['week'][$i][$j]['day'] && ($start_date + $dayDiff) >= $this->calendar['week'][$i][$j]['day']) {
                                    $this->calendar['week'][$i][$j]['actions'][] = $month[$k];
                                }

                            }

                        }

                    }

            }
            // Остальные недели
            else {
                // Дни недели
                for($j=0;$j<7;$j++) {
                    $this->calendar['week'][$i][$j] = array(
                        'day'   => $this->calendar['week'][$i][$j],
                        'another_month' => 0,
                        'today' => $this->calendar['week'][$i][$j] == (int)date('d') && $this->calendar['monthnum'] == $this->__getCurrentMonth() ? 1 : 0,
                        'actions' => array(),
                    );
                    // События
                    for ($k=0;$k<count($month);$k++) {
                        $start_date = (int)CDataBase::FormatDate($month[$k]["PROPERTY_START_EVENT_DATE_VALUE"], "DD.MM.YYYY HH:MI:SS", "DD");
                        $end_date = !empty($month[$k]["PROPERTY_END_EVENT_DATE_VALUE"]) ? (int)CDataBase::FormatDate($month[$k]["PROPERTY_END_EVENT_DATE_VALUE"], "DD.MM.YYYY HH:MI:SS", "DD") : 0;

                        if ($start_date == $this->calendar['week'][$i][$j]['day'])
                            $this->calendar['week'][$i][$j]['actions'][] = $month[$k];
                        elseif($end_date > 0) {
                            $tmp_start_date = DateTime::createFromFormat('d.m.Y H:i:s', $month[$k]["PROPERTY_START_EVENT_DATE_VALUE"]);
                            $tmp_end_date = DateTime::createFromFormat('d.m.Y H:i:s', $month[$k]["PROPERTY_END_EVENT_DATE_VALUE"]);
                            $dayDiff = $tmp_start_date->diff($tmp_end_date)->format('%a');

                            if ($start_date < $this->calendar['week'][$i][$j]['day'] && ($start_date + $dayDiff) >= $this->calendar['week'][$i][$j]['day']) {
                                $this->calendar['week'][$i][$j]['actions'][] = $month[$k];
                            }

                        }
                    }
                        
                }
            }
        }

        // Получение событий на следующий месяц
        $last_week = array_pop($tmp);
        $day_end = $last_week[6] < 20 ? $last_week[6] : 0;
        $next_month = $this->__getActionsMonth($this->calendar['monthnum']+1, 0, $day_end);
        for($j=0;$j<7;$j++)
            if ($this->calendar['week'][$count-1][$j] < 10) {
                $this->calendar['week'][$count-1][$j] = array(
                    'day'   => $this->calendar['week'][$count-1][$j],
                    'another_month' => 1,
                    'today' => 0,
                    'actions' => array(),
                );
                // События
                for ($k=0;$k<count($next_month);$k++) {

//                    if ($next_month[$k]['action_day'] == $this->calendar['week'][$count-1][$j]['day'])
//                        $this->calendar['week'][$count-1][$j]['actions'][] = $next_month[$k];

                    $start_date = (int)CDataBase::FormatDate($next_month[$k]["PROPERTY_START_EVENT_DATE_VALUE"], "DD.MM.YYYY HH:MI:SS", "DD");
                    $end_date = !empty($next_month[$k]["PROPERTY_END_EVENT_DATE_VALUE"]) ? (int)CDataBase::FormatDate($next_month[$k]["PROPERTY_END_EVENT_DATE_VALUE"], "DD.MM.YYYY HH:MI:SS", "DD") : 0;

                    if ($start_date == $this->calendar['week'][$count-1][$j]['day'])
                        $this->calendar['week'][$count-1][$j]['actions'][] = $next_month[$k];
                    elseif($end_date > 0) {
                        $tmp_start_date = DateTime::createFromFormat('d.m.Y H:i:s', $next_month[$k]["PROPERTY_START_EVENT_DATE_VALUE"]);
                        $tmp_end_date = DateTime::createFromFormat('d.m.Y H:i:s', $next_month[$k]["PROPERTY_END_EVENT_DATE_VALUE"]);
                        $dayDiff = $tmp_start_date->diff($tmp_end_date)->format('%a');

                        if ($start_date < $this->calendar['week'][$count-1][$j]['day'] && ($start_date + $dayDiff) >= $this->calendar['week'][$count-1][$j]['day']) {
                            $this->calendar['week'][$count-1][$j]['actions'][] = $next_month[$k];
                        }

                    }
                }

            }

        $this->calendar["prevmonth_name"] = $this->__getMonthName($this->calendar["prevmonth"]);
        $this->calendar["nextmonth_name"] = $this->__getMonthName($this->calendar["nextmonth"]);
    }


    public function showSchedule() {
        $this->__getScheduleActions();
        return $this->calendar;
    }
}
