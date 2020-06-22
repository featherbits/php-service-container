<?php

namespace Featherbits\ServiceContainer\Exceptions;

use Exception;
use Featherbits\ServiceContainer\ResolutionSequence;

class CircularDependencyException extends Exception
{
    private ResolutionSequence $sequence;
    private string $identifier;

    /**
     * @param ResolutionSequence $sequence
     * @param string $identifier Repeated identifier in the sequence.
     */
    public function __construct(ResolutionSequence $sequence, string $identifier)
    {
        $this->sequence = $sequence;
        $this->identifier = $identifier;
    }

    /**
     * Get the value of sequence
     */
    public function getSequence(): ResolutionSequence
    {
        return $this->sequence;
    }

    /**
     * Get the value of identifier
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }
}
