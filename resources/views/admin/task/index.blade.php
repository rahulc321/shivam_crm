@extends('layouts.admin')
@section('title', 'CRM - Task')
@section('content')
<style>
.tooltip-inner {
    max-width: 300px;
    /* Adjust this to set the desired width */
    width: auto;
    /* Allow auto width if needed */
    background-color: #e8f5e9;
    /* Light green background */
    color: #2e7d32;
    /* Dark green text */
    border-radius: 8px;
    /* Rounded corners */
    font-size: 14px;
    padding: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    /* Box shadow for tooltip */
    border: 1px solid #c8e6c9;
    /* Slightly darker border for contrast */
    text-align: left;
    /* Align text to the left */
}

.tooltip-arrow {
    color: #e8f5e9;
    /* Matches the background of the tooltip */
}
</style>
<div class="main-content app-content">
    <div class="container-fluid">
        <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <!-- <h1 class="page-title fw-medium fs-18 mb-2">Data Tables</h1> -->
                <div class="">
                    <nav>
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Task</li>
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
                            List Task
                        </div>
                        @if (Auth::user()->roles->contains('title', 'Admin'))
                        <a class="" href='{{ route("admin.task.create") }}' style="float:right !important"><span
                                class="badge bg-outline-info">Create New Task</span></a>
                        @endif
                    </div>

                    <div class="card-body">

                        <div class="table-responsive">
                            <table id="datatable-basic" class="table table-bordered text-nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Title</th>
                                        <th>Description</th>
                                        <th>End User</th>
                                        <th>Posted By</th>
                                        <th>Assign To</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tasks as $key => $value)

                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{$value->title}}</td>
                                        <td>{{$value->description}}</td>
                                        <td class="end_user" data-bs-toggle="tooltip" data-bs-html="true" title="<strong>Email:</strong> {{$value->end_user_name->email}}<br>
           <strong>Phone:</strong> {{$value->end_user_name->phone_number}}<br>
           <strong>Address:</strong> {{$value->end_user_name->address}}">
                                            <span
                                                class="badge bg-outline-success">{{$value->end_user_name->full_name}}</span>
                                        </td>

                                        <td><span class="badge bg-outline-info">{{@$value->postedBy->full_name}}</span>
                                        </td>
                                        <td>
                                            @foreach(@$value->receivers as $receiver)
                                            <span class="badge bg-outline-success" data-bs-toggle="tooltip"
                                                data-bs-html="true" title="<strong>Email:</strong> {{ $receiver->email }}<br>
               <strong>Phone:</strong> {{ $receiver->phone_number }}<br>
               <strong>Address:</strong> {{ $receiver->address }}">
                                                {{ $receiver->full_name }}
                                            </span>
                                            @endforeach
                                        </td>






                                        <td>
                                            @php
                                            $badgeClass = match($value->status) {
                                            'pending' => 'bg-outline-warning',
                                            'reject' => 'bg-outline-danger',
                                            'completed'=> 'bg-outline-success',
                                            'in_progress'=> 'bg-outline-info',
                                            default => 'bg-outline-secondary',
                                            };
                                            @endphp
                                            <span class="badge {{ $badgeClass }}">{{ $value->status }}</span>
                                        </td>
                                        <td>
                                            @if (Auth::user()->roles->contains('title', 'Admin'))
                                            <a class="" href="{{ route('admin.task.edit', $value->id) }}">
                                                <span class="badge bg-outline-info">Edit</span>
                                            </a>

                                            <a class="" href="javascript:;"
                                                onclick="if(confirm('Are you sure you want to delete this?')) { event.preventDefault(); document.getElementById('deleteFrm<?=$key?>').submit(); }">
                                                <span class="badge bg-outline-secondary">Delete</span>
                                            </a>


                                            <a
                                                href="{{ route('admin.chat.index', $value->receivers[0]->id) }}?taskId={{$value->id}}">
                                                <span class="badge bg-outline-info msg_{{$value->id}}">
                                                    <i class="ri-chat-4-fill"></i>
                                                    <!-- Unread count as a circle -->
                                                    <span class="unread-count" id="unread-count-{{$value->id}}">
                                                        0
                                                        <!-- Default unread count, will be updated by JS -->
                                                    </span>
                                                </span>
                                            </a>

                                            @else

                                            <a href="{{ route('admin.chat.index', 1) }}?taskId={{$value->id}}">
                                                <span class="badge bg-outline-info msg_{{$value->id}}">
                                                    <i class="ri-chat-4-fill"></i>
                                                    <!-- Unread count as a circle -->
                                                    <span class="unread-count" id="unread-count-{{$value->id}}">
                                                        0
                                                        <!-- Default unread count, will be updated by JS -->
                                                    </span>
                                                </span>
                                            </a>


                                            @endif

                                            <form id="deleteFrm{{$key}}"
                                                action="{{ route('admin.task.destroy', $value->id) }}" method="POST"
                                                onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                                style="display: inline-block;">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="submit" class="badge bg-outline-secondary" value="Delete">
                                            </form>

                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<script>
// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});



// Collect task IDs in batches (for example, 100 tasks at a time)
let taskIds = @json($tasks).map(task => task.id);

// Open SSE connection for batch processing
const eventSource = new EventSource("{{ route('admin.getUnreadMessageCounts') }}?task_ids=" + taskIds.join(','));

eventSource.onmessage = function(event) {
    const data = JSON.parse(event.data);
    for (let taskId in data) {
        const unreadCount = data[taskId];
        const unreadCountElement = document.getElementById(`unread-count-${taskId}`);
        if (unreadCountElement) {
            unreadCountElement.textContent = unreadCount;
        }
    }
};

eventSource.onerror = function() {
    console.log("Error with SSE connection");
};
</script>

@endsection