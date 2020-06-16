<?php


namespace App\Model\Entity;


use App\Model\Repository\UserRepository;

class User extends AbstractEntity
{
    /**
     * @var int
     */
    protected $id;
    /**
     * @var string
     */
    protected $username;
    /**
     * @var string
     */
    protected $firstname;
    /**
     * @var string
     */
    protected $lastname;
    /**
     * @var int
     */
    protected $roleid;
    /**
     * @var int
     */
    protected $profileid;
    /**
     * @var string
     */
    protected $password;
    /**
     * @var string
     */
    protected $email;
    /**
     * @var string
     */
    protected $phone;



    public function setMetadata()
    {
        $this->setTable('users');
        $this->setRepositoryClass(UserRepository::class);
        $this->addField('id', 'user_id', 'int');
        $this->addField('username', 'username', 'string', null, true);
        $this->addField('firstname', 'firstname', 'string');
        $this->addField('lastname', 'lastname', 'string');
        $this->addField('password', 'password', 'string');
        $this->addField('email', 'email_address', 'string');
        $this->addField('phone', 'phone', 'string');
        $this->addField('roleid', 'role_id', 'int');
        $this->addField('profileid', 'profile_id', 'int');
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
     * @return User
     */
    public function setId($id)
    {
        $this->id = $id;
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
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     * @return User
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
        return $this;
    }

    /**
     * @return int
     */
    public function getRoleid()
    {
        return $this->roleid;
    }

    /**
     * @param int $roleid
     * @return User
     */
    public function setRoleid($roleid)
    {
        $this->roleid = $roleid;
        return $this;
    }

    /**
     * @return int
     */
    public function getProfileid()
    {
        return $this->profileid;
    }

    /**
     * @param int $profileid
     * @return User
     */
    public function setProfileid($profileid)
    {
        $this->profileid = $profileid;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     * @return User
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }



}