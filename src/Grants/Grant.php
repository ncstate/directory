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

    /**
     * @var string[]
     */
    protected $agencies = [];

    public function __construct($id, $title, $abstract, DateTime $startDate, DateTime $endDate, Amount $awardAmount, array $agencies = [])
    {
        $this->id = $id;
        $this->title = $title;
        $this->abstract = $abstract;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->awardAmount = $awardAmount;

        if (!is_null($agencies)) {
            foreach ($agencies as $agency) {
                $this->addAgency($agency);
            }
        }
    }

    protected function addAgency($agency)
    {
        if (!is_string($agency) or empty($agency)) {
            return;
        }

        $this->agencies[] = $agency;
    }

    public function getAgencyList()
    {
        return implode(', ', $this->agencies);
    }
}
