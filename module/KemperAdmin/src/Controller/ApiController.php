<?php
/**
 * Date: 1/26/2020
 * Time: 9:52 PM
 */

namespace KemperAdmin\Controller;

use App\AbstractAppController;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class ApiController extends AbstractAppController
{
    protected $connectionInfo = [
        'auth' => [
            'url' => 'https://data.simtraction.com/security/authorize',
            'body' => [
                'user' => '',
                'password' => '',
            ],
        ],
        'repEndpoint' => [
            'url' => 'https://data.simtraction.com/security/authorize',
            'headers' => [
                'user-token' => '',
                'content-type' => 'application/json',
            ],
            'body' => [
                "user"=>"idavis",
                "password"=> "RNic2020kh!",
                "OpenClosed" => 1,
                "Producer" => 1,
                "StartDate" => "1/1/2020",
                "EndDate" => "1/31/2020",
                "CompanyID" => 1,
                "BranchID" => "",
            ]
            ,
        ],
    ];
    protected $connectionInfoFinal = [
        'auth' => [
            'url' => 'https://data.simtraction.com/compliance/representatives',
            'body' => [
                'user' => '',
                'password' => '',
            ],
        ],
        'repEndpoint' => [
            'url' => 'https://data.simtraction.com/compliance/representatives',
            'headers' => [
                'user-token' => '',
                'content-type' => 'application/json',
            ],
            'body' => [
                "OpenClosed" => 1,
                "Producer" => 1,
                "StartDate" => "1/1/2020",
                "EndDate" => "1/31/2020",
                "CompanyID" => 1,
                "BranchID" => "",
            ]
            ,
        ],
    ];
    /**
     * @return void|\Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
//        $username = '';
//        $pass = '';
//        $token = $this->getToken($username, $pass);

        $reps = $this->getRepresentatives();
        echo "<pre>";
        print_r($reps);
        exit();
    }

    protected function getToken($username, $password)
    {
        //Todo: Implement if needed
    }

    protected function getRepresentatives()
    {
        $auth = [
            "user"=>"idavis",
            "password"=> "RNic2020kh!"
        ];
        $newAuth = json_encode($auth);
        $result = [];
        try {
            $client = new Client(['defaults' => [
                'verify' => false,
            ]]);
            $endpoint = $this->connectionInfo['repEndpoint'];
            $url = $endpoint['url'];
            $body = json_encode($endpoint['body']);
            $result = $client->request('POST', $url, [
                'headers' => [
                    'user-token' => 'asasdfasdasd',
                    'content-type' => 'application/json',
                ],
                'body' => $body,
            ]);

            $result = $result->getBody();

        $result = json_decode((string)$result,true);
        $token = $result['SecurityToken'];
        $endpointFinal= $this->connectionInfoFinal['repEndpoint'];
        $url = $endpointFinal['url'];
        $body = json_encode($endpoint['body']);
        $result = $client->request('POST', $url, [
            'headers' => [
                'user-token' => $token,
                'content-type' => 'application/json',
            ],
            'body' => $body,
        ]);


        $result = $result->getBody()->getContents();
        } catch (\Throwable $e) {
            error_log("PHP Error: " . $e->getMessage() . $e->getTraceAsString());
        }
        return $result;
    }

}

