<?php

declare(strict_types=1);

namespace Diduhless\Parties\command\presets;


use Diduhless\Parties\command\PartyCommand;
use Diduhless\Parties\session\Session;
use pocketmine\utils\TextFormat;

class InviteCommand extends PartyCommand {

    /**
     * InviteCommand constructor.
     */
    public function __construct() {
        parent::__construct(["invite"], "/party invite (player)", "Invites the player to your party, creating one if you haven't done it");
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
        $plugin = $session->getManager()->getPlugin();
        $player = $plugin->getServer()->getPlayer($args[0]);
        if($player == null) {
            $session->sendValidPlayerMessage();
            return;
        }
        $playerSession = $session->getManager()->getSession($player);
        if($playerSession->getUsername() == $session->getUsername()) {
            $session->sendMessage(TextFormat::RED . "You can't invite yourself!");
            return;
        }
        if($playerSession->hasParty()) {
            $session->sendMessage(TextFormat::RED . "This player is already in a party!");
            return;
        }
        if(!$session->hasParty()) {
            $session->sendMessage(TextFormat::RED . "You have to create a party to invite players!");
            return;
        }
        if(!$session->isLeader()) {
            $session->sendLeaderMessage();
            return;
        }
        if($session->getParty()->isFull()) {
            $session->sendFullPartyMessage();
            return;
        }
        $username = $session->getUsername();
        $playerSession->addInvitationFrom($session);
        $playerSession->sendMessage(
            TextFormat::WHITE . $username . TextFormat::AQUA . " has invited you to his party! Use "
            . TextFormat::WHITE .  "/party accept $username" . TextFormat::AQUA . " to accept the invitation"
        );
        $session->sendMessage(TextFormat::GREEN . "You have invited " . TextFormat::WHITE
            . $playerSession->getUsername() . TextFormat::GREEN . " to the party!"
        );
    }

}