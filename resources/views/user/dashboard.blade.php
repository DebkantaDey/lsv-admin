@extends('user.layout.page-app')
@section('page_title', __('Label.Dashboard'))

@section('content')
    @include('user.layout.sidebar')

    <div class="right-content">
        @include('user.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">{{__('Label.Dashboard')}}</h1>

            <!-- First Counter -->
            <div class="row counter-row">
                <div class="col-6 col-sm-4 col-md col-lg-4 col-xl">
                    <div class="db-color-card color1-card">
                        <i class="fa-solid fa-video fa-4x card-icon"></i>
                        <div class="dropdown dropright">
                            <a href="#" class="btn head-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa-solid fa-ellipsis-vertical fa-xl text-dark dot-icon mr-2"></i>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item color1-viewall" href="{{ route('uvideo.index')}}">{{__('Label.View_All')}}</a>
                            </div>
                        </div>
                        <h2 class="counter">
                            <p class="p-0 m-0 counting" data-count="{{No_Format($VideoCount ?? 0)}}">{{No_Format($VideoCount ?? 0)}}</p>
                            <span>Video</span>
                        </h2>
                    </div>
                </div>
                <div class="col-6 col-sm-4 col-md col-lg-4 col-xl">
                    <div class="db-color-card color2-card">
                        <i class="fa-solid fa-film fa-4x card-icon"></i>
                        <div class="dropdown dropright">
                            <a href="#" class="btn head-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa-solid fa-ellipsis-vertical fa-xl text-dark dot-icon mr-2"></i>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item color2-viewall" href="{{route('ureels.index')}}">{{__('Label.View_All')}}</a>
                            </div>
                        </div>
                        <h2 class="counter">
                            <p class="p-0 m-0 counting" data-count="{{No_Format($ReelsCount ?? 0)}}">{{No_Format($ReelsCount ?? 0)}}</p>
                            <span>Reels</span>
                        </h2>
                    </div>
                </div>
                <div class="col-6 col-sm-4 col-md col-lg-4 col-xl">
                    <div class="db-color-card color3-card">
                        <i class="fa-solid fa-podcast fa-4x card-icon"></i>
                        <div class="dropdown dropright">
                            <a href="#" class="btn head-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa-solid fa-ellipsis-vertical fa-xl text-dark dot-icon mr-2"></i>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item color3-viewall" href="{{ route('upodcasts.index') }}">{{__('Label.View_All')}}</a>
                            </div>
                        </div>
                        <h2 class="counter">
                            <p class="p-0 m-0 counting" data-count="{{No_Format($PodcastsCount ?? 00)}}">{{No_Format($PodcastsCount?? 00)}}</p>
                            <span>Podcasts</span>
                        </h2>
                    </div>
                </div>
                <div class="col-6 col-sm-4 col-md col-lg-4 col-xl">
                    <div class="db-color-card color4-card">
                        <i class="fa-solid fa-headphones fa-4x card-icon"></i>
                        <div class="dropdown dropright">
                            <a href="#" class="btn head-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa-solid fa-ellipsis-vertical fa-xl text-dark dot-icon mr-2"></i>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item color4-viewall" href="{{ route('uplaylist.index') }}">{{__('Label.View_All')}}</a>
                            </div>
                        </div>
                        <h2 class="counter">
                            <p class="p-0 m-0 counting" data-count="{{No_Format($PlaylistCount ?? 00)}}">{{No_Format($PlaylistCount ?? 00)}}</p>
                            <span>Playlist</span>
                        </h2>
                    </div>
                </div>
            </div>

            <!-- Most View Content -->
            <div class="row mb-2">
                <div class="col-12 cart-bg">
                    <div class="box-title">
                        <h2 class="title"><i class="fa-solid fa-chart-bar fa-lg mr-2"></i>Most View Content</h2>
                    </div>

                    <ul class="nav nav-pills custom-tabs" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="pills-video-view-tab" data-toggle="pill" href="#pills-video-view" role="tab" aria-controls="pills-video-view" aria-selected="true">Video</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-reels-view-tab" data-toggle="pill" href="#pills-reels-view" role="tab" aria-controls="pills-reels-view" aria-selected="false">Reels</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-podcasts-view-tab" data-toggle="pill" href="#pills-podcasts-view" role="tab" aria-controls="pills-podcasts-view" aria-selected="false">Podcasts</a>
                        </li>
                    </ul>

                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-video-view" role="tabpanel" aria-labelledby="pills-video-view-tab">
                            <div class="summary-table-card">
                                @for ($i = 0; $i < count($top_video_view); $i++)
                                    <div class="border-card bg-white">
                                        <div class="row">
                                            <div class="col-1">
                                                {{$i + 1 .'.'}}
                                            </div>
                                            <div class="col-9">
                                                <span class="avatar-control">
                                                    <img src="{{$top_video_view[$i]['portrait_img']}}" style='height:40px; width:40px' />
                                                    {{String_Cut($top_video_view[$i]['title'],125)}}
                                                </span>
                                            </div>
                                            <div class="col-2 d-flex justify-content-start">
                                                <i class="fa-solid fa-eye mr-3 fa-xl primary-color"></i>     
                                                <p class="m-0 p-0 counting" data-count="{{No_Format($top_video_view[$i]['total_view'] ?? 00)}}"> {{No_Format($top_video_view[$i]['total_view'] ?? 00)}}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endfor
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pills-reels-view" role="tabpanel" aria-labelledby="pills-reels-view-tab">
                            <div class="summary-table-card">
                                @for ($i = 0; $i < count($top_reels_view); $i++)
                                    <div class="border-card bg-white">
                                        <div class="row">
                                            <div class="col-1">
                                                {{$i + 1 .'.'}}
                                            </div>
                                            <div class="col-9">
                                                <span class="avatar-control">
                                                    <img src="{{ $top_reels_view[$i]['portrait_img'] }}" style='height:40px; width:40px' />
                                                    {{String_Cut($top_reels_view[$i]['title'],125)}}
                                                </span>
                                            </div>
                                            <div class="col-2 d-flex justify-content-start">
                                                <i class="fa-solid fa-eye mr-3 fa-xl primary-color"></i>     
                                                <p class="m-0 p-0 counting" data-count="{{No_Format($top_reels_view[$i]['total_view'] ?? 00)}}"> {{No_Format($top_reels_view[$i]['total_view'] ?? 00)}}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endfor
                            </div>  
                        </div>
                        <div class="tab-pane fade" id="pills-podcasts-view" role="tabpanel" aria-labelledby="pills-podcasts-view-tab">
                            <div class="summary-table-card">
                                @for ($i = 0; $i < count($top_podcasts_view); $i++)
                                    <div class="border-card bg-white">
                                        <div class="row">
                                            <div class="col-1">
                                                {{$i + 1 .'.'}}
                                            </div>
                                            <div class="col-9">
                                                <span class="avatar-control">
                                                    <img src="{{ $top_podcasts_view[$i]['portrait_img'] }}" style='height:40px; width:40px' />
                                                    {{String_Cut($top_podcasts_view[$i]['title'],125)}}
                                                </span>
                                            </div>
                                            <div class="col-2 d-flex justify-content-start">
                                                <i class="fa-solid fa-eye mr-3 fa-xl primary-color"></i>     
                                                <p class="m-0 p-0 counting" data-count="{{No_Format($top_podcasts_view[$i]['total_view'] ?? 00)}}"> {{No_Format($top_podcasts_view[$i]['total_view'] ?? 00)}}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endfor
                            </div>  
                        </div>
                    </div>
                </div>
            </div>

            <!-- Most Like Content-->
            <div class="row mb-2">
                <div class="col-12 cart-bg">
                    <div class="box-title">
                        <h2 class="title"><i class="fa-solid fa-chart-bar fa-lg mr-2"></i>Most Like Content</h2>
                    </div>

                    <ul class="nav nav-pills custom-tabs" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="pills-video-tab" data-toggle="pill" href="#pills-video" role="tab" aria-controls="pills-video" aria-selected="true">Video</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-reels-tab" data-toggle="pill" href="#pills-reels" role="tab" aria-controls="pills-reels" aria-selected="false">Reels</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-podcasts-tab" data-toggle="pill" href="#pills-podcasts" role="tab" aria-controls="pills-podcasts" aria-selected="false">Podcasts</a>
                        </li>
                    </ul>

                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-video" role="tabpanel" aria-labelledby="pills-video-tab">
                            <div class="summary-table-card">
                                @for ($i = 0; $i < count($top_video_like); $i++)
                                    <div class="border-card bg-white">
                                        <div class="row">
                                            <div class="col-1">
                                                {{$i + 1 .'.'}}
                                            </div>
                                            <div class="col-9">
                                                <span class="avatar-control">
                                                    <img src="{{$top_video_like[$i]['portrait_img']}}" style='height:40px; width:40px' />
                                                    {{String_Cut($top_video_like[$i]['title'],125)}}
                                                </span>
                                            </div>
                                            <div class="col-2 d-flex justify-content-start">
                                                <i class="fa-solid fa-thumbs-up mr-3 fa-xl primary-color"></i>     
                                                <p class="m-0 p-0 counting" data-count="{{No_Format($top_video_like[$i]['total_like'] ?? 00)}}"> {{No_Format($top_video_like[$i]['total_like'] ?? 00)}}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endfor
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pills-reels" role="tabpanel" aria-labelledby="pills-reels-tab">
                            <div class="summary-table-card">
                                @for ($i = 0; $i < count($top_reels_like); $i++)
                                    <div class="border-card bg-white">
                                        <div class="row">
                                            <div class="col-1">
                                                {{$i + 1 .'.'}}
                                            </div>
                                            <div class="col-9">
                                                <span class="avatar-control">
                                                    <img src="{{ $top_reels_like[$i]['portrait_img'] }}" style='height:40px; width:40px' />
                                                    {{String_Cut($top_reels_like[$i]['title'],125)}}
                                                </span>
                                            </div>
                                            <div class="col-2 d-flex justify-content-start">
                                                <i class="fa-solid fa-thumbs-up mr-3 fa-xl primary-color"></i>     
                                                <p class="m-0 p-0 counting" data-count="{{No_Format($top_reels_like[$i]['total_like'] ?? 00)}}"> {{No_Format($top_reels_like[$i]['total_like'] ?? 00)}}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endfor
                            </div>  
                        </div>
                        <div class="tab-pane fade" id="pills-podcasts" role="tabpanel" aria-labelledby="pills-podcasts-tab">
                            <div class="summary-table-card">
                                @for ($i = 0; $i < count($top_podcasts_like); $i++)
                                    <div class="border-card bg-white">
                                        <div class="row">
                                            <div class="col-1">
                                                {{$i + 1 .'.'}}
                                            </div>
                                            <div class="col-9">
                                                <span class="avatar-control">
                                                    <img src="{{ $top_podcasts_like[$i]['portrait_img'] }}" style='height:40px; width:40px' />
                                                    {{String_Cut($top_podcasts_like[$i]['title'],125)}}
                                                </span>
                                            </div>
                                            <div class="col-2 d-flex justify-content-start">
                                                <i class="fa-solid fa-thumbs-up mr-3 fa-xl primary-color"></i>     
                                                <p class="m-0 p-0 counting" data-count="{{No_Format($top_podcasts_like[$i]['total_like'] ?? 00)}}"> {{No_Format($top_podcasts_like[$i]['total_like'] ?? 00)}}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endfor
                            </div>  
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection