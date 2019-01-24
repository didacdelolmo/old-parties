<?php

declare(strict_types=1);

namespace Parties\event\party;


use Parties\event\PartiesEvent;
use Parties\party\Party;

abstract class PartyEvent extends PartiesEvent {

    /** @var Party */
    private $party;

    /**
     * PartyEvent constructor.
     * @param Party $party
     */
    public function __construct(Party $party) {
        $this->party = $party;
    }

    /**
     * @return Party
     */
    public function getParty(): Party {
        return $this->party;
    }

}