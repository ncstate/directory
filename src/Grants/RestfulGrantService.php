<?php

namespace NCState\Grants;

use DateTime;
use Exception;
use GuzzleHttp\Client;
use NCState\Amount;

class RestfulGrantService implements GrantService
{
    /**
     * @var Client
     */
    private $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'http://www.webtools.ncsu.edu/grants/api/',
            'verify' => false
        ]);
    }

    /**
     * Returns an array of Grants matching an author by their
     * Unity identifier.
     *
     * @param string $unityIdentifier
     *
     * @return Grant[]
     */
    public function getGrantsByUnityId($unityIdentifier)
    {
        $grants = [];

        $response = $this->client->get("investigators/{$unityIdentifier}/grants");

        if ($response->getStatusCode() !== 200) {
            return [];
        }

        $data = json_decode($response->getBody(), true);

        if (! isset($data['items']) or empty($data['items'])) {
            return [];
        }

        $rawGrants = $data['items'];

        foreach ($rawGrants as $rawGrant) {
            try {
                $grants[] = new Grant(
                    $rawGrant['id'],
                    $rawGrant['title'],
                    $rawGrant['abstract'],
                    DateTime::createFromFormat('Y-m-d', $rawGrant['award']['start_date']),
                    DateTime::createFromFormat('Y-m-d', $rawGrant['award']['end_date']),
                    Amount::fromWholeDollars($rawGrant['award']['amount'])
                );
            } catch (Exception $e) {
                continue;
            }
        }

        return $grants;
    }
}
