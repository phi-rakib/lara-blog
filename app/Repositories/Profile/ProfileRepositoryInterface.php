<?php

namespace App\Repositories\Profile;

interface ProfileRepositoryInterface
{

    public function create($profile);

    public function get(int $id);

}
