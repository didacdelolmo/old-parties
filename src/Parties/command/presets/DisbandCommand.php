<?php

declare(strict_types=1);

namespace Parties\command\presets;


use Parties\command\PartyCommand;
use Parties\session\Session;
use pocketmine\utils\TextFormat;

class DisbandCommand extends PartyCommand {

    /**
     * DisbandCommand constructor.
     */
    public function __construct() {
        parent::__construct(["disband"], "/party disband", "Disbands the party");
    }

    /**
     * @param Session $session
     * @param array $args
     * @throws \ReflectionException
     */
    public function onCommand(Session $session, array $args): void {
        if(!$session->hasParty()) {
            $session->sendMissingPartyMessage();
            return;
        }
        $party = $session->getParty();
        if(!$session->isLeader()) {
            $session->sendLeaderMessage();
            return;
        }
        if(!$session->getManager()->getPlugin()->getPartyManager()->deleteParty($session)) {
            $party->sendMessage(TextFormat::AQUA . "The party has been disbanded!");
        }
    }

}