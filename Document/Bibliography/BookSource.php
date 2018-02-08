<?php

namespace DocConverter\Document\Bibliography;

/**
 * Book source container
 */
class BookSource extends AbstractSource {

    /** @var string $city */
    private $city;

    /** @var string $publisher */
    private $publisher;

    /**
     * @return string
     */
    public function getCity(): string {
        return $this->city;
    }

    /**
     * @return string
     */
    public function getPublisher(): string {
        return $this->publisher;
    }

    /**
     * @param string $city
     * @return $this
     */
    public function setCity(string $city) {
        $this->city = $city;
        return $this;
    }

    /**
     * @param string $publisher
     * @return $this
     */
    public function setPublisher(string $publisher) {
        $this->publisher = $publisher;
        return $this;
    }

}
