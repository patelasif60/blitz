<?php

namespace App\Services;

use App\Repositories\RfqRepository;

/**
 * Company class to handle operator interactions.
 */
class RfqService
{
    public $repository;

    // intilization
    public function __construct()
    {
        $this->repository = new RfqRepository();
    }

    // get rfq data
    public function getRfqList($perPage,$page,$favrfq,$searchedData,$rfqstatus){
        return $this->repository->getRfqList($perPage,$page,$favrfq,$searchedData,$rfqstatus);
    }
}