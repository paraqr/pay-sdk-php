<?php

namespace paraqr\payment\base;

class CardInfo extends Bean {

    /**
     * bank card number
     * @var null|string
     */
    public $number = null;

    /**
     * cardholder's first name
     * @var null|string
     */
    public $firstName = null;

    /**
     * cardholder's last name
     * @var null|string
     */
    public $lastName = null;

    /**
     * the year of validity of the bank card, e.g: 2024
     * @var null|string
     */
    public $expireYear = null;

    /**
     * the month of validity of the bank card, e.g: 01
     * @var null|string
     */
    public $expireMonth = null;

    /**
     * cvv
     * @var null|string
     */
    public $cvv = null;

    /**
     * @param $number
     * @return $this
     */
    public function setNumber($number): CardInfo {
        $this->number = $number;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getNumber() {
        return $this->number;
    }

    /**
     * @param $name
     * @return $this
     */
    public function setFirstName($name): CardInfo {
        $this->firstName = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFirstName() {
        return $this->firstName;
    }

    /**
     * @param $name
     * @return $this
     */
    public function setLastName($name): CardInfo {
        $this->lastName = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getLastName() {
        return $this->lastName;
    }

    /**
     * @param $expireYear
     * @return $this
     */
    public function setExpireYear($expireYear): CardInfo {
        $this->expireYear = $expireYear;
        return $this;
    }

    /**
     * @param $expireMonth
     * @return $this
     */
    public function setExpireMonth($expireMonth): CardInfo {
        $this->expireMonth = $expireMonth;
        return $this;
    }

    /**
     * @param $cvv
     * @return $this
     */
    public function setCvv($cvv): CardInfo {
        $this->cvv = $cvv;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getExpireYear() {
        return $this->expireYear;
    }

    /**
     * @return string|null
     */
    public function getExpireMonth() {
        return $this->expireMonth;
    }

    /**
     * @return string|null
     */
    public function getCvv() {
        return $this->cvv;
    }

    /**
     * @return array
     */
    public function toArray(): array {
        return [
            'number'       => $this->number,
            'first_name'   => $this->firstName,
            'last_name'    => $this->lastName,
            'expire_year'  => $this->expireYear,
            'expire_month' => $this->expireMonth,
            'cvv'          => $this->cvv,
        ];
    }
}
