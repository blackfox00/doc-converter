<?php

namespace DocConverter\Document\Bibliography;

/**
 * InternetBibliography
 */
class InternetSource extends AbstractSource {

    /** @var string $url */
    private $url;

    /** @var string $month */
    private $month;

    /** @var string $day */
    private $day;

    /** @var string $yearAccessed */
    private $yearAccessed;

    /** @var string $monthAccessed */
    private $monthAccessed;

    /** @var string $dayAccessed */
    private $dayAccessed;

    /**
     * @return string
     */
    public function getUrl(): string {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getMonth(): string {
        return $this->month;
    }

    /**
     * @return string
     */
    public function getDay(): string {
        return $this->day;
    }

    /**
     * @return string
     */
    public function getYearAccessed(): string {
        return $this->yearAccessed;
    }

    /**
     * @return string
     */
    public function getMonthAccessed(): string {
        return $this->monthAccessed;
    }

    /**
     * @return string
     */
    public function getDayAccessed(): string {
        return $this->dayAccessed;
    }

    /**
     * @param string $url
     * @return $this
     */
    public function setUrl(string $url) {
        $this->url = $url;
        return $this;
    }

    /**
     * @param string $month
     * @return $this
     */
    public function setMonth(string $month) {
        $this->month = $month;
        return $this;
    }

    /**
     * @param string $day
     * @return $this
     */
    public function setDay(string $day) {
        $this->day = $day;
        return $this;
    }

    /**
     * @param string $yearAccessed
     * @return $this
     */
    public function setYearAccessed(string $yearAccessed) {
        $this->yearAccessed = $yearAccessed;
        return $this;
    }

    /**
     * @param string $monthAccessed
     * @return $this
     */
    public function setMonthAccessed(string $monthAccessed) {
        $this->monthAccessed = $monthAccessed;
        return $this;
    }

    /**
     * @param string $dayAccessed
     * @return $this
     */
    public function setDayAccessed(string $dayAccessed) {
        $this->dayAccessed = $dayAccessed;
        return $this;
    }

}
