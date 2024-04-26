<?php

// this page simply prints out all monsters, items, spells, conditions and enchantments.
// it got a bit long but it is what it is :)

ini_set('display_startup_errors',1); 
ini_set('display_errors',1);
error_reporting(-1);

$p = (isset($_GET['p'])) ? $_GET['p'] : 'monsters';

require "data/data.php";
require "data/monster.php";
include "templates/data_top_form.php";

switch($p)
{
    case "monsters":
        $monsters = MonsterData::get_all_monsters();
        foreach($monsters as $monster)
        {
            echo "<strong>".$monster['name']."</strong><br>";
            echo "HP: ".$monster['attributes']['maxhp']."<br>";
            echo "Might: ".$monster['attributes']['might']."<br>";
            echo "Power: ".$monster['attributes']['power']."<br>";
            echo "Speed: ".$monster['attributes']['speed']."<br>";
            echo "Mind: ".$monster['attributes']['mind']."<br>";

            if (!empty($monster['items']))
            {
                echo "Items:<br>";
                foreach($monster['items'] as $item_id)
                {
                    if ($item_id != 0)
                    {
                        $item = Data::get_item($item_id);
                        echo "-".$item['name']." (".$item['type']->value.")<br>";
                    }
                }
            }

            if (!empty($monster['spells']))
            {
                echo "Spells:<br>";
                foreach($monster['spells'] as $spell_id)
                {
                    $spell = Data::get_spell($spell_id);
                    echo "-".$spell['name']."<br>";
                }
            }

            if (!empty($monster['resistances']))
            {
                echo "Damage Resistances:<br>";
                foreach($monster['resistances'] as $res)
                {
                    echo "-".$res[0]->value." ".$res[1]."<br>";
                }
            }

            if (!empty($monster['damimmunities']))
            {
                echo "Damage Immunities:<br>";
                foreach($monster['damimmunities'] as $im)
                {
                    echo "-".$im->value."<br>";
                }
            }

            if (!empty($monster['vulnerabilities']))
            {
                echo "Damage Vulnerabilities:<br>";
                foreach($monster['vulnerabilities'] as $vu)
                {
                    echo "-".$vu->value."<br>";
                }
            }

            if (!empty($monster['condimmunities']))
            {
                echo "Condition Immunities:<br>";
                foreach($monster['condimmunities'] as $condition_id)
                {
                    $condition = Data::get_condition($condition_id);
                    echo "-".$condition['name']."<br>";
                }
            }

            echo "<br><hr>";
        }
        break;

    case 'items':
        $items = Data::get_item();
        foreach($items as $item)
        {
            echo "<strong>".$item['name']."</strong><br>";
            echo "Type: ".$item['type']->value."<br>";
            if ($item['type'] == ItemType::Weapon)
            {
                echo "Damage Type: ".$item['damagetype']->value."<br>";
            }
            if ($item['damage'] != 0)
            {
                echo "Damage ".$item['damage']."<br>";
            }
            if ($item['armor'] != 0)
            {
                echo "Armor ".$item['armor']."<br>";
            }
            if ($item['attack'] != 0)
            {
                echo "Attack ".$item['attack']."<br>";
            }
            if ($item['defense'] != 0)
            {
                echo "Defense ".$item['defense']."<br>";
            }
            if ($item['initiative'] != 0)
            {
                echo "Initiative ".$item['initiative']."<br>";
            }
            if (!empty($item['enchantments']))
            {
                echo "Enchantments:<br>";
                foreach($item['enchantments'] as $enchantment_id)
                {
                    $enchantment = Data::get_enchantment($enchantment_id);
                    echo "-".$enchantment['name']."<br>";
                }
            }

            echo "<br><hr>";
        }
        break;

    case 'spells':
        $spells = Data::get_spell();
        foreach($spells as $spell)
        {
            echo "<strong>".$spell['name']."</strong><br>";
            echo "Effects: <br>";
            switch($spell['type'])
            {
                case SpellType::Healing:
                    echo "-Restores HP.<br>";
                    break;
                case SpellType::DirectDamage:
                    echo "-Deals ".$spell['variable']->value." damage.<br>";
                    break;
                case SpellType::CauseCondition:
                    $condition = Data::get_condition($spell['variable']);
                    echo "-Causes the ".$condition['name']." condition.<br>";
                    break;
                case SpellType::Drain:
                    echo "-Deals ".$spell['variable']->value." damage and recover part of the damage done in HP.<br>";
                    break;
                case SpellType::ActivateEffect:
                    if ($spell['variable'] == 1)
                    {
                        echo "-When the caster gets to 1 HP or less, they get to 1 HP instead and can keep fighting.<br>";
                    }
                    break;
            }

            echo "<br><hr>";
        }
        break;

    case 'conditions':
        $conditions = Data::get_condition();
        foreach($conditions as $condition)
        {
            echo "<strong>".$condition['name']."</strong><br>";
            echo "Effects: <br>";
            switch($condition['type'])
            {
                case ConditionType::NoActions:
                    echo "Can't take actions for ".$condition['minduration']."-".$condition['maxduration']." turns.<br>";
                    break;
                case ConditionType::NoOffensiveActions:
                    echo "Can't take offensive actions for ".$condition['minduration']."-".$condition['maxduration']." turns.<br>";
                    break;
                case ConditionType::NoSpells:
                    echo "Can't cast spells for ".$condition['minduration']."-".$condition['maxduration']." turns.<br>";
                    break;
                case ConditionType::DoDamage:
                    echo "-Deals ".$condition['variable']->value." damage per turn for ".$condition['minduration']."-".$condition['maxduration']." turns.<br>";
                    break;
            }

            echo "<br><hr>";
        }
        break;

    case 'enchantments':
        $enchantments = Data::get_enchantment();
        foreach($enchantments as $enchantment)
        {
            echo "<strong>".$enchantment['name']."</strong><br>";
            echo "Effects: <br>";
            switch($enchantment['type'])
            {
                case EnchantmentType::AttributeBonus:
                    echo "+".$enchantment['variable2']." bonus to ".$enchantment['variable']."<br>";
                    break;
                case EnchantmentType::ConditionImmunity:
                    $condition = Data::get_condition($enchantment['variable']);
                    echo "-Grants immunity to ".$condition['name']." condition.<br>";
                    break;
                case EnchantmentType::DamageResistance:
                    echo "-Grants ".$enchantment['variable']->value." Resistance ".$enchantment['variable2']."<br>";
                    break;
                case EnchantmentType::DamageImmunity:
                    echo "-Grants immunity to ".$enchantment['variable']->value." damage.<br>";
                    break;
                case EnchantmentType::WeaponImbuement:
                    echo "Make weapon attacks deal extra ".$enchantment['variable']->value." damage.<br>";
                    break;
                case EnchantmentType::WeaponDrain:
                    echo "Weapon attacks restores part of the damage done in HP.<br>";
                    break;
            }

            echo "<br><hr>";
        }
        break;
}