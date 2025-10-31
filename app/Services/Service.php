<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http; 

class Service
{
    public function searchFilter($params, $model, $filters)
    {
        foreach ($filters as $filter) {
            $value = $params[$filter] ?? '';
            if ($value === 'null') $model = $model->whereNull($filter);
            else if ($value === 'not_null') $model = $model->whereNotNull($filter);
            else if ($value !== '') $model = $model->where($filter, $value);
        }
        return $model;
    }

    public function searchResponse($params, $model)
    {
        $isEloquentBuilder = $model instanceof EloquentBuilder || $model instanceof QueryBuilder;

        $with = $params['with'] ?? '';
        if ($with !== '' && $isEloquentBuilder) {
            $model = $model->with($with);
        }

        $limit = $params['limit'] ?? '';
        $skip = $params['skip'] ?? '';

        if ($isEloquentBuilder) {
            if ($limit !== '') $model = $model->limit($limit);
            if ($skip !== '') $model = $model->skip($skip);
        } else if ($model instanceof Collection) {
            if ($skip !== '') $model = $model->skip($skip);
            if ($limit !== '') $model = $model->take($limit);
        }

        $is_trash = $params['is_trash'] ?? '';
        if ($is_trash !== '' && $isEloquentBuilder) {
            $model = $model->onlyTrashed();
        }

        $orders = $params['orders'] ?? '';
        if ($orders !== '') {
            if ($isEloquentBuilder) {
                foreach ($orders as $column => $direction) $model = $model->orderBy($column, $direction);
            } else if ($model instanceof Collection) {
                foreach ($orders as $column => $direction) {
                    $model = $model->sortBy($column, SORT_REGULAR, strtolower($direction) === 'desc');
                }
            }
        }

        $count = $params['count'] ?? '';
        if ($count !== '') return $model->count();
        $sum = $params['sum'] ?? '';
        if ($sum !== '') return $model->sum($sum);
        $first = $params['first'] ?? '';
        if ($first !== '') return $model->first();
        $paginate = $params['paginate'] ?? '';

        if ($paginate !== '') {
            if ($isEloquentBuilder) {
                return $model->paginate($paginate);
            }
        }

        if ($isEloquentBuilder) {
            return $model->get();
        }

        return $model;
    }

    public function cleanNumber($params, $columns = [])
    {
        foreach ($columns as $column) if (!empty($params[$column])) $params[$column] = intval(unformat_number($params[$column]));
        return $params;
    }

    public function cleanDate($params, $columns = [])
    {
        foreach ($columns as $column) if (!empty($params[$column])) $params[$column] = unformat_date($params[$column]);
        return $params;
    }

    public function curlRequest($url, $method, $fields = '', $header = [])
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => $fields,
            CURLOPT_HTTPHEADER => $header,
        ));

        $response = curl_exec($curl);
        $response = json_decode($response, true);

        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
            dd($error_msg);
        }

        curl_close($curl);
        return $response;
    }
}
