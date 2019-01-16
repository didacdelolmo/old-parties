<?php

declare(strict_types=1);

namespace Parties\command\presets;


use Parties\command\PartyCommand;
use Parties\session\Session;
use pocketmine\utils\TextFormat;

class CreateCommand extends PartyCommand {

    /**
     * CreateCommand constructor.
     */
    public function __construct() {
        parent::__construct(["create"], "Usage: /party create", "Creates a party");
    }

    /**
     * @param Session $session
     * @param array $args
     */
    public function onCommand(Session $session, array $args): void {
        if($session->hasParty()) {
            $session->sendMessage(TextFormat::RED . "You already have a party!");
            return;
        }
        $session->getManager()->getPlugin()->getPartyManager()->createParty($session);
    }

}