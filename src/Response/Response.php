<?php

namespace S1njar\Kitsu\Response;

use Psr\Http\Message\ResponseInterface;
use S1njar\Kitsu\Exception\BadResponseException;

/**
 * Class Response
 */
class Response
{
    /** @var ResponseInterface */
    private $response;

    /**
     * Takes body of response and return it, if there are no error code.
     *
     * @param bool $assoc
     * @return array|mixed
     * @throws BadResponseException
     */
    public function get(bool $assoc = false)
    {
        $result = json_decode($this->response->getBody());

        if ($result->data->id) {
            return json_decode(json_encode([$result->data]), $assoc);
        }

        if ($result->meta->count <= 0) {
            throw new BadResponseException('No results where found.', 404);
        }

        return json_decode(json_encode($result->data), $assoc);
    }

    /**
     * Sets response and returns Response object.
     *
     * @param ResponseInterface $response
     * @return Response
     */
    public function setResponse(ResponseInterface $response): Response
    {
        $this->response = $response;
        return $this;
    }

    /**
     * @return ResponseInterface
     */
    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }
}