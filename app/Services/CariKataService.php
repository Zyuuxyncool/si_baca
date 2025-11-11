<?php

namespace App\Services;

use App\Models\CariKataTemplate;
use App\Models\CariKata;
use App\Models\Cerita;
use Illuminate\Support\Str;

class CariKataService extends Service
{
    public function search($params = [])
    {
        $q = CariKataTemplate::orderBy('id', 'desc');
        $q = $this->searchFilter($params, $q, ['title', 'difficulty']);
        return $this->searchResponse($params, $q);
    }

    public function find($value, $column = 'id')
    {
        return CariKataTemplate::where($column, $value)->first();
    }

    public function store($params)
    {
        if (empty($params['slug']) && !empty($params['title'])) $params['slug'] = Str::slug($params['title']);
        return CariKataTemplate::create($params);
    }

    public function update($id, $params)
    {
        $cari_kata_template = CariKataTemplate::find($id);
        if (!empty($cari_kata_template)) $cari_kata_template->update($params);
        return $cari_kata_template;
    }

    public function delete($id)
    {
        $cari_kata_template = $this->find($id);
        if ($cari_kata_template) {
            try {
                $cari_kata_template->delete();
                return true;
            } catch (\Exception $e) {
                return ['error' => 'Delete cari kata template failed! This entry is currently being used'];
            }
        }
        return $cari_kata_template;
    }

    /**
     * Generate a word-search grid using simple placement algorithm.
     * Returns ['grid'=>[][], 'solution'=>[]] or null on failure.
     */
    public function generateGrid(array $words, int $rows = 12, int $cols = 12, array $directions = ['horizontal','vertical','diagonal','reverse'], bool $allowOverlap = true, $seed = null)
    {
        if ($seed !== null) srand(intval($seed));

    // normalize words (multibyte-safe)
    $wordList = array_map(function($w){ return mb_strtoupper(trim($w)); }, $words);

        // init empty grid
        $grid = array_fill(0, $rows, array_fill(0, $cols, null));
        $solution = [];

        $dirVectors = [
            'horizontal' => [0,1],
            'vertical' => [1,0],
            'diagonal' => [1,1],
            'reverse' => [0,-1]
        ];

        $allowed = array_intersect(array_keys($dirVectors), array_map('strtolower', $directions));
        if (empty($allowed)) $allowed = ['horizontal','vertical'];

    // place longest words first (multibyte-safe)
    usort($wordList, function($a,$b){ return mb_strlen($b) - mb_strlen($a); });

        foreach ($wordList as $word) {
            $placed = false;
            $attempts = 0;
            $maxAttempts = 200;
            while (!$placed && $attempts++ < $maxAttempts) {
                $dir = $allowed[array_rand($allowed)];
                $vec = $dirVectors[$dir];
                // pick start
                $r = rand(0, $rows-1);
                $c = rand(0, $cols-1);
                // check bounds for this direction (multibyte-safe)
                $wlen = mb_strlen($word);
                $endR = $r + $vec[0] * ($wlen-1);
                $endC = $c + $vec[1] * ($wlen-1);
                if ($endR < 0 || $endR >= $rows || $endC < 0 || $endC >= $cols) continue;

                // check conflicts
                $conflict = false;
                $coords = [];
                $occupied = 0; // count cells already occupied (for allow_overlap rules)
                for ($i=0;$i<$wlen;$i++) {
                    $rr = $r + $vec[0]*$i;
                    $cc = $c + $vec[1]*$i;
                    $cell = $grid[$rr][$cc];
                    $char = mb_substr($word, $i, 1);
                    if ($cell === null) {
                        $coords[] = ['r'=>$rr,'c'=>$cc];
                        continue;
                    }
                    // occupied cell: allow only if same character (proper overlap)
                    if ($cell === $char) {
                        $coords[] = ['r'=>$rr,'c'=>$cc];
                        $occupied++;
                        continue;
                    }
                    // different letter -> cannot place here
                    $conflict = true; break;
                }
                // if conflict, skip this attempt
                if ($conflict) continue;
                // if overlaps are not allowed but some cells are already occupied, skip
                if (!$allowOverlap && $occupied > 0) continue;

                // place word
                for ($i=0;$i<$wlen;$i++) {
                    $rr = $r + $vec[0]*$i;
                    $cc = $c + $vec[1]*$i;
                    $char = mb_substr($word, $i, 1);
                    // only set cell if it's empty or same char; do not overwrite different letters
                    if ($grid[$rr][$cc] === null || $grid[$rr][$cc] === $char) {
                        $grid[$rr][$cc] = $char;
                    }
                }
                $solution[] = ['word'=>$word,'coords'=>$coords,'dir'=>$dir];
                $placed = true;
            }
            if (!$placed) {
                // failed to place this word, return null as failure
                return null;
            }
        }

        // fill remaining cells with random letters
        for ($r=0;$r<$rows;$r++) for ($c=0;$c<$cols;$c++) if ($grid[$r][$c] === null) $grid[$r][$c] = chr(rand(65,90));

        return ['grid'=>$grid,'solution'=>$solution];
    }

    public function startSession($ceritaId, $userId, $template = null)
    {
        $session = CariKata::create([
            'cerita_id' => $ceritaId,
            'user_id' => $userId,
            'score' => 0,
            'total_words' => $template['total_words'] ?? 0,
            'found_words' => 0,
            'attempts' => 1,
            'time_seconds' => null,
            'grid' => $template['grid'] ?? null,
            'solution' => $template['solution'] ?? null,
            'state' => null,
        ]);
        return $session;
    }

    /**
     * Return cerita options for select (id => nama)
     */
    public function ceritaOptions()
    {
        return Cerita::orderBy('nama')->pluck('nama', 'id');
    }

    public function saveSessionResult($sessionId, $data)
    {
        $s = CariKata::find($sessionId);
        if (!$s) return null;
        $s->fill($data);
        $s->save();
        return $s;
    }
}
