<?php

namespace Modules\Carts\Storage;

interface StorageInterface
{
    public function add($key, $item);

    public function update($key, $id, $item);

    public function get($key, $id, $default = null);

    public function list($key, $params = [], $default = []);

    public function has($key, $id): bool;

    public function count($key): int;

    public function remove($key, $id): self;

    public function clear($key): self;
}