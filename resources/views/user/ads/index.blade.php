@extends('user.layout.page-app')
@section('page_title', 'Ads')

@section('content')
    @include('user.layout.sidebar')

    <div class="right-content">
        @include('user.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">Ads</h1>

            <div class="border-bottom row mb-3">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">{{__('Label.Dashboard')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Ads</li>
                    </ol>
                </div>
            </div>

            <!-- Search -->
            <form action="{{ route('uads.index')}}" method="GET">
                <div class="page-search mb-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">
                                <i class="fa-solid fa-magnifying-glass fa-xl light-gray"></i>
                            </span>
                        </div>
                        <input type="text" name="input_search" value="@if(isset($_GET['input_search'])){{$_GET['input_search']}}@endif" class="form-control" placeholder="Search Ads" aria-label="Search" aria-describedby="basic-addon1">
                    </div>
                    <div class="sorting mr-2" style="width: 50%;">
                        <label>Sort by :</label>
                        <select class="form-control" name="input_type">
                            <option value="0" @if(isset($_GET['input_type'])){{ $_GET['input_type'] == 0 ? 'selected' : ''}} @endif>All Type</option>
                            <option value="1" @if(isset($_GET['input_type'])){{ $_GET['input_type'] == 1 ? 'selected' : ''}} @endif>Banner Ads</option>
                            <option value="2" @if(isset($_GET['input_type'])){{ $_GET['input_type'] == 2 ? 'selected' : ''}} @endif>Interstital Ads</option>
                            <option value="3" @if(isset($_GET['input_type'])){{ $_GET['input_type'] == 3 ? 'selected' : ''}} @endif>Reward Ads</option>
                        </select>
                    </div>
                    <button class="btn btn-default" type="submit">SEARCH</button>
                </div>
            </form>

            <div class="row">
                <div class="col-12 col-sm-6 col-md-4 col-xl-3" title="Add Ads">
                    <a href="{{ route('uads.create') }}" class="add-video-btn">
                        <i class="fa-regular fa-square-plus fa-3x icon" style="color: #818181;"></i>
                        Add New Ads
                    </a>
                </div>

                @foreach ($data as $key => $value)
                <div class="col-12 col-sm-6 col-md-4 col-xl-3">
                    <div class="card video-card">
                        <div class="position-relative">

                            @if($value->status == 1)
                                <div class="ribbon ribbon-top-left"><span>Active</span></div>
                            @else
                                <div class="ribbon ribbon-top-left"><span>In Active</span></div>
                            @endif

                            <img class="card-img-top" src="{{$value->image}}" alt="">
                            @if($value->type == 3)
                            <button class="btn play-btn-top video" data-toggle="modal" data-target="#videoModal" data-video="{{$value->video}}" data-image="{{$value->image}}">
                                <i class="fa-regular fa-circle-play text-white fa-4x mr-2 mt-2"></i>
                            </button>
                            @endif

                            <ul class="list-inline overlap-control" aria-labelledby="dropdownMenuLink">
                                <li class="list-inline-item">
                                    <a class="btn" href="{{route('uads.details', [$value->id])}}" title="Statistics">
                                        <i class="fa-solid fa-chart-line fa-xl" class="dot-icon" style="color: #4e45b8;"></i>
                                    </a>
                                </li>
                                <li class="list-inline-item">
                                    <a class="btn" href="{{$value->redirect_uri}}" target="_blank" title="Redirect URL">
                                        <i class="fa-solid fa-up-right-from-square fa-xl" class="dot-icon" style="color: #4e45b8;"></i>
                                    </a>
                                </li>
                                <li class="list-inline-item">
                                    <a class="btn" href="{{route('uads.show', [$value->id])}}" title="Delete" onclick="return confirm('Are you sure !!! You want to Delete this Ads ?')">
                                        <i class="fa-solid fa-trash-can fa-xl" class="dot-icon" style="color: #4e45b8;"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">{{$value->title}}</h5>
                            <div class="d-flex justify-content-between">
                                <h5>{{Currency_Code()}}{{$value->budget}}</h5>
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
        </div>
    </div>
@endsection

@section('pagescript')
    <script>
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
    </script>
@endsection