<?php

declare(strict_types=1);

class Blackjack

{
    private Player $player;
    private Dealer $dealer;
    private Deck $deck;

    public function __construct()
    {
        $this->deck = new deck();
        $this->deck->shuffle();
        $this->player=new Player($this->deck);
        $this->dealer=new Dealer($this->deck);
    }
    public function getPlayer(): Player
    {
        return $this->player;
    }
    public function getDealer(): Dealer
    {
        return $this->dealer;
    }
    public function getDeck(): Deck
    {
        return $this->deck;
    }





}