<?php


namespace KemperAdmin\Form\Filter;


use App\Di\ContainerAwareInterface;

class DateRange extends AbstractFilter implements ContainerAwareInterface
{
    protected $name = 'date-range';
    protected $description = 'Date Range';


    protected function getHtml()
    {
        $defaults = $this->getDefaults();
        $defaultStartDate = "value=\"" . $defaults['start-date'] . "\"";
        $defaultEndDate = "value=\"" . $defaults['end-date'] . "\"";
        $html = <<<HTML
<div class="md-form">
                                    <input placeholder="Select a date" type="text" id="startdate" name="startdate" {$defaultStartDate} class="form-control datepicker">
                                    <label for="startdate">Start Date</label>
                                </div><br>
                                <div class="md-form">
                                    <input placeholder="Select a date" type="text" id="enddate" name="enddate" {$defaultEndDate} class="form-control datepicker">
                                    <label for="enddate">End Date</label>
                                </div>
HTML;

        return $html;
    }

    protected function getJs()
    {
        return ["js/filters/daterange.js"];
    }

}