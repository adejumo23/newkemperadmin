<?php
/**
 * Date: 1/23/2020
 * Time: 10:07 PM
 */

namespace App\Auth;


use App\Model\Entity\UserProfile;

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
     * @var UserProfile
     */
    protected $profile;
    /**
     * @var int
     */
    protected $profileId;


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

    /**
     * @return UserProfile
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * @param UserProfile $profile
     * @return Identity
     */
    public function setProfile($profile)
    {
        $this->profile = $profile;
        return $this;
    }

    /**
     * @return int
     */
    public function getProfileId()
    {
        return $this->profileId;
    }

    /**
     * @param int $profileId
     * @return Identity
     */
    public function setProfileId($profileId)
    {
        $this->profileId = $profileId;
        return $this;
    }


}