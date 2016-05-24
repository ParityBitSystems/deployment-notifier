<?php

namespace ParityBit\DeploymentNotifier;

/**
 * Details of a change introduced as part of a deployment
 */
class Change
{
    /**
     * The summary of the change
     * @var string
     */
    public $summary;

    /**
     * The author of the change
     * @var string
     */
    public $author;

    /**
     * The full description of the change
     * @var string
     */
    public $fullDescription;

    /**
     * A reference for the change
     * @var string
     */
    public $reference;

    /**
     * Change constructor
     *
     * @param string $summary
     * @param string $author
     * @param string $fullDescription
     * @param string $reference
     */
    public function __construct($summary = null, $author = null, $fullDescription = null, $reference = null)
    {
        $this->summary = $summary;
        $this->author = $author;
        $this->fullDescription = $fullDescription;
        $this->reference = $reference;
    }

    public function __toString()
    {
        return $this->summary;
    }
}
