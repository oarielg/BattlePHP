<?php

// This just represents a REAL database/methods

enum MonsterClass
{
    case Fighter;
    case Caster;
    case Hybrid;
}

class MonsterData
{
    public static function get_monster():array
    {
        $monster = [];
        if (isset($_SESSION['monster']))
        {
            $monster_data = unserialize($_SESSION['monster']);
            $monster = self::get_monster_from_list($monster_data['id']);
            $monster['attributes']['currenthp'] = $monster_data['currenthp'];
            $monster['conditions'] = $monster_data['conditions'];
            $monster['effects'] = $monster_data['effects'];
        }
        else
        {
            $monster = self::generate_monster();
        }

        return $monster;
    }

    public static function save_monster(array $monster_data):void
    {
        $_SESSION['monster'] = serialize($monster_data);
    }

    public static function get_monster_from_list(int $monster_id):array
    {
        global $monsters;
        return $monsters[$monster_id];
    }

    public static function generate_monster():array
    {
        global $monsters;

        // easy way to fight the exact monster I want by just passing the id by GET
        $id = isset($_GET['id']) ? $_GET['id'] : array_rand($monsters);
        $monster = $monsters[$id];

        return $monster;
    }
}

$monsters = [
    1 => [
        "id" => 1,
        "name" => "Dummy",
        "attributes" => [
            "currenthp" => 25,
            "maxhp" => 25,
            "might" => 1,
            "power" => 1,
            "speed" => 1,
            "mind" => 1
        ],
        "items" => [0,0,0,0],
        "spells" => [],
        "resistances" => [],
        "conditions" => [],
        "damimmunities" => [],
        "vulnerabilities" => [],
        "condimmunities" => [],
        "effects" => [],
        "class" => MonsterClass::Fighter
    ],
    2 => [
        "id" => 2,
        "name" => "Deva Angel",
        "attributes" => [
            "currenthp" => 40,
            "maxhp" => 40,
            "might" => 4,
            "power" => 3,
            "speed" => 4,
            "mind" => 3
        ],
        "items" => [15,0,0,0],
        "spells" => [6],
        "resistances" => [[DamageType::Radiant,2]],
        "conditions" => [],
        "damimmunities" => [DamageType::Thunder],
        "vulnerabilities" => [],
        "condimmunities" => [9, 11],
        "effects" => [],
        "class" => MonsterClass::Hybrid
    ],
    3 => [
        "id" => 3,
        "name" => "Planetar Angel",
        "attributes" => [
            "currenthp" => 45,
            "maxhp" => 45,
            "might" => 5,
            "power" => 4,
            "speed" => 4,
            "mind" => 3
        ],
        "items" => [16,0,0,0],
        "spells" => [6,7,19],
        "resistances" => [[DamageType::Radiant,2]],
        "conditions" => [],
        "damimmunities" => [DamageType::Thunder],
        "vulnerabilities" => [],
        "condimmunities" => [9,11],
        "effects" => [],
        "class" => MonsterClass::Hybrid
    ],
    4 => [
        "id" => 4,
        "name" => "Solar Angel",
        "attributes" => [
            "currenthp" => 50,
            "maxhp" => 50,
            "might" => 6,
            "power" => 5,
            "speed" => 5,
            "mind" => 5
        ],
        "items" => [16,0,0,0],
        "spells" => [6,7,9,19],
        "resistances" => [[DamageType::Radiant,3]],
        "conditions" => [],
        "damimmunities" => [DamageType::Thunder,DamageType::Earth,DamageType::Necrotic],
        "vulnerabilities" => [],
        "condimmunities" => [9,11],
        "effects" => [],
        "class" => MonsterClass::Hybrid
    ],
    5 => [
        "id" => 5,
        "name" => "Banshee",
        "attributes" => [
            "currenthp" => 25,
            "maxhp" => 25,
            "might" => 1,
            "power" => 2,
            "speed" => 3,
            "mind" => 2
        ],
        "items" => [39,0,0,0],
        "spells" => [11,13,14],
        "resistances" => [[DamageType::Fire,2],[DamageType::Cold,2],[DamageType::Thunder,2],[DamageType::Earth,2]],
        "conditions" => [],
        "damimmunities" => [DamageType::Cold,DamageType::Necrotic],
        "vulnerabilities" => [],
        "condimmunities" => [1,4,8,9,11,14,15],
        "effects" => [],
        "class" => MonsterClass::Caster
    ],
    6 => [
        "id" => 6,
        "name" => "Basilisk",
        "attributes" => [
            "currenthp" => 30,
            "maxhp" => 30,
            "might" => 2,
            "power" => 2,
            "speed" => 1,
            "mind" => 1
        ],
        "items" => [17,0,0,0],
        "spells" => [21],
        "resistances" => [],
        "conditions" => [],
        "damimmunities" => [],
        "vulnerabilities" => [],
        "condimmunities" => [],
        "effects" => [],
        "class" => MonsterClass::Hybrid
    ],
    7 => [
        "id" => 7,
        "name" => "Beholder",
        "attributes" => [
            "currenthp" => 35,
            "maxhp" => 35,
            "might" => 3,
            "power" => 3,
            "speed" => 1,
            "mind" => 3
        ],
        "items" => [18,0,0,0],
        "spells" => [3,8,14,20,21],
        "resistances" => [],
        "conditions" => [],
        "damimmunities" => [],
        "vulnerabilities" => [],
        "condimmunities" => [],
        "effects" => [],
        "class" => MonsterClass::Caster
    ],
    8 => [
        "id" => 8,
        "name" => "Bugbear",
        "attributes" => [
            "currenthp" => 25,
            "maxhp" => 25,
            "might" => 2,
            "power" => 1,
            "speed" => 2,
            "mind" => 1
        ],
        "items" => [19,10,0,0],
        "spells" => [],
        "resistances" => [],
        "conditions" => [],
        "damimmunities" => [],
        "vulnerabilities" => [],
        "condimmunities" => [],
        "effects" => [],
        "class" => MonsterClass::Fighter
    ],
    9 => [
        "id" => 9,
        "name" => "Bullywug",
        "attributes" => [
            "currenthp" => 20,
            "maxhp" => 20,
            "might" => 1,
            "power" => 1,
            "speed" => 1,
            "mind" => 1
        ],
        "items" => [20,0,0,0],
        "spells" => [],
        "resistances" => [],
        "conditions" => [],
        "damimmunities" => [],
        "vulnerabilities" => [],
        "condimmunities" => [],
        "effects" => [],
        "class" => MonsterClass::Fighter
    ],
    10 => [
        "id" => 10,
        "name" => "Cambion",
        "attributes" => [
            "currenthp" => 25,
            "maxhp" => 25,
            "might" => 2,
            "power" => 2,
            "speed" => 1,
            "mind" => 1
        ],
        "items" => [20,0,0,0],
        "spells" => [10],
        "resistances" => [[DamageType::Fire,2],[DamageType::Cold,2],[DamageType::Thunder,2],[DamageType::Earth,2]],
        "conditions" => [],
        "damimmunities" => [],
        "vulnerabilities" => [],
        "condimmunities" => [],
        "effects" => [],
        "class" => MonsterClass::Hybrid
    ],
    11 => [
        "id" => 11,
        "name" => "Chimera",
        "attributes" => [
            "currenthp" => 35,
            "maxhp" => 35,
            "might" => 3,
            "power" => 2,
            "speed" => 2,
            "mind" => 1
        ],
        "items" => [18,0,0,0],
        "spells" => [10],
        "resistances" => [],
        "conditions" => [],
        "damimmunities" => [],
        "vulnerabilities" => [],
        "condimmunities" => [],
        "effects" => [],
        "class" => MonsterClass::Hybrid
    ],
    12 => [
        "id" => 12,
        "name" => "Couatl",
        "attributes" => [
            "currenthp" => 35,
            "maxhp" => 35,
            "might" => 1,
            "power" => 4,
            "speed" => 4,
            "mind" => 4
        ],
        "items" => [18,0,0,0],
        "spells" => [6,13,15],
        "resistances" => [[DamageType::Radiant,2]],
        "conditions" => [],
        "damimmunities" => [DamageType::Psychic],
        "vulnerabilities" => [],
        "condimmunities" => [3],
        "effects" => [],
        "class" => MonsterClass::Hybrid
    ],
    13 => [
        "id" => 13,
        "name" => "Cyclops",
        "attributes" => [
            "currenthp" => 45,
            "maxhp" => 45,
            "might" => 5,
            "power" => 1,
            "speed" => 3,
            "mind" => 1
        ],
        "items" => [21,0,0,0],
        "spells" => [],
        "resistances" => [],
        "conditions" => [],
        "damimmunities" => [],
        "vulnerabilities" => [],
        "condimmunities" => [],
        "effects" => [],
        "class" => MonsterClass::Fighter
    ],
    14 => [
        "id" => 14,
        "name" => "Death Knight",
        "attributes" => [
            "currenthp" => 40,
            "maxhp" => 40,
            "might" => 4,
            "power" => 3,
            "speed" => 3,
            "mind" => 2
        ],
        "items" => [22,0,14,0],
        "spells" => [3,10],
        "resistances" => [],
        "conditions" => [],
        "damimmunities" => [DamageType::Earth,DamageType::Necrotic],
        "vulnerabilities" => [],
        "condimmunities" => [4,9],
        "effects" => [],
        "class" => MonsterClass::Hybrid
    ],
    15 => [
        "id" => 15,
        "name" => "Demilich",
        "attributes" => [
            "currenthp" => 30,
            "maxhp" => 30,
            "might" => 1,
            "power" => 4,
            "speed" => 5,
            "mind" => 4
        ],
        "items" => [18,0,0,0],
        "spells" => [2,11,14],
        "resistances" => [],
        "conditions" => [],
        "damimmunities" => [DamageType::Earth,DamageType::Necrotic,DamageType::Psychic],
        "vulnerabilities" => [],
        "condimmunities" => [4,8,9,11],
        "effects" => [],
        "class" => MonsterClass::Caster
    ],
    16 => [
        "id" => 16,
        "name" => "Balor Demon",
        "attributes" => [
            "currenthp" => 45,
            "maxhp" => 45,
            "might" => 5,
            "power" => 5,
            "speed" => 4,
            "mind" => 4
        ],
        "items" => [23,24,0,0],
        "spells" => [],
        "resistances" => [[DamageType::Cold,2],[DamageType::Thunder,2]],
        "conditions" => [],
        "damimmunities" => [DamageType::Fire,DamageType::Earth],
        "vulnerabilities" => [],
        "condimmunities" => [2,9],
        "effects" => [],
        "class" => MonsterClass::Fighter
    ],
    17 => [
        "id" => 17,
        "name" => "Hezrou Demon",
        "attributes" => [
            "currenthp" => 35,
            "maxhp" => 35,
            "might" => 3,
            "power" => 2,
            "speed" => 2,
            "mind" => 2
        ],
        "items" => [18,25,0,0],
        "spells" => [5],
        "resistances" => [[DamageType::Fire,3],[DamageType::Cold,2],[DamageType::Thunder,2]],
        "conditions" => [],
        "damimmunities" => [DamageType::Earth],
        "vulnerabilities" => [],
        "condimmunities" => [9],
        "effects" => [],
        "class" => MonsterClass::Hybrid
    ],
    18 => [
        "id" => 18,
        "name" => "Horned Devil",
        "attributes" => [
            "currenthp" => 35,
            "maxhp" => 35,
            "might" => 4,
            "power" => 3,
            "speed" => 3,
            "mind" => 3
        ],
        "items" => [26,0,0,0],
        "spells" => [10],
        "resistances" => [[DamageType::Cold,2]],
        "conditions" => [],
        "damimmunities" => [DamageType::Fire,DamageType::Earth],
        "vulnerabilities" => [],
        "condimmunities" => [2,9],
        "effects" => [],
        "class" => MonsterClass::Hybrid
    ],
    19 => [
        "id" => 19,
        "name" => "Pit Fiend",
        "attributes" => [
            "currenthp" => 45,
            "maxhp" => 45,
            "might" => 5,
            "power" => 4,
            "speed" => 5,
            "mind" => 4
        ],
        "items" => [15,17,0,0],
        "spells" => [10],
        "resistances" => [[DamageType::Cold,2]],
        "conditions" => [],
        "damimmunities" => [DamageType::Fire,DamageType::Earth],
        "vulnerabilities" => [],
        "condimmunities" => [2,9],
        "effects" => [],
        "class" => MonsterClass::Hybrid
    ],
    20 => [
        "id" => 20,
        "name" => "Blue Dragon",
        "attributes" => [
            "currenthp" => 60,
            "maxhp" => 60,
            "might" => 6,
            "power" => 6,
            "speed" => 4,
            "mind" => 6
        ],
        "items" => [27,28,0,0],
        "spells" => [12,18],
        "resistances" => [],
        "conditions" => [],
        "damimmunities" => [DamageType::Thunder],
        "vulnerabilities" => [],
        "condimmunities" => [1,6,8],
        "effects" => [],
        "class" => MonsterClass::Hybrid
    ],
    21 => [
        "id" => 21,
        "name" => "Black Dragon",
        "attributes" => [
            "currenthp" => 60,
            "maxhp" => 60,
            "might" => 6,
            "power" => 6,
            "speed" => 4,
            "mind" => 6
        ],
        "items" => [27,28,0,0],
        "spells" => [1,5],
        "resistances" => [],
        "conditions" => [],
        "damimmunities" => [DamageType::Earth],
        "vulnerabilities" => [],
        "condimmunities" => [1,8,9],
        "effects" => [],
        "class" => MonsterClass::Hybrid
    ],
    22 => [
        "id" => 22,
        "name" => "Red Dragon",
        "attributes" => [
            "currenthp" => 60,
            "maxhp" => 60,
            "might" => 7,
            "power" => 6,
            "speed" => 4,
            "mind" => 6
        ],
        "items" => [27,28,0,0],
        "spells" => [10,17],
        "resistances" => [],
        "conditions" => [],
        "damimmunities" => [DamageType::Fire],
        "vulnerabilities" => [],
        "condimmunities" => [1,2,8],
        "effects" => [],
        "class" => MonsterClass::Hybrid
    ],
    23 => [
        "id" => 23,
        "name" => "White Dragon",
        "attributes" => [
            "currenthp" => 60,
            "maxhp" => 60,
            "might" => 6,
            "power" => 6,
            "speed" => 4,
            "mind" => 6
        ],
        "items" => [27,28,0,0],
        "spells" => [4,16],
        "resistances" => [],
        "conditions" => [],
        "damimmunities" => [DamageType::Cold],
        "vulnerabilities" => [],
        "condimmunities" => [1,7,8],
        "effects" => [],
        "class" => MonsterClass::Hybrid
    ],
    24 => [
        "id" => 24,
        "name" => "Air Elemental",
        "attributes" => [
            "currenthp" => 30,
            "maxhp" => 30,
            "might" => 2,
            "power" => 2,
            "speed" => 4,
            "mind" => 1
        ],
        "items" => [29,0,0,0],
        "spells" => [12],
        "resistances" => [[DamageType::Thunder,2]],
        "conditions" => [],
        "damimmunities" => [DamageType::Earth],
        "vulnerabilities" => [],
        "condimmunities" => [8,9,11,14,15,16],
        "effects" => [],
        "class" => MonsterClass::Hybrid
    ],
    25 => [
        "id" => 25,
        "name" => "Earth Elemental",
        "attributes" => [
            "currenthp" => 35,
            "maxhp" => 35,
            "might" => 4,
            "power" => 2,
            "speed" => 1,
            "mind" => 2
        ],
        "items" => [29,0,30,0],
        "spells" => [],
        "resistances" => [],
        "conditions" => [],
        "damimmunities" => [DamageType::Earth],
        "vulnerabilities" => [DamageType::Thunder],
        "condimmunities" => [8,9,11,14,15],
        "effects" => [],
        "class" => MonsterClass::Fighter
    ],
    26 => [
        "id" => 26,
        "name" => "Fire Elemental",
        "attributes" => [
            "currenthp" => 30,
            "maxhp" => 30,
            "might" => 2,
            "power" => 4,
            "speed" => 2,
            "mind" => 1
        ],
        "items" => [29,0,0,0],
        "spells" => [10,17],
        "resistances" => [],
        "conditions" => [],
        "damimmunities" => [DamageType::Fire,DamageType::Earth],
        "vulnerabilities" => [DamageType::Cold],
        "condimmunities" => [2,8,9,11,14,15,16],
        "effects" => [],
        "class" => MonsterClass::Hybrid
    ],
    27 => [
        "id" => 27,
        "name" => "Water Elemental",
        "attributes" => [
            "currenthp" => 30,
            "maxhp" => 30,
            "might" => 3,
            "power" => 3,
            "speed" => 3,
            "mind" => 1
        ],
        "items" => [29,0,0,0],
        "spells" => [],
        "resistances" => [],
        "conditions" => [],
        "damimmunities" => [DamageType::Earth],
        "vulnerabilities" => [],
        "condimmunities" => [8,9,11,14,15],
        "effects" => [],
        "class" => MonsterClass::Fighter
    ],
    28 => [
        "id" => 28,
        "name" => "Deep Elf Warrior",
        "attributes" => [
            "currenthp" => 25,
            "maxhp" => 25,
            "might" => 2,
            "power" => 1,
            "speed" => 3,
            "mind" => 1
        ],
        "items" => [33,0,12,0],
        "spells" => [],
        "resistances" => [],
        "conditions" => [],
        "damimmunities" => [],
        "vulnerabilities" => [],
        "condimmunities" => [1],
        "effects" => [],
        "class" => MonsterClass::Fighter
    ],
    29 => [
        "id" => 29,
        "name" => "Deep Elf Mage",
        "attributes" => [
            "currenthp" => 25,
            "maxhp" => 25,
            "might" => 1,
            "power" => 2,
            "speed" => 2,
            "mind" => 1
        ],
        "items" => [31,0,10,0],
        "spells" => [8,12,16],
        "resistances" => [],
        "conditions" => [],
        "damimmunities" => [],
        "vulnerabilities" => [],
        "condimmunities" => [1],
        "effects" => [],
        "class" => MonsterClass::Caster
    ],
    30 => [
        "id" => 30,
        "name" => "Deep Elf Priestess",
        "attributes" => [
            "currenthp" => 25,
            "maxhp" => 25,
            "might" => 1,
            "power" => 2,
            "speed" => 2,
            "mind" => 1
        ],
        "items" => [32,0,12,0],
        "spells" => [6,9],
        "resistances" => [],
        "conditions" => [],
        "damimmunities" => [],
        "vulnerabilities" => [],
        "condimmunities" => [1],
        "effects" => [],
        "class" => MonsterClass::Hybrid
    ],
    31 => [
        "id" => 31,
        "name" => "Ghoul",
        "attributes" => [
            "currenthp" => 25,
            "maxhp" => 25,
            "might" => 2,
            "power" => 1,
            "speed" => 2,
            "mind" => 1
        ],
        "items" => [25,0,0,0],
        "spells" => [],
        "resistances" => [],
        "conditions" => [],
        "damimmunities" => [DamageType::Earth,DamageType::Necrotic],
        "vulnerabilities" => [],
        "condimmunities" => [1,4,8,9],
        "effects" => [],
        "class" => MonsterClass::Fighter
    ],
    32 => [
        "id" => 32,
        "name" => "Fire Giant",
        "attributes" => [
            "currenthp" => 45,
            "maxhp" => 45,
            "might" => 6,
            "power" => 5,
            "speed" => 2,
            "mind" => 2
        ],
        "items" => [5,0,0,0],
        "spells" => [10],
        "resistances" => [],
        "conditions" => [],
        "damimmunities" => [DamageType::Fire],
        "vulnerabilities" => [DamageType::Cold],
        "condimmunities" => [],
        "effects" => [],
        "class" => MonsterClass::Hybrid
    ],
    33 => [
        "id" => 33,
        "name" => "Frost Giant",
        "attributes" => [
            "currenthp" => 45,
            "maxhp" => 45,
            "might" => 5,
            "power" => 2,
            "speed" => 2,
            "mind" => 2
        ],
        "items" => [6,0,0,0],
        "spells" => [],
        "resistances" => [],
        "conditions" => [],
        "damimmunities" => [DamageType::Cold],
        "vulnerabilities" => [DamageType::Fire],
        "condimmunities" => [],
        "effects" => [],
        "class" => MonsterClass::Fighter
    ],
    34 => [
        "id" => 34,
        "name" => "Storm Giant",
        "attributes" => [
            "currenthp" => 45,
            "maxhp" => 45,
            "might" => 6,
            "power" => 5,
            "speed" => 2,
            "mind" => 2
        ],
        "items" => [5,0,0,0],
        "spells" => [12],
        "resistances" => [],
        "conditions" => [],
        "damimmunities" => [DamageType::Thunder],
        "vulnerabilities" => [],
        "condimmunities" => [8],
        "effects" => [],
        "class" => MonsterClass::Hybrid
    ],
    35 => [
        "id" => 35,
        "name" => "Gnoll",
        "attributes" => [
            "currenthp" => 25,
            "maxhp" => 25,
            "might" => 2,
            "power" => 1,
            "speed" => 2,
            "mind" => 1
        ],
        "items" => [5,0,0,0],
        "spells" => [],
        "resistances" => [],
        "conditions" => [],
        "damimmunities" => [],
        "vulnerabilities" => [],
        "condimmunities" => [],
        "effects" => [],
        "class" => MonsterClass::Fighter
    ],
    36 => [
        "id" => 36,
        "name" => "Lich",
        "attributes" => [
            "currenthp" => 35,
            "maxhp" => 35,
            "might" => 1,
            "power" => 5,
            "speed" => 2,
            "mind" => 5
        ],
        "items" => [31,0,0,0],
        "spells" => [2,3,10,14],
        "resistances" => [[DamageType::Cold,2],[DamageType::Thunder,2]],
        "conditions" => [],
        "damimmunities" => [DamageType::Earth,DamageType::Necrotic],
        "vulnerabilities" => [],
        "condimmunities" => [1,4,8,9],
        "effects" => [],
        "class" => MonsterClass::Caster
    ],
    37 => [
        "id" => 37,
        "name" => "Mummy",
        "attributes" => [
            "currenthp" => 35,
            "maxhp" => 35,
            "might" => 3,
            "power" => 1,
            "speed" => 2,
            "mind" => 1
        ],
        "items" => [25,0,0,0],
        "spells" => [],
        "resistances" => [],
        "conditions" => [],
        "damimmunities" => [DamageType::Earth,DamageType::Necrotic],
        "vulnerabilities" => [DamageType::Fire],
        "condimmunities" => [1,4,8,9,13],
        "effects" => [],
        "class" => MonsterClass::Fighter
    ],
    38 => [
        "id" => 38,
        "name" => "Ogre",
        "attributes" => [
            "currenthp" => 35,
            "maxhp" => 35,
            "might" => 4,
            "power" => 1,
            "speed" => 2,
            "mind" => 1
        ],
        "items" => [21,0,0,0],
        "spells" => [],
        "resistances" => [],
        "conditions" => [],
        "damimmunities" => [],
        "vulnerabilities" => [],
        "condimmunities" => [],
        "effects" => [],
        "class" => MonsterClass::Fighter
    ],
    39 => [
        "id" => 39,
        "name" => "Orc",
        "attributes" => [
            "currenthp" => 25,
            "maxhp" => 25,
            "might" => 2,
            "power" => 1,
            "speed" => 2,
            "mind" => 1
        ],
        "items" => [34,0,10,0],
        "spells" => [],
        "resistances" => [],
        "conditions" => [],
        "damimmunities" => [],
        "vulnerabilities" => [],
        "condimmunities" => [],
        "effects" => [],
        "class" => MonsterClass::Fighter
    ],
    40 => [
        "id" => 40,
        "name" => "Salamander",
        "attributes" => [
            "currenthp" => 35,
            "maxhp" => 35,
            "might" => 3,
            "power" => 2,
            "speed" => 2,
            "mind" => 1
        ],
        "items" => [20,0,0,0],
        "spells" => [],
        "resistances" => [],
        "conditions" => [],
        "damimmunities" => [DamageType::Fire],
        "vulnerabilities" => [DamageType::Cold],
        "condimmunities" => [],
        "effects" => [],
        "class" => MonsterClass::Fighter
    ],
    41 => [
        "id" => 41,
        "name" => "Skeleton",
        "attributes" => [
            "currenthp" => 20,
            "maxhp" => 20,
            "might" => 1,
            "power" => 1,
            "speed" => 1,
            "mind" => 1
        ],
        "items" => [2,0,0,0],
        "spells" => [],
        "resistances" => [],
        "conditions" => [],
        "damimmunities" => [DamageType::Earth],
        "vulnerabilities" => [DamageType::Bludgeoning],
        "condimmunities" => [9,14,15],
        "effects" => [],
        "class" => MonsterClass::Fighter
    ],
    42 => [
        "id" => 42,
        "name" => "Frostmarrow Skeleton",
        "attributes" => [
            "currenthp" => 20,
            "maxhp" => 20,
            "might" => 1,
            "power" => 1,
            "speed" => 1,
            "mind" => 1
        ],
        "items" => [36,0,0,0],
        "spells" => [],
        "resistances" => [],
        "conditions" => [],
        "damimmunities" => [DamageType::Cold,DamageType::Earth],
        "vulnerabilities" => [DamageType::Fire,DamageType::Bludgeoning],
        "condimmunities" => [9,14,15],
        "effects" => [],
        "class" => MonsterClass::Fighter
    ],
    43 => [
        "id" => 43,
        "name" => "Blackbone Skeleton",
        "attributes" => [
            "currenthp" => 20,
            "maxhp" => 20,
            "might" => 1,
            "power" => 1,
            "speed" => 1,
            "mind" => 1
        ],
        "items" => [35,0,0,0],
        "spells" => [],
        "resistances" => [],
        "conditions" => [],
        "damimmunities" => [DamageType::Fire,DamageType::Earth],
        "vulnerabilities" => [DamageType::Cold,DamageType::Bludgeoning],
        "condimmunities" => [9,14,15],
        "effects" => [],
        "class" => MonsterClass::Fighter
    ],
    44 => [
        "id" => 44,
        "name" => "Tarrasque",
        "attributes" => [
            "currenthp" => 70,
            "maxhp" => 70,
            "might" => 7,
            "power" => 5,
            "speed" => 2,
            "mind" => 5
        ],
        "items" => [27,28,37,0],
        "spells" => [],
        "resistances" => [],
        "conditions" => [],
        "damimmunities" => [DamageType::Fire,DamageType::Earth],
        "vulnerabilities" => [],
        "condimmunities" => [2,8,9],
        "effects" => [],
        "class" => MonsterClass::Fighter
    ],
    45 => [
        "id" => 45,
        "name" => "Treant",
        "attributes" => [
            "currenthp" => 40,
            "maxhp" => 40,
            "might" => 5,
            "power" => 1,
            "speed" => 1,
            "mind" => 1
        ],
        "items" => [29,0,0,0],
        "spells" => [],
        "resistances" => [],
        "conditions" => [],
        "damimmunities" => [],
        "vulnerabilities" => [DamageType::Fire],
        "condimmunities" => [],
        "effects" => [],
        "class" => MonsterClass::Fighter
    ],
    46 => [
        "id" => 46,
        "name" => "Troll",
        "attributes" => [
            "currenthp" => 35,
            "maxhp" => 35,
            "might" => 4,
            "power" => 1,
            "speed" => 2,
            "mind" => 1
        ],
        "items" => [25,0,0,0],
        "spells" => [],
        "resistances" => [],
        "conditions" => [],
        "damimmunities" => [],
        "vulnerabilities" => [DamageType::Fire],
        "condimmunities" => [],
        "effects" => [],
        "class" => MonsterClass::Fighter
    ],
    47 => [
        "id" => 47,
        "name" => "Vampire",
        "attributes" => [
            "currenthp" => 35,
            "maxhp" => 35,
            "might" => 4,
            "power" => 3,
            "speed" => 4,
            "mind" => 3
        ],
        "items" => [38,0,0,0],
        "spells" => [2,3],
        "resistances" => [],
        "conditions" => [],
        "damimmunities" => [DamageType::Earth,DamageType::Necrotic],
        "vulnerabilities" => [DamageType::Radiant],
        "condimmunities" => [1,4,8,9],
        "effects" => [],
        "class" => MonsterClass::Hybrid
    ],
    48 => [
        "id" => 48,
        "name" => "Zombie",
        "attributes" => [
            "currenthp" => 25,
            "maxhp" => 25,
            "might" => 2,
            "power" => 1,
            "speed" => 1,
            "mind" => 1
        ],
        "items" => [29,0,0,0],
        "spells" => [],
        "resistances" => [],
        "conditions" => [],
        "damimmunities" => [DamageType::Earth,DamageType::Necrotic],
        "vulnerabilities" => [],
        "condimmunities" => [1,4,8,9],
        "effects" => [],
        "class" => MonsterClass::Fighter
    ],
    49 => [
        "id" => 49,
        "name" => "Ghost",
        "attributes" => [
            "currenthp" => 25,
            "maxhp" => 25,
            "might" => 1,
            "power" => 1,
            "speed" => 3,
            "mind" => 1
        ],
        "items" => [39,0,0,0],
        "spells" => [],
        "resistances" => [],
        "conditions" => [],
        "damimmunities" => [DamageType::Earth,DamageType::Necrotic],
        "vulnerabilities" => [],
        "condimmunities" => [1,4,8,9,11,14,15,16],
        "effects" => [],
        "class" => MonsterClass::Fighter
    ],
];