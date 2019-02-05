<?php

declare(strict_types=1);

namespace Diduhless\Parties\command\presets;


use Diduhless\Parties\command\PartyCommand;
use Diduhless\Parties\session\Session;
use pocketmine\utils\TextFormat;

class ChatCommand extends PartyCommand {

    /**
     * ChatCommand constructor.
     */
    public function __construct() {
        parent::__construct(["chat", "c"], "/party chat (message)", "Sends a message to the party members");
    }

    /**
     * @param Session $session
     * @param array $args
     */
    public function onCommand(Session $session, array $args): void {
        if(!isset($args[0])) {
            $session->sendMessage("Usage: " . $this->getUsageMessageId());
            return;
        }
        if(!$session->hasParty()) {
            $session->sendMissingPartyMessage();
            return;
        }
        $session->getParty()->sendMessage(
            TextFormat::LIGHT_PURPLE . "@Party " . TextFormat::GREEN .
            $session->getUsername() . ": " . implode(" ", $args)
        );
    }

}