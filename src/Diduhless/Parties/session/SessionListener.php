<?php

declare(strict_types=1);

namespace Diduhless\Parties\session;


use pocketmine\event\Listener;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;

class SessionListener implements Listener {

    /** @var SessionManager */
    private $manager;

    /**
     * SessionListener constructor.
     * @param SessionManager $manager
     */
    public function __construct(SessionManager $manager) {
        $this->manager = $manager;
    }

    /**
     * @param PlayerLoginEvent $event
     * @throws \ReflectionException
     */
    public function onLogin(PlayerLoginEvent $event): void {
        $this->manager->openSession($event->getPlayer());
    }

    /**
     * @param PlayerQuitEvent $event
     * @throws \ReflectionException
     */
    public function onQuit(PlayerQuitEvent $event): void {
        $this->manager->closeSession($event->getPlayer());
    }

}