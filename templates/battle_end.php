<?php
 echo (($battle->battle_state == BattleState::Win) ? '<b>You have won!</b><br>' : '<b>You have lost.</b><br>');
 echo "Winner: <b>".$battle->winner."</b><br><br><br>";
 echo '<a href="'.$_SERVER['PHP_SELF'].'">[Continue]</a>';