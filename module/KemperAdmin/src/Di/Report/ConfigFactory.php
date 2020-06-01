<?php


namespace KemperAdmin\Di\Report;


use App\Di\ContainerAwareInterface;
use App\Di\InjectableInterface;
use Interop\Container\ContainerInterface;

class ConfigFactory  implements ContainerAwareInterface, InjectableInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;
    /**
     * @var array
     */
    protected $reportConfigs;


    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getReportsByClassification($classification)
    {
        $reportConfigs = $this->getReportConfigs();
//        $reportSaveLocation = $this->container->get('config')['reportConfig']['report_save_location'];
        $return = [];
        foreach ($reportConfigs as $reportTitle => $reportConfig) {
            if ($reportConfig['classification'] == $classification) {
                $return[] = $reportConfig;
            }
        }
        return $return;
    }

    /**
     * @param string $reportTitle
     * @return array
     */
    public function getReportConfigByTitle($reportTitle)
    {
        $reportConfigs = $this->getReportConfigs();
        return $reportConfigs[$reportTitle];
    }

    /**
     * @return mixed
     */
    public function getReportConfigs()
    {
        if (!$this->reportConfigs) {
            $reportConfigs =  $this->container->get('config')['reportConfig']['reports'];
            foreach ($reportConfigs as $reportTitle => $reportConfig) {
                $reportConfigs[$reportTitle]['report-title'] = $reportTitle;
            }
            $this->reportConfigs = $reportConfigs;
        }
        return $this->reportConfigs;
    }
}