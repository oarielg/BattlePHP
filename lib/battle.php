<?php

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

        $this->init($this->player);
        $this->init($this->monster);

        // this should be moved elsewhere
        $this->battle_quotes = [
            "attackhit" => "<b>%s</b> attacked with <i>%s</i> for <b>%s</b> %s damage.",
            "attackcritical" => "Critical hit! <b>%s</b> attacked with <i>%s</i> for <b>%s</b> %s damage.",
            "attackmiss" => "<b>%s</b> attacked with <i>%s</i> but missed.",
            "attackfail" => "<b>%s</b> attacked with <i>%s</i> but <b>%s</b> is immune!.",
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
            $this->attack_turn($condition_check, $this->player, $this->monster);
        }
        elseif (isset($post['spell']))
        {
            $this->spell_turn($condition_check, $_POST['spell_id'], $this->player, $this->monster);
        }
        elseif (isset($post['run']))
        {
            $this->run_turn($condition_check);
        }
    }

    private function monster_turn():void
    {
        $condition_check = ["is_free" => true, "can_cast" => true, "is_charmed" => false];
        $this->check_condition_action($this->monster['conditions'], $condition_check);

        switch($this->monster['class'])
        {
            case MonsterClass::Fighter:
                $this->attack_turn($condition_check, $this->monster, $this->player);
                break;
            case MonsterClass::Caster:
                $this->monster_spell_turn($condition_check);
                break;
            case MonsterClass::Hybrid:
                if (rand(1,10) < 6)
                {
                    $this->attack_turn($condition_check, $this->monster, $this->player);
                }
                else
                {
                    $this->monster_spell_turn($condition_check);
                }
                break;
        }
    }

    private function attack_turn(array $condition_check, array &$attacker, array &$defender):void
    {
        if (!$condition_check['is_free'] || $condition_check['is_charmed'])
            return;

        $this->attack_action($attacker, $attacker['items'][0], $defender);
        if ($attacker['items'][1] != 0)
        {
            if ($attacker['items'][1]['type'] == ItemType::Weapon)
            {
                $this->attack_action($attacker, $attacker['items'][1], $defender);
            }
        }
    }

    private function spell_turn(array $condition_check, int $spell_id, array &$attacker, array &$defender):void
    {       
        if (!$condition_check['is_free'] || !$condition_check['can_cast'])
            return;

        $spell = Data::get_spell($spell_id);

        switch($spell['type'])
        {
            case SpellType::Healing:
                if ($attacker['attributes']['currenthp'] == $attacker['attributes']['maxhp'])
                {
                    $this->add_battle_line(sprintf($spell['missquote'], $attacker['name']));
                }
                else
                {
                    $heal = rand(1,6) + $spell['variable2'] + $attacker['attributes']['power'];
                    $new_hp = $attacker['attributes']['currenthp'] + $heal;
                    if ($new_hp > $attacker['attributes']['maxhp'])
                    {
                        $heal = $attacker['attributes']['maxhp'] - $attacker['attributes']['currenthp'];
                        $new_hp = $attacker['attributes']['currenthp'] + $heal;
                    }
                    $attacker['attributes']['currenthp'] = $new_hp;
                    $this->add_battle_line(sprintf($spell['hitquote'], $attacker['name'], $heal));
                }
                break;
            case SpellType::CauseCondition:
                if ($condition_check['is_charmed'])
                    return;
                $chance = $this->check($attacker['attributes']['power'], $defender['attributes']['mind']);
                $roll = rand(1, 20);
                if ($roll >= $chance)
                {
                    if ($this->has_condition_immunity($defender['condimmunities'], $spell['variable']))
                    {
                        $this->add_battle_line(sprintf($spell['fail2quote'], $attacker['name'], $defender['name']));
                    }
                    else
                    {
                        if ($this->has_condition($defender['conditions'], $spell['variable']))
                        {
                            $this->add_battle_line(sprintf($spell['failquote'], $attacker['name'], $defender['name']));
                        }
                        else
                        {
                            $condition = Data::get_condition($spell['variable']);
                            array_push($defender['conditions'], [$spell['variable'], rand($condition['minduration'], $condition['maxduration'])]);                         
                            $this->add_battle_line(sprintf($spell['hitquote'], $attacker['name'], $defender['name']));
                        }
                    }
                }
                else
                {
                    $this->add_battle_line(sprintf($spell['missquote'], $attacker['name'], $defender['name']));
                }
                break;
            case SpellType::DirectDamage:
                if ($condition_check['is_charmed'])
                    return;
                $chance = $this->check($attacker['attributes']['power'], $defender['attributes']['speed']);
                $roll = rand(1, 20);
                if ($roll >= $chance)
                {
                    if ($this->is_immune_damage($defender['damimmunities'], $spell['variable']))
                    {
                        $this->add_battle_line(sprintf($spell['failquote'], $attacker['name'], $defender['name']));
                    }
                    else
                    {
                        $vulnerable_text = '';
                        $damage = rand(1,6) + $spell['variable2'] + $attacker['attributes']['power'];
                        $resistance = $this->has_resistance_damage($defender['resistances'], $spell['variable']);
                        if ($this->has_vulnerability_damage($defender['vulnerabilities'], $spell['variable']))
                        {
                            $damage += rand(1,6);
                            $vulnerable_text = $defender['name']." is vulnerable!";
                        }
                        $damage = max(($damage-$resistance), 0);
                        $defender['attributes']['currenthp'] -= $damage;
                        $this->add_battle_line(sprintf($spell['hitquote'], $attacker['name'], $damage), $vulnerable_text);
                    }
                }
                else
                {
                    $this->add_battle_line(sprintf($spell['missquote'], $attacker['name'], $defender['name']));
                }
                break;
            case SpellType::Drain:
                if ($condition_check['is_charmed'])
                    return;
                $chance = $this->check($attacker['attributes']['power'], $defender['attributes']['speed']);
                $roll = rand(1, 20);
                if ($roll >= $chance)
                {
                    if ($this->is_immune_damage($defender['damimmunities'], $spell['variable']))
                    {
                        $this->add_battle_line(sprintf($spell['failquote'], $attacker['name'], $defender['name']));
                    }
                    else
                    {
                        $damage = rand(1,3) + $spell['variable2'] + $attacker['attributes']['power'];
                        $resistance = $this->has_resistance_damage($defender['resistances'], $spell['variable']);
                        $damage = max(($damage-$resistance), 0);
                        $defender['attributes']['currenthp'] -= $damage;
                        $heal = ceil($damage/2);
                        $new_hp = $attacker['attributes']['currenthp'] + $heal;
                        if ($new_hp > $attacker['attributes']['maxhp'])
                        {
                            $heal = $attacker['attributes']['maxhp'] - $attacker['attributes']['currenthp'];
                            $new_hp = $attacker['attributes']['currenthp'] + $heal;
                        }
                        $attacker['attributes']['currenthp'] = $new_hp;
                        $this->add_battle_line(sprintf($spell['hitquote'], $attacker['name'], $damage, $heal));
                    }
                }
                else
                {
                    $this->add_battle_line(sprintf($spell['missquote'], $attacker['name'], $defender['name']));
                }
                break;
            case SpellType::ActivateEffect:
                $chance = $this->check($attacker['attributes']['power'], 1);
                $roll = rand(1, 20);
                if ($roll >= $chance)
                {
                    if (!$this->has_effect_on($attacker['effects'], $spell['variable']))
                    {
                        array_push($attacker['effects'], $spell['variable']);
                        $this->add_battle_line(sprintf($spell['hitquote'], $attacker['name']));
                    }
                    else
                    {
                        $this->add_battle_line(sprintf($spell['failquote'], $attacker['name']));
                    }
                }
                else
                {
                    $this->add_battle_line(sprintf($spell['missquote'], $attacker['name']));
                }
                break;
        }
    }

    private function run_turn(array $condition_check):void
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

    private function attack_action(array &$attacker, mixed $weapon, array &$defender):void
    {
        $attack = $attacker['attributes']['might'];
        $damage = rand(1, 6) + $attacker['attributes']['might'];
        $weapon_name = "Fists";

        if (!empty($weapon))
        {
            $attack += $weapon['attack'];
            $damage += $weapon['damage'];
            $weapon_name = $weapon['name'];
        }

        $defense = $defender['attributes']['speed'];
        $armor = 0;

        // do you have an armor?
        if ($defender['items'][2] != 0) 
        {
            $defense += $defender['items'][2]['defense'];
            $armor += $defender['items'][2]['armor'];
        }

        // have anything in secondary slot?
        if ($defender['items'][1] != 0) 
        {
            // is it a shield?
            if ($defender['items'][1]['type'] == ItemType::Shield) 
            {
                $defense += $defender['items'][1]['defense'];
                $armor += $defender['items'][1]['armor'];
            }
        }

        $roll = rand(1, 20);
        $chance = $this->check($attack, $defense);
        if ($roll >= $chance || $roll == 20)
        {
            if ($this->is_immune_damage($defender['damimmunities'], $weapon['damagetype']))
            {
                $this->add_battle_line(sprintf($this->battle_quotes['attackfail'], $attacker['name'], $weapon_name, $defender['name']));
            }
            else
            {
                $vulnerable_text = '';
                if ($this->has_vulnerability_damage($defender['vulnerabilities'], $weapon['damagetype']))
                {
                    $damage += rand(1,6);
                    $vulnerable_text = $defender['name']." is vulnerable!";
                }
                $damage_type_name = $weapon['damagetype']->value;
                if ($roll == 20)
                {
                    $damage *= 2;                  
                    $this->add_battle_line(sprintf($this->battle_quotes['attackcritical'], $attacker['name'], $weapon_name, $damage, $damage_type_name), $vulnerable_text);
                }
                else
                {
                    $this->add_battle_line(sprintf($this->battle_quotes['attackhit'], $attacker['name'], $weapon_name, $damage, $damage_type_name), $vulnerable_text);
                }
                
                $damage = max(($damage-$armor), 0);
                $defender['attributes']['currenthp'] -= $damage;  
            }
         
            if (isset($weapon['extradamage']))
            {
                foreach($weapon['extradamage'] as $damage_type)
                {
                    $damage_type_name = $damage_type->value;
                    if ($this->is_immune_damage($defender['damimmunities'], $damage_type))
                    {
                        $this->add_battle_line(sprintf($this->battle_quotes['attackextrafail'], $attacker['name'], $damage_type_name, $defender['name']));
                    }
                    else
                    {
                        $vulnerable_text = '';
                        $extra_damage = rand(1,3);
                        $resistance = $this->has_resistance_damage($defender['resistances'], $damage_type);
                        if ($this->has_vulnerability_damage($defender['vulnerabilities'], $damage_type))
                        {
                            $extra_damage += rand(1,3);
                            $vulnerable_text = $defender['name']." is vulnerable!";
                        }
                        $extra_damage = max(($extra_damage-$resistance), 0);
                        $defender['attributes']['currenthp'] -= $extra_damage;
                        $this->add_battle_line(sprintf($this->battle_quotes['attackextrahit'], $attacker['name'], $extra_damage, $damage_type_name), $vulnerable_text);
                    }
                }
            }

            if (isset($weapon['lifesteal']))
            {
                $heal = ceil(0.1 * $damage);
                $attacker['attributes']['currenthp'] += $heal;
                $this->add_battle_line(sprintf($this->battle_quotes['attacklifesteal'], $attacker['name'], $heal));
                if ($attacker['attributes']['currenthp'] > $attacker['attributes']['maxhp'])
                {
                    $attacker['attributes']['currenthp'] = $attacker['attributes']['maxhp'];
                }
            }
        }
        else
        {
            $this->add_battle_line(sprintf($this->battle_quotes['attackmiss'], $attacker['name'], $weapon_name));
        }
    }

    private function monster_spell_turn(array $condition_check):void
    {
        // just for safety, but Idk
        if (empty($this->monster['spells']) || !$condition_check['can_cast'])
        {
            $this->attack_turn($condition_check, $this->monster, $this->player);
        }

        $spells = array_merge($this->monster['spells']);   
        $chosen_spell = 0;
        $picked_spell = false;

        while($picked_spell == false)
        {
            if (empty($spells))
            {
                $chosen_spell = 0;
                $picked_spell = true;
                break;
            }
            elseif (count($spells) == 1)
            {
                $chosen_spell = array_shift($spells);
                $picked_spell = true;
            }
            else
            {
                $chosen_spell = $spells[ array_rand($spells) ];
                $picked_spell = true;
            }

            // now check for the chosen spell is valid or not
            $spell = Data::get_spell($chosen_spell);

            if ($spell['type'] == SpellType::Healing)
            {
                if ($this->monster['attributes']['currenthp'] == $this->monster['attributes']['maxhp'])
                {
                    $spells = array_diff($spells,[$chosen_spell]);
                    $chosen_spell = 0;
                    $picked_spell = false;
                }
            }
            elseif ($spell['type'] == SpellType::CauseCondition)
            {
                if ($this->has_condition($this->player['conditions'], $spell['variable']))
                {
                    $spells = array_diff($spells,[$chosen_spell]);
                    $chosen_spell = 0;
                    $picked_spell = false;
                }
            }
            elseif ($spell['type'] == SpellType::ActivateEffect)
            {
                if ($this->has_effect_on($this->monster['effects'], $spell['variable']))
                {
                    $spells = array_diff($spells,[$chosen_spell]);
                    $chosen_spell = 0;
                    $picked_spell = false;
                }
            }
        }

        if ($chosen_spell != 0)
        {
            $this->spell_turn($condition_check, $chosen_spell, $this->monster, $this->player);
        }
        else
        {
            $this->attack_turn($condition_check, $this->monster, $this->player);
        }
    }

    // battle functions
    private function per_turn_checks(array &$entity, bool $is_player):void
    {
        if (!empty($entity['conditions']))
        {
            foreach($entity['conditions'] as $key => &$value)
            {
                $condition = Data::get_condition($value[0]);
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
                        case ConditionType::NoActions:
                        case ConditionType::NoSpells:
                            $this->add_battle_line(sprintf($condition['activequote'], $entity['name']));
                            break;
                        case ConditionType::DoDamage:
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

    private function init(array &$entity):void
    {
        foreach($entity['items'] as $key => $item_id)
        {
            if ($item_id != 0)
            {            
                $item = Data::get_item($item_id);

                if (!empty($item['enchantments']))
                {
                    foreach($item['enchantments'] as $enchantment_id)
                    {
                        $enchantment = Data::get_enchantment($enchantment_id);

                        // what does the enchantment do?
                        switch($enchantment['type'])
                        {
                            case EnchantmentType::AttributeBonus:
                                $entity['attributes'][$enchantment['variable']] += $enchantment['variable2'];
                                break;
                            case EnchantmentType::ConditionImmunity: 
                                if (!$this->has_condition_immunity($entity['condimmunities'], $enchantment['variable']))
                                {
                                    array_push($entity['condimmunities'], $enchantment['variable']);
                                }
                                break;
                            case EnchantmentType::DamageResistance:
                                $current_resistance = $this->has_resistance_damage($entity['resistances'], $enchantment['variable']);
                                if ($current_resistance == 0)
                                {
                                    array_push($entity['resistances'], [$enchantment['variable'], $enchantment['variable2']]);
                                }
                                elseif ($current_resistance > 0)
                                {
                                    if ($current_resistance < $enchantment['variable2'])
                                    {
                                        $resistance_pos = $this->get_resistance_damage($entity['resistances'], $enchantment['variable']);
                                        $entity['resistances'][$resistance_pos][1] = $enchantment['variable2'];
                                    }
                                }
                                break;
                            case EnchantmentType::DamageImmunity:
                                if (!$this->is_immune_damage($entity['damimmunities'], $enchantment['variable']))
                                {
                                    array_push($entity['damimmunities'], $enchantment['variable']);
                                }
                                break;
                            case EnchantmentType::WeaponImbuement:
                                if (!isset($item['extradamage']))
                                {
                                    $item['extradamage'] = [];
                                }
                                array_push($item['extradamage'], $enchantment['variable']);
                                break;
                            case EnchantmentType::WeaponDrain:
                                if (!isset($item['lifesteal']))
                                {
                                    $item['lifesteal'] = 0;
                                } 
                                if ($enchantment['variable'] > $item['lifesteal'])
                                {
                                    $item['lifesteal'] = $enchantment['variable'];
                                }
                                break;
                            default:
                                die("Something wrong with enchantments..");
                        }
                    }
                }

                $entity['items'][$key] = $item;
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
                $winner = "";
                if (!$is_player)
                {
                    $state = BattleState::Win;
                    $winner = $this->player['name'];
                }
                else
                {
                    $state = BattleState::Loss;
                    $winner = $this->monster['name'];
                }

                $this->end_battle($state, $winner);
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
            $condition = Data::get_condition($condition[0]);
            if ($condition['type'] == ConditionType::NoActions)
                $condition_check['is_free'] = false;
            
            if ($condition['type'] == ConditionType::NoSpells)
                $condition_check['can_cast'] = false;

            if ($condition['type'] == ConditionType::NoOffensiveActions)
                $condition_check['is_charmed'] = true;
        }
    }

    private function has_resistance_damage(array $resistances, DamageType $damage_type):int
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

    private function get_resistance_damage(array $resistances, DamageType $damage_type):int
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

    private function has_vulnerability_damage(array $vulnerabilities, DamageType $damage_type):bool
    {
        return in_array($damage_type, $vulnerabilities) == true; 
    }

    private function is_immune_damage(array $damimmunities, DamageType $damage_type):bool
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