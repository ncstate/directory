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
            'base_uri' => 'http://www.webtools.ncsu.edu/spr/api/',
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

        $response = $this->client->get("authors/{$authorIdentifier}/citations");

        if ($response->getStatusCode() !== 200) {
            return [];
        }

        $data = json_decode($response->getBody(), true);

        if (! isset($data['items']) or empty($data['items'])) {
            return [];
        }

        $rawCitations = $data['items'];

        foreach ($rawCitations as $rawCitation) {
            try {
                $authors = [];

                foreach ($rawCitation['authors'] as $rawAuthor) {
                    $authors[] = $rawAuthor['last_name'] . ', ' . $rawAuthor['first_name'];
                }

                $citations[] = new Citation(
                    $rawCitation['id'],
                    $rawCitation['title'],
                    $rawCitation['journal'],
                    $rawCitation['year'],
                    $authors
                );
            } catch (Exception $e) {
                continue;
            }
        }

        return $citations;
    }
}
