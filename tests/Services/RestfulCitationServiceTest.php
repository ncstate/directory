<?php

namespace NCState\Services;

use NCState\Publications\RestfulCitationService;
use PHPUnit_Framework_TestCase;

class RestfulCitationServiceTest extends PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_should_pull_author_by_spr_id()
    {
        $service = new RestfulCitationService();

        // Grab citations for User "gould" with SPR ID 858.
        // See: https://ci.lib.ncsu.edu/api/v1/authors/858
        $citations = $service->getCitationsByAuthorId(858);

        // This researcher happens to have more than 1 citation.
        // Verify this by making sure we get back more than one.
        $this->assertTrue(count($citations) > 1);

        // Make sure that Gould shows up in all citations pulled
        // and that links to citation are generated as expected.
        foreach ($citations as $citation) {
            $this->assertContains('Gould', $citation->fullcitation);
            $this->assertEquals('https://ci.lib.ncsu.edu/citation/' . $citation->id, $citation->getLinkToLibraryCitation());
        }
    }

    /** @test */
    public function it_should_limit_to_ten_citations_by_default()
    {
        $service = new RestfulCitationService();

        // Grab citations for User "gould" with SPR ID 858.
        // See: https://ci.lib.ncsu.edu/api/v1/authors/858
        $citations = $service->getCitationsByAuthorId(858);

        // This researcher happens to have more than 10 citations.
        // Verify this by making sure we get back less than or equal to 10.
        $this->assertTrue(count($citations) <= 10);
    }

    /** @test */
    public function it_should_get_more_than_ten_citiations()
    {
        $service = new RestfulCitationService();

        // Grab citations for User "gould" with SPR ID 858 and a limit option
        // See: https://ci.lib.ncsu.edu/api/v1/authors/858?limit=15
        $citations = $service->getCitationsByAuthorId(858, 15);

        // This researcher happens to have more than 15 citations.
        // Verify this by making sure we get back less than or equal to 15.
        $this->assertTrue(count($citations) <= 15);
    }
}
