<?php

namespace S1njar\Kitsu\Builder;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use S1njar\Kitsu\Exception\BadResponseException;
use S1njar\Kitsu\Response\Response;
use Psr\Http\Message\ResponseInterface;

/**
 * Class RequestBuilder
 */
class RequestBuilder
{
    /** @var Client */
    private $httpClient;

    /** @var Response */
    private $response;

    /**
     * RequestBuilder constructor.
     */
    public function __construct()
    {
        $this->httpClient = new Client();
        $this->response = new Response();
    }

    /**
     * Takes SearchBuilder object and returns a Response object.
     *
     * @param SearchBuilder $searchBuilder
     * @return Response
     * @throws BadResponseException
     */
    public function build(SearchBuilder $searchBuilder): Response
    {
        $response = $this->get($searchBuilder);

        return $this->response->setResponse($response);
    }

    /**
     * Takes data from SearchBuilder and creates a request to kitsu api.
     * If something went wrong a BadResponseException will thrown with specified exception message.
     *
     * @param SearchBuilder $searchBuilder
     * @return ResponseInterface
     * @throws BadResponseException
     */
    private function get(SearchBuilder $searchBuilder): ResponseInterface
    {
        try {
            $response = $this->httpClient->get(
                $searchBuilder->getUrl(),
                [
                    'headers' => [
                        'Content-Type' => 'application/json'
                    ]
                ]
            );
        } catch (ConnectException $connectException){
            throw  new BadResponseException($connectException->getMessage(), $connectException->getCode());
        } catch (RequestException $requestException){
            throw  new BadResponseException($requestException->getMessage(), $requestException->getCode());
        }catch (GuzzleException $guzzleException){
            throw  new BadResponseException($guzzleException->getMessage(), $guzzleException->getCode());
        }

        return $response;
    }
}