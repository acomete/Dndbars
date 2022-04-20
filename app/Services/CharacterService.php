<?php


namespace App\Services;

use Illuminate\Support\Facades\Http;

class CharacterService
{
    private $token;

    public function auth()
    {
        $auth = Http::withHeaders([
            'cookie' => config('beyond.cookie')
        ])
            ->post('https://auth-service.dndbeyond.com/v1/cobalt-token');

        abort_unless(isset($auth['token']), 403, 'Echec de récupération du token DnD beyond.');

        $this->token = $auth['token'];
    }

    public function maxHps($character)
    {
        $level = $this->level($character);

        return $character->overrideHitPoints ?:
            $character->baseHitPoints + $this->bonus($character->stats[2]['value'])*$level;
    }

    public function currentHps($character)
    {
        return $this->maxHps($character) - $character->removedHitPoints;
    }

    /**
     * Compute bonus from stat value.
     *
     * @param $stat
     * @return int
     */
    public function bonus($stat): int
    {
        $bonues = [
            8 => -1,
            9 => -1,
            10 => 0,
            11 => 0,
            12 => 1,
            13 => 1,
            14 => 2,
            15 => 2,
            16 => 3,
            17 => 3,
            18 => 4,
            19 => 4,
            20 => 5,
        ];

        return $bonues[$stat] ?? 0;
    }

    public function level($character)
    {
        $level = 0;

        foreach ($character->classes as $class) {
            $level += $class['level'];
        }

        return $level;
    }

    public function ki($character): array {
        return array_values(array_map(function ($action) {
                return $action['limitedUse'];
            },
            array_filter($character->actions['class'], function ($action) {
            return $action['id'] === "1024";
        })))[0] ?? [];
    }

    public function maxKi($character): int {
        return $this->ki($character)['maxUses'] ?? 0;
    }

    public function currentki($character): int {
        return $this->maxKi($character) - ($this->ki($character)['numberUsed'] ?? 0);
    }

    /**
     * Compute max mana.
     *
     * @param $character
     * @return float|int
     */
    public function maxMana($character)
    {
        $mana = 0;
        $level = $this->level($character);

        if ($character->classes[0]['definition']['canCastSpells'] === false) {
            return 0;
        }

        $spellSlots = $character->classes[0]['definition']['spellRules']['levelSpellSlots'][$level];

        foreach ($spellSlots as $level => $slots) {
            $mana += ($level + 1) * $slots;
        }

        return $mana;
    }

    public function currentMana($character)
    {
        $mana = $this->maxMana($character);
        $usedMana = 0;

        foreach ($character->spellSlots as $slot) {
            $usedMana += $slot['level'] * $slot['used'];
        }

        return $mana - $usedMana;
    }

    public function getCharacterData(int $id)
    {
        $response = Http::withToken($this->token)
            ->get("https://character-service.dndbeyond.com/character/v5/character/{$id}");

        return $response->json()['data'] ?? null;
    }
}
