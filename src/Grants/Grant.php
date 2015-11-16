<?php

namespace NCState\Grants;

use DateTime;
use NCState\Amount;

class Grant
{
    public $id;
    public $title;
    public $abstract;
    public $startDate;
    public $endDate;
    public $awardAmount;

    public function __construct($id, $title, $abstract, DateTime $startDate, DateTime $endDate, Amount $awardAmount)
    {
        $this->id = $id;
        $this->title = $title;
        $this->abstract = $abstract;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->awardAmount = $awardAmount;
    }
}
