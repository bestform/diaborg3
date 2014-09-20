<?php

namespace Diaborg3Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="diaborg_entry")
 */
class DiaborgEntry {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $timestamp;

    /**
     * @ORM\Column(type="decimal")
     */
    protected $value;

    /**
     * @ORM\Column(type="decimal")
     */
    protected $be;

    /**
     * @ORM\Column(type="decimal")
     */
    protected $insulin;



    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set timestamp
     *
     * @param \DateTime $timestamp
     * @return DiaborgEntry
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * Get timestamp
     *
     * @return \DateTime 
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * Set value
     *
     * @param string $value
     * @return DiaborgEntry
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set be
     *
     * @param string $be
     * @return DiaborgEntry
     */
    public function setBe($be)
    {
        $this->be = $be;

        return $this;
    }

    /**
     * Get be
     *
     * @return string 
     */
    public function getBe()
    {
        return $this->be;
    }

    /**
     * Set insulin
     *
     * @param string $insulin
     * @return DiaborgEntry
     */
    public function setInsulin($insulin)
    {
        $this->insulin = $insulin;

        return $this;
    }

    /**
     * Get insulin
     *
     * @return string 
     */
    public function getInsulin()
    {
        return $this->insulin;
    }
}
