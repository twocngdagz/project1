<?php

class ReferralgridController extends BaseController {

    protected $referral;
    public function __construct(Referralgrid $referral)
    {
        parent::__construct();
        $this->referral = $referral;
    }
}
