<?php

namespace Diaborg3Bundle\Data;


interface EntryModelInterface {

    /**
     * @return \DateTime
     */
    function getTimestamp();
    function getValue();
    function getBE();
    function getInsulin();
    function getId();

} 