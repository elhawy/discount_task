<?php

namespace Modules\Infrastructure\Http\Traits;

use Vinkla\Hashids\Facades\Hashids;

trait Hashidable
{
    public function getRouteKey()
    {
        return Hashids::connection(get_called_class())->encode($this->getKey());
    }

    public function decryptIDs($keys)
    {
        foreach ($keys as $index => $key) {
            $keys[$index] = (Hashids::connection(get_called_class())->decode($key))[0] ?? null;
        }
        return $keys;
    }

    public function decodeId($id) {
        return (Hashids::connection(get_called_class())->decode($id))[0] ?? null;
    }
    public function encryptIDs($keys)
    {
        foreach ($keys as $index => $key) {
            $keys[$index] = (Hashids::connection(get_called_class())->encode($key)) ?? null;
        }
        return $keys;
    }
}
