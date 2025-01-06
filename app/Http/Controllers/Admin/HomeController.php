<?php

namespace App\Http\Controllers\Admin;
use Auth;
use App\Leads;
use App\Card;
use App\User;

class HomeController
{
    public function index()
    {   
        $this->data['user_count'] =0; 

        $this->data['card_count'] = 0;


        $this->data['total_revenue'] = 0;

        $this->data['users'] = [];

        return view('home',$this->data);
    }
}
