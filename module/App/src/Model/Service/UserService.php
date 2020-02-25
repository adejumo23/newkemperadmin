<?php
/**
 * Date: 1/23/2020
 * Time: 8:52 PM
 */
namespace App\Model\Service;
use App\Auth\Identity;
use App\Auth\Storage;
use Zend\Authentication\Result;
use Zend\Authentication\Storage\Session;
use Zend\Db\Adapter\Adapter;
use Zend\Db;
use Zend\Authentication\AuthenticationService;
use Zend\Db\Adapter\Adapter as DbAdapter;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;
use Zend\Session\Service\SessionConfigFactory;
use Zend\Session\SessionManager;
use Zend\Session\Storage\SessionStorage;

class UserService
{
    /** @var AuthenticationService */
    private $_auth;


    /**
     * @param $username
     * @return array|null
     */
    public function authenticateUser($username, $password)
    {
        $dbAdapter = new DbAdapter(array(
            'driver' => 'sqlsrv',
            'hostname' => 'localhost\SQLEXPRESS',
            'username' => 'sa',
            'password' => 'tiger',
            'database' => 'kemperadmin',
        ));

        $authAdapter = new AuthAdapter($dbAdapter,
            'users',
            'username',
            'password'
        );

        $authAdapter
            ->setIdentity($username)
            ->setCredential($password)
        ;
        $this->getAuth()->getStorage()->clear();
        $result = $this->getAuth()->authenticate($authAdapter);
        if (!$result->isValid()) {
            switch ($result->getCode()) {
                case Result::FAILURE_IDENTITY_NOT_FOUND:
                    return [
                        'error' => 'Could not find user.'
                    ];
                    break;

                case Result::FAILURE_CREDENTIAL_INVALID:
                    return [
                        'error' => 'Invalid Username or Password.'
                    ];
                    break;

                default:
                    return [
                        'error' => 'Failed verifying credential. Please try again later.'
                    ];
                    break;
            }
        }
        $columnsToOmit = array(
            'password'
        );
        $data = $authAdapter->getResultRowObject(null, $columnsToOmit);
        $identity = $this->createIdentity($data);
        $storage = $this->_auth->getStorage();
        $storage->write($identity);

        /*
        if ($this->_auth->hasIdentity()) {
            $identity = $this->_auth->getIdentity();
        }

        */

    }

    /**
     * @param object $data
     * @return Identity
     */
    private function createIdentity($data)
    {
        $identity = new Identity();
        $identity->setUsername($data->username);
        $identity->setFirstname($data->firstname);
        $identity->setLastname($data->lastname);
        return $identity;
    }

    /**
     * @return AuthenticationService
     */
    public function getAuth()
    {
        if (!$this->_auth) {
            $sessionConfig = new SessionConfigFactory();
            $sessionManager = new SessionManager();
            $this->_auth = new AuthenticationService(new Session('Kemper_Auth', 'session_auth', $sessionManager));
        }
        return $this->_auth;
    }


}