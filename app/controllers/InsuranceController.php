<?php

class InsuranceController extends BaseController {

    protected $insurance;
    public function __construct(Insurance $insurance)
    {
        parent::__construct();
        $this->insurance = $insurance;
    }
}
