<?php

declare(strict_types=1);

namespace Diduhless\Parties;


use Diduhless\Parties\session\Session;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityLevelChangeEvent;
use pocketmine\event\Listener;
use pocketmine\Player;

class PartiesListener implements Listener {

    /** @var Parties */
    private $plugin;

    /**
     * PartiesListener constructor.
     * @param Parties $plugin
     */
    public function __construct(Parties $plugin) {
        $this->plugin = $plugin;
    }

    /**
     * @param EntityLevelChangeEvent $event
     */
    public function onLevelChange(EntityLevelChangeEvent $event): void {
        $player = $event->getEntity();
        if(!$this->plugin->getConfig()->get("teleport_members_to_leader") or !$player instanceof Player) {
            return;
        }
        $session = $this->plugin->getSessionManager()->getSession($player);
        if(!$session->hasParty() or !$session->isLeader()) {
            return;
        }
        /** @var Session $member */
        foreach($session->getParty()->getMembers() as $member) {
            if(!$member->isLeader()) {
                $member->getOwner()->teleport($session->getOwner()->asVector3());
            }
        }
    }

    /**
     * @param EntityDamageEvent $event
     */
    public function onDamage(EntityDamageEvent $event): void {
        $entity = $event->getEntity();
        if($this->plugin->getConfig()->get("friendly_fire") or !$entity instanceof Player) {
            return;
        }
        $entitySession = $this->plugin->getSessionManager()->getSession($entity);
        if(!$entitySession->hasParty()) {
            return;
        }
        $cause = $entity->getLastDamageCause();
        if(!$cause instanceof EntityDamageByEntityEvent or !($damager = $cause->getDamager()) instanceof Player) {
            return;
        }
        /** @var Player $damager */
        $damagerSession = $this->plugin->getSessionManager()->getSession($damager);
        if(!$damagerSession->hasParty() or
            $entitySession->getParty()->getIdentifier() !== $damagerSession->getParty()->getIdentifier()) {
            return;
        }
        $event->setCancelled();
    }

}