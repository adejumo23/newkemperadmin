<?php


namespace KemperAdmin\Form;


use App\Di\InjectableInterface;
use App\Form\AbstractForm;
use App\Form\Element\Button;
use App\Form\Element\ElementGroup;
use App\Form\Element\Form;
use App\Form\Element\Input;
use App\Model\Entity\User;

class EditUserForm extends AbstractForm
{
    /**
     * @var User
     */
    protected $user;

    /**
     * Implement the doPrepare function in your child class
     */
    protected function doPrepare()
    {
//Element group is a div and can be used for grouping elements

        $elementGroup = new ElementGroup();
        $elementGroup->addClass('form-row');
        $elementGroup5 = new ElementGroup();
        $elementGroup5->addClass('col');
        $elementGroup8 = new ElementGroup();
        $elementGroup8->addClass('col');
        $elementGroup6 = new ElementGroup();
        $elementGroup6->addClass('md-form');
        $elementGroup7 = new ElementGroup();
        $elementGroup7->addClass('md-form');
        $elementGroup10 = new ElementGroup();
        $elementGroup10->addClass('md-form');
        $elementGroup1 = new ElementGroup();

        $firstname = new Input();
        $firstname->setName('firstname');
        $firstname->setLabel('Firstname');
        $firstname->setClasses(['form-control']);

        $elementGroup2 = new ElementGroup();
        $elementGroup2->addClass('row');

        $lastname = new Input();
        $lastname->setName('lastname');
        $lastname->setLabel('Lastname');
        $lastname->setClasses(['form-control']);

        $password = new Input();
        $password->setName('password');
        $password->setLabel('Password');
        $password->setClasses(['form-control']);
        $password->addAttribute('type','text');
/*        $elementGroup2->addElement($lastname);*/

        $submit = new Input();
        $submit->addAttribute('type', 'submit');
        $submit->setValue('Submit');

        if ($this->user) {
            $firstname->setValue($this->user->getFirstname());
            $lastname->setValue($this->user->getLastname());
            $password->setValue($this->user->getPassword());
        }

        $elementGroup6->addElement($firstname);
        $elementGroup5->addElement($elementGroup6);
        $elementGroup->addElement($elementGroup5);
        $elementGroup7->addElement($lastname);
        $elementGroup8->addElement($elementGroup7);
        $elementGroup->addElement($elementGroup8);
        $elementGroup10->addElement($password);
//        $elementGroup->addElement($elementGroup10);
        $this->addElement($elementGroup);
        $this->addElement($elementGroup10);
        $this->addElement($submit);

    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return EditUserForm
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

}