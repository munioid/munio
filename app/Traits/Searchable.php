<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Searchable
{
    /**
     * Scope untuk pencarian global
     */
    public function scopeSearch(Builder $query, ?string $keyword): Builder
    {
        if (empty($keyword)) {
            return $query;
        }

        if (!property_exists($this, 'searchable') || empty($this->searchable)) {
            return $query;
        }

        $operator = config('database.default') == 'pgsql' ? 'ILIKE' : 'LIKE';

        return $query->where(function (Builder $q) use ($keyword, $operator) {
            foreach ($this->searchable as $column) {
                // Support relasi: relation.column
                if (str_contains($column, '.')) {
                    [$relation, $relColumn] = explode('.', $column, 2);

                    $q->orWhereHas($relation, function (Builder $relQuery) use ($relColumn, $keyword, $operator) {
                        $relQuery->where($relColumn, $operator, "%{$keyword}%");
                    });

                } else {
                    $q->orWhere($column, $operator, "%{$keyword}%");
                }
            }
        });
    }
}
