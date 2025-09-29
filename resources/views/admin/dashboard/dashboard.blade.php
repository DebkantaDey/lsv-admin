@extends('admin.layout.page-app')
@section('page_title', __('Label.Dashboard'))

@section('content')
        @include('admin.layout.sidebar')

        <div class="right-content">
            @include('admin.layout.header')

            <div class="body-content">
                <!-- mobile title -->
                <h1 class="page-title-sm">{{__('Label.Dashboard')}}</h1>

                <!-- First Counter -->
                <div class="row counter-row">
                    <div class="col-6 col-sm-4 col-md col-lg-4 col-xl">
                        <div class="db-color-card color1-card">
                            <i class="fa-solid fa-users fa-4x card-icon"></i>
                            <div class="dropdown dropright">
                                <a href="#" class="btn head-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical fa-xl text-dark dot-icon mr-2"></i>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    <a class="dropdown-item color1-viewall" href="{{ route('user.index')}}">{{__('Label.View_All')}}</a>
                                </div>
                            </div>
                            <h2 class="counter">
                                <p class="p-0 m-0 counting" data-count="{{No_Format($UserCount ?? 0)}}">{{No_Format($UserCount ?? 0)}}</p>
                                <span>{{__('Label.Users')}}</span>
                            </h2>
                        </div>
                    </div>
                    <div class="col-6 col-sm-4 col-md col-lg-4 col-xl">
                        <div class="db-color-card color2-card">
                            <i class="fa-solid fa-shapes fa-4x card-icon"></i>
                            <div class="dropdown dropright">
                                <a href="#" class="btn head-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical fa-xl text-dark dot-icon mr-2"></i>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    <a class="dropdown-item color2-viewall" href="{{route('category.index')}}">{{__('Label.View_All')}}</a>
                                </div>
                            </div>
                            <h2 class="counter">
                                <p class="p-0 m-0 counting" data-count="{{No_Format($CategoryCount ?? 0)}}">{{No_Format($CategoryCount ?? 0)}}</p>
                                <span>Category</span>
                            </h2>
                        </div>
                    </div>
                    <div class="col-6 col-sm-4 col-md col-lg-4 col-xl">
                        <div class="db-color-card color3-card">
                            <i class="fa-solid fa-globe fa-4x card-icon"></i>
                            <div class="dropdown dropright">
                                <a href="#" class="btn head-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical fa-xl text-dark dot-icon mr-2"></i>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    <a class="dropdown-item color3-viewall" href="{{route('language.index')}}">{{__('Label.View_All')}}</a>
                                </div>
                            </div>
                            <h2 class="counter">
                                <p class="p-0 m-0 counting" data-count="{{No_Format($LanguageCount ?? 0)}}">{{No_Format($LanguageCount ?? 0)}}</p>
                                <span>Language</span>
                            </h2>
                        </div>
                    </div>
                    <div class="col-6 col-sm-4 col-md col-lg-4 col-xl">
                        <div class="db-color-card color4-card">
                            <i class="fa-solid fa-user-tie fa-4x card-icon"></i>
                            <div class="dropdown dropright">
                                <a href="#" class="btn head-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical fa-xl text-dark dot-icon mr-2"></i>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    <a class="dropdown-item color4-viewall" href="{{route('artist.index')}}">{{__('Label.View_All')}}</a>
                                </div>
                            </div>
                            <h2 class="counter">
                                <p class="p-0 m-0 counting" data-count="{{No_Format($ArtistCount ?? 0)}}">{{No_Format($ArtistCount ?? 0)}}</p>
                                <span>Artist</span>
                            </h2>
                        </div>
                    </div>
                    <div class="col-6 col-sm-4 col-md col-lg-4 col-xl">
                        <div class="db-color-card color5-card">
                            <i class="fa-solid fa-headphones fa-4x card-icon"></i>
                            <div class="dropdown dropright">
                                <a href="#" class="btn head-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical fa-xl text-dark dot-icon mr-2"></i>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    <a class="dropdown-item color5-viewall" href="{{ route('playlist.index') }}">{{__('Label.View_All')}}</a>
                                </div>
                            </div>
                            <h2 class="counter mt-4">
                                <p class="p-0 m-0 counting" data-count="{{No_Format($PlaylistCount ?? 00)}}">{{No_Format($PlaylistCount ?? 00)}}</p>
                                <span>Playlist</span>
                            </h2>
                        </div>
                    </div>
                </div>

                <!-- Second Counter -->
                <div class="row counter-row">
                    <div class="col-6 col-sm-4 col-md col-lg-4 col-xl">
                        <div class="db-color-card color6-card">
                            <i class="fa-solid fa-video fa-4x card-icon"></i>
                            <div class="dropdown dropright">
                                <a href="#" class="btn head-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical fa-xl text-dark dot-icon mr-2"></i>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    <a class="dropdown-item color6-viewall" href="{{route('video.index') }}">{{__('Label.View_All')}}</a>
                                </div>
                            </div>
                            <h2 class="counter">
                                <p class="p-0 m-0 counting" data-count="{{No_Format($VideoCount ?? 00)}}">{{No_Format($VideoCount ?? 00)}}</p>
                                <span>Video</span>
                            </h2>
                        </div>
                    </div>
                    <div class="col-6 col-sm-4 col-md col-lg-4 col-xl">
                        <div class="db-color-card color7-card">
                            <i class="fa-solid fa-music fa-4x card-icon"></i>
                            <div class="dropdown dropright">
                                <a href="#" class="btn head-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical fa-xl text-dark dot-icon mr-2"></i>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    <a class="dropdown-item color7-viewall" href="{{route('music.index') }}">{{__('Label.View_All')}}</a>
                                </div>
                            </div>
                            <h2 class="counter">
                                <p class="p-0 m-0 counting" data-count="{{No_Format($MusicCount ?? 00)}}">{{No_Format($MusicCount ?? 00)}}</p>
                                <span>Music</span>
                            </h2>
                        </div>
                    </div>
                    <div class="col-6 col-sm-4 col-md col-lg-4 col-xl">
                        <div class="db-color-card color8-card">
                            <i class="fa-solid fa-film fa-4x card-icon"></i>
                            <div class="dropdown dropright">
                                <a href="#" class="btn head-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical fa-xl text-dark dot-icon mr-2"></i>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    <a class="dropdown-item color8-viewall" href="{{route('reels.index') }}">{{__('Label.View_All')}}</a>
                                </div>
                            </div>
                            <h2 class="counter">
                                <p class="p-0 m-0 counting" data-count="{{No_Format($ReelsCount ?? 00)}}">{{No_Format($ReelsCount ?? 00)}}</p>
                                <span>Reels</span>
                            </h2>
                        </div>
                    </div>
                    <div class="col-6 col-sm-4 col-md col-lg-4 col-xl">
                        <div class="db-color-card color9-card">
                            <i class="fa-solid fa-podcast fa-4x card-icon"></i>
                            <div class="dropdown dropright">
                                <a href="#" class="btn head-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical fa-xl text-dark dot-icon mr-2"></i>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    <a class="dropdown-item color9-viewall" href="{{ route('podcasts.index') }}">{{__('Label.View_All')}}</a>
                                </div>
                            </div>
                            <h2 class="counter mt-4">
                                <p class="p-0 m-0 counting" data-count="{{No_Format($PodcastsCount ?? 00)}}">{{No_Format($PodcastsCount?? 00)}}</p>
                                <span>Podcasts</span>
                            </h2>
                        </div>
                    </div>
                    <div class="col-6 col-sm-4 col-md col-lg-4 col-xl">
                        <div class="db-color-card color10-card">
                            <i class="fa-solid fa-radio fa-4x card-icon"></i>
                            <div class="dropdown dropright">
                                <a href="#" class="btn head-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical fa-xl text-dark dot-icon mr-2"></i>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    <a class="dropdown-item color10-viewall" href="{{route('radio.index') }}">{{__('Label.View_All')}}</a>
                                </div>
                            </div>
                            <h2 class="counter">
                                <p class="p-0 m-0 counting" data-count="{{No_Format($RadioCount ?? 00)}}">{{No_Format($RadioCount ?? 00)}}</p>
                                <span>Radio</span>
                            </h2>
                        </div>
                    </div>
                </div>

                <!-- Join User Statistice && Most Subscribed Channel -->
                <div class="row mb-2">
                    <div class="col-12 col-xl-8 cart-bg">
                        <div class="box-title">
                            <h2 class="title"><i class="fa-solid fa-chart-column fa-lg mr-2"></i>Join Users Statistice (Current Year)</h2>
                            <a href="{{ route('user.index') }}" class="btn btn-link">{{__('Label.View_All')}}</a>
                        </div>
                        <div class="row mt-2 mb-2">
                            <div class="col-12 col-sm-12">
                                <Button id="year" class="btn btn-default">This Year</Button>
                                <Button id="month" class="btn btn-default">This Month</Button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-sm-12">
                                <canvas id="UserChart" width="100%" height="40px"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-xl-4">
                        <div class="video-box pb-2">
                            <div class="box-title mt-0">
                                <h2 class="title"><i class="fa-solid fa-chart-bar fa-lg mr-2"></i>Most Subscribed Channel</h2>
                            </div>
                            <div class="summary-table-card mt-2">
                                @if(isset($top_subscriber) && $top_subscriber != null)
                                    @for ($i = 0; $i < count($top_subscriber); $i++) 
                                        @if(isset($top_subscriber[$i]['to_user']) && $top_subscriber[$i]['to_user'] !=null)
                                            <div class="border-card bg-white">
                                                <div class="row">
                                                    <div class="col-10">
                                                        <span class="avatar-control">
                                                            <img src="{{ $top_subscriber[$i]['to_user']['image'] }}" class="avatar-img" style='height:50px; width:50px' />
                                                            {{ String_Cut($top_subscriber[$i]['to_user']['channel_name'],22) }}
                                                        </span>
                                                    </div>
                                                    <div class="col-2">
                                                        <h5 class="counting pt-2" data-count="{{No_Format($top_subscriber[$i]['total_subscriber'] ?? 0)}}">{{No_Format($top_subscriber[$i]['total_subscriber'] ?? 0)}} </h5>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endfor
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Most View Content && Best Category -->
                <div class="row mb-2">
                    <div class="col-12 col-xl-8 cart-bg">
                        <div class="box-title">
                            <h2 class="title"><i class="fa-solid fa-chart-bar fa-lg mr-2"></i>Most View Content</h2>
                        </div>

                        <ul class="nav nav-pills custom-tabs" id="pills-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="pills-video-view-tab" data-toggle="pill" href="#pills-video-view" role="tab" aria-controls="pills-video-view" aria-selected="true">Video</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="pills-music-view-tab" data-toggle="pill" href="#pills-music-view" role="tab" aria-controls="pills-music-view" aria-selected="false">Music</a>
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
                                                        {{String_Cut($top_video_view[$i]['title'],65)}}
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
                            <div class="tab-pane fade" id="pills-music-view" role="tabpanel" aria-labelledby="pills-music-view-tab">
                                <div class="summary-table-card">
                                    @for ($i = 0; $i < count($top_music_view); $i++)
                                        <div class="border-card bg-white">
                                            <div class="row">
                                                <div class="col-1">
                                                    {{$i + 1 .'.'}}
                                                </div>
                                                <div class="col-9">
                                                    <span class="avatar-control">
                                                        <img src="{{ $top_music_view[$i]['portrait_img'] }}" style='height:40px; width:40px' />
                                                        {{String_Cut($top_music_view[$i]['title'],65)}}
                                                    </span>
                                                </div>
                                                <div class="col-2 d-flex justify-content-start">
                                                    <i class="fa-solid fa-eye mr-3 fa-xl primary-color"></i>
                                                    <p class="m-0 p-0 counting" data-count="{{No_Format($top_music_view[$i]['total_view'] ?? 00)}}"> {{No_Format($top_music_view[$i]['total_view'] ?? 00)}}</p>
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
                                                        {{String_Cut($top_reels_view[$i]['title'],65)}}
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
                                                        {{String_Cut($top_podcasts_view[$i]['title'],65)}}
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
                    
                    <div class="col-12 col-xl-4">
                        <div class="category-box">
                            <div class="box-title mt-0">
                                <h2 class="title"><i class="fa-solid fa-table-cells-large fa-lg mr-2"></i>Best Category</h2>
                                <a href="{{ route('category.index')}}" class="btn btn-link">{{__('Label.View_All')}}</a>
                            </div>
                            <div class="pt-3 mt-0">
                                <div class="row pr-3">
                                    @for ($i = 0; $i < count($best_category); $i++)
                                        @if($i> 0 && (($i % 4) == 1 || ($i % 4) == 2))
                                            <div class="col-5 mb-2 pr-0">
                                                <img src="{{$best_category[$i]['image']}}" class="category-image">
                                                <div class="centered">{{$best_category[$i]['name']}}</div>
                                            </div>
                                        @else
                                            <div class="col-7 mb-2 pr-0">
                                                <img src="{{$best_category[$i]['image']}}" class="category-image">
                                                <div class="centered">{{$best_category[$i]['name']}}</div>
                                            </div>
                                        @endif
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Most Famous Artist -->
                <div class="row mb-2">
                    <div class="col-12 cart-bg">
                        <div class="box-title">
                            <h2 class="title"><i class="fa-solid fa-user-tie fa-lg mr-2"></i>Most Famous Artist</h2>
                            <a href="{{ route('artist.index')}}" class="btn btn-link">{{__('Label.View_All')}}</a>
                        </div>
                        <div class="row p-2 pl-3">
                            @if(isset($top_artist) && $top_artist != null)
                                @for ($i = 0; $i < count($top_artist); $i++)
                                    <div class="col-6 col-md-1 bg-white pt-2 pb-2 mr-2" style="border-radius: 10px;">
                                        <div class="avatar-control">
                                            @if(isset($top_artist[$i]['artist']) != null && $top_artist[$i]['artist']->image)
                                                <img src="{{$top_artist[$i]['artist']->image}}" class="artist-image" />
                                            @else
                                                <img src="{{asset('assets/imgs/default.png')}}" class="artist-image" />
                                            @endif
                                        </div>
                                        <h6 class="mt-1 mb-0 artist-name">{{ $top_artist[$i]['artist']->name ?? "-" }}</h6>
                                        <h6 class="mt-1 mb-0 artist-name counting" data-count="{{No_Format($top_artist[$i]->total_count ?? 0)}}">{{No_Format($top_artist[$i]->total_count ?? 0)}}</h6>
                                    </div>
                                    @if($i == 10)
                                        @break;
                                    @endif
                                @endfor
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Most Like Content && Most Used Hashtag -->
                <div class="row mb-2">
                    <div class="col-12 col-xl-8 cart-bg">
                        <div class="box-title">
                            <h2 class="title"><i class="fa-solid fa-chart-bar fa-lg mr-2"></i>Most Like Content</h2>
                        </div>

                        <ul class="nav nav-pills custom-tabs" id="pills-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="pills-video-tab" data-toggle="pill" href="#pills-video" role="tab" aria-controls="pills-video" aria-selected="true">Video</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="pills-music-tab" data-toggle="pill" href="#pills-music" role="tab" aria-controls="pills-music" aria-selected="false">Music</a>
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
                                                        {{String_Cut($top_video_like[$i]['title'],65)}}
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
                            <div class="tab-pane fade" id="pills-music" role="tabpanel" aria-labelledby="pills-music-tab">
                                <div class="summary-table-card">
                                    @for ($i = 0; $i < count($top_music_like); $i++)
                                        <div class="border-card bg-white">
                                            <div class="row">
                                                <div class="col-1">
                                                    {{$i + 1 .'.'}}
                                                </div>
                                                <div class="col-9">
                                                    <span class="avatar-control">
                                                        <img src="{{ $top_music_like[$i]['portrait_img'] }}" style='height:40px; width:40px' />
                                                        {{String_Cut($top_music_like[$i]['title'],65)}}
                                                    </span>
                                                </div>
                                                <div class="col-2 d-flex justify-content-start">
                                                    <i class="fa-solid fa-thumbs-up mr-3 fa-xl primary-color"></i>
                                                    <p class="m-0 p-0 counting" data-count="{{No_Format($top_music_like[$i]['total_like'] ?? 00)}}"> {{No_Format($top_music_like[$i]['total_like'] ?? 00)}}</p>
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
                                                        {{String_Cut($top_reels_like[$i]['title'],65)}}
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
                                                        {{String_Cut($top_podcasts_like[$i]['title'],65)}}
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
                    <div class="col-12 col-xl-4">
                        <div class="video-box pb-2">
                            <div class="box-title mt-0">
                                <h2 class="title"><i class="fa-solid fa-hashtag fa-lg mr-2"></i>Most Used Hashtag</h2>
                                <a href="{{ route('hashtag.index') }}" class="btn btn-link">{{__('Label.View_All')}}</a>
                            </div>
                            <div class="summary-table-card mt-2">
                                @for ($i = 0; $i < count($most_used_hashtag); $i++)
                                    <div class="hashtag-card mb-3">
                                        <div class="row">
                                            <div class="col-10 pl-2">
                                                <p class="m-0">{{String_Cut($most_used_hashtag[$i]['name'],28)}}</p>
                                            </div>
                                            <div class="col-2 pl-0">
                                                <p class="m-0 counting" data-count="{{No_Format($most_used_hashtag[$i]['total_used'] ?? 0)}}">{{No_Format($most_used_hashtag[$i]['total_used'] ?? 0)}}</p>
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
@endsection

@section('pagescript')
    <script>
        var month = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

        // User Statistice
        var cData = JSON.parse(`<?php echo $user_year; ?>`);
        var ctx = $("#UserChart");
        var data = {
            labels: month,
            datasets: [{
                label: 'Users',
                data: cData['sum'],
                backgroundColor: '#4e45b8',
            }],
        };
        var options = {
            responsive: true,
            legend: {
                title: "text",
                display: true,
                position: 'top',
                labels: {
                    fontSize: 16,
                    fontColor: "#000000",
                }
            },
            scales: {
                yAxes: [{
                    scaleLabel: {
                        display: true,
                        labelString: 'Total Count',
                        fontSize: 16,
                        fontColor: "#000000",
                    },
                }],
                xAxes: [{
                    scaleLabel: {
                        display: true,
                        labelString: 'Month',
                        fontSize: 16,
                        fontColor: "#000000",
                    }
                }]
            }
        };
        var chart1 = new Chart(ctx, {
            type: "bar",
            data: data,
            options: options
        });
        $("#year").on("click", function() {
            chart1.destroy();

            chart1 = new Chart(ctx, {
                type: "bar",
                data: data,
                options: options

            });
        });
        $("#month").on("click", function() {

            var date = new Date();
            var currentYear = date.getFullYear();
            var currentMonth = date.getMonth() + 1;
            const getDays = (year, month) => new Date(year, month, 0).getDate();
            const days = getDays(currentYear, currentMonth);

            var all1 = [];
            for (let i = 0; i < days; i++) {
                all1.push(i + 1);
            }

            chart1.destroy();
            var cData = JSON.parse(`<?php echo $user_month ?>`);

            var data = {
                labels: all1,
                datasets: [{
                    label: 'Users',
                    data: cData['sum'],
                    backgroundColor: '#4e45b8',
                }],
            };
            var options = {
                responsive: true,
                legend: {
                    title: "text",
                    display: true,
                    position: 'top',
                    labels: {
                        fontSize: 16,
                        fontColor: "#000000",
                    }
                },
                scales: {
                    yAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: 'Total Count',
                            fontSize: 16,
                            fontColor: "#000000",
                        },
                    }],
                    xAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: 'Date',
                            fontSize: 16,
                            fontColor: "#000000",
                        }
                    }]
                }
            };
            chart1 = new Chart(ctx, {
                type: "bar",
                data: data,
                options: options,
            });
        });
    </script>
@endsection