<?php

declare(strict_types=1);

namespace Parties;


use pocketmine\event\Listener;

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

}