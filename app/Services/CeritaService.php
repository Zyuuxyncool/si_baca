<?php

namespace App\Services;
use App\Models\Cerita;

class CeritaService extends Service
{
    public function search($params = [])
    {
        $cerita = Cerita::orderBy('id');
        $cerita = $this->searchFilter($params, $cerita, ['nama']);
        return $this->searchResponse($params, $cerita);
    }

    public function find($value, $column = 'id')
    {
        return Cerita::where($column, $value)->first();
    }

    public function store($params)
    {
        return Cerita::create($params);
    }

    public function update($id, $params)
    {
        $cerita = Cerita::find($id);
        if (!empty($cerita)) $cerita->update($params);
        return $cerita;
    }

    public function delete($id)
    {
        $cerita = Cerita::find($id);
        if (!empty($cerita)) {
            try {
                $cerita->delete();
                return true;
            } catch (\Exception $e) {
                return ['error' => 'Delete cerita failed! This cerita currently being used'];
            }
        }
        return $cerita;
    }
}