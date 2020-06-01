<?php


namespace KemperAdmin\Form;


use App\Di\InjectableInterface;
use App\Form\AbstractForm;
use App\Form\Element\ElementGroup;
use App\Form\Element\SubmitButton;
use Zend\Form\Element\Button;

class ReportGenerateForm extends AbstractForm implements InjectableInterface
{


    protected function doPrepare()
    {

        $this->addGenerateButton();

    }

    protected function addGenerateButton()
    {
        $button = new SubmitButton();
        $button->setName('rptgenerate');
        $button->addClass('btn blue-gradient');
        $button->setText('Generate');

        $buttonGroup = new ElementGroup();
        $buttonGroup->addClass('report-generate-buttons');
        $buttonGroup->addElement($button);
        $this->addElement($buttonGroup);
    }


}