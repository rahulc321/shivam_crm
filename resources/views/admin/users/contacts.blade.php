@extends('layouts.admin')
@section('title', 'CRM - List Contacts')
@section('content')
<style>
    .card {
        box-shadow: rgba(0, 0, 0, 0.16) 0px 3px 6px, rgba(0, 0, 0, 0.23) 0px 3px 6px;
    }
</style>
<div class="main-content app-content">
    <div class="container-fluid">
        <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Contacts</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">
                            Contacts
                        </div>
                        <a class="" href='{{ route("admin.createContact") }}' style="float:right !important"><span
                                class="badge bg-outline-info">Add Contacts</span></a>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <div class="container">
                                <div class="row">
                                    @foreach($contacts as $user)
                                        <div class="col-md-6 mb-4">
                                            <div class="card" 
                                                 style="background-color: 
                                                        {{ $user->type == 'store' ? 'rgb(230, 245, 255)' : ($user->type == 'bm' ? 'rgb(245, 230, 255)' : 'rgb(230, 255, 230)') }};">
                                                <div class="card-body">
                                                    <h5 class="card-title">

                                                        @if($user->type == 'store')

                                                            {{'Store Details'}}
                                                        @elseif($user->type == 'bm')
                                                            {{'Branch Manager Details'}}
                                                        @elseif($user->type == 'tt')
                                                             {{'Territory Manager Details'}}
                                                        @endif
                                                    </h5>
                                                    <p><b>Location:</b> {{ $user->store_location }}</p>
                                                    <p><b>Email:</b> {{ $user->email }}</p>
                                                    <p><b>Phone:</b> {{ $user->phone }}</p>
                                                    @if($user->type == 'bm' || $user->type == 'tt')
                                                        <p>
                                                            <b>Notes:</b> {{ $user->notes }}
                                                            <a href="javascript:void(0);" data-bs-toggle="modal" 
                                                               data-bs-target="#notesModal-{{ $user->id }}" 
                                                               style="color: blue;">Click Here</a>
                                                        </p>
                                                    @else
                                                    <p>&nbsp</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Dynamic Modals -->
                            @foreach($contacts as $user)
                                @if($user->type == 'bm' || $user->type == 'tt')
                                    <div class="modal fade" id="notesModal-{{ $user->id }}" tabindex="-1" 
                                         aria-labelledby="notesModalLabel-{{ $user->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="notesModalLabel-{{ $user->id }}">
                                                        {{ ucfirst($user->type) }} Notes
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" 
                                                            aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <!-- Display Existing Notes -->
                                                    <?php 
                                                    $bm_notes = App\Notes::where('distributer_id',$user->id)->where('type',$user->type.'_notes')->where('notes_type','one_to_one')->get();
                                                    ?>
                                                    @foreach($bm_notes as $note)
                                                        <div class="mb-3 p-2 rounded"
                                                             style="background-color: rgb(240, 248, 255); 
                                                                    border: 1px solid rgb(200, 230, 255);">
                                                            <p class="mb-0">{{ $note->notes }}</p>
                                                        </div>
                                                    @endforeach

                                                    <!-- Form to Add or Update Notes -->
                                                    <form action="{{ route('admin.notesStore', $user->id) }}" method="post">
                                                        @csrf
                                                        <input type="hidden" name="type" value="{{ $user->type }}_notes">
                                                        <input type="hidden" name="notes_type" value="one_to_one">
                                                        <div class="form-group">
                                                            <label for="notes-{{ $user->id }}" 
                                                                   class="form-label fw-bold">Add or Update Notes:</label>
                                                            <textarea name="notes" id="notes-{{ $user->id }}" 
                                                                      class="form-control" rows="5" 
                                                                      placeholder="Write your notes here..."></textarea>
                                                        </div>
                                                        <div class="form-group text-end mt-2">
                                                            <button type="submit" class="btn btn-primary">Submit</button>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" 
                                                            data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                            <!-- End of Modals -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
