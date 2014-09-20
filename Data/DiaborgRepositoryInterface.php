<?php

namespace Diaborg3Bundle\Data;


use Diaborg3Bundle\Entity\DiaborgEntry;

interface DiaborgRepositoryInterface {

    /**
     * @return EntryModelInterface[]
     */
    public function getList();

    public function getEntry($id);

    public function addEntry($timestamp, $value, $insulin, $be);

    public function addEntity(DiaborgEntry $entry);

    public function deleteEntry($id);

} 