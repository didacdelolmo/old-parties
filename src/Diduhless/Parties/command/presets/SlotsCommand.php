<?php

declare(strict_types=1);

namespace Diduhless\Parties\command\presets;


use Diduhless\Parties\command\PartyCommand;
use Diduhless\Parties\session\Session;
use pocketmine\utils\TextFormat;

class SlotsCommand extends PartyCommand {

    /**
     * SlotsCommand constructor.
     */
    public function __construct() {
        parent::__construct(["slots"], "/party slots (amount)", "Changes the slots limit of the party");
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
        if(!is_int($args[0])) {
            $session->sendMessage(TextFormat::RED . "You must specify a correct amount!");
            return;
        }
        if(!$session->hasParty()) {
            $session->sendMissingPartyMessage();
            return;
        }
        $party = $session->getParty();
        if(!$session->isLeader()) {
            $session->sendLeaderMessage();
            return;
        }
        $party->setSlots($args[0]);
        $party->sendMessage(
            TextFormat::WHITE . $session->getUsername() . TextFormat::GREEN .
            "has set the slots limit to ". TextFormat::WHITE . $args[0] . TextFormat::GREEN . "!"
        );
    }

}