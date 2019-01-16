<?php

declare(strict_types=1);

namespace Parties\command\presets;


use Parties\command\PartyCommand;
use Parties\command\PartyCommandMap;
use Parties\Parties;
use Parties\session\Session;
use pocketmine\utils\TextFormat;

class InviteCommand extends PartyCommand {

    /** @var Parties */
    private $plugin;

    /**
     * InviteCommand constructor.
     * @param PartyCommandMap $map
     */
    public function __construct(PartyCommandMap $map) {
        $this->plugin = $map->getPlugin();
        parent::__construct(["invite"], "/party invite (player)", "Invites the player to your party, creating one if you haven't created it");
    }

    /**
     * @param Session $session
     * @param array $args
     */
    public function onCommand(Session $session, array $args): void {
        if(isset($args[0])) {
            $session->getOwner()->sendMessage($this->getUsageMessageId());
            return;
        }
        $player = $this->plugin->getServer()->getPlayer($args[0]);
        if($player == null) {
            $session->sendMessage(TextFormat::RED . "You must provide a valid player!");
            return;
        }
        $playerSession = $session->getManager()->getSession($player);
        if(!$session->hasParty()) {
            $this->plugin->getPartyManager()->createParty($session);
        }
        if(!$session->isLeader()) {
            $session->sendMessage(TextFormat::RED . "You must be leader of the party to do that!");
            return;
        }
        if($playerSession->hasParty()) {
            $session->sendMessage(TextFormat::RED . "This player is already in a party!");
            return;
        }
        $username = $session->getUsername();
        $playerSession->addInvitationFrom($session);
        $playerSession->sendMessage(TextFormat::AQUA . $username . " has invited you to a party! Use " . TextFormat::WHITE .  "/party accept $username" . TextFormat::AQUA . " to accept the invitation");
        $session->sendMessage(TextFormat::GREEN . "You have invited {$playerSession->getUsername()} to the party!");
    }

}