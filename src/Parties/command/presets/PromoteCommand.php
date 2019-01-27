<?php

declare(strict_types=1);

namespace Parties\command\presets;


use Parties\command\PartyCommand;
use Parties\session\Session;
use pocketmine\utils\TextFormat;

class PromoteCommand extends PartyCommand {

    /**
     * PromoteCommand constructor.
     */
    public function __construct() {
        parent::__construct(["promote"], "/party promote (player)", "Makes the player a party leader");
    }

    /**
     * @param Session $session
     * @param array $args
     * @throws \ReflectionException
     */
    public function onCommand(Session $session, array $args): void {
        if(!isset($args[0])) {
            $session->sendMessage("Usage: " .$this->getUsageMessageId());
            return;
        }
        if(!$session->hasParty()) {
            $session->sendMissingPartyMessage();
            return;
        }
        $player = $session->getManager()->getPlugin()->getServer()->getPlayer($args[0]);
        if($player == null) {
            $session->sendValidPlayerMessage();
            return;
        }
        $playerSession = $session->getManager()->getSession($player);
        $party = $session->getParty();
        if($playerSession->getUsername() == $session->getUsername()) {
            $session->sendMessage(TextFormat::RED . "You can't promote yourself!");
            return;
        }
        if(!$session->isLeader()) {
            $session->sendLeaderMessage();
            return;
        }
        if($playerSession->getParty()->getIdentifier() != $session->getUsername()) {
            $session->sendMissingPlayerMessage();
            return;
        }
        $username = $playerSession->getUsername();
        if($party->getManager()->renameParty($playerSession)) {
            $session->sendMessage(TextFormat::AQUA . "You have promoted $username to party leader!");
            $party->sendMessage(TextFormat::GREEN . "$username is now the party leader!");
        }
    }

}