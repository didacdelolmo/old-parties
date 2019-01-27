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
     * Use this function to transfer the party leader to a different member
     *
     * @param Session $session
     * @return bool
     * @throws \ReflectionException
     */
    public function renameParty(Session $session): bool {
        if(!isset($this->parties[$identifier = $session->getParty()->getIdentifier()])) {
            return false;
        }
        $event = new PartyPromoteEvent($party = $this->parties[$identifier]);
        $event->call();
        $cancelled = $event->isCancelled();
        if(!$cancelled) {
            $party->setIdentifier($username = $session->getUsername());
            $party->setLeader($session);
            unset($this->parties[$identifier]);
            $this->parties[$username] = $party;
        }
        return $cancelled;
    }

    /**
     * @param Session $session
     * @return bool
     * @throws \ReflectionException
     */
    public function createParty(Session $session): bool {
        if(isset($this->parties[$identifier = $session->getUsername()])) {
            return false;
        }
        $event = new PartyCreateEvent($party = new Party($this, $identifier, $session));
        $event->call();
        $cancelled = $event->isCancelled();
        if(!$cancelled) {
            $session->clearInvitations();
            $party->addMember($session);
            $this->parties[$identifier] = $party;
        }
        return $cancelled;
    }

    /**
     * @param Session $session
     * @return bool
     * @throws \ReflectionException
     */
    public function deleteParty(Session $session): bool {
        if(!isset($this->parties[$identifier = $session->getUsername()])) {
            return false;
        }
        $event = new PartyDisbandEvent($this->parties[$identifier]);
        $event->call();
        $cancelled = $event->isCancelled();
        if(!$cancelled) {
            foreach($this->parties[$identifier]->getMembers() as $member) {
                $member->setParty(null);
            }
            unset($this->parties[$identifier]);
        }
        return $cancelled;
    }

}