<?php

declare(strict_types=1);

namespace Parties\command\presets;


use Parties\command\PartyCommand;
use Parties\session\Session;
use pocketmine\utils\TextFormat;

class KickCommand extends PartyCommand {

    /**
     * KickCommand constructor.
     */
    public function __construct() {
        parent::__construct(["kick"], "Usage: /party kick (player)", "Kicks the player from your party");
    }

    /**
     * @param Session $session
     * @param array $args
     */
    public function onCommand(Session $session, array $args): void {
        if(!isset($args[0])) {
            $session->sendMessage($this->getUsageMessageId());
            return;
        }
        $player = $session->getManager()->getPlugin()->getServer()->getPlayer($args[0]);
        if($player == null) {
            $session->sendValidPlayerMessage();
            return;
        }
        $playerSession = $session->getManager()->getSession($player);
        if(!$session->hasParty()) {
            $session->sendMissingPartyMessage();
            return;
        }
        $party = $session->getParty();
        if(!$session->isLeader()) {
            $session->sendLeaderMessage();
            return;
        }
        if($playerSession->getParty()->getIdentifier() != $session->getUsername()) {
            $session->sendMissingPlayerMessage();
            return;
        }
        $username = $playerSession->getUsername();
        $party->removeMember($playerSession);
        $party->sendMessage(TextFormat::GREEN . $username . " has been kicked from the party!");
        $session->sendMessage(TextFormat::AQUA . "You have kicked $username from the party!");
    }

}