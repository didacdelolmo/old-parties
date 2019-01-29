<?php

declare(strict_types=1);

namespace Diduhless\Parties\command;


use Diduhless\Parties\session\Session;

abstract class PartyCommand {

    /** @var string */
    private $name;

    /** @var array */
    private $aliases = [];

    /** @var string */
    private $usageMessageId;

    /** @var string */
    private $descriptionMessageId;

    /**
     * IsleCommand constructor.
     * @param array $aliases
     * @param string $usageMessageId
     * @param string $descriptionMessageId
     */
    public function __construct(array $aliases, string $usageMessageId, string $descriptionMessageId) {
        $this->aliases = array_map("strtolower", $aliases);
        $this->name = array_shift($this->aliases);
        $this->usageMessageId = $usageMessageId;
        $this->descriptionMessageId = $descriptionMessageId;
    }

    /**
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getAliases(): array {
        return $this->aliases;
    }

    /**
     * @return string
     */
    public function getUsageMessageId(): string {
        return $this->usageMessageId;
    }

    /**
     * @return string
     */
    public function getDescriptionMessageId(): string {
        return $this->descriptionMessageId;
    }

    /**
     * @param Session $session
     * @param array $args
     * @return void
     */
    public abstract function onCommand(Session $session, array $args): void;

}