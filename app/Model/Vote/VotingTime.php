<?php

declare(strict_types=1);

namespace Model\Vote;

use DateInterval;
use DateTimeImmutable;
use function max;
use function min;

class VotingTime
{
    private ?DateTimeImmutable $begin;
    private ?DateTimeImmutable $end;

    public function __construct(?DateTimeImmutable $begin, ?DateTimeImmutable $end)
    {
        $this->begin = $begin;
        $this->end   = $end;
    }

    public static function fromFormat(string $format, string $begin, string $end) : self
    {
        $begin = DateTimeImmutable::createFromFormat($format, $begin);
        $end   = DateTimeImmutable::createFromFormat($format, $end);

        return new self($begin, $end);
    }

    public function getBegin() : ?DateTimeImmutable
    {
        return $this->begin;
    }

    public function getEnd() : ?DateTimeImmutable
    {
        return $this->end;
    }

    public function isBeforeVoting() : bool
    {
        $now = new DateTimeImmutable();

        return $this->getBegin() === null || $now < $this->getBegin();
    }

    public function isVotingInProgress() : bool
    {
        $now = new DateTimeImmutable();

        return $this->getBegin() <= $now && $now < $this->getEnd();
    }

    public function isAfterVoting() : bool
    {
        return $this->getEnd() === null ? false : $this->getEnd() <= new DateTimeImmutable();
    }

    public function getBeforeInterval() : ?DateInterval
    {
        return $this->getBegin() === null ? null : $this->getBegin()->diff(min(new DateTimeImmutable(), $this->getBegin()));
    }

    public function getToEndInterval() : ?DateInterval
    {
        $now = new DateTimeImmutable();

        return $this->getEnd() === null ? null :$now->diff(max($now, $this->getEnd()));
    }
}
