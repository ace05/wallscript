<?php

namespace App\Repositories;

use App\Entities\FriendTag;

/**
 * Class FriendTagRepository
 * @package namespace App\Repositories;
 */
class FriendTagRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return FriendTag::class;
    }

    
}
