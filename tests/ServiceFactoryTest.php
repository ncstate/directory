<?php

use NCState\ServiceFactory;
use NCState\Publications\CitationService;
use NCState\Grants\GrantService;

class ServiceFactoryTest extends PHPUnit_Framework_TestCase
{

    /** @test */
    public function it_creates_an_implementation_of_citation_service()
    {
        $service = ServiceFactory::makeCitationService();
        $this->assertInstanceOf(CitationService::class, $service);
    }

    /** @test */
    public function it_creates_an_implementation_of_grant_service()
    {
        $service = ServiceFactory::makeGrantService();
        $this->assertInstanceOf(GrantService::class, $service);
    }

}
