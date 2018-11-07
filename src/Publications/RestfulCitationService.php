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
     *
     * @return Citation[]
     */
    public function getCitationsByAuthorId($authorIdentifier)
    {
        $citations = [];

        $response = $this->client->get("authors/{$authorIdentifier}");

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
                $citation = sprintf(
                    "%s (%s). %s. %s, %s(%s), %s.",
                    $rawCitation['author'],
                    $rawCitation['source']['year'],
                    $rawCitation['source']['title'],
                    $rawCitation['source']['journal'],
                    $rawCitation['volume'],
                    $rawCitation['number'],
                    $rawCitation['pages']
                );

                $citations[] = new Citation(
                    $rawCitation['source']['id'],
                    $rawCitation['source']['title'],
                    $rawCitation['source']['journal'],
                    $rawCitation['source']['year'],
                    $authors,
                    $citation
                );
            } catch (Exception $e) {
                continue;
            }
        }

        return $citations;
    }
}
