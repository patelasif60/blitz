<?php

namespace App\Services;

use App\Repositories\OrderRepository;

/**
 * Company class to handle operator interactions.
 */
class orderService
{
    public $repository;

    // intilization
    public function __construct()
    {
        $this->repository = new OrderRepository();
    }

    // get rfq data
    public function getRfqList($perPage,$page,$favorder,$searchedData,$status){
        return $this->repository->getRfqList($perPage,$page,$favorder,$searchedData,$status);
    }
}