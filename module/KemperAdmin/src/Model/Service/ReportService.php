<?php
/**
 * Date: 1/23/2020
 * Time: 10:31 PM
 */

namespace KemperAdmin\Model\Service;


class ReportService
{
    /**
     * @var \StdClass
     * @Inject(name="\StdClass")
     */
    protected $repo1;

    /**
     * @var \StdClass
     * @Inject(name="\StdClass")
     */
    protected $repo2;

    /**
     * @var string
     */
    protected $repo3;


    /**
     * @param $username
     * @return array
     */
    public function calcReportDataForUser($username)
    {
        print_r($username);
        return [];
    }

    /**
     * @param \StdClass $repo1
     * @return ReportService
     */
    public function setRepo1($repo1)
    {
        $this->repo1 = $repo1;
        return $this;
    }

    /**
     * @param \StdClass $repo2
     * @return ReportService
     */
    public function setRepo2($repo2)
    {
        $this->repo2 = $repo2;
        return $this;
    }

    /**
     * @param \StdClass $repo3
     * @return ReportService
     */
    public function setRepo3($repo3)
    {
        $this->repo3 = $repo3;
        return $this;
    }
}