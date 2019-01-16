<?php

declare(strict_types=1);

namespace Parties\command\presets;


use Parties\command\PartyCommand;
use Parties\session\Session;
use pocketmine\utils\TextFormat;

class AcceptCommand extends PartyCommand {

    /**
     * AcceptCommand constructor.
     */
    public function __construct() {
        parent::__construct(["accept"], "Usage: /party accept (player)", "Accepts a party invite from the player");
    }

    /**
     * @param Session $session
     * @param array $args
     */
    public function onCommand(Session $session, array $args): void {
        $player = null;
        if(!isset($args[0])) {
            if($session->hasLastInvitation()) {
                $player = $session->getLastInvitation();
            }
        } else {
            $player = $session->getManager()->getPlugin()->getServer()->getPlayer($args[0]);
        }
        if($player == null) {
            $session->sendValidPlayerMessage();
            return;
        }
        $playerSession = $session->getManager()->getSession($player);
        // If a party has been disbanded it will show this message
        if(!$session->hasInvitationFrom($playerSession)) {
            $session->sendMessage(TextFormat::RED . "You don't have an invitation from this player!");
            return;
        }
        $party = $playerSession->getParty();
        $party->addMember($session);
        $party->sendMessage(TextFormat::GREEN . $session->getUsername() . " has joined the party!");
        $session->sendMessage(TextFormat::GREEN . "You have joined {$playerSession->getUsername()}'s party!'");
        $session->removeInvitationFrom($playerSession);
    }

}