<?php

declare(strict_types=1);

namespace Diduhless\Parties;


use Diduhless\Parties\session\Session;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityLevelChangeEvent;
use pocketmine\event\entity\EntityTeleportEvent;
use pocketmine\event\Event;
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
        if($player instanceof Player) {
            $this->onLeaderTeleport($this->plugin->getSessionManager()->getSession($player), $event);
        }
    }

    /**
     * @param EntityTeleportEvent $event
     */
    public function onTeleport(EntityTeleportEvent $event): void {
        $player = $event->getEntity();
        if($player instanceof Player and ($session = $this->plugin->getSessionManager()->getSession($player)) != null) {
            $this->onLeaderTeleport($session, $event);
        }
    }

    /**
     * @param Session $session
     * @param Event $event
     */
    public function onLeaderTeleport(Session $session, Event $event): void {
        if(!$this->plugin->getConfig()->get("teleport_members_to_leader")) {
            return;
        }
        if(!$session->hasParty() or !$session->isLeader()) {
            return;
        }
        /** @var Session $member */
        foreach($session->getParty()->getMembers() as $member) {
            if(!$member->isLeader()) {
                $owner = $member->getOwner();
                if($event instanceof EntityLevelChangeEvent) {
                    $owner->teleport($event->getTarget()->getSafeSpawn());
                }
                if($event instanceof EntityTeleportEvent) {
                    $owner->teleport($event->getTo());
                }
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
        if(!$cause instanceof EntityDamageByEntityEvent or !(($damager = $cause->getDamager()) instanceof Player)) {
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