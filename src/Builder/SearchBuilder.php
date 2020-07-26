<?php

namespace S1njar\Kitsu\Builder;

use S1njar\Kitsu\Exception\BadResponseException;
use S1njar\Kitsu\Response\Response;

/**
 * Class SearchBuilder
 */
class SearchBuilder
{
    public const FILTER_RANGE_GREATER_THAN = 'gt';
    public const FILTER_RANGE_LOWER_THAN = 'lt';
    public const ORDER_DIRECTION_ASC = '';
    public const ORDER_DIRECTION_DESC = '-';

    /** @var string */
    private const DEFAULT_ENDPOINT = 'anime';

    /** @var RequestBuilder */
    private $requestBuilder;

    /** @var string */
    private $originalUrl;

    /** @var string */
    private $url;

    /** @var string */
    private $endpoint = '';

    /**
     * SearchBuilder constructor.
     *
     * @param string $url
     */
    public function __construct(string $url = 'https://kitsu.io/api/edge')
    {
        $this->url = $url;
        $this->originalUrl = $url;
        $this->requestBuilder = new RequestBuilder();
    }

    /**
     * Builds url and searchs by instance criteria.
     *
     * @return Response
     * @throws BadResponseException
     */
    public function search(): Response
    {
        return $this->requestBuilder->build($this);
    }

    /**
     * Builds url and searchs by id.
     *
     * @param int $id
     * @param array $fields
     * @return Response
     * @throws BadResponseException
     */
    public function searchById(int $id, array $fields = []): Response
    {
        $this->url = rtrim($this->originalUrl, '/').'/'.$this->endpoint.'/'.(string) $id.'?';

        if ($fields) {
            $this->addFields($fields, $this->endpoint);
        }

        return $this->requestBuilder->build($this);
    }

    /**
     * Sets endpoint to search criteria.
     * Resets all other filters.
     *
     * @param string $endpoint
     * @return SearchBuilder
     */
    public function setEndpoint(string $endpoint): SearchBuilder
    {
        $this->endpoint = $endpoint;
        $this->url = rtrim($this->originalUrl, '/').'/'.$endpoint.'?';
        return $this;
    }

    /**
     * Adds search needle to search criteria.
     *
     * @param string $search
     * @return $this
     */
    public function addSearch(string $search): SearchBuilder
    {
        $search = 'filter[text]=' . $search;

        return $this->extendUrl($search);
    }

    /**
     * Adds fields to search criteria.
     *
     * @param array $fields
     * @param string $resource
     * @return $this
     */
    public function addFields(array $fields, string $resource): SearchBuilder
    {
        $fields = implode(',', $fields);
        $fields = 'fields[' . $resource . ']=' . $fields;

        return $this->extendUrl($fields);
    }

    /**
     * Adds filter to search criteria.
     * For greater than operator => $range = 'gt' use const.
     * For lower than operator => $range = 'lt' use const.
     *
     * @param string $field
     * @param string $filter
     * @param string $range
     * @return $this
     */
    public function addFilter(string $field, string $filter, string $range = ''): SearchBuilder
    {
        if (is_array($filter)) {
            $filter = '(' . implode(',', $filter) . ')';
        }

        if ($range === self::FILTER_RANGE_GREATER_THAN) {
            $filter = 'filter[' . $field . ']=' . $filter . '..';
        } elseif ($range === self::FILTER_RANGE_LOWER_THAN) {
            $filter = 'filter[' . $field . ']=' . '..' .$filter;
        } else {
            $filter = 'filter[' . $field . ']=' . $filter;
        }

        return $this->extendUrl($filter);
    }

    /**
     * Adds order to search criteria.
     * For orderDirection use const.
     *
     * @param string $field
     * @param string $orderDirection
     * @return $this
     */
    public function addOrder(string $field, string $orderDirection = ''): SearchBuilder
    {
        $order = 'sort=' . $orderDirection . $field;
        return $this->extendUrl($order);
    }

    /**
     * Adds limit to search criteria.
     *
     * @param int $limit
     * @return $this
     */
    public function addLimit(int $limit): SearchBuilder
    {
        $limit = 'page[limit]=' . (string) $limit;
        return $this->extendUrl($limit);
    }

    /**
     * Adds offset to search criteria.
     *
     * @param string $offset
     * @return $this
     */
    public function addOffset(string $offset): SearchBuilder
    {
        $offset = 'page[offset]=' . $offset;
        return $this->extendUrl($offset);
    }

    /**
     * Clears all search criterias.
     *
     * @return $this
     */
    public function clear(): SearchBuilder
    {
        $this->url = $this->originalUrl;
        $this->endpoint = '';
        return $this;
    }

    /**
     * Returns endpoint.
     *
     * @return string
     */
    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    /**
     * Returns url.
     *
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * Extends URL.
     *
     * @param string $string
     * @return SearchBuilder
     */
    private function extendUrl(string $string): SearchBuilder
    {
        if (!$this->endpoint) {
            $this->setEndpoint(self::DEFAULT_ENDPOINT);
        }

        $this->url = rtrim($this->getUrl(), '/').$string.'&';
        return $this;
    }
}