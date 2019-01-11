<?php

namespace TresEnRayaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Partida
 *
 * @ORM\Table(name="partida")
 * @ORM\Entity(repositoryClass="TresEnRayaBundle\Repository\Entity\PartidaRepository")
 */
class Partida
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="board", type="string", length=255)
     */
    private $board;

    /**
     * @var bool
     *
     * @ORM\Column(name="finished", type="boolean")
     */
    private $finished;

    /**
     * @var string
     *
     * @ORM\Column(name="lastMoved", type="string", length=1, nullable=true)
     */
    private $lastMoved;

    /**
     * @var integer
     *
     * @ORM\Column(name="movements", type="integer", length=1, nullable=true)
     */
    private $movements;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set board
     *
     * @param string $board
     *
     * @return Partida
     */
    public function setBoard($board)
    {
        $this->board = $board;

        return $this;
    }

    /**
     * Get board
     *
     * @return string
     */
    public function getBoard()
    {
        return $this->board;
    }

    /**
     * Set finished
     *
     * @param boolean $finished
     *
     * @return Partida
     */
    public function setFinished($finished)
    {
        $this->finished = $finished;

        return $this;
    }

    /**
     * Get finished
     *
     * @return bool
     */
    public function getFinished()
    {
        return $this->finished;
    }

    /**
     * Set lastMoved
     *
     * @param string $lastMoved
     *
     * @return Partida
     */
    public function setLastMoved($lastMoved)
    {
        $this->lastMoved = $lastMoved;

        return $this;
    }

    /**
     * Get lastMoved
     *
     * @return string
     */
    public function getLastMoved()
    {
        return $this->lastMoved;
    }

    /**
     * Set movements
     *
     * @param integer $movements
     *
     * @return Partida
     */
    public function setMovements($movements)
    {
        $this->movements = $movements;

        return $this;
    }

    /**
     * Get movements
     *
     * @return integer
     */
    public function getMovements()
    {
        return $this->movements;
    }
    
}
