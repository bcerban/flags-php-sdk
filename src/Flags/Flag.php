<?php

namespace Flags;


class Flag
{
    /** @var string */
    private $identifier;

    /** @var string */
    private $name;

    /**
     * Flag constructor.
     * @param string $name
     * @param string $identifier
     */
    public function __construct(
        $identifier,
        $name = ''
    )
    {
        $this->identifier = $identifier;
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * @param string $identifier
     */
    public function setIdentifier(string $identifier): void
    {
        $this->identifier = $identifier;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }
}