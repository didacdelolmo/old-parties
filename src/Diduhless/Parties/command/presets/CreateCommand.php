<?php

declare(strict_types=1);

namespace Diduhless\Parties\command\presets;


use Diduhless\Parties\command\PartyCommand;
use Diduhless\Parties\session\Session;
use pocketmine\utils\TextFormat;

class CreateCommand extends PartyCommand {

    /**
     * CreateCommand constructor.
     */
    public function __construct() {
        parent::__construct(["create"], "/party create", "Creates a party");
    }

    /**
     * @param Session $session
     * @param array $args
     * @throws \ReflectionException
     */
    public function onCommand(Session $session, array $args): void {
        if($session->hasParty()) {
            $session->sendAlreadyPartyMessage();
            return;
        }
        if($session->getManager()->getPlugin()->getPartyManager()->createParty($session)) {
            $session->sendMessage(TextFormat::GREEN . "You have created a party!");
        }
    }

}