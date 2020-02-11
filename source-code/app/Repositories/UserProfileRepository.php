<?php

namespace App\Repositories;

use App\Entities\UserProfile;

/**
 * Class UserProfileRepository
 * @package namespace App\Repositories;
 */
class UserProfileRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return UserProfile::class;
    }

}
