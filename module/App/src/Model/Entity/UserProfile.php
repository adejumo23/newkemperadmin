<?php


namespace App\Model\Entity;


use App\Auth\Identity;
use App\Model\Repository\ProfileRepository;

class UserProfile extends AbstractEntity
{
    /**
     * @var int
     */
    protected $id;
    /**
     * @var string
     */
    protected $name;
    /**
     * Stores the users that the profile has access to
     * @var array
     */
    protected $users;


    public function preSaveHook()
    {
        if (is_array($this->users)) {
            $this->users = json_encode($this->users);
        }
    }


    public function setMetadata()
    {
        $this->setTable('user_profiles');
        $this->setRepositoryClass(ProfileRepository::class);
        $this->addField('id', 'id', 'int', null, true);
        $this->addField('name', 'name', 'string');
        $this->addField('users', 'users', 'string');
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return UserProfile
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return UserProfile
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return array
     */
    public function getUsers()
    {
        if (is_string($this->users)) {
            $this->users = json_decode($this->users);
        }
        return $this->users;
    }

    /**
     * @param array $users
     * @return UserProfile
     */
    public function setUsers($users)
    {
        $this->users = $users;
        return $this;
    }

}