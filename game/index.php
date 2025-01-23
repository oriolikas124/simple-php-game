<?php

require_once __DIR__ . '/classes/Player.php';
require_once __DIR__ . '/classes/Enemy.php';

session_start();

/** @var ?Player */
$player = isset($_SESSION['player'])
  ? unserialize($_SESSION['player'])
  : null;

/** @var ?Enemy */
$enemy = isset($_SESSION['enemy'])
  ? unserialize($_SESSION['enemy'])
  : null;

$playerPower = 0;
$enemyPower = 0;

if (isset($_POST['start'])) {
  $player = new Player('124', 100);
  $enemy = new Enemy('スライム', 100);

  $_SESSION['player'] = serialize($player);
  $_SESSION['enemy'] = serialize($enemy);
}

if (isset($_POST['restart'])) {
  unset($_SESSION['player']);
  unset($_SESSION['enemy']);

  $player = null;
  $enemy = null;
}

if (isset($_POST['attack'])) {
  $playerPower = random_int(1, 20);
  $enemyPower = random_int(1, 20);

  $player->attacked($enemyPower);
  $enemy->attacked($playerPower);

  $_SESSION['player'] = serialize($player);
  $_SESSION['enemy'] = serialize($enemy);
}

if (isset($_POST['heal'])) {
  $player->hp = min($player->hp + 20, 100);

  $enemyPower = random_int(1, 20);
  $player->attacked($enemyPower);

  $_SESSION['player'] = serialize($player);
  $_SESSION['enemy'] = serialize($enemy);
}

if (isset($_POST['heal_enemy'])) {
  $enemy->hp = min($enemy->hp + 20, 100);

  $_SESSION['player'] = serialize($player);
  $_SESSION['enemy'] = serialize($enemy);
}

?>
<html>

<body>
  <h1>RPG風ゲーム</h1>
  <form action="./index.php" method="post">
    <?php if (is_null($player) && is_null($enemy)): ?>
      <div>
        <input type="submit" name="start" value="ゲームスタート">
      </div>
    <?php else: ?>
      <?php if ($playerPower > 0): ?>
        <p>
          <?php echo $player->name ?>は、
          <?php echo $playerPower ?>のダメージを与えた！
        </p>
      <?php endif ?>
      <?php if ($enemyPower > 0): ?>
        <p>
          <?php echo $enemy->name ?>は、
          <?php echo $enemyPower ?>のダメージを与えた！
        </p>
      <?php endif ?>
      <p>
        <?php echo $player->name ?>のHP:
        <?php echo $player->hp ?>
      </p>
      <p>
        <?php echo $enemy->name ?>のHP:
        <?php echo $enemy->hp ?>
      </p>

      <?php if ($player->hp <= 0): ?>
        <p>ゲーム終了！<?php echo $enemy->name ?>の勝利！</p>
      <?php elseif ($enemy->hp <= 0): ?>
        <p>ゲーム終了！<?php echo $player->name ?>の勝利！</p>
      <?php else: ?>
        <div>
          <input type="submit" name="attack" value="攻撃">
        </div>
        <div>
          <input type="submit" name="heal" value="回復">
        </div>
      <?php endif ?>
      <div>
        <input type="submit" name="heal_enemy" value="敵を回復">
      </div>

      <div>
        <input type="submit" name="restart" value="リスタート">
      </div>
    <?php endif ?>
  </form>
</body>

</html>
