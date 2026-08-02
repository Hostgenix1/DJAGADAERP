<?php

namespace App\Repositories;

use App\Contracts\Repositories\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;

abstract class BaseCrudRepository extends BaseRepository implements RepositoryInterface
{
    public function create(array $attributes): Model
    {
        $result = parent::create($attributes);
        activity()->performedOn($result)->withProperties(['log' => 'created'])->event('created')->log('created');

        return $result;
    }

    public function update(Model|int|string $model, array $attributes): Model
    {
        $model = $model instanceof Model ? $model : $this->findOrFail($model);

        $affected = collect($attributes)->filter(
            fn ($value, $key) => isset($model->getOriginal()[$key]) && $model->getOriginal()[$key] != $value
        )->all();

        $model->update($attributes);

        if ($affected !== []) {
            activity()->performedOn($model)->withProperties(['changes' => $affected])->event('updated')->log('updated');
        }

        return $model;
    }

    public function delete(Model|int|string $model): bool
    {
        if (! $model instanceof Model) {
            $model = $this->findOrFail($model);
        }

        activity()->performedOn($model)->event('deleted')->log('deleted');

        return (bool) $model->delete();
    }
}
