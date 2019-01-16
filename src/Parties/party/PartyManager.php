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
     * @param Session $session
     */
    public function renameParty(Session $session): void {
        if(isset($this->parties[$identifier = $session->getParty()->getIdentifier()])) {
            $party = $this->parties[$identifier];
            $party->setIdentifier($username = $session->getUsername());
            unset($this->parties[$identifier]);
            $this->parties[$username] = $party;
        }
    }

    /**
     * @param Session $session
     */
    public function createParty(Session $session): void {
        if(!isset($this->parties[$identifier = $session->getUsername()])) {
            $session->clearInvitations();
            $this->parties[$identifier] = new Party($this, $identifier, $session);
        }
    }

    /**
     * @param Session $session
     */
    public function deleteParty(Session $session): void {
        if(isset($this->parties[$identifier = $session->getUsername()])) {
            foreach($this->parties[$identifier]->getMembers() as $member) {
                $member->setParty(null);
            }
            unset($this->parties[$identifier]);
        }
    }

}