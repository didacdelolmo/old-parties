<?php

declare(strict_types=1);

namespace Diduhless\Parties\session;


use Diduhless\Parties\party\Party;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Session {
    
    /** @var SessionManager */
    private $manager;

    /** @var Player */
    private $owner;

    /** @var null|Party */
    private $party = null;

    /** @var Session[] */
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
     * @return Session[]
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
     * @param null|Party $party
     */
    public function setParty(?Party $party): void {
        $this->party = $party;
    }

    /**
     * @param null|Session $session
     */
    public function setLastInvitation(?Session $session): void {
        $this->lastInvitation = $session;
    }

    /**
     * @return bool
     */
    public function isLeader(): bool {
        return $this->party->getLeader()->getUsername() == $this->getUsername();
    }

    /**
     * @return bool
     */
    public function hasParty(): bool {
        return $this->party != null;
    }

    /**
     * @param Session $session
     * @return bool
     */
    public function hasInvitationFrom(Session $session): bool {
        return in_array($session, $this->invitations);
    }

    /**
     * @return bool
     */
    public function hasLastInvitation(): bool {
        return $this->lastInvitation != null;
    }

    /**
     * @param Session $session
     */
    public function addInvitationFrom(Session $session): void {
        if(!in_array($session, $this->invitations)) {
            $this->invitations[] = $session;
            $this->setLastInvitation($session);
        }
    }

    /**
     * @param Session $session
     */
    public function removeInvitationFrom(Session $session): void {
        if(in_array($session, $this->invitations)) {
            unset($this->invitations[array_search($session, $this->invitations)]);
        }
    }

    public function clearInvitations(): void {
        foreach($this->manager->getSessions() as $session) {
            if($session->hasInvitationFrom($this)) {
                $session->removeInvitationFrom($this);
            }
            if($session->hasLastInvitation() and $session->getLastInvitation()->getUsername() == $this->getUsername()) {
                $session->setLastInvitation(null);
            }
        }
    }

    /**
     * @param string $message
     */
    public function sendMessage(string $message): void {
        $this->owner->sendMessage($message);
    }

    public function sendValidPlayerMessage(): void {
        $this->sendMessage(TextFormat::RED . "You must provide a valid player!");
    }

    public function sendAlreadyPartyMessage(): void {
        $this->sendMessage(TextFormat::RED . "You already are in a party!");
    }

    public function sendMissingPartyMessage(): void {
        $this->sendMessage(TextFormat::RED . "You must be in a party to do that!");
    }

    public function sendLeaderMessage(): void {
        $this->sendMessage(TextFormat::RED . "You must be leader of the party to do that!");
    }

    public function sendMissingPlayerMessage(): void {
        $this->sendMessage(TextFormat::RED . "This player is not in your party!");
    }

    public function sendFullPartyMessage(): void {
        $this->sendMessage(TextFormat::RED . "The party you're attempting to join is full!");
    }

}