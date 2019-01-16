<?php

declare(strict_types=1);

namespace Parties\command\presets;


use Parties\command\PartyCommand;
use Parties\session\Session;
use pocketmine\utils\TextFormat;

class JoinCommand extends PartyCommand {

    /**
     * JoinCommand constructor.
     */
    public function __construct() {
        parent::__construct(["join"], "/party join (player)", "Joins the player party if it's unlocked");
    }

    /**
     * @param Session $session
     * @param array $args
     */
    public function onCommand(Session $session, array $args): void {
        if(!isset($args[0])) {
            $session->sendMessage("Usage: " .$this->getUsageMessageId());
            return;
        }
        $player = $session->getManager()->getPlugin()->getServer()->getPlayer($args[0]);
        if($player == null) {
            $session->sendValidPlayerMessage();
            return;
        }
        $playerSession = $session->getManager()->getSession($player);
        if(!$playerSession->hasParty()) {
            $session->sendMessage(TextFormat::RED . "This player doesn't have a party!");
            return;
        }
        $party = $playerSession->getParty();
        if($party->isLocked()) {
            $session->sendMessage(TextFormat::RED . "This party is locked!");
            return;
        }
        if($session->hasParty()) {
            $session->sendAlreadyPartyMessage();
            return;
        }
        $party->addMember($session);
        $party->sendMessage(TextFormat::GREEN . $session->getUsername() . " has joined the party!");
        $session->sendMessage(TextFormat::GREEN . "You have joined {$playerSession->getUsername()}'s party!");
    }

}