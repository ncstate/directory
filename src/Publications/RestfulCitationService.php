<?php

namespace NCState\Publications;

use Exception;
use GuzzleHttp\Client;

class RestfulCitationService implements CitationService
{
    /**
     * @var Client
     */
    private $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://ci.lib.ncsu.edu/api/v1/',
            'verify' => false
        ]);
    }

    /**
     * Returns an array of Citations matching an author by their
     * SPR-internal author identifier.
     *
     * @param int $authorIdentifier
     * @param int $limit default 10
     *
     * @return Citation[]
     */
    public function getCitationsByAuthorId($authorIdentifier, $limit = 10)
    {
        $citations = [];

        $response = $this->client->request('GET', "authors/{$authorIdentifier}", ['query' => ['limit' => $limit]]);

        if ($response->getStatusCode() !== 200) {
            return [];
        }

        $data = json_decode($response->getBody(), true);

        if (! isset($data[0]) or empty($data[0]) or json_last_error() !== JSON_ERROR_NONE) {
            return [];
        }

        $rawCitations = $data[0]['citations'];

        foreach ($rawCitations as $rawCitation) {
            try {
                $authors = explode(' and ', $rawCitation['author']);

                $citations[] = new Citation(
                    $rawCitation['id'],
                    $rawCitation['title'],
                    $rawCitation['journal'],
                    $rawCitation['year'],
                    $authors,
                    $rawCitation['html']
                );
            } catch (Exception $e) {
                continue;
            }
        }

        return $citations;
    }
}
