<?php

class Enemy
{
  public string $name;
  public int $hp;

  public function __construct(string $name, int $hp)
  {
    $this->name = $name;
    $this->hp = $hp;
  }

  public function attacked(int $power): void
  {
      $this->hp -= $power;
  }
}
