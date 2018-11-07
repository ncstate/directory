<?php

use NCState\Publications\Citation;

class CitationTest extends PHPUnit_Framework_TestCase
{

    /** @test */
    public function it_should_be()
    {
        $citation = new Citation(1, 'Winnie-the-Pooh', 'Ashdown Forest Review', 2015);

        $this->assertInstanceOf(Citation::class, $citation);
        $this->assertEquals(1, $citation->id);
        $this->assertEquals('Winnie-the-Pooh', $citation->title);
        $this->assertEquals('Ashdown Forest Review', $citation->journal);
        $this->assertEquals(2015, $citation->year);
    }

    /** @test */
    public function it_accepts_author_names_as_strings()
    {
        $citation = new Citation(1, 'Winnie-the-Pooh', 'Ashdown Forest Review', 2015, [
            'Winnie', 'Tigger', 'Eeyore'
        ]);

        $this->assertEquals('Winnie, Tigger, Eeyore', $citation->getAuthorsList());
    }

    /** @test */
    public function it_generates_uri_to_library_citation()
    {
        $citation = new Citation(1, 'Winnie-the-Pooh', 'Ashdown Forest Review', 2015);
        $expectedLink = 'https://ci.lib.ncsu.edu/citation/1';

        $this->assertEquals($expectedLink, $citation->getLinkToLibraryCitation());
    }

}
