<?php

declare(strict_types=1);

namespace Diduhless\Parties\party;


use Diduhless\Parties\session\Session;

class Party {

    /** @var PartyManager */
    private $manager;

    /** @var string */
    private $identifier;

    /** @var Session */
    private $leader;

    /** @var Session[] */
    private $members = [];

    /** @var bool */
    private $locked = true;

    /**
     * Party constructor.
     * @param PartyManager $manager
     * @param string $identifier
     * @param Session $leader
     */
    public function __construct(PartyManager $manager, string $identifier, Session $leader) {
        $this->manager = $manager;
        $this->identifier = $identifier;
        $this->leader = $leader;
    }

    /**
     * @return PartyManager
     */
    public function getManager(): PartyManager {
        return $this->manager;
    }

    /**
     * @return string
     */
    public function getIdentifier(): string {
        return $this->identifier;
    }

    /**
     * @return Session
     */
    public function getLeader(): Session {
        return $this->leader;
    }

    /**
     * @return Session[]
     */
    public function getMembers(): array {
        return $this->members;
    }

    /**
     * @return bool
     */
    public function getLocked(): bool {
        return $this->locked;
    }

    /**
     * @return bool
     */
    public function isLocked(): bool {
        return $this->locked == true;
    }

    /**
     * @param string $identifier
     */
    public function setIdentifier(string $identifier): void {
        $this->identifier = $identifier;
    }

    /**
     * @param Session $leader
     */
    public function setLeader(Session $leader): void {
        $this->leader = $leader;
    }

    /**
     * @param Session[] $members
     */
    public function setMembers(array $members): void {
        $this->members = $members;
    }

    /**
     * @param bool $bool
     */
    public function setLocked(bool $bool = true): void {
        $this->locked = $bool;
    }

    /**
     * @param Session $member
     */
    public function addMember(Session $member): void {
        $this->members[] = $member;
        $member->setParty($this);
    }

    /**
     * @param Session $member
     */
    public function removeMember(Session $member): void {
        unset($this->members[array_search($member, $this->members)]);
        $member->setParty(null);
    }

    /**
     * @param string $message
     */
    public function sendMessage(string $message): void {
        foreach($this->members as $member) {
            $member->sendMessage($message);
        }
    }

}