<?php

declare(strict_types=1);

namespace Diduhless\Parties\session;


use Diduhless\Parties\event\session\SessionCloseEvent;
use Diduhless\Parties\event\session\SessionOpenEvent;
use Diduhless\Parties\Parties;
use pocketmine\Player;

class SessionManager {

    /** @var Parties */
    private $plugin;

    /** @var Session[] */
    private $sessions = [];

    /**
     * SessionManager constructor.
     * @param Parties $plugin
     */
    public function __construct(Parties $plugin) {
        $this->plugin = $plugin;
        $plugin->getServer()->getPluginManager()->registerEvents(new SessionListener($this), $plugin);
    }

    /**
     * @return Parties
     */
    public function getPlugin(): Parties {
        return $this->plugin;
    }

    /**
     * @return Session[]
     */
    public function getSessions(): array {
        return $this->sessions;
    }

    /**
     * @param Player $player
     * @return Session|null
     */
    public function getSession(Player $player): ?Session {
        return $this->sessions[$player->getName()] ?? null;
    }

    /**
     * @param Player $player
     * @throws \ReflectionException
     */
    public function openSession(Player $player): void {
        if(!isset($this->sessions[$username = $player->getName()])) {
            $session = new Session($this, $player);
            $this->sessions[$username] = $session;
            $event = new SessionOpenEvent($session);
            $event->call();
        }
    }

    /**
     * @param Player $player
     * @throws \ReflectionException
     */
    public function closeSession(Player $player): void {
        if(isset($this->sessions[$username = $player->getName()])) {
            $session = $this->sessions[$username];
            $session->clearInvitations();
            $event = new SessionCloseEvent($session);
            $event->call();
            unset($session);
        }
    }

}