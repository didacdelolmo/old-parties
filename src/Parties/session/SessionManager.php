<?php

declare(strict_types=1);

namespace Parties\session;


use Parties\Parties;
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
     * @return Session
     */
    public function getSession(Player $player): Session {
        return $this->sessions[$player->getName()];
    }

    /**
     * @param Player $player
     */
    public function openSession(Player $player): void {
        if(!isset($this->sessions[$username = $player->getName()])) {
            $this->sessions[$username] = new Session($this, $player);
        }
    }

    /**
     * @param Player $player
     */
    public function closeSession(Player $player): void {
        $session = $this->sessions[$player->getName()];
        if(isset($session)) {
            $session->clearInvitations();
            unset($session);
        }
    }

}