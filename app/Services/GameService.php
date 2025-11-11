<?php

namespace App\Services;

use App\Models\GameScore;

class GameService extends Service
{
    public function search($params = [])
    {
        $game_scores = GameScore::orderBy('id');
        // Tambahkan filter pencarian sesuai kebutuhan
        $game_scores = $this->searchFilter($params, $game_scores, ['user_id', 'cerita_id', 'game_id']);
        return $this->searchResponse($params, $game_scores);
    }

    public function find($value, $column = 'id')
    {
        return GameScore::where($column, $value)->first();
    }

    public function store($params)
    {
        // If we have an authenticated user and a template id, update existing score
        // for the same user/cerita/template instead of creating duplicates.
        $userId = $params['user_id'] ?? null;
        $ceritaId = $params['cerita_id'] ?? null;
        $tpl1 = $params['game_template_id'] ?? null;
        $tpl2 = $params['game_template_id2'] ?? null;

        if (!empty($userId) && ($tpl1 || $tpl2)) {
            $query = GameScore::where('user_id', $userId)->where('cerita_id', $ceritaId);
            if ($tpl1) $query->where('game_template_id', $tpl1);
            else $query->where('game_template_id2', $tpl2);
            $existing = $query->first();
            if ($existing) {
                // Overwrite existing score with new params (e.g., update score)
                $existing->update($params);
                return $existing;
            }
        }

        return GameScore::create($params);
    }

    public function update($id, $params)
    {
        $game_score = GameScore::find($id);
        if (!empty($game_score)) $game_score->update($params);
        return $game_score;
    }

    public function delete($id)
    {
        $game_score = GameScore::find($id);
        if (!empty($game_score)) {
            try {
                $game_score->delete();
                return true;
            } catch (\Exception $e) {
                return ['error' => 'Delete game score failed! This entry is currently being used'];
            }
        }
        return $game_score;
    }

    
}