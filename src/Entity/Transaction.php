<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table
 */
class Transaction
{
    const STATE_NEW = 'new';
    const STATE_PROCESSING = 'processing';
    const STATE_ACCEPTED = 'accepted';
    const STATE_DECLINED = 'declined';

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $status;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $totalPrice;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setStatus(string $value) :self
    {
        $this->status = $value;

        return $this;
    }

    /**
     * @return int
     */
    public function getTotalPrice(): int
    {
        return $this->totalPrice;
    }

    /**
     * @param int $value
     *
     * @return $this
     */
    public function setTotalPrice(int $value) :self
    {
        $this->totalPrice = $value;

        return $this;
    }
}
