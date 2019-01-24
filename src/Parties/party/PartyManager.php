<?php

declare(strict_types=1);

namespace Parties\party;


use Parties\event\party\PartyCreateEvent;
use Parties\event\party\PartyDisbandEvent;
use Parties\event\party\PartyPromoteEvent;
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
            $this->getPlugin()->getServer()->getPluginManager()->callEvent(new PartyPromoteEvent($party));
        }
    }

    /**
     * @param Session $session
     */
    public function createParty(Session $session): void {
        if(!isset($this->parties[$identifier = $session->getUsername()])) {
            $party = new Party($this, $identifier, $session);
            $session->clearInvitations();
            $this->parties[$identifier] = $party;
            $this->getPlugin()->getServer()->getPluginManager()->callEvent(new PartyCreateEvent($party));
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
            $this->getPlugin()->getServer()->getPluginManager()->callEvent(new PartyDisbandEvent($this->parties[$identifier]));
            unset($this->parties[$identifier]);
        }
    }

}