<?php

declare(strict_types=1);

namespace Parties\party;


use Parties\Parties;
use Parties\session\Session;

class PartyManager {

    /** @var Parties */
    private $plugin;

    /** @var Party[] */
    private $parties = [];

    /**
     * PartyManager constructor.
     * @param Parties $plugin
     */
    public function __construct(Parties $plugin) {
        $this->plugin = $plugin;
    }

    /**
     * @return Parties
     */
    public function getPlugin(): Parties {
        return $this->plugin;
    }

    /**
     * @return Party[]
     */
    public function getParties(): array {
        return $this->parties;
    }

    /**
     * @param Session $leader
     */
    public function createParty(Session $leader): void {
        if(!isset($this->parties[$identifier = $leader->getUsername()])) {
            $leader->clearInvitations();
            $this->parties[$identifier] = new Party($this, $identifier, $leader);
        }
    }

    /**
     * @param string $identifier
     */
    public function deleteParty(string $identifier): void {
        $party = $this->parties[$identifier];
        if(isset($party)) {
            foreach($party->getMembers() as $member) {
                $member->setParty(null);
            }
            unset($party);
        }
    }

}