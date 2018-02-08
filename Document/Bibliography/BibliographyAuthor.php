<?php

namespace DocConverter\Document\Bibliography;

/**
 * Bibliography author representation container
 */
class BibliographyAuthor {

    /** @var bool $main */
    private $main;

    /** @var string $firstName */
    private $firstName;

    /** @var string $lastName */
    private $lastName;

    /**
     * @param string $firstName
     * @param string $lastName
     * @param bool $main
     */
    public function __construct(string $firstName, string $lastName, bool $main = false) {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->main = $main;
    }

    /**
     * @return bool
     */
    public function getMain(): bool {
        return $this->main;
    }

    /**
     * @return string
     */
    public function getFirstName(): string {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string {
        return $this->lastName;
    }

    /**
     * @return bool
     */
    public function isMain(): bool {
        return $this->main;
    }

}
