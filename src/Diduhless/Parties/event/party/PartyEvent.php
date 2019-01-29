<?php

declare(strict_types=1);

namespace Diduhless\Parties\event\party;


use Diduhless\Parties\event\PartiesEvent;
use Diduhless\Parties\party\Party;
use pocketmine\event\Cancellable;

abstract class PartyEvent extends PartiesEvent implements Cancellable {

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