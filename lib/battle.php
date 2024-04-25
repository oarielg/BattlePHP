<?php

require "data/data.php";

enum BattleState
{
    case Win;
    case Loss;
    case InProgress;
    case Run;
}

class Battle
{
    public array $player;
    public array $monster;
    public string $battle_text;
    private array $battle_quotes;

    public BattleState $battle_state;
    public string $winner;

    public function __construct($player, $monster)
    {
        $this->player = $player;
        $this->monster = $monster;

        $this->battle_state = BattleState::InProgress;
        $this->battle_text = "";

        // this should be moved elsewhere
        $this->battle_quotes = [
            "attackhit" => "<b>%s</b> attacked with <i>%s</i> for <b>%s</b> damage.",
            "attackcritical" => "Critical hit! <b>%s</b> attacked with <i>%s</i> for <b>%s</b> damage.",
            "attackmiss" => "<b>%s</b> attacked with <i>%s</i> but missed.",
            "attackextrahit" => "<b>%s's</b> attack did <b>%s</b> extra %s damage.",
            "attackextrafail" => "<b>%s's</b> attack do extra %s damage but <b>%s</b> is immune!",
            "attacklifesteal" => "<b>%s</b> recovered <b>%s</b> HP from their attack.",
            "runsuccess" => "<b>%s</b> escaped!",
            "runfail" => "<b>%s</b> tried to escape but <b>%s</b> blocked the way!",
        ];
    }

    // this is where the battle really happens
    public function battle_turn(array $post):void
    {
        $this->per_turn_checks($this->player, true);
        $this->per_turn_checks($this->monster, false);

        if ($this->battle_state == BattleState::InProgress)
        {
            $this->player_turn($post);
        }

        if ($this->monster['attributes']['currenthp'] > 0 && $this->battle_state == BattleState::InProgress)
        {
            if (empty($post))
            {
                $initiative = $this->initiative($this->monster, $this->player);
                if ($initiative)
                {
                    $this->monster_turn();
                }
            }
            else
                $this->monster_turn();
        }

        $this->check_death($this->player, true);
        $this->check_death($this->monster, false);
    }

    // turn functions
    private function player_turn(array $post):void
    {
        $condition_check = ["is_free" => true, "can_cast" => true, "is_charmed" => false];
        $this->check_condition_action($this->player['conditions'], $condition_check);

        if (isset($post['attack']))
        {

        }
        elseif (isset($post['spell']))
        {

        }
        elseif (isset($post['run']))
        {
            $this->run_turn($condition_check);
        }
    }

    private function monster_turn():void
    {

    }

    function run_turn(array $condition_check):void
    {
        if (!$condition_check['is_free'] || $condition_check['is_charmed'])
            return;

        $to_run = $this->player['attributes']['speed'] + rand(1,20);
        $to_block = $this->monster['attributes']['might'] + rand(1,20);
        
        if ($to_run >= $to_block)
        {
            $this->add_battle_line(sprintf($this->battle_quotes['runsuccess'], $this->player['name']));
            $this->end_battle(BattleState::Run, "");
        }
        else
        {
            $this->add_battle_line(sprintf($this->battle_quotes['runfail'], $this->player['name'], $this->monster['name']));
        }
    }

    // battle functions
    private function per_turn_checks(array &$entity, bool $is_player):void
    {
        if (!empty($entity['conditions']))
        {
            foreach($entity['conditions'] as $key => &$value)
            {
                $condition = get_condition($value[0]);
                $value[1]--;

                if ($value[1] == 0)
                {
                    // remove from list
                    unset($entity['conditions'][$key]);                   
                    $this->add_battle_line(sprintf($condition['endquote'], $entity['name']));
                }
                else
                {
                    switch($condition['type'])
                    {
                        case 1: // can't perform actions
                        case 3: // can't cast spells
                            $this->add_battle_line(sprintf($condition['activequote'], $entity['name']));
                            break;
                        case 2: // take damage
                            if ($this->is_immune_damage($entity['damimmunities'], $condition['variable']))
                            {
                                $this->add_battle_line(sprintf($condition['failquote'], $entity['name']));
                            }
                            else
                            {
                                $vulnerable_text = '';
                                $damage = rand(1,3);
                                $resistance = $this->has_resistance_damage($entity['resistances'], $condition['variable']);
                                if ($this->has_vulnerability_damage($entity['vulnerabilities'], $condition['variable']))
                                {
                                    $damage += rand(1,3);
                                    $vulnerable_text = $entity['name']." is vulnerable!";
                                }
                                $damage = max(($damage-$resistance), 0);
                                $entity['attributes']['currenthp'] -= $damage;
                                $this->add_battle_line(sprintf($condition['activequote'], $entity['name'], $damage), $vulnerable_text);
                                $this->check_death($entity, $is_player);
                            }
                            break;
                    }
                }
            }
        }
    }

    private function check_death(array &$entity, bool $is_player):void
    {
        if ($entity['attributes']['currenthp'] <= 0)
        {
            // check if entity has a "No Death" effect type
            if ($this->check_effect_type($entity['effects'], 1)) 
            {
                $entity['attributes']['currenthp'] = 1;
                $entity['effects'] = array_diff($entity['effects'], [1]);
                $this->add_battle_line("The Reaper spared ".$entity['name']."'s life this once!");
            }
            else
            {
                $state = BattleState::InProgress;
                if (!$is_player)
                    $state = BattleState::Win;
                else
                    $state = BattleState::Loss;

                $this->end_battle($state, $entity['name']);
            }
        }
    }

    private function end_battle(BattleState $state, string $winner):void
    {
        $this->battle_state = $state;
        $this->winner = $winner;
    }

    private function check_effect_type(array $effects, int $effect_type):bool
    {
        return in_array($effect_type, $effects) == true;
    }

    private function has_condition(array $conditions, int $condition_id):bool
    {
        if (empty($conditions))
            return false;

        foreach($conditions as $condition)
        {
            if ($condition[0] == $condition_id)
                return true;
        }

        return false;
    }

    private function check_condition_action(array $conditions, array &$condition_check):void
    {
        if (empty($conditions))
            return;

        foreach($conditions as $condition)
        {
            $condition = get_condition($condition[0]);
            if ($condition['type'] == 1)
                $condition_check['is_free'] = false;
            
            if ($condition['type'] == 3)
                $condition_check['can_cast'] = false;

            if ($condition['type'] == 4)
                $condition_check['is_charmed'] = true;
        }
    }

    private function has_resistance_damage(array $resistances, int $damage_type):int
    {
        if (empty($resistances))
            return 0;

        foreach($resistances as $resistance)
        {
            if ($resistance[0] == $damage_type)
                return $resistance[1];
        }

        return 0;
    }

    private function get_resistance_damage(array $resistances, int $damage_type):int
    {
        if (empty($resistances))
            return 0;

        foreach($resistances as $key => $value)
        {
            if ($value[0] == $damage_type)
                return $key;
        }

        return 0;
    }

    private function has_vulnerability_damage(array $vulnerabilities, int $damage_type):bool
    {
        return in_array($damage_type, $vulnerabilities) == true; 
    }

    private function is_immune_damage(array $damimmunities, int $damage_type):bool
    {
        return in_array($damage_type, $damimmunities) == true;
    }

    private function has_condition_immunity(array $condimmunities, int $condition_id):bool
    {
        return in_array($condition_id, $condimmunities) == true;
    }

    private function has_effect_on(array $effects, int $effect_id):bool
    {
        return in_array($effect_id, $effects) == true;
    }

    private function check(int $active, int $passive):int
    {
        return min(20, max(1, (8 - $active) + $passive));
    }

    private function initiative(array $attacker, array $defender):bool
    {
        return ((rand(1,20)+$attacker['attributes']['speed']) >= (rand(1,20)+$defender['attributes']['speed']));
    }

    private function add_battle_line(string $text, string $extra_text = ''):void
    {
        $text = $text." ".$extra_text."<br>";
        $this->battle_text .= $text;
    }

    public function get_entity_data(string $who):array
    {
        return [
            "id" => $this->$who['id'],
            "currenthp" => $this->$who['attributes']['currenthp'],
            "conditions" => $this->$who['conditions'],
            "effects" => $this->$who['effects']
        ];
    }
}