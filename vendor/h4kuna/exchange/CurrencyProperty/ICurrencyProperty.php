<?php

namespace h4kuna\Exchange;

use h4kuna\INumberFormat;

/**
 *
 * @author Milan Matějček
 */
interface ICurrencyProperty {

    /** @return int */
    public function getHome();

    /** @return string */
    public function getCode();

    /** @return float */
    public function getForeing();

    /** @return float */
    public function getRate();

    /**
     * This is setup automaticaly
     *
     * @param string
     * @return ICurrencyProperty
     */
    public function setNext($code);

    /**
     * @param string
     */
    public function getNext();

    /**
     * Set how render currency
     *
     * @param INumberFormat $nf
     * @return ICurrencyProperty
     */
    public function setFormat(INumberFormat $nf);

    /** @return INumberFormat */
    public function getFormat();

    /**
     * Set history rate
     *
     * @param float $number
     * @return ICurrencyProperty
     */
    public function pushRate($number);

    /**
     * @return ICurrencyProperty
     */
    public function popRate();

    /**
     * Set last value in stack and clear stack
     *
     * @return ICurrencyProperty
     */
    public function revertRate();
}
