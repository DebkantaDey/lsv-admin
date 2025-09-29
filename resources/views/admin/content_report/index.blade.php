@extends('admin.layout.page-app')
@section('page_title', 'Content Report')

@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">Content Report</h1>

            <div class="border-bottom row mb-3">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('Label.Dashboard')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Content Report</li>
                    </ol>
                </div>
            </div>

            <!-- Search -->
            <form action="{{ route('contentreport.index')}}" method="GET">
                <div class="page-search mb-3">
                    <div class="sorting mr-4">
                        <label>Sort by :</label>
                        <select class="form-control" name="input_type" id="input_type">
                            <option value="0" @if(isset($_GET['input_type'])){{ $_GET['input_type'] == 0 ? 'selected' : ''}} @endif>All Content</option>
                            <option value="1" @if(isset($_GET['input_type'])){{ $_GET['input_type'] == 1 ? 'selected' : ''}} @endif>Video</option>
                            <option value="3" @if(isset($_GET['input_type'])){{ $_GET['input_type'] == 3 ? 'selected' : ''}} @endif>Reels</option>
                            <option value="4" @if(isset($_GET['input_type'])){{ $_GET['input_type'] == 4 ? 'selected' : ''}} @endif>Podcasts</option>
                        </select>
                    </div>
                    <div class="sorting mr-2" style="width: 40%;">
                        <label>Sort by :</label>
                        <select class="form-control" name="input_channel" id="input_channel">
                            <option value="0" selected>All User</option>
                            @for ($i = 0; $i < count($channel); $i++) 
                            <option value="{{ $channel[$i]['id'] }}" @if(isset($_GET['input_channel'])){{ $_GET['input_channel'] == $channel[$i]['id'] ? 'selected' : ''}} @endif>
                                {{ $channel[$i]['channel_name'] }}
                            </option>
                            @endfor
                        </select>
                    </div>
                    <button class="btn btn-default" type="submit">SEARCH</button>
                </div>
            </form>

            <div class="row">
                @foreach ($data as $key => $value)
                <div class="col-12 col-sm-6 col-md-4 col-xl-3">
                    <div class="card video-card">
                        <div class="position-relative">
                            <img class="card-img-top" src="{{$value->portrait_img ?? ''}}" alt="" style="height: 150px;">
                            @if($value->video_upload_type == "server_video")
                            <button class="btn play-btn-top video" data-toggle="modal" data-target="#videoModal" data-video="{{$value->video}}" data-image="{{$value->portrait_img}}">
                                <i class="fa-regular fa-circle-play text-white fa-4x mr-2 mt-2"></i>
                            </button>
                            @endif
                        </div>
                        <div class="card-body">
                            <h5 class="card-title" style="font-size: 14px;">{{$value->content->title ?? '-'}}</h5><hr class="mt-0 mb-1">
                            <h5 class="card-title" style="font-size: 14px;"><b>User :</b> {{$value->user->channel_name ?? '-'}}</h5><hr class="mt-0 mb-1">
                            <h5 class="card-title" style="font-size: 14px;"><b>Channel :</b> {{$value->report_user->channel_name ?? '-'}}</h5><hr class="mt-0 mb-2">
                            <h5 style="font-size: 14px;"><b>Message :</b> {{$value->message}}</h5><hr class="mt-0 mb-1">
                            <div class="d-flex justify-content-between">
                                @if($value->content != null && $value->content->status == 1)
                                <button class="btn btn-sm" id="{{$value->content->id}}" onclick="change_status({{$value->content->id}}, {{$value->content->status}})" style="background:#058f00; color:#fff; font-weight:bold; border:none">Show Content</button>
                                @elseif($value->content != null && $value->content->status == 0)
                                <button class="btn btn-sm" id="{{$value->content->id}}" onclick="change_status({{$value->content->id}}, {{$value->content->status}})" style="background:#e3000b; color:#fff; font-weight:bold; border:none">Hide Content</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="modal fade" id="videoModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-body p-0 bg-transparent">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true" class="text-dark">&times;</span>
                            </button>
                            <video controls width="800" height="500" preload='none' poster="" id="theVideo" controlsList="nodownload noplaybackrate" disablepictureinpicture>
                                <source src="">
                            </video>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center">
                <div> Showing {{ $data->firstItem() }} to {{ $data->lastItem() }} of total {{$data->total()}} entries </div>
                <div class="pb-5"> {{ $data->links() }} </div>
            </div>
        </div>
    </div>
@endsection

@section('pagescript')
    <script>

        // Sidebar Scroll Down
        sidebar_down(700);

        $("#input_channel").select2();

        $(function() {
            $(".video").click(function() {
                var theModal = $(this).data("target"),
                    videoSRC = $(this).attr("data-video"),
                    videoPoster = $(this).attr("data-image"),
                    videoSRCauto = videoSRC + "";

                $(theModal + ' source').attr('src', videoSRCauto);
                $(theModal + ' video').attr('poster', videoPoster);
                $(theModal + ' video').load();
                $(theModal + ' button.close').click(function() {
                    $(theModal + ' source').attr('src', videoSRC);
                });
            });
        });
        $("#videoModal .close").click(function() {
            theVideo.pause()
        });

        function change_status(id, status) {
            var Check_Admin = '<?php echo Check_Admin_Access(); ?>';
            if(Check_Admin == 1){

                $("#dvloader").show();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "GET",
                    url: "{{route('contentreport.status')}}",
                    data: {id: id},
                    success: function(resp) {
                        $("#dvloader").hide();
                        get_responce_message(resp, '', '{{ route("contentreport.index") }}');
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        $("#dvloader").hide();
                        toastr.error(errorThrown.msg, 'failed');
                    }
                });
            } else {
                toastr.error('You have no right to Add, Edit and Delete.');
            }
        };
    </script>
@endsection