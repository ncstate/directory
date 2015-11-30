<?php

namespace NCState\Grants;

use DateTime;
use NCState\Amount;

class Grant
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $abstract;

    /**
     * @var DateTime
     */
    public $startDate;

    /**
     * @var DateTime
     */
    public $endDate;

    /**
     * @var Amount
     */
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
