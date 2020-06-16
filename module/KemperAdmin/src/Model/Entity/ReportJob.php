<?php


namespace KemperAdmin\Model\Entity;


use App\Model\Entity\AbstractEntity;
use DateTime;
use KemperAdmin\Model\Repository\ReportJobRepo;

class ReportJob extends AbstractEntity implements \JsonSerializable
{

    /**
     * @var int
     */
    protected $reportid;
    /**
     * @var string
     */
    protected $reportTitle;
    /**
     * @var string
     */
    protected $formData;
    /**
     * @var string
     */
    protected $username;
    /**
     * @var DateTime
     */
    protected $timeSubmitted;
    /**
     * @var DateTime
     */
    protected $timeStarted;
    /**
     * @var DateTime
     */
    protected $timeFinished;

    /**
     * @var array
     */
    protected $reportConfig;

    public function preSaveHook()
    {
        $this->timeSubmitted = new DateTime();
        if (is_array($this->formData)) {
            $this->formData = json_encode($this->formData);
        }
    }

    //Todo: Clean table and fields properties from entities - move them to abstractrepo
    public function setMetadata()
    {
        $this->setTable('report_jobs');
        $this->setRepositoryClass(ReportJobRepo::class);
        $this->addField('reportid', 'id', 'int', null, true);
        $this->addField('reportTitle', 'report_title', 'string');
        $this->addField('formData', 'form_data', 'string');
        $this->addField('username', 'username', 'string');
        $this->addField('timeSubmitted', 'timesubmitted', 'datetime');
        $this->addField('timeStarted', 'timestarted', 'datetime');
        $this->addField('timeFinished', 'timefinished', 'datetime');
    }

    /**
     * @return int
     */
    public function getReportid()
    {
        return $this->reportid;
    }

    /**
     * @param int $reportid
     * @return ReportJob
     */
    public function setReportid($reportid)
    {
        $this->reportid = $reportid;
        return $this;
    }

    /**
     * @return string
     */
    public function getReportTitle()
    {
        return $this->reportTitle;
    }

    /**
     * @param string $reportTitle
     * @return ReportJob
     */
    public function setReportTitle($reportTitle)
    {
        $this->reportTitle = $reportTitle;
        return $this;
    }

    /**
     * @return string
     */
    public function getFormData()
    {
        return $this->formData;
    }

    /**
     * @param string $formData
     * @return ReportJob
     */
    public function setFormData($formData)
    {
        $this->formData = $formData;
        return $this;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return ReportJob
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getTimeSubmitted()
    {
        return $this->timeSubmitted;
    }

    /**
     * @param DateTime $timeSubmitted
     * @return ReportJob
     */
    public function setTimeSubmitted($timeSubmitted)
    {
        $this->timeSubmitted = $timeSubmitted;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getTimeStarted()
    {
        return $this->timeStarted;
    }

    /**
     * @param DateTime $timeStarted
     * @return ReportJob
     */
    public function setTimeStarted($timeStarted)
    {
        $this->timeStarted = $timeStarted;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getTimeFinished()
    {
        return $this->timeFinished;
    }

    /**
     * @param DateTime $timeFinished
     * @return ReportJob
     */
    public function setTimeFinished($timeFinished)
    {
        $this->timeFinished = $timeFinished;
        return $this;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return [
            'reportid' => $this->reportid,
            'reportTitle' => $this->reportTitle,
            'username' => $this->username,
            'timeSubmitted' => $this->timeSubmitted,
            'timeStarted' => $this->timeStarted,
            'timeFinished' => $this->timeFinished,
            'formdata' => $this->formData,
            'reportConfig' => $this->reportConfig,
        ];
    }

    /**
     * @return array
     */
    public function getReportConfig()
    {
        return $this->reportConfig;
    }

    /**
     * @param array $reportConfig
     * @return ReportJob
     */
    public function setReportConfig($reportConfig)
    {
        $this->reportConfig = $reportConfig;
        return $this;
    }
}