<?php
/**
 * Date: 1/23/2020
 * Time: 10:07 PM
 */

namespace App\Auth;


class Identity
{

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
     * @var array
     */
    protected $privs;

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return Identity
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
     * @return Identity
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
     * @return Identity
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
        return $this;
    }

    /**
     * @return array
     */
    public function getPrivs()
    {
        return $this->privs;
    }

    /**
     * @param array $privs
     * @return Identity
     */
    public function setPrivs($privs)
    {
        $this->privs = $privs;
        return $this;
    }

    public function getPriv($privName)
    {
        return $this->privs[$privName]??0;
    }


}