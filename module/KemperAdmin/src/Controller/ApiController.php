<?php
/**
 * Date: 1/26/2020
 * Time: 9:52 PM
 */

namespace KemperAdmin\Controller;

use App\AbstractAppController;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Throwable;
use Zend\View\Model\ViewModel;

class ApiController extends AbstractAppController
{
    const LOGIN = 'login';
    const REPRESENTATIVES = 'representatives';
    protected $connectionInfo = [
        self::LOGIN => [
            'url' => 'https://data.simtraction.com/security/authorize',
            'options' => [
                'headers' => [],
                'body' => [
                    "user" => "idavis",
                    "password" => "RNic2020kh!",
                ],
            ],
        ],
        self::REPRESENTATIVES => [
            'url' => 'https://data.simtraction.com/compliance/representatives',
            'options' => [
                'headers' => [

                ],
                'body' => [
                    "OpenClosed" => 1,
                    "Producer" => 1,
                    "StartDate" => "1/1/2020",
                    "EndDate" => "1/31/2020",
                    "CompanyID" => 1,
                    "BranchID" => "",
                ],
            ]
            ,
        ],
    ];
    private $defaultHeaders = [
        'content-type' => 'application/json',
    ];
    /**
     * @var Client
     */
    private $client;

    /**
     * @return void|ViewModel
     */
    public function indexAction()
    {
        $identity = $this->getIdentity();
        $reps = $this->getRepresentatives();
        echo "<pre>";
        print_r($reps);
        exit();
    }

    /**
     * @return mixed
     */
    protected function getRepresentatives()
    {
        $endpoint = $this->getConnectionInfo(self::REPRESENTATIVES);
        $url = $endpoint['url'];
        $options = $endpoint['options'];
        $result = $this->doRequest('POST', $url, $options);
        return $this->getFromResult($result);
    }

    private function getConnectionInfo($endpoint)
    {
        return $this->connectionInfo[$endpoint];
    }

    /**
     * @param $method
     * @param $url
     * @param $options
     * @return ResponseInterface
     */
    private function doRequest($method, $url, $options)
    {
        try {
            $token = $this->getToken();
            $options['headers']['content-type'] = 'application/json';
            $options['headers']['user-token'] = $token;
            $body = json_encode($options['body']);
            $options['body'] = $body;
            //$options['headers']['user-token'] = $token;               //This line if they need it in headers
            return $this->getClient()->request($method, $url, $options);
        } catch (Throwable $e) {
            error_log("PHP Error: API Request Failed: " . $e->getMessage() . $e->getTraceAsString());
            die("Error");
        }
    }

    protected function getToken()
    {
        try {
            $client = $this->getClient();
            $endpoint = $this->getConnectionInfo(self::LOGIN);
            $url = $endpoint['url'];
            $body = json_encode($endpoint['options']['body']);
            $headers = array_merge($endpoint['options']['headers'], $this->defaultHeaders);
            $result = $client->request('POST', $url, [
                'headers' => $headers,
                'body' => $body,
            ]);
            return $this->getFromResult($result, 'SecurityToken');
        } catch (Throwable $e) {
            error_log("PHP Error: API Login Failed: " . $e->getMessage() . $e->getTraceAsString());
            die("Error");
        }
    }

    private function getClient()
    {
        if (!$this->client) {
            $this->client = new Client(['defaults' => ['verify' => false,]]);
        }
        return $this->client;
    }

    /**
     * @param ResponseInterface $result
     * @param string $key
     * @return mixed
     */
    private function getFromResult($result, $key = null)
    {
        $result = json_decode((string)$result->getBody()->getContents(), true);
        if ($key) {
            return $result[$key];
        }
        return $result;
    }

}

