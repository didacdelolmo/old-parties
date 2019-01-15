<?php

declare(strict_types=1);

namespace Parties\command\presets;


use Parties\command\PartyCommand;
use Parties\command\PartyCommandMap;
use Parties\Parties;
use Parties\session\Session;

class InviteCommand extends PartyCommand {

    /** @var Parties */
    private $plugin;

    public function __construct(PartyCommandMap $map) {
        $this->plugin = $map->getPlugin();
        parent::__construct(["invite"], "/party invite (player)", "Invites the player to your party, creating one if you haven't created it");
    }

    public function onCommand(Session $session, array $args): void {

    }

}