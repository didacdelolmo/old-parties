<?php

declare(strict_types=1);

namespace Parties\command;


use Parties\Parties;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\Player;
use pocketmine\plugin\Plugin;
use pocketmine\utils\TextFormat;

class PartyCommandMap extends Command implements PluginIdentifiableCommand {

    /** @var Parties */
    private $plugin;

    /** @var PartyCommand[] */
    private $commands = [];

    public function __construct(Parties $plugin) {
        $this->plugin = $plugin;
        // register commands
        parent::__construct("party", "Manage party commands", "Usage: /party", ["p"]);
    }

    /**
     * @return Parties|Plugin
     */
    public function getPlugin(): Plugin {
        return $this->plugin;
    }

    /**
     * @return PartyCommand[]
     */
    public function getCommands(): array {
        return $this->commands;
    }

    /**
     * @param string $alias
     * @return null|PartyCommand
     */
    public function getCommand(string $alias): ?PartyCommand {
        foreach($this->commands as $key => $command) {
            if(in_array(strtolower($alias), $command->getAliases()) or $alias == $command->getName()) {
                return $command;
            }
        }
        return null;
    }

    /**
     * @param PartyCommand $command
     */
    public function registerCommand(PartyCommand $command) {
        $this->commands[] = $command;
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        if(!$sender instanceof Player) {
            $sender->sendMessage("Please, run this command in game");
            return;
        }
        $session = $this->plugin->getSessionManager()->getSession($sender);
        if(isset($args[0]) and $this->getCommand($args[0]) != null) {
            $this->getCommand(array_shift($args))->onCommand($session, $args);
        } else {
            foreach($this->commands as $key => $command) {
                $sender->sendMessage(TextFormat::GREEN . $command->getUsageMessageId() . ": " . $command->getDescriptionMessageId());
            }
        }
    }

}