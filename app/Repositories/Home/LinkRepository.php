<?php

namespace App\Repositories\Home;

use App\Repositories\BaseRepository;

class LinkRepository extends BaseRepository
{
    public function model()
    {
        return 'App\Models\Common\Link';
    }


}
