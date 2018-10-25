<?php

namespace Flags;


class Evaluation
{
    /** @var Flag */
    private $flag;

    /** @var bool|null */
    private $result;

    /**
     * Evaluation constructor.
     * @param Flag $flag
     */
    public function __construct(Flag $flag, $result = null)
    {
        $this->flag = $flag;
        $this->result = $result;
    }

    /**
     * @return Flag
     */
    public function getFlag(): Flag
    {
        return $this->flag;
    }

    /**
     * @param Flag $flag
     */
    public function setFlag(Flag $flag): void
    {
        $this->flag = $flag;
    }

    /**
     * @return bool|null
     */
    public function getResult(): ?bool
    {
        return $this->result;
    }

    /**
     * @param bool|null $result
     */
    public function setResult(?bool $result): void
    {
        $this->result = $result;
    }
}