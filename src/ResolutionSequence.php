<?php

namespace Featherbits\ServiceContainer;

use Featherbits\ServiceContainer\Exceptions\CircularDependencyException;

/**
 * Maintains a sequence of resolved identifiers to
 * prevent circular dependency occurrence by rising exception
 * whenever repeted identifier is added to sequence.
 */
class ResolutionSequence
{
    private ?ResolutionSequence $parent;
    private string $identifier;

    private function __construct(string $identifier, ?ResolutionSequence $parent = null)
    {
        $this->identifier = $identifier;
        $this->parent = $parent;
    }

    /**
     * Add new identifier to the sequence.
     * 
     * @param string $identifier
     * @return ResolutionSequence
     */
    public function add(string $identifier): self
    {
        if ($this->isUnique($identifier)) {
            return new self($identifier, $this);
        }

        throw new CircularDependencyException($this, $identifier);
    }

    /**
     * Creates root of the sequence.
     *
     * @param string $identifier
     * @return ResolutionSequence
     */
    public static function create(string $identifier): self
    {
        return new self($identifier);
    }

    /**
     * Test if identifier is unique in sequence.
     * 
     * @param string $identifier
     * @return bool
     */
    private function isUnique(string $identifier): bool
    {
        $resolutionSequence = $this;

        while ($resolutionSequence) {
            if ($resolutionSequence->identifier === $identifier) {
                return false;
            }
            $resolutionSequence = $resolutionSequence->parent;
        }

        return true;
    }
}
