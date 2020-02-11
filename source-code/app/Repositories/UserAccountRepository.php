<?php

namespace App\Repositories;

use App\Entities\UserAccount;

/**
 * Class UserAccountRepository
 * @package namespace App\Repositories;
 */
class UserAccountRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return UserAccount::class;
    }

}
