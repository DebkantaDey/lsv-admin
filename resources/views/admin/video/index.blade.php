@extends('admin.layout.page-app')
@section('page_title', __('Label.Videos'))

@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm"> {{__('Label.Videos')}} </h1>

            <div class="border-bottom row mb-3">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('Label.Dashboard')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('Label.Videos')}}</li>
                    </ol>
                </div>
                <!-- <div class="col-sm-2 d-flex align-items-center justify-content-end">
                    <a href="#" data-toggle="modal" data-target="#exampleModal" data-backdrop="static" class="btn btn-default mw-120" style="margin-top:-14px">Import Youtube Video</a>
                </div> -->
            </div>

            <!-- Search -->
            <form action="{{ route('video.index')}}" method="GET">
                <div class="page-search mb-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">
                                <i class="fa-solid fa-magnifying-glass fa-xl light-gray"></i>
                            </span>
                        </div>
                        <input type="text" name="input_search" value="@if(isset($_GET['input_search'])){{$_GET['input_search']}}@endif" class="form-control" placeholder="Search Video" aria-label="Search" aria-describedby="basic-addon1">
                    </div>
                    <div class="sorting mr-2" style="width: 60%;">
                        <label>Sort by :</label>
                        <select class="form-control" name="input_channel" id="input_channel">
                            <option value="0" selected>All Channel</option>
                            @for ($i = 0; $i < count($channel); $i++) 
                            <option value="{{ $channel[$i]['channel_id'] }}" @if(isset($_GET['input_channel'])){{ $_GET['input_channel'] == $channel[$i]['channel_id'] ? 'selected' : ''}} @endif>
                                {{ $channel[$i]['channel_name'] }}
                            </option>
                            @endfor
                        </select>
                    </div>
                    <div class="sorting mr-2" style="width: 50%;">
                        <label>Sort by :</label>
                        <select class="form-control" name="input_category" id="input_category">
                            <option value="0" selected>All Category</option>
                            @for ($i = 0; $i < count($category); $i++) 
                            <option value="{{ $category[$i]['id'] }}" @if(isset($_GET['input_category'])){{ $_GET['input_category'] == $category[$i]['id'] ? 'selected' : ''}} @endif>
                                {{ $category[$i]['name'] }}
                            </option>
                            @endfor
                        </select>
                    </div>
                    <div class="sorting mr-2" style="width: 50%;">
                        <label>Sort by :</label>
                        <select class="form-control" name="input_language" id="input_language">
                            <option value="0" selected>All Language</option>
                            @for ($i = 0; $i < count($language); $i++) 
                            <option value="{{ $language[$i]['id'] }}" @if(isset($_GET['input_language'])){{ $_GET['input_language'] == $language[$i]['id'] ? 'selected' : ''}} @endif>
                                {{ $language[$i]['name'] }}
                            </option>
                            @endfor
                        </select>
                    </div>
                    <div class="sorting mr-4" style="width: 48%;">
                        <label>Sort by :</label>
                        <select class="form-control" name="input_rent" id="input_rent">
                            <option value="0" @if(isset($_GET['input_rent'])){{ $_GET['input_rent'] == 0 ? 'selected' : ''}} @endif>All Video</option>
                            <option value="1" @if(isset($_GET['input_rent'])){{ $_GET['input_rent'] == 1 ? 'selected' : ''}} @endif>Rent Video</option>
                            <option value="2" @if(isset($_GET['input_rent'])){{ $_GET['input_rent'] == 2 ? 'selected' : ''}} @endif>Free Video</option>
                        </select>
                    </div>
                    <button class="btn btn-default" type="submit">SEARCH</button>
                </div>
            </form>

            <div class="row">
                <div class="col-12 col-sm-6 col-md-4 col-xl-3">
                    <a href="{{ route('video.create') }}" class="add-video-btn">
                        <i class="fa-regular fa-square-plus fa-3x icon" style="color: #818181;"></i>
                        Add New Video
                    </a>
                </div>

                @foreach ($data as $key => $value)
                <div class="col-12 col-sm-6 col-md-4 col-xl-3">
                    <div class="card video-card">
                        <div class="position-relative">

                            @if($value->is_rent == 1)
                                <div class="ribbon ribbon-top-left"><span>On Rent</span></div>
                            @endif

                            <img class="card-img-top" src="{{$value->portrait_img}}" alt="">
                            @if($value->content_upload_type == "server_video")
                            <button class="btn play-btn-top video" data-toggle="modal" data-target="#videoModal" data-video="{{$value->content}}" data-image="{{$value->landscape_img}}">
                                <i class="fa-regular fa-circle-play text-white fa-4x mr-2 mt-2"></i>
                            </button>
                            @endif

                            <ul class="list-inline overlap-control" aria-labelledby="dropdownMenuLink">
                                <li class="list-inline-item">
                                    <a class="btn" href="{{route('video.edit', [$value->id])}}" title="Edit">
                                        <i class="fa-solid fa-pen-to-square fa-xl" class="dot-icon" style="color: #4e45b8;"></i>
                                    </a>
                                </li>
                                <li class="list-inline-item">
                                    <a class="btn" href="{{route('video.show', [$value->id])}}" title="Delete" onclick="return confirm('Are you sure !!! You want to Delete this Video ?')">
                                        <i class="fa-solid fa-trash-can fa-xl" class="dot-icon" style="color: #4e45b8;"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">{{$value->title}}</h5>
                            <div class="d-flex justify-content-between">
                                @if($value->status == 1)
                                <button class="btn btn-sm" id="{{$value->id}}" onclick="change_status({{$value->id}}, {{$value->status}})" style="background:#058f00; color:#fff; font-weight:bold; border:none">Show</button>
                                @elseif($value->status == 0)
                                <button class="btn btn-sm" id="{{$value->id}}" onclick="change_status({{$value->id}}, {{$value->status}})" style="background:#e3000b; color:#fff; font-weight:bold; border:none">Hide</button>
                                @endif

                                @if($value->is_rent == 1)
                                    <h5>{{Currency_Code()}}{{$value->rent_price}}</h5>
                                @endif

                                <div class="d-flex text-align-center">
                                    <span class="d-flex text-align-center mr-3">
                                        <i class="fa-solid fa-thumbs-up fa-xl mr-3" style="color:#4e45b8; margin-top:12px"></i>
                                        <h5 class="counting" data-count="{{No_Format($value->total_like ?? 0)}}">{{No_Format($value->total_like)}}</h5>
                                    </span>
                                    <span class="d-flex text-align-center">
                                        <i class="fa-regular fa-eye fa-xl mr-3" style="color:#4e45b8; margin-top:12px"></i>
                                        <h5 class="counting" data-count="{{No_Format($value->total_view ?? 0)}}">{{No_Format($value->total_view)}}</h5>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

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
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center">
                <div> Showing {{ $data->firstItem() }} to {{ $data->lastItem() }} of total {{$data->total()}} entries </div>
                <div class="pb-5"> {{ $data->links() }} </div>
            </div>

            <!-- Modal -->
            <div class="modal fade bd-example-modal-lg" id="exampleModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Import Youtube Video</h5>
                            <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="youtube_video" enctype="multipart/form-data">
                            <div class="modal-body">
                                <div class="form-row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Channel</label>
                                            <select class="form-control" name="channel_id" id="channel_id" style="width:100%!important;">
                                                <option value="" selected>Select Channel</option>
                                                @foreach ($channel as $key => $value)
                                                <option value="{{$value->channel_id}}"> 
                                                    {{$value->channel_name}} || {{$value->email}} || {{$value->mobile_number}}
                                                </option>
                                                @endforeach  
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__('Label.Category')}}</label>
                                            <select class="form-control" name="category_id" id="category_id" style="width:100%!important;">
                                                <option value="">{{__('Label.Select Category')}}</option>
                                                @foreach ($category as $key => $value)
                                                <option value="{{$value->id}}"> 
                                                    {{$value->name}} 
                                                </option>
                                                @endforeach  
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__('Label.Language')}}</label>
                                            <select class="form-control" name="language_id" id="language_id" style="width:100%!important;">
                                                <option value="">{{__('Label.Select Language')}}</option>
                                                @foreach ($language as $key => $value)
                                                <option value="{{$value->id}}"> 
                                                    {{$value->name}} 
                                                </option>
                                                @endforeach  
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-12 mb-3">
                                        <div class="form-group">
                                            <label>Channel Id (As Per Youtube)</label>
                                            <input type="text" name="youtube_channel_id" class="form-control" placeholder="Enter Channel Id">
                                            <label class="mt-1 text-gray">Search for Youtube Channel Id : <a href="https://support.google.com/youtube/answer/3250431?hl=en" target="_blank" class="btn-link">Click Here</a></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default mw-120" onclick="save_youtube_video()">{{__('Label.SAVE')}}</button>
                                <button type="button" class="btn btn-cancel mw-120 ml-2" data-dismiss="modal">{{__('Label.CANCEL')}}</button>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('pagescript')
    <script>
        $("#input_channel").select2();
        $("#input_category").select2();
        $("#input_language").select2();
        $("#channel_id").select2();
        $("#category_id").select2();
        $("#language_id").select2();

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
                    url: "{{route('video.status')}}",
                    data: {id: id},
                    success: function(resp) {
                        $("#dvloader").hide();
                        if (resp.status == 200) {

                            if (resp.Status == 1) {
                                $('#' + id).text('Show');
                                $('#' + id).css({
                                    "background": "#058f00",
                                    "color": "white",
                                    "font-weight": "bold",
                                    "border": "none"
                                });
                            } else {
                                $('#' + id).text('Hide');
                                $('#' + id).css({
                                    "background": "#e3000b",
                                    "color": "white",
                                    "font-weight": "bold",
                                    "border": "none"
                                });
                            }
                        } else {
                            toastr.error(resp.errors);
                        }
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

        function save_youtube_video(){

            var Check_Admin = '<?php echo Check_Admin_Access(); ?>';
            if(Check_Admin == 1){

                $("#dvloader").show();
                var formData = new FormData($("#youtube_video")[0]);
                $.ajax({
                    type:'POST',
                    url:'{{ route("video.import") }}',
                    data:formData,
                    cache:false,
                    contentType: false,
                    processData: false,
                    success:function(resp){
                        $("#dvloader").hide();
                        get_responce_message(resp, 'youtube_video', '{{ route("video.index") }}');
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        $("#dvloader").hide();
                        toastr.error(errorThrown.msg,'failed');         
                    }
                });
            } else {
                toastr.error('You have no right to Add, Edit and Delete.');
            }
		}
    </script>
@endsection