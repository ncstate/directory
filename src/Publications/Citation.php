<?php

namespace NCState\Publications;

use Exception;

class Citation
{
    public $id;
    public $title;
    public $journal;
    public $year;
    public $fullcitation;

    protected $authors;

    public function __construct($id, $title, $journal, $year, array $authors = null, $fullcitation = '')
    {
        foreach(func_get_args() as $arg){
            if (empty($arg)) {
                throw new Exception("All properties of a citation are required.");
            }
        }

        if ( ! is_null($authors)) {
            foreach ($authors as $author) {
                $this->addAuthor($author);
            }
        }

        $this->id = $id;
        $this->title = $title;
        $this->journal = $journal;
        $this->year = $year;
        $this->fullcitation = $fullcitation;
    }

    /**
     * @param string $author
     */
    protected function addAuthor($author)
    {
        if (!is_string($author) or empty($author)) {
            return;
        }

        $this->authors[] = $author;
    }

    public function getAuthorsList()
    {
        return implode(', ', $this->authors);
    }

    public function getLinkToLibraryCitation()
    {
        return sprintf('https://ci.lib.ncsu.edu/citation/%s', $this->id);
    }
}
