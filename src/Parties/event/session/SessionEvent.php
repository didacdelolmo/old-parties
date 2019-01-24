<?php

declare(strict_types=1);

namespace Parties\event\session;


use Parties\event\PartiesEvent;
use Parties\session\Session;

abstract class SessionEvent extends PartiesEvent {

    /** @var Session */
    private $session;

    /**
     * SessionEvent constructor.
     * @param Session $session
     */
    public function __construct(Session $session) {
        $this->session = $session;
    }

    /**
     * @return Session
     */
    public function getSession(): Session {
        return $this->session;
    }

}