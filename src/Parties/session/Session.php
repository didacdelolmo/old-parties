<?php

declare(strict_types=1);

namespace Parties\session;


use Parties\party\Party;
use pocketmine\Player;

class Session {
    
    /** @var SessionManager */
    private $manager;

    /** @var Player */
    private $owner;

    /** @var null|Party */
    private $party = null;

    /** @var string[] */
    private $invitations = [];

    /** @var null|Session */
    private $lastInvitation = null;

    /**
     * Session constructor.
     * @param SessionManager $manager
     * @param Player $owner
     */
    public function __construct(SessionManager $manager, Player $owner) {
        $this->manager = $manager;
        $this->owner = $owner;
    }

    /**
     * @return SessionManager
     */
    public function getManager(): SessionManager {
        return $this->manager;
    }

    /**
     * @return Player
     */
    public function getOwner(): Player {
        return $this->owner;
    }

    /**
     * @return string
     */
    public function getUsername(): string {
        return $this->owner->getName();
    }

    /**
     * @return null|Party
     */
    public function getParty(): ?Party {
        return $this->party;
    }

    /**
     * @return string[]
     */
    public function getInvitations(): array {
        return $this->invitations;
    }

    /**
     * @return null|Session
     */
    public function getLastInvitation(): ?Session {
        return $this->lastInvitation;
    }

    /**
     * @param Party $party
     */
    public function setParty(Party $party): void {
        $this->party = $party;
    }

    /**
     * @return bool
     */
    public function hasParty(): bool {
        return $this->party != null;
    }

    /**
     * @return bool
     */
    public function isLeader(): bool {
        return $this->hasParty() and $this->party->getLeader();
    }

    /**
     * @param Session $session
     */
    public function addInvitationFrom(Session $session): void {
        $this->invitations[] = $session->getUsername();
        $this->lastInvitation = $session;
    }

    /**
     * @param Session $session
     */
    public function removeInvitationFrom(Session $session): void {
        unset($this->invitations[array_search($session, $this->invitations)]);
    }

    /**
     * @param string $message
     */
    public function sendMessage(string $message): void {
        $this->owner->sendMessage($message);
    }

}