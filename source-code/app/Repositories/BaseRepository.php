<?php
namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository as Repository;

class BaseRepository extends Repository
{
	public function model(){ }

	public function getRepoCounts($condtions = [])
	{	
		if(empty($condtions) === false){
			return $this->model->where($condtions)->count();
		}
		return $this->model->count();
	}
}