<?php
namespace Adianti\Widget\Util;

use Adianti\Control\TAction;
use Adianti\Widget\Container\TTable;
use Adianti\Widget\Base\TElement;

/**
 * Calendar Widget
 *
 * @version    2.0
 * @package    widget
 * @subpackage util
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006-2014 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class TCalendar extends TElement
{
    private $months;
    private $year;
    private $month;
    private $width;
    private $height;
    private $action;
    private $selectedDays;
    
    /**
     * Class Constructor
     */
    public function __construct()
    {
        parent::__construct('div');
        $this->{'class'} = 'tcalendar';
        $this->width = 400;
        $this->height = 300;
        $this->selectedDays = array();
        $this->months = array(_t('January'), _t('February'), _t('March'), _t('April'), _t('May'), _t('June'),
                              _t('July'), _t('August'), _t('September'), _t('October'), _t('November'), _t('December'));
    }
    
    /**
     * Define the calendar's size
     * @param  $width  Window's width
     * @param  $height Window's height
     */
    public function setSize($width, $height)
    {
        $this->width  = $width;
        $this->height = $height;
    }
    
    /**
     * Define the current month to display
     * @param  $month Month to display
     */
    public function setMonth($month)
    {
        $this->month = $month;
    }
    
    /**
     * Define the current year to display
     * @param  $year Year to display
     */
    public function setYear($year)
    {
        $this->year = $year;
    }
    
    /**
     * Return the current month
     */
    public function getMonth()
    {
        return $this->month;
    }
    
    /**
     * Return the current year
     */
    public function getYear()
    {
        return $this->year;
    }
    
    /**
     * Define the action when click at some day
     * @param  $action TAction object
     */
    public function setAction(TAction $action)
    {
        $this->action = $action;
    }
    
    /**
     * Select a collection of days
     * @param  $days Collection of days
     */
    public function selectDays(array $days)
    {
        $this->selectedDays = $days;
    }
    
    /**
     * Show the calendar
     */
    public function show()
    {
        $this-> style = "width: {$this->width}px; height: {$this->height}px";
        
        $this->month = $this->month ? $this->month : date('m');
        $this->year = $this->year ? $this->year : date('Y');
        
        $table = new TTable;
        $table-> width = '100%';
        parent::add($table);
        
        $row = $table->addRow();
        $cell = $row->addCell($this->months[$this->month -1] . ' ' . $this->year);
        $cell-> colspan = 7;
        $cell-> class = 'calendar-header';
        
        $row = $table->addRow();
        $row->addCell('S')->class='calendar-header';
        $row->addCell('M')->class='calendar-header';
        $row->addCell('T')->class='calendar-header';
        $row->addCell('W')->class='calendar-header';
        $row->addCell('T')->class='calendar-header';
        $row->addCell('F')->class='calendar-header';
        $row->addCell('S')->class='calendar-header';
        
        
        $prev_year = $this->year;
        $next_year = $this->year;
        $prev_month = $this->month - 1;
        $next_month = $this->month + 1;
         
        if ($prev_month == 0 )
        {
            $prev_month = 12;
            $prev_year = $this->year - 1;
        }
        
        if ($next_month == 13 )
        {
            $next_month = 1;
            $next_year = $this->year + 1;
        }
        
        $timestamp = mktime( 0, 0, 0, $this->month, 1, $this->year );
        $maxday = date("t", $timestamp);
        $thismonth = getdate ($timestamp);
        $startday = $thismonth['wday'];
        for ($i=0; $i<($maxday + $startday); $i++)
        {
            if (($i % 7) == 0 )
            {
                $row = $table->addRow();
                $row-> class = 'calendar-rowdata';
            }
            
            if ($i < $startday)
            {
                $row->addCell('');
            }
            else
            {
                $current_day = ($i - $startday + 1);
                $cell = $row->addCell( $current_day );
                if (in_array($current_day, $this->selectedDays))
                {
                    $cell-> class = 'calendar-data calendar-selected';
                }
                else
                {
                    $cell-> class = 'calendar-data';
                }
                $cell-> valign = 'middle';
                
                if ($this->action instanceof TAction)
                {
                    $this->action->setParameter('year', $this->year); 
                    $this->action->setParameter('month', $this->month);
                    $this->action->setParameter('day', $current_day);
                    $string_action = $this->action->serialize(FALSE);
                    $cell-> onclick = "__adianti_ajax_exec('{$string_action}')";
                }
            }
        }
        parent::show();
    }
}
