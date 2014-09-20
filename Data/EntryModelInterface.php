<?php

namespace Diaborg3Bundle\Data;


interface EntryModelInterface {

    function __construct($timestamp, $value, $be, $insulin);

    /**
     * @return \DateTime
     */
    function getTimestamp();
    function getValue();
    function getBE();
    function getInsulin();

} 