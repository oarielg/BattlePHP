<?php

require "data/monster.php";
require "lib/battle.php";

session_start();

$player = Data::get_player();
$monster = MonsterData::get_monster();

$battle = new Battle($player, $monster);
$battle->battle_turn($_POST);

$updated_player = $battle->get_entity_data("player");
$updated_monster = $battle->get_entity_data("monster");
$player['attributes']['currenthp'] = $updated_player['currenthp'];
$monster['attributes']['currenthp'] = $updated_monster['currenthp'];

include "templates/top.php";

echo $battle->battle_text;

if ($battle->battle_state == BattleState::InProgress)
{
    Data::save_player($updated_player);
    MonsterData::save_monster($updated_monster);
    include "templates/form.php";
}
elseif ($battle->battle_state == BattleState::Run)
{
    Data::reset_player_data();
    include "templates/continue.php";
}
else
{
    Data::reset_player_data();
    include "templates/battle_end.php";
}