@extends('layouts.admin')
@section('title', 'CRM - View Records')
@section('content')

<div class="main-content app-content">
    <div class="container-fluid">
        <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <div class="">
                    <nav>
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">View Records</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">
                            View Records
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-12 mb-4">
                                        <div class="card" style="background-color: 
                                            {{ $user->type == 'store' ? 'rgb(230, 245, 255)' : 
                                            ($user->type == 'bm' ? 'rgb(245, 230, 255)' : 
                                            ($user->type == 'tt' ? 'rgb(230, 255, 230)' : 'white')) }};">
                                            <div class="card-body">
                                                <h5 class="card-title">
                                                    @if($user->type == 'store')
                                                    Store Details
                                                    @elseif($user->type == 'bm')
                                                    Branch Manager Details
                                                    @elseif($user->type == 'tt')
                                                    Territory Manager Details
                                                    @else
                                                    User Details
                                                    @endif
                                                </h5>

                                                <p><b>Location:</b> {{ $user->store_location ?? 'N/A' }}</p>
                                                <p><b>State:</b> {{ $user->store_state ?? 'N/A' }}</p>
                                                <p><b>Email:</b> {{ $user->store_email ?? $user->email }}</p>
                                                <p><b>Phone:</b> <a style="color:blue" href="tel:{{ $user->phone ?? $user->phone }}">{{ $user->phone ?? $user->phone }}</a></p>
                                                <?php
                                                    $distributorName = DB::table('users');
                                                    if($user->type == 'bm'){
                                                        $distributorName = $distributorName->where('bm_name',$user->id);
                                                    }elseif($user->type == 'tt'){
                                                        $distributorName = $distributorName->where('tt_name',$user->id);
                                                    }elseif($user->type == 'store'){
                                                        $distributorName = $distributorName->where('store_location',$user->id);
                                                    }

                                                    $distributorName = $distributorName->get()->pluck('full_name')->toArray();

                                                    $disName = "";
                                                    if($distributorName){
                                                        $disName = implode(', ',$distributorName);
                                                    }
                                                   // dd($distributorName);
                                                ?>
                                                <p><b>Distributor Name:</b> {{$disName}}</p>
                                                @if($user->type == 'bm' || $user->type == 'tt')
                                                <!-- <p>
                                                    <b>Notes:</b> {{ $user->notes ?? 'No notes available' }}
                                                    <a href="javascript:void(0);" data-bs-toggle="modal"
                                                        data-bs-target="#notesModal" style="color: blue;">View
                                                        Details</a>
                                                </p> -->
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
 
                             
                        </div>
                        @endsection