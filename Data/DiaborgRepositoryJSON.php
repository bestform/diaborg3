<?php

namespace Diaborg3Bundle\Data;


use Diaborg3Bundle\Entity\DiaborgEntry;

class DiaborgRepositoryJSON implements DiaborgRepositoryInterface
{
    const KEY_BE = 'BE';
    const KEY_INSULIN = 'insulin';
    const KEY_VALUE = 'value';

    private function getDataDir()
    {
        return __DIR__ . '/../../../app/database';
    }

    private function getDataFile()
    {
        return $this->getDataDir() . '/data.json';
    }

    public function getList()
    {
        $data = $this->getRawJsonData();

        ksort($data);
        $models = array();

        foreach($data as $key => $entry){
            $date = new \DateTime();
            $date->setTimestamp($key);
            $models[] = new EntryModel($date, $entry[self::KEY_VALUE], $entry[self::KEY_BE], $entry[self::KEY_INSULIN]);
        }

        return $models;
    }

    public function getEntry($id)
    {
        // TODO: Implement getEntry() method.
    }

    public function addEntry($timestamp, $value, $insulin, $be)
    {
        $data = $this->getRawJsonData();
        $entry = array(
            self::KEY_VALUE => $value,
            self::KEY_INSULIN => $insulin,
            self::KEY_BE => $be
        );
        while(isset($data[$timestamp])){
            $timestamp++;
        }
        $data[$timestamp] = $entry;

        file_put_contents($this->getDataFile(), json_encode($data));
    }

    public function deleteEntry($id)
    {
        $data = $this->getRawJsonData();
        if(null !== $id){
            if(isset($data[$id])){
                unset($data[$id]);
                file_put_contents($this->getDataFile(), json_encode($data));
            }
        }
    }

    /**
     * @return array|mixed
     */
    private function getRawJsonData()
    {
        if (!file_exists($this->getDataFile())) {
            return array();
        }
        $rawdata = file_get_contents($this->getDataFile());
        $data = json_decode($rawdata, true);
        if (null === $data) {
            $data = array();
            return $data;
        }
        return $data;
    }

    public function addEntity(DiaborgEntry $entry)
    {
        $this->addEntry($entry->getTimestamp()->getTimestamp(), $entry->getValue(), $entry->getInsulin(), $entry->getBe());
    }
}