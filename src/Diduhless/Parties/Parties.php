<?php

declare(strict_types=1);

namespace Diduhless\Parties;


use Diduhless\Parties\command\PartyCommandMap;
use Diduhless\Parties\party\PartyManager;
use Diduhless\Parties\session\SessionManager;
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
        if(!is_dir($folder = $this->getDataFolder())) {
            mkdir($folder);
        }
        $this->saveDefaultConfig();
    }

    public function onEnable() {
        $this->commandMap = new PartyCommandMap($this);
        $this->partyManager = new PartyManager($this);
        $this->sessionManager = new SessionManager($this);
        $this->getServer()->getPluginManager()->registerEvents(new PartiesListener($this), $this);
        if(!$this->getServer()->isLevelLoaded("hub")) {
            $this->getServer()->loadLevel("hub");
        }
        $this->getLogger()->info(TextFormat::GOLD . "Parties has been enabled!");
        $this->getLogger()->info(TextFormat::BOLD . TextFormat::DARK_GRAY . "» " . TextFormat::RESET . TextFormat::GOLD . "Remember to check out for new versions at https://github.com/Diduhless/Parties");
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

}