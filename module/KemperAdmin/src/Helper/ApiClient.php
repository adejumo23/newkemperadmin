<?php
/**
 * Date: 1/26/2020
 * Time: 10:03 PM
 */

namespace KemperAdmin\Helper;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface as GuzzleClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Zend\Feed\Reader\Http\ClientInterface as FeedReaderHttpClientInterface;
use Zend\Feed\Reader\Http\Psr7ResponseDecorator;
use Zend\Feed\Reader\Http\ResponseInterface;
use Zend\Http\Client as HttpClient;

class ApiClient implements FeedReaderHttpClientInterface
{

    /**
     * @var GuzzleClientInterface
     */
    private $client;

    /**
     * @param GuzzleClientInterface|null $client
     */
    public function __construct(GuzzleClientInterface $client = null)
    {
        $this->client = $client ?: new Client();
    }

    /**
     * Make a GET request to a given URI
     *
     * @param  string $uri
     * @return ResponseInterface
     */
    public function get($uri)
    {

        try {
            return new Psr7ResponseDecorator(
                $this->client->request('GET', $uri)
            );
        } catch (\Throwable $e) {
            error_log('PHP Error: '.$e->getMessage() . $e->getTraceAsString());
        }
    }
}