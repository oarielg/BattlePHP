<?php

// This just represents a REAL database/methods

enum ItemType
{
    case Weapon;
    case Shield;
    case Armor;
    case Ring;
}

enum SpellType
{
    case Healing;
    case DirectDamage;
    case Drain;
    case CauseCondition;
    case RemoveCondition;
    case ActivateEffect;
}

enum ConditionType
{
    case NoActions;
    case NoOffensiveActions;
    case NoSpells;
    case DoDamage;
}

enum EnchantmentType
{
    case AttributeBonus;
    case ConditionImmunity;
    case DamageResistance;
    case DamageImmunity;
    case WeaponImbuement;
    case WeaponDrain;
}

enum DamageType:string
{
    case Slashing = 'Slashing';
    case Piercing = 'Piercing';
    case Bludgeoning = 'Bludgeoning';
    case Fire = 'Fire';
    case Cold = 'Cold';
    case Thunder = 'Thunder';
    case Earth = 'Earth';
    case Radiant = 'Radiant';
    case Necrotic = 'Necrotic';
    case Psychic = 'Psychic';
}

class Data
{
    public static function get_player():array
    {
        $player = self::generate_player();
        if (isset($_SESSION['player']))
        {
            $player_data = unserialize($_SESSION['player']);
            $player['attributes']['currenthp'] = $player_data['currenthp'];
            $player['conditions'] = $player_data['conditions'];
            $player['effects'] = $player_data['effects'];
        }
        return $player;
    }

    public static function save_player(array $player_data):void
    {
        $_SESSION['player'] = serialize($player_data);
    }

    public static function generate_player():array
    {
        return [
            "id" => 1,
            "name" => "Player",
            "attributes" => [
                "currenthp" => 30,
                "maxhp" => 30,
                "might" => 2,
                "power" => 2,
                "speed" => 2,
                "mind" => 2
            ],
            "items" => [40,0,11,0],
            "spells" => [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24],
            "resistances" => [],
            "conditions" => [],
            "damimmunities" => [],
            "vulnerabilities" => [],
            "condimmunities" => [],
            "effects" => []
        ];
    }

    public static function reset_player_data():void
    {
        unset($_SESSION['player']);
        unset($_SESSION['monster']);
    }

    public static function get_condition(int $condition_id):array
    {
        global $conditions;
        return $conditions[$condition_id];
    }

    public static function get_spell(int $spell_id):array
    {
        global $spells;
        return $spells[$spell_id];
    }

    public static function get_item(int $item_id):array
    {
        global $items;
        return $items[$item_id];
    }

    public static function get_enchantment(int $enchantment_id):array
    {
        global $enchantments;
        return $enchantments[$enchantment_id];
    }
}

$conditions = [
    1 => [
        "id" => 1,
        "name" => "Asleep",
        "type" => ConditionType::NoActions,
        "minduration" => 2,
        "maxduration" => 4,
        "activequote" => "<b>%s</b> is Asleep!",
        "endquote" => "<b>%s</b> woke up."
    ],
    2 => [
        "id" => 2,
        "name" => "Burned",
        "type" => ConditionType::DoDamage,
        "variable" => DamageType::Fire,
        "minduration" => 3,
        "maxduration" => 6,
        "activequote" => "<b>%s</b> is Burned! It takes <b>%s</b> Fire damage.",
        "failquote" => "<b>%s</b> is Burned but it is immune to Fire damage!",
        "endquote" => "<b>%s</b> is no longer Burned."
    ],
    3 => [
        "id" => 3,
        "name" => "Confused",
        "type" => ConditionType::DoDamage,
        "variable" => DamageType::Psychic,
        "minduration" => 3,
        "maxduration" => 6,
        "activequote" => "<b>%s</b> is Confused! It takes <b>%s</b> Psychic damage.",
        "failquote" => "<b>%s</b> is Confused but it is immune to Psychic damage!",
        "endquote" => "<b>%s</b> is no longer Confused."
    ],
    4 => [
        "id" => 4,
        "name" => "Cursed",
        "type" => ConditionType::DoDamage,
        "variable" => DamageType::Necrotic,
        "minduration" => 3,
        "maxduration" => 6,
        "activequote" => "<b>%s</b> is Cursed! It takes <b>%s</b> Necrotic damage.",
        "failquote" => "<b>%s</b> is Cursed but it is immune to Necrotic damage!",
        "endquote" => "<b>%s</b> is no longer Cursed."
    ],
    5 => [
        "id" => 5,
        "name" => "Doomed",
        "type" => ConditionType::DoDamage,
        "variable" => DamageType::Radiant,
        "minduration" => 3,
        "maxduration" => 6,
        "activequote" => "<b>%s</b> is Doomed! It takes <b>%s</b> Radiant damage.",
        "failquote" => "<b>%s</b> is Doomed but it is immune to Radiant damage!",
        "endquote" => "<b>%s</b> is no longer Doomed."
    ],
    6 => [
        "id" => 6,
        "name" => "Electrified",
        "type" => ConditionType::DoDamage,
        "variable" => DamageType::Thunder,
        "minduration" => 3,
        "maxduration" => 6,
        "activequote" => "<b>%s</b> is Electrified! It takes <b>%s</b> Thunder damage.",
        "failquote" => "<b>%s</b> is Electrified but it is immune to Thunder damage!",
        "endquote" => "<b>%s</b> is no longer Electrified."
    ],
    7 => [
        "id" => 7,
        "name" => "Frozen",
        "type" => ConditionType::DoDamage,
        "variable" => DamageType::Cold,
        "minduration" => 3,
        "maxduration" => 6,
        "activequote" => "<b>%s</b> is Frozen! It takes <b>%s</b> Cold damage.",
        "failquote" => "<b>%s</b> is Frozen but it is immune to Cold damage!",
        "endquote" => "<b>%s</b> is no longer Frozen."
    ],
    8 => [
        "id" => 8,
        "name" => "Paralyzed",
        "type" => ConditionType::NoActions,
        "minduration" => 2,
        "maxduration" => 4,
        "activequote" => "<b>%s</b> is Paralyzed!",
        "endquote" => "<b>%s</b> is no longer Paralyzed."
    ],
    9 => [
        "id" => 9,
        "name" => "Poisoned",
        "type" => ConditionType::DoDamage,
        "variable" => DamageType::Earth,
        "minduration" => 3,
        "maxduration" => 6,
        "activequote" => "<b>%s</b> is Poisoned! It takes <b>%s</b> Earth damage.",
        "failquote" => "<b>%s</b> is Poisoned but it is immune to Earth damage!",
        "endquote" => "<b>%s</b> is no longer Poisoned."
    ],
    10 => [
        "id" => 10,
        "name" => "Silenced",
        "type" => ConditionType::NoSpells,
        "minduration" => 3,
        "maxduration" => 6,
        "activequote" => "<b>%s</b> is Silenced!",
        "endquote" => "<b>%s</b> is no longer Silenced."
    ],
    11 => [
        "id" => 11,
        "name" => "Petrified",
        "type" => ConditionType::NoActions,
        "minduration" => 2,
        "maxduration" => 4,
        "activequote" => "<b>%s</b> is Petrified!",
        "endquote" => "<b>%s</b> is no longer Petrified."
    ],
    12 => [
        "id" => 12,
        "name" => "Charmed",
        "type" => ConditionType::NoOffensiveActions,
        "minduration" => 3,
        "maxduration" => 6,
        "activequote" => "<b>%s</b> is Charmed!",
        "endquote" => "<b>%s</b> is no longer Charmed."
    ],
    13 => [
        "id" => 13,
        "name" => "Scared",
        "type" => ConditionType::NoActions,
        "minduration" => 2,
        "maxduration" => 4,
        "activequote" => "<b>%s</b> is Scared!",
        "endquote" => "<b>%s</b> is no longer Scared."
    ],
    14 => [
        "id" => 14,
        "name" => "Bleeding",
        "type" => ConditionType::DoDamage,
        "variable" => DamageType::Slashing,
        "minduration" => 3,
        "maxduration" => 6,
        "activequote" => "<b>%s</b> is Bleeding! It takes <b>%s</b> Slashing damage.",
        "failquote" => "<b>%s</b> is Bleeding but it is immune to Slashing damage!",
        "endquote" => "<b>%s</b> is no longer Bleeding."
    ],
    15 => [
        "id" => 15,
        "name" => "Bleeding",
        "type" => ConditionType::DoDamage,
        "variable" => DamageType::Piercing,
        "minduration" => 3,
        "maxduration" => 6,
        "activequote" => "<b>%s</b> is Bleeding! It takes <b>%s</b> Piercing damage.",
        "failquote" => "<b>%s</b> is Bleeding but it is immune to Piercing damage!",
        "endquote" => "<b>%s</b> is no longer Bleeding."
    ],
    16 => [
        "id" => 16,
        "name" => "Entangled",
        "type" => ConditionType::NoActions,
        "minduration" => 2,
        "maxduration" => 4,
        "activequote" => "<b>%s</b> is Entangled!",
        "endquote" => "<b>%s</b> is no longer Entangled."
    ],
];

$spells = [
    1 => [
        "id" => 1,
        "name" => "Acid Arrow",
        "type" => SpellType::DirectDamage,
        "variable" => DamageType::Earth,
        "variable2" => 2,
        "hitquote" => "<b>%s</b> cast <i>Acid Arrow</i> for <b>%s</b> Earth damage.",
        "missquote" => "<b>%s</b> cast <i>Acid Arrow</i> but <b>%s</b> successfully avoided the spell!",
        "failquote" => "<b>%s</b> cast <i>Acid Arrow</i> but <b>%s</b> is immune!"
    ],
    2 => [
        "id" => 2,
        "name" => "Bestow Curse",
        "type" => SpellType::CauseCondition,
        "variable" => 4,
        "hitquote" => "<b>%s</b> cast <i>Bestow Curse</i>. <b>%s</b> is now Cursed!",
        "missquote" => "<b>%s</b> cast <i>Bestow Curse</i>, but <b>%s</b> resisted and the spell failed!",
        "failquote" => "<b>%s</b> cast <i>Bestow Curse</i>, but <b>%s</b> is already Cursed!",
        "extraquote" => "<b>%s</b> cast <i>Bestow Curse</i>, but <b>%s</b> is immune!",
    ],
    3 => [
        "id" => 3,
        "name" => "Chill Touch",
        "type" => SpellType::CauseCondition,
        "variable" => 8,
        "hitquote" => "<b>%s</b> cast <i>Chill Touch</i>. <b>%s</b> is now Paralyzed!",
        "missquote" => "<b>%s</b> cast <i>Chill Touch</i>, but <b>%s</b> resisted and the spell failed!",
        "failquote" => "<b>%s</b> cast <i>Chill Touch</i>, but <b>%s</b> is already Cursed!",
        "extraquote" => "<b>%s</b> cast <i>Chill Touch</i>, but <b>%s</b> is immune!",
    ],
    4 => [
        "id" => 4,
        "name" => "Cold Burst",
        "type" => SpellType::DirectDamage,
        "variable" => DamageType::Cold,
        "variable2" => 2,
        "hitquote" => "<b>%s</b> cast <i>Cold Burst</i> for <b>%s</b> Cold damage.",
        "missquote" => "<b>%s</b> cast <i>Cold Burst</i> but <b>%s</b> successfully avoided the spell!",
        "failquote" => "<b>%s</b> cast <i>Cold Burst</i> but <b>%s</b> is immune!"
    ],
    5 => [
        "id" => 5,
        "name" => "Contagion",
        "type" => SpellType::CauseCondition,
        "variable" => 9,
        "hitquote" => "<b>%s</b> cast <i>Contagion</i>. <b>%s</b> is now Poisoned!",
        "missquote" => "<b>%s</b> cast <i>Contagion</i> but <b>%s</b> resisted and the spell failed!",
        "failquote" => "<b>%s</b> cast <i>Contagion</i>, but <b>%s</b> is already Poisoned!",
        "extraquote" => "<b>%s</b> cast <i>Contagion</i>, but <b>%s</b> is immune!",
    ],
    6 => [
        "id" => 6,
        "name" => "Cure Wounds",
        "type" => SpellType::Healing,
        "variable" => 0,
        "variable2" => 2,
        "hitquote" => "<b>%s</b> cast <i>Cure Wounds</i> and healed for <b>%s</b> HP.",
        "missquote" => "<b>%s</b> cast <i>Cure Wounds</i> but they're already full HP."
    ],
    7 => [
        "id" => 7,
        "name" => "Death Pact",
        "type" => SpellType::ActivateEffect,
        "variable" => 1,
        "hitquote" => "<b>%s</b> cast <i>Death Pact</i>. Death will spare them once!",
        "missquote" => "<b>%s</b> cast <i>Death Pact</i>, but they've failed having the Reaper answer their bidding!",
        "failquote" => "<b>%s</b> cast <i>Death Pact</i>, but the Reaper already fulfilled their request!"
    ],
    8 => [
        "id" => 8,
        "name" => "Deep Slumber",
        "type" => SpellType::CauseCondition,
        "variable" => 1,
        "hitquote" => "<b>%s</b> cast Deep <i>Slumber</i>. <b>%s</b> is now Asleep!",
        "missquote" => "<b>%s</b> cast Deep <i>Slumber</i>, but <b>%s</b> resisted and the spell failed!",
        "failquote" => "<b>%s</b> cast Deep <i>Slumber</i>, but <b>%s</b> is already Asleep!",
        "extraquote" => "<b>%s</b> cast Deep <i>Slumber</i>, but <b>%s</b> is immune!",
    ],
    9 => [
        "id" => 9,
        "name" => "Divine Punishment",
        "type" => SpellType::CauseCondition,
        "variable" => 5,
        "hitquote" => "<b>%s</b> cast <i>Divine Punishment</i>. <b>%s</b> is now Doomed!",
        "missquote" => "<b>%s</b> cast <i>Divine Punishment</i>, but <b>%s</b> resisted and the spell failed!",
        "failquote" => "<b>%s</b> cast <i>Divine Punishment</i>, but <b>%s</b> is already Doomed!",
        "extraquote" => "<b>%s</b> cast <i>Divine Punishment</i>, but <b>%s</b> is immune!",
    ],
    10 => [
        "id" => 10,
        "name" => "Fire Ball",
        "type" => SpellType::DirectDamage,
        "variable" => DamageType::Fire,
        "variable2" => 2,
        "hitquote" => "<b>%s</b> cast <i>Fire Ball</i> for <b>%s</b> Fire damage.",
        "missquote" => "<b>%s</b> cast <i>Fire Ball</i> but <b>%s</b> successfully avoided the spell!",
        "failquote" => "<b>%s</b> cast <i>Fire Ball</i> but <b>%s</b> is immune!"
    ],
    11 => [
        "id" => 11,
        "name" => "Life Drain",
        "type" => SpellType::Drain,
        "variable" => DamageType::Necrotic,
        "variable2" => 2,
        "hitquote" => "<b>%s</b> cast <i>Life Drain</i> for <b>%s</b> Necrotic damage and healed for <b>%s</b> HP.",
        "missquote" => "<b>%s</b> cast <i>Life Drain</i>, but <b>%s</b> resisted and the spell failed!",
        "failquote" => "<b>%s</b> cast <i>Life Drain</i> but <b>%s</b> is immune!"
    ],
    12 => [
        "id" => 12,
        "name" => "Lightning Bolt",
        "type" => SpellType::DirectDamage,
        "variable" => DamageType::Thunder,
        "variable2" => 2,
        "hitquote" => "<b>%s</b> cast <i>Lightning Bolt</i> for <b>%s</b> Thunder damage.",
        "missquote" => "<b>%s</b> cast <i>Lightning Bolt</i> but <b>%s</b> successfully avoided the spell!",
        "failquote" => "<b>%s</b> cast <i>Lightning Bolt</i> but <b>%s</b> is immune!"
    ],
    13 => [
        "id" => 13,
        "name" => "Mind Blast",
        "type" => SpellType::CauseCondition,
        "variable" => 3,
        "hitquote" => "<b>%s</b> cast <i>Mind Blast</i>. <b>%s</b> is now Confused!",
        "missquote" => "<b>%s</b> cast <i>Mind Blast</i>, but <b>%s</b> resisted and the spell failed!",
        "failquote" => "<b>%s</b> cast <i>Mind Blast</i>, but <b>%s</b> is already Confused!",
        "extraquote" => "<b>%s</b> cast <i>Mind Blast</i>, but <b>%s</b> is immune!",
    ],
    14 => [
        "id" => 14,
        "name" => "Necrotic Bolt",
        "type" => SpellType::DirectDamage,
        "variable" => DamageType::Necrotic,
        "variable2" => 2,
        "hitquote" => "<b>%s</b> cast <i>Necrotic Bolt</i> for <b>%s</b> Necrotic damage.",
        "missquote" => "<b>%s</b> cast <i>Necrotic Bolt</i> but <b>%s</b> successfully avoided the spell!",
        "failquote" => "<b>%s</b> cast <i>Necrotic Bolt</i> but <b>%s</b> is immune!"
    ],
    15 => [
        "id" => 15,
        "name" => "Psybolt",
        "type" => SpellType::DirectDamage,
        "variable" => DamageType::Psychic,
        "variable2" => 2,
        "hitquote" => "<b>%s</b> cast <i>Psybolt</i> for <b>%s</b> Psychic damage.",
        "missquote" => "<b>%s</b> cast <i>Psybolt</i> but <b>%s</b> successfully avoided the spell!",
        "failquote" => "<b>%s</b> cast <i>Psybolt</i> but <b>%s</b> is immune!"
    ],
    16 => [
        "id" => 16,
        "name" => "Ray of Frost",
        "type" => SpellType::CauseCondition,
        "variable" => 7,
        "hitquote" => "<b>%s</b> cast <i>Ray of Frost</i>. <b>%s</b> is now Frozen!",
        "missquote" => "<b>%s</b> cast <i>Ray of Frost</i>, but <b>%s</b> resisted and the spell failed!",
        "failquote" => "<b>%s</b> cast <i>Ray of Frost</i>, but <b>%s</b> is already Frozen!",
        "extraquote" => "<b>%s</b> cast <i>Ray of Frost</i>, but <b>%s</b> is immune!",
    ],
    17 => [
        "id" => 17,
        "name" => "Scorching Ray",
        "type" => SpellType::CauseCondition,
        "variable" => 2,
        "hitquote" => "<b>%s</b> cast <i>Scorching Ray</i>. <b>%s</b> is now Burned!",
        "missquote" => "<b>%s</b> cast <i>Scorching Ray</i>, but <b>%s</b> resisted and the spell failed!",
        "failquote" => "<b>%s</b> cast <i>Scorching Ray</i>, but <b>%s</b> is already Burned!",
        "extraquote" => "<b>%s</b> cast <i>Scorching Ray</i>, but <b>%s</b> is immune!",
    ],
    18 => [
        "id" => 18,
        "name" => "Shocking Touch",
        "type" => SpellType::CauseCondition,
        "variable" => 6,
        "hitquote" => "<b>%s</b> cast <i>Shocking Touch</i>. <b>%s</b> is now Electrified!",
        "missquote" => "<b>%s</b> cast <i>Shocking Touch</i>, but <b>%s</b> resisted and the spell failed!",
        "failquote" => "<b>%s</b> cast <i>Shocking Touch</i>, but <b>%s</b> is already Electrified!",
        "extraquote" => "<b>%s</b> cast <i>Shocking Touch</i>, but <b>%s</b> is immune!",
    ],
    19 => [
        "id" => 19,
        "name" => "Sun Bolt",
        "type" => SpellType::DirectDamage,
        "variable" => DamageType::Radiant,
        "variable2" => 2,
        "hitquote" => "<b>%s</b> cast <i>Sun Bolt</i> for <b>%s</b> Radiant damage.",
        "missquote" => "<b>%s</b> cast <i>Sun Bolt</i> but <b>%s</b> successfully avoided the spell!",
        "failquote" => "<b>%s</b> cast <i>Sun Bolt</i> but <b>%s</b> is immune!"
    ],
    20 => [
        "id" => 20,
        "name" => "Silence",
        "type" => SpellType::CauseCondition,
        "variable" => 10,
        "hitquote" => "<b>%s</b> cast <i>Silence</i>. <b>%s</b> is now Silenced!",
        "missquote" => "<b>%s</b> cast <i>Silence</i>, but <b>%s</b> resisted and the spell failed!",
        "failquote" => "<b>%s</b> cast <i>Silence</i>, but <b>%s</b> is already Silenced!",
        "extraquote" => "<b>%s</b> cast <i>Silence</i>, but <b>%s</b> is immune!",
    ],
    21 => [
        "id" => 21,
        "name" => "Flesh to Stone",
        "type" => SpellType::CauseCondition,
        "variable" => 11,
        "hitquote" => "<b>%s</b> cast <i>Flesh to Stone</i>. <b>%s</b> is now Petrified!",
        "missquote" => "<b>%s</b> cast <i>Flesh to Stone</i>, but <b>%s</b> resisted and the spell failed!",
        "failquote" => "<b>%s</b> cast <i>Flesh to Stone</i>, but <b>%s</b> is already Petrified!",
        "extraquote" => "<b>%s</b> cast <i>Flesh to Stone</i>, but <b>%s</b> is immune!",
    ],
    22 => [
        "id" => 22,
        "name" => "Charm",
        "type" => SpellType::CauseCondition,
        "variable" => 12,
        "hitquote" => "<b>%s</b> cast <i>Charm</i>. <b>%s</b> is now Charmed!",
        "missquote" => "<b>%s</b> cast <i>Charm</i>, but <b>%s</b> resisted and the spell failed!",
        "failquote" => "<b>%s</b> cast <i>Charm</i>, but <b>%s</b> is already Charmed!",
        "extraquote" => "<b>%s</b> cast <i>Charm</i>, but <b>%s</b> is immune!",
    ],
    23 => [
        "id" => 23,
        "name" => "Cause Fear",
        "type" => SpellType::CauseCondition,
        "variable" => 13,
        "hitquote" => "<b>%s</b> cast <i>Cause Fear</i>. <b>%s</b> is now Scared!",
        "missquote" => "<b>%s</b> cast <i>Cause Fear</i>, but <b>%s</b> resisted and the spell failed!",
        "failquote" => "<b>%s</b> cast <i>Cause Fear</i>, but <b>%s</b> is already Scared!",
        "extraquote" => "<b>%s</b> cast <i>Cause Fear</i>, but <b>%s</b> is immune!",
    ],
    24 => [
        "id" => 24,
        "name" => "Living Vines",
        "type" => SpellType::CauseCondition,
        "variable" => 16,
        "hitquote" => "<b>%s</b> cast <i>Living Vines</i>. <b>%s</b> is now Entangled!",
        "missquote" => "<b>%s</b> cast <i>Living Vines</i>, but <b>%s</b> resisted and the spell failed!",
        "failquote" => "<b>%s</b> cast <i>Living Vines</i>, but <b>%s</b> is already Entangled!",
        "extraquote" => "<b>%s</b> cast <i>Living Vines</i>, but <b>%s</b> is immune!",
    ],
];

$items = [
    1 => [
        "id" => 1,
        "name" => "Dagger",
        "type" => ItemType::Weapon,
        "damage" => 1,
        "armor" => 0,
        "attack" => 1,
        "defense" => 0,
        "initiative" => 0,
        "damagetype" => DamageType::Piercing,
        "enchantments" => []
    ],
    2 => [
        "id" => 2,
        "name" => "Shortsword",
        "type" => ItemType::Weapon,  
        "damage" => 2,
        "armor" => 0,
        "attack" => 1,
        "defense" => 0,
        "initiative" => 0,
        "damagetype" => DamageType::Piercing,
        "enchantments" => []
    ],
    3 => [
        "id" => 3,
        "name" => "Rapier",
        "type" => ItemType::Weapon,  
        "damage" => 3,
        "armor" => 0,
        "attack" => 0,
        "defense" => 0,
        "initiative" => 0,
        "damagetype" => DamageType::Piercing,
        "enchantments" => []
    ],
    4 => [
        "id" => 4,
        "name" => "Longsword",
        "type" => ItemType::Weapon,  
        "damage" => 4,
        "armor" => 0,
        "attack" => 0,
        "defense" => 0,
        "initiative" => 0,
        "damagetype" => DamageType::Slashing,
        "enchantments" => []
    ],
    5 => [
        "id" => 5,
        "name" => "Greatsword",
        "type" => ItemType::Weapon,  
        "damage" => 5,
        "armor" => 0,
        "attack" => 0,
        "defense" => 0,
        "initiative" => 0,
        "damagetype" => DamageType::Slashing,
        "enchantments" => []
    ],
    6 => [
        "id" => 6,
        "name" => "Greataxe",
        "type" => ItemType::Weapon,  
        "damage" => 6,
        "armor" => 0,
        "attack" => 0,
        "defense" => 0,
        "initiative" => 0,
        "damagetype" => DamageType::Slashing,
        "enchantments" => []
    ],
    7 => [
        "id" => 7,
        "name" => "Buckler",
        "type" => ItemType::Shield,  
        "damage" => 0,
        "armor" => 1,
        "attack" => 0,
        "defense" => 0,
        "initiative" => 0,
        "enchantments" => []
    ],
    8 => [
        "id" => 8,
        "name" => "Small Shield",
        "type" => ItemType::Shield,  
        "damage" => 0,
        "armor" => 2,
        "attack" => 0,
        "defense" => 0,
        "initiative" => -2,
        "enchantments" => []
    ],
    9 => [
        "id" => 9,
        "name" => "Large Shield",
        "type" => ItemType::Shield,  
        "damage" => 0,
        "armor" => 3,
        "attack" => 0,
        "defense" => 0,
        "initiative" => -3,
        "enchantments" => []
    ],
    10 => [
        "id" => 10,
        "name" => "Leather Armor",
        "type" => ItemType::Armor,  
        "damage" => 0,
        "armor" => 1,
        "attack" => 0,
        "defense" => 0,
        "initiative" => 0,
        "enchantments" => []
    ],
    11 => [
        "id" => 11,
        "name" => "Hide Armor",
        "type" => ItemType::Armor,  
        "damage" => 0,
        "armor" => 2,
        "attack" => 0,
        "defense" => -1,
        "initiative" => -2,
        "enchantments" => []
    ],
    12 => [
        "id" => 12,
        "name" => "Chainmail",
        "type" => ItemType::Armor,  
        "damage" => 0,
        "armor" => 3,
        "attack" => 0,
        "defense" => -1,
        "initiative" => -2,
        "enchantments" => []
    ],
    13 => [
        "id" => 13,
        "name" => "Half-Plate",
        "type" => ItemType::Armor,  
        "damage" => 0,
        "armor" => 4,
        "attack" => 0,
        "defense" => -2,
        "initiative" => -3,
        "enchantments" => []
    ],
    14 => [
        "id" => 14,
        "name" => "Full Plate",
        "type" => ItemType::Armor,  
        "damage" => 0,
        "armor" => 5,
        "attack" => 0,
        "defense" => -2,
        "initiative" => -3,
        "enchantments" => []
    ],
    15 => [
        "id" => 15,
        "name" => "Flaming Mace",
        "type" => ItemType::Weapon,  
        "damage" => 4,
        "armor" => 0,
        "attack" => 0,
        "defense" => 0,
        "initiative" => 0,
        "damagetype" => DamageType::Bludgeoning,
        "enchantments" => [30]
    ],
    16 => [
        "id" => 16,
        "name" => "Flaming Greatsword",
        "type" => ItemType::Weapon,  
        "damage" => 6,
        "armor" => 0,
        "attack" => 0,
        "defense" => 0,
        "initiative" => 0,
        "damagetype" => DamageType::Slashing,
        "enchantments" => [30]
    ],
    17 => [
        "id" => 17,
        "name" => "Poisonous Bite",
        "type" => ItemType::Weapon,  
        "damage" => 3,
        "armor" => 0,
        "attack" => 1,
        "defense" => 0,
        "initiative" => 0,
        "damagetype" => DamageType::Piercing,
        "enchantments" => [33]
    ],
    18 => [
        "id" => 18,
        "name" => "Bite",
        "type" => ItemType::Weapon,  
        "damage" => 2,
        "armor" => 0,
        "attack" => 1,
        "defense" => 0,
        "initiative" => 0,
        "damagetype" => DamageType::Piercing,
        "enchantments" => []
    ],
    19 => [
        "id" => 19,
        "name" => "Mace",
        "type" => ItemType::Weapon,  
        "damage" => 3,
        "armor" => 0,
        "attack" => 0,
        "defense" => 0,
        "initiative" => 0,
        "damagetype" => DamageType::Bludgeoning,
        "enchantments" => []
    ],
    20 => [
        "id" => 20,
        "name" => "Spear",
        "type" => ItemType::Weapon,  
        "damage" => 3,
        "armor" => 0,
        "attack" => 0,
        "defense" => 0,
        "initiative" => 0,
        "damagetype" => DamageType::Piercing,
        "enchantments" => []
    ],
    21 => [
        "id" => 21,
        "name" => "Greatclub",
        "type" => ItemType::Weapon,  
        "damage" => 6,
        "armor" => 0,
        "attack" => 0,
        "defense" => 0,
        "initiative" => 0,
        "damagetype" => DamageType::Bludgeoning,
        "enchantments" => []
    ],
    22 => [
        "id" => 22,
        "name" => "Cursed Longsword",
        "type" => ItemType::Weapon,  
        "damage" => 4,
        "armor" => 0,
        "attack" => 0,
        "defense" => 0,
        "initiative" => 0,
        "damagetype" => DamageType::Slashing,
        "enchantments" => [35]
    ],
    23 => [
        "id" => 23,
        "name" => "Electrifying Sword",
        "type" => ItemType::Weapon,  
        "damage" => 4,
        "armor" => 0,
        "attack" => 0,
        "defense" => 0,
        "initiative" => 0,
        "damagetype" => DamageType::Slashing,
        "enchantments" => [32]
    ],
    24 => [
        "id" => 24,
        "name" => "Flaming Whip",
        "type" => ItemType::Weapon,  
        "damage" => 4,
        "armor" => 0,
        "attack" => 0,
        "defense" => 0,
        "initiative" => 0,
        "damagetype" => DamageType::Slashing,
        "enchantments" => [30]
    ],
    25 => [
        "id" => 25,
        "name" => "Claw",
        "type" => ItemType::Weapon,  
        "damage" => 2,
        "armor" => 0,
        "attack" => 1,
        "defense" => 0,
        "initiative" => 0,
        "damagetype" => DamageType::Slashing,
        "enchantments" => []
    ],
    26 => [
        "id" => 26,
        "name" => "Fork",
        "type" => ItemType::Weapon,  
        "damage" => 4,
        "armor" => 0,
        "attack" => 0,
        "defense" => 0,
        "initiative" => 0,
        "damagetype" => DamageType::Piercing,
        "enchantments" => []
    ],
    27 => [
        "id" => 27,
        "name" => "Claw",
        "type" => ItemType::Weapon,  
        "damage" => 4,
        "armor" => 0,
        "attack" => 0,
        "defense" => 0,
        "initiative" => 0,
        "damagetype" => DamageType::Slashing,
        "enchantments" => []
    ],
    28 => [
        "id" => 28,
        "name" => "Bite",
        "type" => ItemType::Weapon,  
        "damage" => 4,
        "armor" => 0,
        "attack" => 0,
        "defense" => 0,
        "initiative" => 0,
        "damagetype" => DamageType::Piercing,
        "enchantments" => []
    ],
    29 => [
        "id" => 29,
        "name" => "Slam",
        "type" => ItemType::Weapon,  
        "damage" => 3,
        "armor" => 0,
        "attack" => 0,
        "defense" => 0,
        "initiative" => 0,
        "damagetype" => DamageType::Bludgeoning,
        "enchantments" => []
    ],
    30 => [
        "id" => 30,
        "name" => "Natural Armor",
        "type" => ItemType::Armor,  
        "damage" => 0,
        "armor" => 2,
        "attack" => 0,
        "defense" => 0,
        "initiative" => 0,
        "enchantments" => []
    ],
    31 => [
        "id" => 31,
        "name" => "Staff",
        "type" => ItemType::Weapon,  
        "damage" => 3,
        "armor" => 0,
        "attack" => 0,
        "defense" => 0,
        "initiative" => 0,
        "damagetype" => DamageType::Bludgeoning,
        "enchantments" => []
    ],
    32 => [
        "id" => 32,
        "name" => "Poisoned Mace",
        "type" => ItemType::Weapon,  
        "damage" => 3,
        "armor" => 0,
        "attack" => 0,
        "defense" => 0,
        "initiative" => 0,
        "damagetype" => DamageType::Bludgeoning,
        "enchantments" => [33]
    ],
    33 => [
        "id" => 33,
        "name" => "Poisoned Shortsword",
        "type" => ItemType::Weapon,  
        "damage" => 2,
        "armor" => 0,
        "attack" => 1,
        "defense" => 0,
        "initiative" => 0,
        "damagetype" => DamageType::Piercing,
        "enchantments" => [33]
    ],
    34 => [
        "id" => 34,
        "name" => "Battleaxe",
        "type" => ItemType::Weapon,  
        "damage" => 4,
        "armor" => 0,
        "attack" => 0,
        "defense" => 0,
        "initiative" => 0,
        "damagetype" => DamageType::Slashing,
        "enchantments" => []
    ],
    35 => [
        "id" => 35,
        "name" => "Flaming Shortsword",
        "type" => ItemType::Weapon,  
        "damage" => 2,
        "armor" => 0,
        "attack" => 1,
        "defense" => 0,
        "initiative" => 0,
        "damagetype" => DamageType::Piercing,
        "enchantments" => [30]
    ],
    36 => [
        "id" => 36,
        "name" => "Frost Shortsword",
        "type" => ItemType::Weapon,  
        "damage" => 2,
        "armor" => 0,
        "attack" => 1,
        "defense" => 0,
        "initiative" => 0,
        "damagetype" => DamageType::Piercing,
        "enchantments" => [31]
    ],
    37 => [
        "id" => 37,
        "name" => "Natural Armor",
        "type" => ItemType::Armor,  
        "damage" => 0,
        "armor" => 4,
        "attack" => 0,
        "defense" => 0,
        "initiative" => 0,
        "enchantments" => []
    ],
    38 => [
        "id" => 38,
        "name" => "Vampiric Bite",
        "type" => ItemType::Weapon,  
        "damage" => 3,
        "armor" => 0,
        "attack" => 1,
        "defense" => 0,
        "initiative" => 0,
        "damagetype" => DamageType::Piercing,
        "enchantments" => [35]
    ],
    39 => [
        "id" => 39,
        "name" => "Corrupting Touch",
        "type" => ItemType::Weapon,  
        "damage" => 2,
        "armor" => 0,
        "attack" => 1,
        "defense" => 0,
        "initiative" => 0,
        "damagetype" => DamageType::Necrotic,
        "enchantments" => []
    ],
    40 => [
        "id" => 40,
        "name" => "Vampiric Rapier",
        "type" => ItemType::Weapon,  
        "damage" => 3,
        "armor" => 0,
        "attack" => 1,
        "defense" => 0,
        "initiative" => 0,
        "damagetype" => DamageType::Piercing,
        "enchantments" => [37]
    ],
];

$enchantments = [
    1 => [
        "id" => 1,
        "name" => "Lesser Might Bonus",
        "type" => EnchantmentType::AttributeBonus,
        "variable" => "might",
        "variable2" => 1
    ],
    2 => [
        "id" => 2,
        "name" => "Lesser Power Bonus",
        "type" => EnchantmentType::AttributeBonus,
        "variable" => "power",
        "variable2" => 1
    ],
    3 => [
        "id" => 3,
        "name" => "Lesser Speed Bonus",
        "type" => EnchantmentType::AttributeBonus,
        "variable" => "speed",
        "variable2" => 1
    ],
    4 => [
        "id" => 4,
        "name" => "Lesser Mind Bonus",
        "type" => EnchantmentType::AttributeBonus,
        "variable" => "mind",
        "variable2" => 1
    ],
    5 => [
        "id" => 5,
        "name" => "Lesser HP Bonus",
        "type" => EnchantmentType::AttributeBonus,
        "variable" => "maxhp",
        "variable2" => 5
    ],
    6 => [
        "id" => 6,
        "name" => "Tireless",
        "type" => EnchantmentType::ConditionImmunity,
        "variable" => 1,
        "variable2" => 0
    ],
    7 => [
        "id" => 7,
        "name" => "Chilled",
        "type" => EnchantmentType::ConditionImmunity,
        "variable" => 2,
        "variable2" => 0
    ],
    8 => [
        "id" => 8,
        "name" => "Still Mind",
        "type" => EnchantmentType::ConditionImmunity,
        "variable" => 3,
        "variable2" => 0
    ],
    9 => [
        "id" => 9,
        "name" => "Blessed",
        "type" => EnchantmentType::ConditionImmunity,
        "variable" => 4,
        "variable2" => 0
    ],
    10 => [
        "id" => 10,
        "name" => "Favorable",
        "type" => EnchantmentType::ConditionImmunity,
        "variable" => 5,
        "variable2" => 0
    ],
    11 => [
        "id" => 11,
        "name" => "Grounded",
        "type" => EnchantmentType::ConditionImmunity,
        "variable" => 6,
        "variable2" => 0
    ],
    12 => [
        "id" => 12,
        "name" => "Blazing",
        "type" => EnchantmentType::ConditionImmunity,
        "variable" => 7,
        "variable2" => 0
    ],
    13 => [
        "id" => 13,
        "name" => "Freedom",
        "type" => EnchantmentType::ConditionImmunity,
        "variable" => 8,
        "variable2" => 0
    ],
    14 => [
        "id" => 14,
        "name" => "Purity",
        "type" => EnchantmentType::ConditionImmunity,
        "variable" => 9,
        "variable2" => 0
    ],
    15 => [
        "id" => 15,
        "name" => "Superior Voice",
        "type" => EnchantmentType::ConditionImmunity,
        "variable" => 10,
        "variable2" => 0
    ],
    16 => [
        "id" => 16,
        "name" => "Firewarded",
        "type" => EnchantmentType::DamageResistance,
        "variable" => DamageType::Fire,
        "variable2" => 2
    ],
    17 => [
        "id" => 17,
        "name" => "Icewarded",
        "type" => EnchantmentType::DamageResistance,
        "variable" => DamageType::Cold,
        "variable2" => 2
    ],
    18 => [
        "id" => 18,
        "name" => "Airwarded",
        "type" => EnchantmentType::DamageResistance,
        "variable" => DamageType::Thunder,
        "variable2" => 2
    ],
    19 => [
        "id" => 19,
        "name" => "Earthwarded",
        "type" => EnchantmentType::DamageResistance,
        "variable" => DamageType::Earth,
        "variable2" => 2
    ],
    20 => [
        "id" => 20,
        "name" => "Lightwarded",
        "type" => EnchantmentType::DamageResistance,
        "variable" => DamageType::Radiant,
        "variable2" => 2
    ],
    21 => [
        "id" => 21,
        "name" => "Deathwarded",
        "type" => EnchantmentType::DamageResistance,
        "variable" => DamageType::Necrotic,
        "variable2" => 2
    ],
    22 => [
        "id" => 22,
        "name" => "Mindwarded",
        "type" => EnchantmentType::DamageResistance,
        "variable" => DamageType::Psychic,
        "variable2" => 2
    ],
    23 => [
        "id" => 23,
        "name" => "Firesealed",
        "type" => EnchantmentType::DamageImmunity,
        "variable" => DamageType::Fire,
        "variable2" => 0
    ],
    24 => [
        "id" => 24,
        "name" => "Icesealed",
        "type" => EnchantmentType::DamageImmunity,
        "variable" => DamageType::Cold,
        "variable2" => 0
    ],
    25 => [
        "id" => 25,
        "name" => "Thundersealed",
        "type" => EnchantmentType::DamageImmunity,
        "variable" => DamageType::Thunder,
        "variable2" => 0
    ],
    26 => [
        "id" => 26,
        "name" => "Earthsealed",
        "type" => EnchantmentType::DamageImmunity,
        "variable" => DamageType::Earth,
        "variable2" => 0
    ],
    27 => [
        "id" => 27,
        "name" => "Lightsealed",
        "type" => EnchantmentType::DamageImmunity,
        "variable" => DamageType::Radiant,
        "variable2" => 0
    ],
    28 => [
        "id" => 28,
        "name" => "Deathsealed",
        "type" => EnchantmentType::DamageImmunity,
        "variable" => DamageType::Necrotic,
        "variable2" => 0
    ],
    29 => [
        "id" => 29,
        "name" => "Mindsealed",
        "type" => EnchantmentType::DamageImmunity,
        "variable" => DamageType::Psychic,
        "variable2" => 0
    ],
    30 => [
        "id" => 30,
        "name" => "Flaming",
        "type" => EnchantmentType::WeaponImbuement,
        "variable" => DamageType::Fire,
        "variable2" => 0
    ],
    31 => [
        "id" => 31,
        "name" => "Frozen",
        "type" => EnchantmentType::WeaponImbuement,
        "variable" => DamageType::Cold,
        "variable2" => 0
    ],
    32 => [
        "id" => 32,
        "name" => "Electrifying",
        "type" => EnchantmentType::WeaponImbuement,
        "variable" => DamageType::Thunder,
        "variable2" => 0
    ],
    33 => [
        "id" => 33,
        "name" => "Earth Attuned",
        "type" => EnchantmentType::WeaponImbuement,
        "variable" => DamageType::Earth,
        "variable2" => 0
    ],
    34 => [
        "id" => 34,
        "name" => "Coruscating",
        "type" => EnchantmentType::WeaponImbuement,
        "variable" => DamageType::Radiant,
        "variable2" => 0
    ],
    35 => [
        "id" => 35,
        "name" => "Necromantic",
        "type" => EnchantmentType::WeaponImbuement,
        "variable" => DamageType::Necrotic,
        "variable2" => 0
    ],
    36 => [
        "id" => 36,
        "name" => "Mystic",
        "type" => EnchantmentType::WeaponImbuement,
        "variable" => DamageType::Psychic,
        "variable2" => 0
    ],
    37 => [
        "id" => 37,
        "name" => "Vampiric",
        "type" => EnchantmentType::WeaponDrain,
        "variable" => 10,
        "variable2" => 0
    ]
];