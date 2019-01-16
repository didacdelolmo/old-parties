<?php

declare(strict_types=1);

namespace Parties;


use Parties\command\PartyCommandMap;
use Parties\party\PartyManager;
use Parties\session\Session;
use Parties\session\SessionManager;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;

class Parties extends PluginBase {

    /** @var Parties */
    private static $instance;

    /** @var PartyCommandMap */
    private $commandMap;

    /** @var PartyManager */
    private $partyManager;

    /** @var SessionManager */
    private $sessionManager;

    public function onLoad() {
        self::$instance = $this;
    }

    public function onEnable() {
        $this->commandMap = new PartyCommandMap($this);
        $this->partyManager = new PartyManager($this);
        $this->sessionManager = new SessionManager($this);
        $this->getServer()->getPluginManager()->registerEvents(new PartiesListener($this), $this);
        $this->getLogger()->info(TextFormat::GOLD . "Parties has been enabled!");
        $this->getLogger()->info(TextFormat::GOLD . "Remember to check out for new versions at https://github.com/Diduhless/Parties");
    }
    
    public function onDisable() {
        $this->getLogger()->info(TextFormat::GOLD . "Parties has been disabled!");
    }

    /**
     * @return Parties
     */
    public static function getInstance() {
        return self::$instance;
    }

    /**
     * @return PartyCommandMap
     */
    public function getCommandMap(): PartyCommandMap {
        return $this->commandMap;
    }

    /**
     * @return PartyManager
     */
    public function getPartyManager(): PartyManager {
        return $this->partyManager;
    }

    /**
     * @return SessionManager
     */
    public function getSessionManager(): SessionManager {
        return $this->sessionManager;
    }

    /**
     * @param Player $player
     * @return null|Session
     */
    public function getSession(Player $player): ?Session {
        return $this->sessionManager->getSession($player);
    }

}