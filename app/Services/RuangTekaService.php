<?php

namespace App\Services;

use App\Models\RuangTeka;
use App\Models\RuangTekaSession;
use App\Models\Cerita;

class RuangTekaService extends Service
{
    public function search($params = [])
    {
        $q = RuangTeka::orderBy('id');
        $q = $this->searchFilter($params, $q, ['title', 'difficulty']);
        return $this->searchResponse($params, $q);
    }

    public function find($value, $column = 'id')
    {
        return RuangTeka::where($column, $value)->first();
    }

    public function store($params)
    {
        return RuangTeka::create($params);
    }

    public function update($id, $params)
    {
        $ruangTeka = RuangTeka::find($id);
        if (!empty($ruangTeka)) $ruangTeka->update($params);
        return $ruangTeka;
    }

    public function delete($id)
    {
        $ruangTeka = RuangTeka::find($id);
        if (!empty($ruangTeka)) {
            try {
                $ruangTeka->delete();
                return true;
            } catch (\Exception $e) {
                return ['error' => 'Delete ruang teka failed! This entry is currently being used'];
            }
        }
        return $ruangTeka;
    }

    public function ceritaOptions()
    {
        return Cerita::orderBy('nama')->pluck('nama', 'id');
    }

    public function generateGridFromClues(array $clues, int $rows, int $cols): array
    {
        // init empty grid with null for black cells
        $grid = array_fill(0, $rows, array_fill(0, $cols, null));
        $errors = [];

        $placeWord = function($word, $r, $c, $dir) use (&$grid, $rows, $cols, &$errors) {
            $len = mb_strlen($word);
            for ($i = 0; $i < $len; $i++) {
                $rr = $r + ($dir === 'down' ? $i : 0);
                $cc = $c + ($dir === 'across' ? $i : 0);
                if ($rr < 0 || $rr >= $rows || $cc < 0 || $cc >= $cols) {
                    $errors[] = "Word '{$word}' out of bounds at ({$r},{$c}) dir {$dir}";
                    return;
                }
                $ch = mb_substr($word, $i, 1);
                if ($grid[$rr][$cc] === null || $grid[$rr][$cc] === '' ) {
                    $grid[$rr][$cc] = $ch;
                } elseif ($grid[$rr][$cc] !== $ch) {
                    $errors[] = "Conflict placing '{$word}' at ({$rr},{$cc}): grid has '{$grid[$rr][$cc]}' vs '{$ch}'";
                    return;
                }
            }
        };

        foreach (['across','down'] as $dir) {
            if (empty($clues[$dir]) || !is_array($clues[$dir])) continue;
            foreach ($clues[$dir] as $item) {
                if (!isset($item['answer'],$item['row'],$item['col'])) {
                    $errors[] = 'Invalid clue item: missing answer/row/col';
                    continue;
                }
                $word = strtoupper(trim($item['answer']));
                $r = intval($item['row']);
                $c = intval($item['col']);
                $placeWord($word, $r, $c, $dir);
            }
        }

        return ['grid' => $grid, 'errors' => $errors];
    }

    /**
     * Start a game session for a template and user
     */
    public function startSession(int $templateId, ?int $userId = null): RuangTekaSession
    {
        $session = RuangTekaSession::create([
            'template_id' => $templateId,
            'user_id' => $userId,
            'grid_state' => [],
            'answers' => [],
            'score' => 0,
            'state' => 'playing'
        ]);
        return $session;
    }
}
