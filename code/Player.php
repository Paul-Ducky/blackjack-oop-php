<?php
declare(strict_types=1);

class Player
{
    public const WINNING_VALUE = 21;
    public const STARTING_HAND_SIZE = 2;
    private array $cards = [];
    private bool $lost = false;

    public function __construct(deck $deck)
    {
        for ($i=0;$i<$this::STARTING_HAND_SIZE;$i++) {
            $this->cards[] = $deck->drawCard();
        }
    }

    public function hit(deck $deck): void
    {
        $this->cards[] = $deck->drawCard();
        if ($this->getScore()>self::WINNING_VALUE){
            $this->lost = true;
        }
    }

    public function surrender(): void
    {
        $this->lost = true;
    }

    public function getScore(): int
    {
        $score = 0;
        foreach ($this->cards as $card){
          $score += $card->getValue();
        }

        return $score;
    }

    public function hasLost(): bool
    {
        return $this->lost;
    }
    public function getCards() : array
    {
        return $this->cards;
    }
    public function displayHand():void
    {
        foreach ($this->getCards() as $card) {
            echo $card->getUnicodeCharacter(true);
            echo PHP_EOL;
        }
    }

}
class Dealer extends Player
{
    public const MIN_HIT_VALUE = 15;
    public function hit(deck $deck): void
    {
        while ($this->getScore()<self::MIN_HIT_VALUE) {
            parent::hit($deck);
        }
    }
}