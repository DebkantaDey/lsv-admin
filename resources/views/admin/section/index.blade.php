@extends('admin.layout.page-app')
@section('page_title', 'Section')

@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">Section</h1>

            <div class="border-bottom row mb-3">
                <div class="col-sm-11">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('Label.Dashboard')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Section</li>
                    </ol>
                </div>
                <div class="col-sm-1 d-flex justify-content-start mb-3" title="Sortable">
                    <button type="button" data-toggle="modal" data-target="#sortableModal" onclick="sortableBTN()" class="btn btn-default" style="border-radius: 10px;">
                        <i class="fa-solid fa-sort fa-1x"></i>
                    </button>
                </div>
            </div>

            <ul class="tabs nav nav-pills custom-tabs inline-tabs " id="pills-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="app-tab" onclick="Top_Content('1', '0')" data-is_home_screen="1" data-content_type="0"  data-toggle="pill" href="#app" role="tab" aria-controls="home" aria-selected="true">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="music-tab" onclick="Top_Content('2', '1')" data-is_home_screen="2" data-content_type="1" data-toggle="pill" href="#music" role="tab" aria-controls="music" aria-selected="true">Music</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="radio-tab" onclick="Top_Content('2', '3')" data-is_home_screen="2" data-content_type="3" data-toggle="pill" href="#radio" role="tab" aria-controls="radio" aria-selected="true">Radio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="podcasts-tab" onclick="Top_Content('2', '2')" data-is_home_screen="2" data-content_type="2" data-toggle="pill" href="#podcasts" role="tab" aria-controls="podcasts" aria-selected="true">Podcasts</a>
                </li>
            </ul>

            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="app" role="tabpanel" aria-labelledby="app-tab">
                    <div class="card custom-border-card">
                        <h5 class="card-header">Section</h5>
                        <div class="card-body">
                            <form id="save_content_section" enctype="multipart/form-data">
                                <input type="hidden" name="id" value="">
                                <input type="hidden" name="is_home_screen" id="is_home_screen" value="">
                                <div class="form-row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>{{__('Label.Title')}}<span class="text-danger">*</span></label>
                                            <input type="text" name="title" class="form-control" placeholder="Enter Title" autofocus>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Short Title<span class="text-danger">*</span></label>
                                            <input type="text" name="short_title" class="form-control" placeholder="Enter Short Title">
                                        </div>
                                    </div>
                                    <div class="col-md-3 content_type_drop">
                                        <div class="form-group">
                                            <label>Content Type<span class="text-danger">*</span></label>
                                            <select name="content_type" class="form-control" id="content_type">
                                                <option value="">Select Type</option>
                                                <option value="1">Music</option>
                                                <option value="2">Podcasts</option>
                                                <option value="3">Radio</option>
                                                <option value="4">Playlist</option>
                                                <option value="5">Category</option>
                                                <option value="6">Language</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 screen_layout_drop">
                                        <div class="form-group">
                                            <label>Screen Layout<span class="text-danger">*</span></label>
                                            <select name="screen_layout" class="form-control" id="screen_layout">
                                                <option value="">Select Screen Layout</option>
                                                <option value="list_view">List View</option>
                                                <option value="portrait">Portrait</option>
                                                <option value="square">Square</option>
                                                <option value="playlist">Playlist</option>
                                                <option value="category">Category</option>
                                                <option value="language">Language</option>
                                                <option value="round">Round</option>
                                                <option value="banner_view">Banner View</option>
                                                <option value="landscape">Landscape</option>
                                                <option value="podcast_list_view">Podcast List View</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-3 category_drop">
                                        <div class="form-group">
                                            <label>Category<span class="text-danger">*</span></label>
                                            <select name="category_id" class="form-control" id="category_id">
                                                <option value="0">All Category</option>
                                                @for ($i = 0; $i < count($category); $i++) 
                                                <option value="{{ $category[$i]['id'] }}">
                                                    {{ $category[$i]['name'] }}
                                                </option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 language_drop">
                                        <div class="form-group">
                                            <label>Language<span class="text-danger">*</span></label>
                                            <select name="language_id" class="form-control" id="language_id">
                                                <option value="0">All Language</option>
                                                @for ($i = 0; $i < count($language); $i++) 
                                                <option value="{{ $language[$i]['id'] }}">
                                                    {{ $language[$i]['name'] }}
                                                </option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 artist_drop">
                                        <div class="form-group">
                                            <label>Artist<span class="text-danger">*</span></label>
                                            <select name="artist_id" class="form-control" id="artist_id">
                                                <option value="0">All Artist</option>
                                                @for ($i = 0; $i < count($artist); $i++) 
                                                <option value="{{ $artist[$i]['id'] }}">
                                                    {{ $artist[$i]['name'] }}
                                                </option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 no_of_content_drop">
                                        <div class="form-group">
                                            <label>No of Content<span class="text-danger">*</span></label>
                                            <input type="number" min="1" value="1" name="no_of_content" class="form-control" placeholder="Enter Number Of Content">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-3 order_by_upload_drop">
                                        <div class="form-group">
                                            <label>Order by Upload<span class="text-danger">*</span></label>
                                            <div class="radio-group">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="order_by_upload" id="order_by_upload_yes" class="custom-control-input" value="1" checked>
                                                    <label class="custom-control-label" for="order_by_upload_yes">Asc</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="order_by_upload" id="order_by_upload_no" class="custom-control-input" value="2">
                                                    <label class="custom-control-label" for="order_by_upload_no">Desc</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 order_by_view_drop">
                                        <div class="form-group">
                                            <label>Order by View<span class="text-danger">*</span></label>
                                            <div class="radio-group">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="order_by_view" id="order_by_view_yes" class="custom-control-input" value="1" checked>
                                                    <label class="custom-control-label" for="order_by_view_yes">Asc</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="order_by_view" id="order_by_view_no" class="custom-control-input" value="2">
                                                    <label class="custom-control-label" for="order_by_view_no">Desc</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 order_by_like_drop">
                                        <div class="form-group">
                                            <label>Order by Like<span class="text-danger">*</span></label>
                                            <div class="radio-group">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="order_by_like" id="order_by_like_yes" class="custom-control-input" value="1" checked>
                                                    <label class="custom-control-label" for="order_by_like_yes">Asc</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="order_by_like" id="order_by_like_no" class="custom-control-input" value="2">
                                                    <label class="custom-control-label" for="order_by_like_no">Desc</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 view_all_drop">
                                        <div class="form-group">
                                            <label>View All<span class="text-danger">*</span></label>
                                            <div class="radio-group">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="view_all" id="view_all_yes" class="custom-control-input" value="1" checked>
                                                    <label class="custom-control-label" for="view_all_yes">Yes</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="view_all" id="view_all_no" class="custom-control-input" value="0">
                                                    <label class="custom-control-label" for="view_all_no">No</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="border-top pt-3 text-right">
                                    <button type="button" class="btn btn-default mw-120" onclick="save_section(Is_home_screen, Content_type)">{{__('Label.SAVE')}}</button>
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="after-add-more"></div>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Edit Section</h5>
                            <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="edit_content_section" enctype="multipart/form-data">
                            <div class="modal-body">
                                <input type="hidden" name="id" id="edit_id" value="">
                                <input type="hidden" name="is_home_screen" id="edit_is_home_screen" value="">
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__('Label.Title')}}<span class="text-danger">*</span></label>
                                            <input type="text" name="title" id="edit_title" class="form-control" placeholder="Enter Title">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Short Title<span class="text-danger">*</span></label>
                                            <input type="text" name="short_title" id="edit_short_title" class="form-control" placeholder="Enter Short Title">
                                        </div>
                                    </div>
                                    <div class="col-md-6 edit_content_type_drop">
                                        <div class="form-group">
                                            <label>Content Type<span class="text-danger">*</span></label>
                                            <select name="content_type" class="form-control" id="edit_content_type">
                                                <option value="">Select Type</option>
                                                <option value="1">Music</option>
                                                <option value="2">Podcasts</option>
                                                <option value="3">Radio</option>
                                                <option value="4">Playlist</option>
                                                <option value="5">Category</option>
                                                <option value="6">Language</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 edit_screen_layout_drop">
                                        <div class="form-group">
                                            <label>Screen Layout<span class="text-danger">*</span></label>
                                            <select name="screen_layout" class="form-control" id="edit_screen_layout">
                                                <option value="">Select Screen Layout</option>
                                                <option value="list_view">List View</option>
                                                <option value="portrait">Portrait</option>
                                                <option value="square">Square</option>
                                                <option value="playlist">Playlist</option>
                                                <option value="category">Category</option>
                                                <option value="language">Language</option>
                                                <option value="round">Round</option>
                                                <option value="banner_view">Banner View</option>
                                                <option value="landscape">Landscape</option>
                                                <option value="podcast_list_view">Podcast List View</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 edit_category_drop">
                                        <div class="form-group">
                                            <label>Category<span class="text-danger">*</span></label>
                                            <select name="category_id" class="form-control" id="edit_category_id" style="width:100%!important;">
                                                <option value="0">All Category</option>
                                                @for ($i = 0; $i < count($category); $i++) 
                                                <option value="{{ $category[$i]['id'] }}">
                                                    {{ $category[$i]['name'] }}
                                                </option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 edit_language_drop">
                                        <div class="form-group">
                                            <label>Language<span class="text-danger">*</span></label>
                                            <select name="language_id" class="form-control" id="edit_language_id" style="width:100%!important;">
                                                <option value="0">All Language</option>
                                                @for ($i = 0; $i < count($language); $i++) 
                                                <option value="{{ $language[$i]['id'] }}">
                                                    {{ $language[$i]['name'] }}
                                                </option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 edit_artist_drop">
                                        <div class="form-group">
                                            <label>Artist<span class="text-danger">*</span></label>
                                            <select name="artist_id" class="form-control" id="edit_artist_id" style="width:100%!important;">
                                                <option value="0">All Artist</option>
                                                @for ($i = 0; $i < count($artist); $i++) 
                                                <option value="{{ $artist[$i]['id'] }}">
                                                    {{ $artist[$i]['name'] }}
                                                </option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 edit_no_of_content_drop">
                                        <div class="form-group">
                                            <label>No of Content<span class="text-danger">*</span></label>
                                            <input type="number" min="1" name="no_of_content" id="edit_no_of_content" class="form-control" placeholder="Enter Number Of Content">
                                        </div>
                                    </div>
                                    <div class="col-md-6 edit_order_by_upload_drop">
                                        <div class="form-group">
                                            <label>Order by Upload<span class="text-danger">*</span></label>
                                            <div class="radio-group">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="order_by_upload" id="edit_order_by_upload_yes" class="custom-control-input" value="1" checked>
                                                    <label class="custom-control-label" for="edit_order_by_upload_yes">Asc</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="order_by_upload" id="edit_order_by_upload_no" class="custom-control-input" value="2">
                                                    <label class="custom-control-label" for="edit_order_by_upload_no">Desc</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 edit_order_by_view_drop">
                                        <div class="form-group">
                                            <label>Order by View<span class="text-danger">*</span></label>
                                            <div class="radio-group">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="order_by_view" id="edit_order_by_view_yes" class="custom-control-input" value="1" checked>
                                                    <label class="custom-control-label" for="edit_order_by_view_yes">Asc</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="order_by_view" id="edit_order_by_view_no" class="custom-control-input" value="2">
                                                    <label class="custom-control-label" for="edit_order_by_view_no">Desc</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 edit_order_by_like_drop">
                                        <div class="form-group">
                                            <label>Order by Like<span class="text-danger">*</span></label>
                                            <div class="radio-group">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="order_by_like" id="edit_order_by_like_yes" class="custom-control-input" value="1" checked>
                                                    <label class="custom-control-label" for="edit_order_by_like_yes">Asc</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="order_by_like" id="edit_order_by_like_no" class="custom-control-input" value="2">
                                                    <label class="custom-control-label" for="edit_order_by_like_no">Desc</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 edit_view_all_drop">
                                        <div class="form-group">
                                            <label>View All<span class="text-danger">*</span></label>
                                            <div class="radio-group">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="view_all" id="edit_view_all_yes" class="custom-control-input" value="1" checked>
                                                    <label class="custom-control-label" for="edit_view_all_yes">Yes</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="view_all" id="edit_view_all_no" class="custom-control-input" value="0">
                                                    <label class="custom-control-label" for="edit_view_all_no">No</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default mw-120" onclick="update_section()">Update</button>
                                <button type="button" class="btn btn-cancel mw-120" data-dismiss="modal">Close</button>
                                <input type="hidden" name="_method" value="PATCH">
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- sortableModal -->
            <div class="modal fade" id="sortableModal" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="sortableModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title w-100 text-center" id="sortableModalLabel">Section Sortable List</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="close">
                                <span aria-hidden="true" class="text-dark">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div id="imageListId">
                                
                            </div>
                        </div>
                        <div class="modal-footer justify-content-center">
                            <form enctype="multipart/form-data" id="save_section_sortable">
                                @csrf
                                <input id="outputvalues" type="hidden" name="ids" value="" />
                                <div class="w-100 text-center">
                                    <button type="button" class="btn btn-default mw-120" onclick="save_section_sortable()">{{__('Label.SAVE')}}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('pagescript')
    <script>
        $("#language_id").select2();
        $("#artist_id").select2();
        $("#category_id").select2();
        $("#edit_language_id").select2();
        $("#edit_artist_id").select2();
        $("#edit_category_id").select2();

        $(".category_drop").hide();
        $(".language_drop").hide();
        $(".artist_drop").hide();
        $(".no_of_content_drop").hide();
        $(".order_by_upload_drop").hide();
        $(".order_by_view_drop").hide();
        $(".order_by_like_drop").hide();
        $(".view_all_drop").hide();
        $(".screen_layout_drop").hide();

        var Tab = $("ul.tabs li a.active");
        var Is_home_screen = Tab.data("is_home_screen");
        var Content_type = 0;
        $("#is_home_screen").val(Is_home_screen);

        $("#content_type").change(function() {

            var content_type = $(this).children("option:selected").val();
            if(content_type == 1){

                $(".category_drop").show();
                $(".language_drop").show();
                $(".artist_drop").show();
                $(".no_of_content_drop").show();
                $(".order_by_upload_drop").show();
                $(".order_by_view_drop").show();
                $(".order_by_like_drop").show();
                $(".view_all_drop").show();

                $(".screen_layout_drop").show();
                $("#screen_layout").children().removeAttr("selected");
                $("#screen_layout option[value='list_view']").show();
                $("#screen_layout option[value='portrait']").show();
                $("#screen_layout option[value='square']").show();
                $("#screen_layout option[value='playlist']").hide();
                $("#screen_layout option[value='category']").hide();
                $("#screen_layout option[value='language']").hide();
                $("#screen_layout option[value='round']").hide();
                $("#screen_layout option[value='banner_view']").hide();
                $("#screen_layout option[value='landscape']").hide();
                $("#screen_layout option[value='podcast_list_view']").hide();

            } else if(content_type == 2){

                $(".category_drop").show();
                $(".language_drop").show();
                $(".artist_drop").hide();
                $(".no_of_content_drop").show();
                $(".order_by_upload_drop").show();
                $(".order_by_view_drop").show();
                $(".order_by_like_drop").show();
                $(".view_all_drop").show();

                $(".screen_layout_drop").show();
                $("#screen_layout").children().removeAttr("selected");
                $("#screen_layout option[value='list_view']").hide();
                $("#screen_layout option[value='portrait']").hide();
                $("#screen_layout option[value='square']").hide();
                $("#screen_layout option[value='playlist']").hide();
                $("#screen_layout option[value='category']").hide();
                $("#screen_layout option[value='language']").hide();
                $("#screen_layout option[value='round']").hide();
                $("#screen_layout option[value='banner_view']").show();
                $("#screen_layout option[value='landscape']").show();
                $("#screen_layout option[value='podcast_list_view']").show();

            } else if(content_type == 3){

                $(".category_drop").show();
                $(".language_drop").show();
                $(".artist_drop").show();
                $(".no_of_content_drop").show();
                $(".order_by_upload_drop").show();
                $(".order_by_view_drop").hide();
                $(".order_by_like_drop").hide();
                $(".view_all_drop").show();

                $(".screen_layout_drop").show();
                $("#screen_layout").children().removeAttr("selected");
                $("#screen_layout option[value='list_view']").hide();
                $("#screen_layout option[value='portrait']").hide();
                $("#screen_layout option[value='square']").show();
                $("#screen_layout option[value='playlist']").hide();
                $("#screen_layout option[value='category']").hide();
                $("#screen_layout option[value='language']").hide();
                $("#screen_layout option[value='round']").show();
                $("#screen_layout option[value='banner_view']").hide();
                $("#screen_layout option[value='landscape']").hide();
                $("#screen_layout option[value='podcast_list_view']").hide();

            } else if(content_type == 4){

                $(".category_drop").hide();
                $(".language_drop").hide();
                $(".artist_drop").hide();
                $(".no_of_content_drop").show();
                $(".order_by_upload_drop").show();
                $(".order_by_view_drop").hide();
                $(".order_by_like_drop").hide();
                $(".view_all_drop").show();

                $(".screen_layout_drop").show();
                $("#screen_layout").children().removeAttr("selected");
                $("#screen_layout option[value='list_view']").hide();
                $("#screen_layout option[value='portrait']").hide();
                $("#screen_layout option[value='square']").hide();
                $("#screen_layout option[value='playlist']").show();
                $("#screen_layout option[value='category']").hide();
                $("#screen_layout option[value='language']").hide();
                $("#screen_layout option[value='round']").hide();
                $("#screen_layout option[value='banner_view']").hide();
                $("#screen_layout option[value='landscape']").hide();
                $("#screen_layout option[value='podcast_list_view']").hide();
            } else if(content_type == 5){

                $(".category_drop").hide();
                $(".language_drop").hide();
                $(".artist_drop").hide();
                $(".no_of_content_drop").hide();
                $(".order_by_upload_drop").hide();
                $(".order_by_view_drop").hide();
                $(".order_by_like_drop").hide();
                $(".view_all_drop").hide();

                $(".screen_layout_drop").show();
                $("#screen_layout").children().removeAttr("selected");
                $("#screen_layout option[value='list_view']").hide();
                $("#screen_layout option[value='portrait']").hide();
                $("#screen_layout option[value='square']").hide();
                $("#screen_layout option[value='playlist']").hide();
                $("#screen_layout option[value='category']").show();
                $("#screen_layout option[value='language']").hide();
                $("#screen_layout option[value='round']").hide();
                $("#screen_layout option[value='banner_view']").hide();
                $("#screen_layout option[value='landscape']").hide();
                $("#screen_layout option[value='podcast_list_view']").hide();
            } else if(content_type == 6){

                $(".category_drop").hide();
                $(".language_drop").hide();
                $(".artist_drop").hide();
                $(".no_of_content_drop").hide();
                $(".order_by_upload_drop").hide();
                $(".order_by_view_drop").hide();
                $(".order_by_like_drop").hide();
                $(".view_all_drop").hide();

                $(".screen_layout_drop").show();
                $("#screen_layout").children().removeAttr("selected");
                $("#screen_layout option[value='list_view']").hide();
                $("#screen_layout option[value='portrait']").hide();
                $("#screen_layout option[value='square']").hide();
                $("#screen_layout option[value='playlist']").hide();
                $("#screen_layout option[value='category']").hide();
                $("#screen_layout option[value='language']").show();
                $("#screen_layout option[value='round']").hide();
                $("#screen_layout option[value='banner_view']").hide();
                $("#screen_layout option[value='landscape']").hide();
                $("#screen_layout option[value='podcast_list_view']").hide();
            } else {

                $(".category_drop").hide();
                $(".language_drop").hide();
                $(".artist_drop").hide();
                $(".no_of_content_drop").hide();
                $(".order_by_upload_drop").hide();
                $(".order_by_view_drop").hide();
                $(".order_by_like_drop").hide();
                $(".view_all_drop").hide();
                $(".screen_layout_drop").hide();
            }
        });

        // Save Section
        function save_section(is_home_screen, content_type){
            var Check_Admin = '<?php echo Check_Admin_Access(); ?>';
            if(Check_Admin == 1){

                $("#dvloader").show();
                var formData = new FormData($("#save_content_section")[0]);
                formData.append('is_home_screen', is_home_screen);
                if(is_home_screen == 2){
                    
                    var ContentType = content_type;
                    if(content_type == 1){
                        var ContentType = $('#content_type').find(":selected").val();
                    }
                    formData.append('content_type', ContentType);
                }

                $.ajax({
                    type:'POST',
                    url:'{{ route("section.store") }}',
                    data:formData,
                    cache:false,
                    contentType: false,
                    processData: false,
                    success:function(resp){
                        $("#dvloader").hide();
                        get_responce_message(resp, 'save_content_section', '{{ route("section.index") }}');
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

        // List Section
        if(Is_home_screen == 1) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: '{{ route("section.content.data") }}',
                data: {
                    is_home_screen: Is_home_screen,
                },
                success: function(resp) {
                    $('.after-add-more').html('');
                    for (var i = 0; i < resp.result.length; i++) {

                        if (resp.result[i].content_type == 1) {
                            var content_type = "Music";
                        } else if (resp.result[i].content_type == 2) {
                            var content_type = "Podcasts";
                        } else if (resp.result[i].content_type == 3) {
                            var content_type = "Radio";
                        } else if (resp.result[i].content_type == 4) {
                            var content_type = "Playlist";
                        } else if (resp.result[i].content_type == 5) {
                            var content_type = "Category";
                        } else if (resp.result[i].content_type == 6) {
                            var content_type = "Language";
                        } 

                        if (resp.result[i].screen_layout == "list_view") {
                            var screen_layout = "List View";
                        } else if (resp.result[i].screen_layout == "portrait") {
                            var screen_layout = "Portrait";
                        } else if (resp.result[i].screen_layout == "square") {
                            var screen_layout = "Square";
                        } else if (resp.result[i].screen_layout == "playlist") {
                            var screen_layout = "Playlist";
                        } else if (resp.result[i].screen_layout == "category") {
                            var screen_layout = "Category";
                        } else if (resp.result[i].screen_layout == "language") {
                            var screen_layout = "Language";
                        } else if (resp.result[i].screen_layout == "round") {
                            var screen_layout = "Round";
                        } else if (resp.result[i].screen_layout == "banner_view") {
                            var screen_layout = "Banner View";
                        } else if (resp.result[i].screen_layout == "landscape") {
                            var screen_layout = "Landscape";
                        } else if (resp.result[i].screen_layout == "podcast_list_view") {
                            var screen_layout = "Podcast List View";
                        }

                        var data = '<div class="card custom-border-card mt-3">'+
                                '<h5 class="card-header">Edit Section</h5>'+
                                '<div class="card-body">'+
                                    '<form id="edit_section_'+resp.result[i].id+'" enctype="multipart/form-data">'+
                                        '<input type="hidden" name="id" value="'+resp.result[i].id+'">'+
                                        '<div class="form-row">'+
                                            '<div class="col-md-4">'+
                                                '<div class="form-group">'+
                                                    '<label>{{__("Label.Title")}}</label>'+
                                                    '<input type="text" name="title" value="'+resp.result[i].title+'" class="form-control" readonly>'+
                                                '</div>'+
                                            '</div>'+
                                            '<div class="col-md-4">'+
                                                '<div class="form-group">'+
                                                    '<label>Short Title</label>'+
                                                    '<input type="text" name="short_title" value="'+resp.result[i].short_title+'" class="form-control" readonly>'+
                                                '</div>'+
                                            '</div>'+
                                            '<div class="col-md-2">'+
                                                '<div class="form-group">'+
                                                    '<label>Content Type</label>'+
                                                    '<input type="text" name="content_type" value="'+content_type+'" class="form-control" readonly>'+
                                                '</div>'+
                                            '</div>'+
                                            '<div class="col-md-2">'+
                                                '<div class="form-group">'+
                                                    '<label>Screen Layout</label>'+
                                                    '<input type="text" name="screen_layout" value="'+screen_layout+'" class="form-control" readonly>'+
                                                '</div>'+
                                            '</div>'+
                                        '</div>'+
                                        '<div class="border-top pt-3 text-right">'+
                                            '<button type="button" data-toggle="modal" data-target="#exampleModal" class="btn btn-default mw-120" onclick="edit_section('+resp.result[i].id+')">{{__("Label.UPDATE")}}</button>'+
                                            '<button type="button" class="btn btn-cancel mw-120 ml-2" onclick="delete_section('+resp.result[i].id+')">DELETE</button>'+
                                            '<input type="hidden" name="_method" value="PATCH">'+
                                        '</div>'+
                                    '</form>'+
                                '</div>'+
                            '</div>';

                        $('.after-add-more').append(data);

                        $("#edit_content_type_"+resp.result[i].id+"").val(resp.result[i].content_type).attr("selected","selected");
                        $("#edit_screen_layout"+resp.result[i].id+"").val(resp.result[i].screen_layout).attr("selected","selected");
                        $("#edit_category_id_"+resp.result[i].id+"").val(resp.result[i].category_id).attr("selected","selected");
                        $("#edit_language_id_"+resp.result[i].id+"").val(resp.result[i].language_id).attr("selected","selected");
                        $("#edit_artist_id_"+resp.result[i].id+"").val(resp.result[i].artist_id).attr("selected","selected");
                        if(resp.result[i].order_by_upload == 1){
                            $("#order_by_upload_yes_"+resp.result[i].id+"").attr('checked','checked');
                        } else {
                            $("#order_by_upload_no_"+resp.result[i].id+"").attr('checked','checked');
                        }
                        if(resp.result[i].order_by_view == 1){
                            $("#order_by_view_yes_"+resp.result[i].id+"").attr('checked','checked');
                        } else {
                            $("#order_by_view_no_"+resp.result[i].id+"").attr('checked','checked');
                        }
                        if(resp.result[i].order_by_like == 1){
                            $("#order_by_like_yes_"+resp.result[i].id+"").attr('checked','checked');
                        } else {
                            $("#order_by_like_no_"+resp.result[i].id+"").attr('checked','checked');
                        }
                        if(resp.result[i].view_all == 1){
                            $("#view_all_yes_"+resp.result[i].id+"").attr('checked','checked');
                        } else {
                            $("#view_all_no_"+resp.result[i].id+"").attr('checked','checked');
                        }

                        $(".edit_category_drop_"+resp.result[i].id+"").hide();
                        $(".edit_language_drop_"+resp.result[i].id+"").hide();
                        $(".edit_artist_drop_"+resp.result[i].id+"").hide();
                        $(".edit_no_of_content_drop_"+resp.result[i].id+"").hide();
                        $(".edit_order_by_upload_drop_"+resp.result[i].id+"").hide();
                        $(".edit_order_by_view_drop_"+resp.result[i].id+"").hide();
                        $(".edit_order_by_like_drop_"+resp.result[i].id+"").hide();
                        $(".edit_view_all_drop_"+resp.result[i].id+"").hide();
                        if(resp.result[i].content_type == 1){

                            $(".edit_category_drop_"+resp.result[i].id+"").show();
                            $(".edit_language_drop_"+resp.result[i].id+"").show();
                            $(".edit_artist_drop_"+resp.result[i].id+"").show();
                            $(".edit_no_of_content_drop_"+resp.result[i].id+"").show();
                            $(".edit_order_by_upload_drop_"+resp.result[i].id+"").show();
                            $(".edit_order_by_view_drop_"+resp.result[i].id+"").show();
                            $(".edit_order_by_like_drop_"+resp.result[i].id+"").show();
                            $(".edit_view_all_drop_"+resp.result[i].id+"").show();
                        } else if(resp.result[i].content_type == 2){

                            $(".edit_category_drop_"+resp.result[i].id+"").show();
                            $(".edit_language_drop_"+resp.result[i].id+"").show();
                            $(".edit_artist_drop_"+resp.result[i].id+"").hide();
                            $(".edit_no_of_content_drop_"+resp.result[i].id+"").show();
                            $(".edit_order_by_upload_drop_"+resp.result[i].id+"").show();
                            $(".edit_order_by_view_drop_"+resp.result[i].id+"").show();
                            $(".edit_order_by_like_drop_"+resp.result[i].id+"").show();
                            $(".edit_view_all_drop_"+resp.result[i].id+"").show();
                        } else if(resp.result[i].content_type == 3){

                            $(".edit_category_drop_"+resp.result[i].id+"").show();
                            $(".edit_language_drop_"+resp.result[i].id+"").show();
                            $(".edit_artist_drop_"+resp.result[i].id+"").show();
                            $(".edit_no_of_content_drop_"+resp.result[i].id+"").show();
                            $(".edit_order_by_upload_drop_"+resp.result[i].id+"").show();
                            $(".edit_order_by_view_drop_"+resp.result[i].id+"").hide();
                            $(".edit_order_by_like_drop_"+resp.result[i].id+"").hide();
                            $(".edit_view_all_drop_"+resp.result[i].id+"").show();
                        } else if(resp.result[i].content_type == 4){

                            $(".edit_category_drop_"+resp.result[i].id+"").hide();
                            $(".edit_language_drop_"+resp.result[i].id+"").hide();
                            $(".edit_artist_drop_"+resp.result[i].id+"").hide();
                            $(".edit_no_of_content_drop_"+resp.result[i].id+"").show();
                            $(".edit_order_by_upload_drop_"+resp.result[i].id+"").show();
                            $(".edit_order_by_view_drop_"+resp.result[i].id+"").hide();
                            $(".edit_order_by_like_drop_"+resp.result[i].id+"").hide();
                            $(".edit_view_all_drop_"+resp.result[i].id+"").show();
                        } else {

                            $(".edit_category_drop_"+resp.result[i].id+"").hide();
                            $(".edit_language_drop_"+resp.result[i].id+"").hide();
                            $(".edit_artist_drop_"+resp.result[i].id+"").hide();
                            $(".edit_no_of_content_drop_"+resp.result[i].id+"").hide();
                            $(".edit_order_by_upload_drop_"+resp.result[i].id+"").hide();
                            $(".edit_order_by_view_drop_"+resp.result[i].id+"").hide();
                            $(".edit_order_by_like_drop_"+resp.result[i].id+"").hide();
                            $(".edit_view_all_drop_"+resp.result[i].id+"").hide();
                        }
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    toastr.error(errorThrown.msg, 'failed');
                }
            });
        }
        function Top_Content(is_home_screen, content_type) {

            Is_home_screen = is_home_screen;
            Content_type = content_type;
            $("#is_home_screen").val(is_home_screen);

            document.getElementById("save_content_section").reset();
            $("#language_id").val(0).trigger("change");
            $("#artist_id").val(0).trigger("change"); 
            $("#category_id").val(0).trigger("change"); 

            if(is_home_screen == 1){

                $(".content_type_drop").show();
                $(".content_type_drop option[value='1']").show();
                $(".content_type_drop option[value='2']").show();
                $(".content_type_drop option[value='3']").show();
                $(".content_type_drop option[value='4']").show();
                $(".content_type_drop option[value='5']").show();
                $(".content_type_drop option[value='6']").show();

                $(".category_drop").hide();
                $(".language_drop").hide();
                $(".artist_drop").hide();
                $(".no_of_content_drop").hide();
                $(".order_by_upload_drop").hide();
                $(".order_by_view_drop").hide();
                $(".order_by_like_drop").hide();
                $(".view_all_drop").hide();
                $(".screen_layout_drop").hide();

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    url: '{{ route("section.content.data") }}',
                    data: {
                        is_home_screen: Is_home_screen,
                    },
                    success: function(resp) {
                        $('.after-add-more').html('');
                        for (var i = 0; i < resp.result.length; i++) {

                            if (resp.result[i].content_type == 1) {
                                var content_type = "Music";
                            } else if (resp.result[i].content_type == 2) {
                                var content_type = "Podcasts";
                            } else if (resp.result[i].content_type == 3) {
                                var content_type = "Radio";
                            } else if (resp.result[i].content_type == 4) {
                                var content_type = "Playlist";
                            } else if (resp.result[i].content_type == 5) {
                                var content_type = "Category";
                            } else if (resp.result[i].content_type == 6) {
                                var content_type = "Language";
                            } 

                            if (resp.result[i].screen_layout == "list_view") {
                                var screen_layout = "List View";
                            } else if (resp.result[i].screen_layout == "portrait") {
                                var screen_layout = "Portrait";
                            } else if (resp.result[i].screen_layout == "square") {
                                var screen_layout = "Square";
                            } else if (resp.result[i].screen_layout == "playlist") {
                                var screen_layout = "Playlist";
                            } else if (resp.result[i].screen_layout == "category") {
                                var screen_layout = "Category";
                            } else if (resp.result[i].screen_layout == "language") {
                                var screen_layout = "Language";
                            } else if (resp.result[i].screen_layout == "round") {
                                var screen_layout = "Round";
                            } else if (resp.result[i].screen_layout == "banner_view") {
                                var screen_layout = "Banner View";
                            } else if (resp.result[i].screen_layout == "landscape") {
                                var screen_layout = "Landscape";
                            } else if (resp.result[i].screen_layout == "podcast_list_view") {
                                var screen_layout = "Podcast List View";
                            }

                            var data = '<div class="card custom-border-card mt-3">'+
                                    '<h5 class="card-header">Edit Section</h5>'+
                                    '<div class="card-body">'+
                                        '<form id="edit_section_'+resp.result[i].id+'" enctype="multipart/form-data">'+
                                            '<input type="hidden" name="id" value="'+resp.result[i].id+'">'+
                                            '<div class="form-row">'+
                                                '<div class="col-md-4">'+
                                                    '<div class="form-group">'+
                                                        '<label>{{__("Label.Title")}}</label>'+
                                                        '<input type="text" name="title" value="'+resp.result[i].title+'" class="form-control" readonly>'+
                                                    '</div>'+
                                                '</div>'+
                                                '<div class="col-md-4">'+
                                                    '<div class="form-group">'+
                                                        '<label>Short Title</label>'+
                                                        '<input type="text" name="short_title" value="'+resp.result[i].short_title+'" class="form-control" readonly>'+
                                                    '</div>'+
                                                '</div>'+
                                                '<div class="col-md-2">'+
                                                    '<div class="form-group">'+
                                                        '<label>Content Type</label>'+
                                                        '<input type="text" name="content_type" value="'+content_type+'" class="form-control" readonly>'+
                                                    '</div>'+
                                                '</div>'+
                                                '<div class="col-md-2">'+
                                                    '<div class="form-group">'+
                                                        '<label>Screen Layout</label>'+
                                                        '<input type="text" name="screen_layout" value="'+screen_layout+'" class="form-control" readonly>'+
                                                    '</div>'+
                                                '</div>'+
                                            '</div>'+
                                            '<div class="border-top pt-3 text-right">'+
                                                '<button type="button" data-toggle="modal" data-target="#exampleModal" class="btn btn-default mw-120" onclick="edit_section('+resp.result[i].id+')">{{__("Label.UPDATE")}}</button>'+
                                                '<button type="button" class="btn btn-cancel mw-120 ml-2" onclick="delete_section('+resp.result[i].id+')">DELETE</button>'+
                                                '<input type="hidden" name="_method" value="PATCH">'+
                                            '</div>'+
                                        '</form>'+
                                    '</div>'+
                                '</div>';

                            $('.after-add-more').append(data);

                            $("#edit_content_type_"+resp.result[i].id+"").val(resp.result[i].content_type).attr("selected","selected");
                            $("#edit_screen_layout"+resp.result[i].id+"").val(resp.result[i].screen_layout).attr("selected","selected");
                            $("#edit_category_id_"+resp.result[i].id+"").val(resp.result[i].category_id).attr("selected","selected");
                            $("#edit_language_id_"+resp.result[i].id+"").val(resp.result[i].language_id).attr("selected","selected");
                            $("#edit_artist_id_"+resp.result[i].id+"").val(resp.result[i].artist_id).attr("selected","selected");
                            if(resp.result[i].order_by_upload == 1){
                                $("#order_by_upload_yes_"+resp.result[i].id+"").attr('checked','checked');
                            } else {
                                $("#order_by_upload_no_"+resp.result[i].id+"").attr('checked','checked');
                            }
                            if(resp.result[i].order_by_view == 1){
                                $("#order_by_view_yes_"+resp.result[i].id+"").attr('checked','checked');
                            } else {
                                $("#order_by_view_no_"+resp.result[i].id+"").attr('checked','checked');
                            }
                            if(resp.result[i].order_by_like == 1){
                                $("#order_by_like_yes_"+resp.result[i].id+"").attr('checked','checked');
                            } else {
                                $("#order_by_like_no_"+resp.result[i].id+"").attr('checked','checked');
                            }
                            if(resp.result[i].view_all == 1){
                                $("#view_all_yes_"+resp.result[i].id+"").attr('checked','checked');
                            } else {
                                $("#view_all_no_"+resp.result[i].id+"").attr('checked','checked');
                            }

                            $(".edit_category_drop_"+resp.result[i].id+"").hide();
                            $(".edit_language_drop_"+resp.result[i].id+"").hide();
                            $(".edit_artist_drop_"+resp.result[i].id+"").hide();
                            $(".edit_no_of_content_drop_"+resp.result[i].id+"").hide();
                            $(".edit_order_by_upload_drop_"+resp.result[i].id+"").hide();
                            $(".edit_order_by_view_drop_"+resp.result[i].id+"").hide();
                            $(".edit_order_by_like_drop_"+resp.result[i].id+"").hide();
                            $(".edit_view_all_drop_"+resp.result[i].id+"").hide();
                            if(resp.result[i].content_type == 1){

                                $(".edit_category_drop_"+resp.result[i].id+"").show();
                                $(".edit_language_drop_"+resp.result[i].id+"").show();
                                $(".edit_artist_drop_"+resp.result[i].id+"").show();
                                $(".edit_no_of_content_drop_"+resp.result[i].id+"").show();
                                $(".edit_order_by_upload_drop_"+resp.result[i].id+"").show();
                                $(".edit_order_by_view_drop_"+resp.result[i].id+"").show();
                                $(".edit_order_by_like_drop_"+resp.result[i].id+"").show();
                                $(".edit_view_all_drop_"+resp.result[i].id+"").show();
                            } else if(resp.result[i].content_type == 2){

                                $(".edit_category_drop_"+resp.result[i].id+"").show();
                                $(".edit_language_drop_"+resp.result[i].id+"").show();
                                $(".edit_artist_drop_"+resp.result[i].id+"").hide();
                                $(".edit_no_of_content_drop_"+resp.result[i].id+"").show();
                                $(".edit_order_by_upload_drop_"+resp.result[i].id+"").show();
                                $(".edit_order_by_view_drop_"+resp.result[i].id+"").show();
                                $(".edit_order_by_like_drop_"+resp.result[i].id+"").show();
                                $(".edit_view_all_drop_"+resp.result[i].id+"").show();
                            } else if(resp.result[i].content_type == 3){

                                $(".edit_category_drop_"+resp.result[i].id+"").show();
                                $(".edit_language_drop_"+resp.result[i].id+"").show();
                                $(".edit_artist_drop_"+resp.result[i].id+"").show();
                                $(".edit_no_of_content_drop_"+resp.result[i].id+"").show();
                                $(".edit_order_by_upload_drop_"+resp.result[i].id+"").show();
                                $(".edit_order_by_view_drop_"+resp.result[i].id+"").hide();
                                $(".edit_order_by_like_drop_"+resp.result[i].id+"").hide();
                                $(".edit_view_all_drop_"+resp.result[i].id+"").show();
                            } else if(resp.result[i].content_type == 4){

                                $(".edit_category_drop_"+resp.result[i].id+"").hide();
                                $(".edit_language_drop_"+resp.result[i].id+"").hide();
                                $(".edit_artist_drop_"+resp.result[i].id+"").hide();
                                $(".edit_no_of_content_drop_"+resp.result[i].id+"").show();
                                $(".edit_order_by_upload_drop_"+resp.result[i].id+"").show();
                                $(".edit_order_by_view_drop_"+resp.result[i].id+"").hide();
                                $(".edit_order_by_like_drop_"+resp.result[i].id+"").hide();
                                $(".edit_view_all_drop_"+resp.result[i].id+"").show();
                            } else {

                                $(".edit_category_drop_"+resp.result[i].id+"").hide();
                                $(".edit_language_drop_"+resp.result[i].id+"").hide();
                                $(".edit_artist_drop_"+resp.result[i].id+"").hide();
                                $(".edit_no_of_content_drop_"+resp.result[i].id+"").hide();
                                $(".edit_order_by_upload_drop_"+resp.result[i].id+"").hide();
                                $(".edit_order_by_view_drop_"+resp.result[i].id+"").hide();
                                $(".edit_order_by_like_drop_"+resp.result[i].id+"").hide();
                                $(".edit_view_all_drop_"+resp.result[i].id+"").hide();
                            }
                        }
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        toastr.error(errorThrown.msg, 'failed');
                    }
                });
            } else if(is_home_screen == 2) {

                if(content_type == 1){

                    $(".content_type_drop").show();
                    $(".content_type_drop option[value='1']").show();
                    $(".content_type_drop option[value='2']").hide();
                    $(".content_type_drop option[value='3']").hide();
                    $(".content_type_drop option[value='4']").show();
                    $(".content_type_drop option[value='5']").hide();
                    $(".content_type_drop option[value='6']").hide();

                    $(".category_drop").show();
                    $(".language_drop").show();
                    $(".artist_drop").show();
                    $(".no_of_content_drop").show();
                    $(".order_by_upload_drop").show();
                    $(".order_by_view_drop").show();
                    $(".order_by_like_drop").show();
                    $(".view_all_drop").show();
                    $(".screen_layout_drop").hide();

                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'POST',
                        url: '{{ route("section.content.data") }}',
                        data: {
                            is_home_screen: Is_home_screen,
                            content_type: Content_type,
                        },
                        success: function(resp) {
                            $('.after-add-more').html('');
                            for (var i = 0; i < resp.result.length; i++) {

                                if (resp.result[i].content_type == 1) {
                                    var content_type = "Music";
                                } else if (resp.result[i].content_type == 2) {
                                    var content_type = "Podcasts";
                                } else if (resp.result[i].content_type == 3) {
                                    var content_type = "Radio";
                                } else if (resp.result[i].content_type == 4) {
                                    var content_type = "Playlist";
                                } else if (resp.result[i].content_type == 5) {
                                    var content_type = "Category";
                                } else if (resp.result[i].content_type == 6) {
                                    var content_type = "Language";
                                } 

                                if (resp.result[i].screen_layout == "list_view") {
                                    var screen_layout = "List View";
                                } else if (resp.result[i].screen_layout == "portrait") {
                                    var screen_layout = "Portrait";
                                } else if (resp.result[i].screen_layout == "square") {
                                    var screen_layout = "Square";
                                } else if (resp.result[i].screen_layout == "playlist") {
                                    var screen_layout = "Playlist";
                                } else if (resp.result[i].screen_layout == "category") {
                                    var screen_layout = "Category";
                                } else if (resp.result[i].screen_layout == "language") {
                                    var screen_layout = "Language";
                                } else if (resp.result[i].screen_layout == "round") {
                                    var screen_layout = "Round";
                                } else if (resp.result[i].screen_layout == "banner_view") {
                                    var screen_layout = "Banner View";
                                } else if (resp.result[i].screen_layout == "landscape") {
                                    var screen_layout = "Landscape";
                                } else if (resp.result[i].screen_layout == "podcast_list_view") {
                                    var screen_layout = "Podcast List View";
                                }

                                var data = '<div class="card custom-border-card mt-3">'+
                                        '<h5 class="card-header">Edit Section</h5>'+
                                        '<div class="card-body">'+
                                            '<form id="edit_section_'+resp.result[i].id+'" enctype="multipart/form-data">'+
                                                '<input type="hidden" name="id" value="'+resp.result[i].id+'">'+
                                                '<div class="form-row">'+
                                                    '<div class="col-md-4">'+
                                                        '<div class="form-group">'+
                                                            '<label>{{__("Label.Title")}}</label>'+
                                                            '<input type="text" name="title" value="'+resp.result[i].title+'" class="form-control" readonly>'+
                                                        '</div>'+
                                                    '</div>'+
                                                    '<div class="col-md-4">'+
                                                        '<div class="form-group">'+
                                                            '<label>Short Title</label>'+
                                                            '<input type="text" name="short_title" value="'+resp.result[i].short_title+'" class="form-control" readonly>'+
                                                        '</div>'+
                                                    '</div>'+
                                                    '<div class="col-md-2">'+
                                                        '<div class="form-group">'+
                                                            '<label>Content Type</label>'+
                                                            '<input type="text" name="content_type" value="'+content_type+'" class="form-control" readonly>'+
                                                        '</div>'+
                                                    '</div>'+
                                                    '<div class="col-md-2">'+
                                                        '<div class="form-group">'+
                                                            '<label>Screen Layout</label>'+
                                                            '<input type="text" name="screen_layout" value="'+screen_layout+'" class="form-control" readonly>'+
                                                        '</div>'+
                                                    '</div>'+
                                                '</div>'+
                                                '<div class="border-top pt-3 text-right">'+
                                                    '<button type="button" data-toggle="modal" data-target="#exampleModal" class="btn btn-default mw-120" onclick="edit_section('+resp.result[i].id+')">{{__("Label.UPDATE")}}</button>'+
                                                    '<button type="button" class="btn btn-cancel mw-120 ml-2" onclick="delete_section('+resp.result[i].id+')">DELETE</button>'+
                                                    '<input type="hidden" name="_method" value="PATCH">'+
                                                '</div>'+
                                            '</form>'+
                                        '</div>'+
                                    '</div>';

                                $('.after-add-more').append(data);

                                $("#edit_content_type_"+resp.result[i].id+"").val(resp.result[i].content_type).attr("selected","selected");
                                $("#edit_screen_layout"+resp.result[i].id+"").val(resp.result[i].screen_layout).attr("selected","selected");
                                $("#edit_category_id_"+resp.result[i].id+"").val(resp.result[i].category_id).attr("selected","selected");
                                $("#edit_language_id_"+resp.result[i].id+"").val(resp.result[i].language_id).attr("selected","selected");
                                $("#edit_artist_id_"+resp.result[i].id+"").val(resp.result[i].artist_id).attr("selected","selected");
                                if(resp.result[i].order_by_upload == 1){
                                    $("#order_by_upload_yes_"+resp.result[i].id+"").attr('checked','checked');
                                } else {
                                    $("#order_by_upload_no_"+resp.result[i].id+"").attr('checked','checked');
                                }
                                if(resp.result[i].order_by_view == 1){
                                    $("#order_by_view_yes_"+resp.result[i].id+"").attr('checked','checked');
                                } else {
                                    $("#order_by_view_no_"+resp.result[i].id+"").attr('checked','checked');
                                }
                                if(resp.result[i].order_by_like == 1){
                                    $("#order_by_like_yes_"+resp.result[i].id+"").attr('checked','checked');
                                } else {
                                    $("#order_by_like_no_"+resp.result[i].id+"").attr('checked','checked');
                                }
                                if(resp.result[i].view_all == 1){
                                    $("#view_all_yes_"+resp.result[i].id+"").attr('checked','checked');
                                } else {
                                    $("#view_all_no_"+resp.result[i].id+"").attr('checked','checked');
                                }

                                $(".edit_category_drop_"+resp.result[i].id+"").hide();
                                $(".edit_language_drop_"+resp.result[i].id+"").hide();
                                $(".edit_artist_drop_"+resp.result[i].id+"").hide();
                                $(".edit_no_of_content_drop_"+resp.result[i].id+"").hide();
                                $(".edit_order_by_upload_drop_"+resp.result[i].id+"").hide();
                                $(".edit_order_by_view_drop_"+resp.result[i].id+"").hide();
                                $(".edit_order_by_like_drop_"+resp.result[i].id+"").hide();
                                $(".edit_view_all_drop_"+resp.result[i].id+"").hide();
                                if(resp.result[i].content_type == 1){

                                    $(".edit_category_drop_"+resp.result[i].id+"").show();
                                    $(".edit_language_drop_"+resp.result[i].id+"").show();
                                    $(".edit_artist_drop_"+resp.result[i].id+"").show();
                                    $(".edit_no_of_content_drop_"+resp.result[i].id+"").show();
                                    $(".edit_order_by_upload_drop_"+resp.result[i].id+"").show();
                                    $(".edit_order_by_view_drop_"+resp.result[i].id+"").show();
                                    $(".edit_order_by_like_drop_"+resp.result[i].id+"").show();
                                    $(".edit_view_all_drop_"+resp.result[i].id+"").show();
                                } else if(resp.result[i].content_type == 4){

                                    $(".edit_category_drop_"+resp.result[i].id+"").hide();
                                    $(".edit_language_drop_"+resp.result[i].id+"").hide();
                                    $(".edit_artist_drop_"+resp.result[i].id+"").hide();
                                    $(".edit_no_of_content_drop_"+resp.result[i].id+"").show();
                                    $(".edit_order_by_upload_drop_"+resp.result[i].id+"").show();
                                    $(".edit_order_by_view_drop_"+resp.result[i].id+"").hide();
                                    $(".edit_order_by_like_drop_"+resp.result[i].id+"").hide();
                                    $(".edit_view_all_drop_"+resp.result[i].id+"").show();
                                } else {

                                    $(".edit_category_drop_"+resp.result[i].id+"").hide();
                                    $(".edit_language_drop_"+resp.result[i].id+"").hide();
                                    $(".edit_artist_drop_"+resp.result[i].id+"").hide();
                                    $(".edit_no_of_content_drop_"+resp.result[i].id+"").hide();
                                    $(".edit_order_by_upload_drop_"+resp.result[i].id+"").hide();
                                    $(".edit_order_by_view_drop_"+resp.result[i].id+"").hide();
                                    $(".edit_order_by_like_drop_"+resp.result[i].id+"").hide();
                                    $(".edit_view_all_drop_"+resp.result[i].id+"").hide();
                                }
                            }
                        },
                        error: function(XMLHttpRequest, textStatus, errorThrown) {
                            toastr.error(errorThrown.msg, 'failed');
                        }
                    });
                } else if(content_type == 2){

                    $(".content_type_drop").hide();
                    $(".content_type_drop option[value='1']").hide();
                    $(".content_type_drop option[value='2']").show();
                    $(".content_type_drop option[value='3']").hide();
                    $(".content_type_drop option[value='4']").hide();
                    $(".content_type_drop option[value='5']").hide();
                    $(".content_type_drop option[value='6']").hide();

                    $(".category_drop").show();
                    $(".language_drop").show();
                    $(".artist_drop").hide();
                    $(".no_of_content_drop").show();
                    $(".order_by_upload_drop").show();
                    $(".order_by_view_drop").show();
                    $(".order_by_like_drop").show();
                    $(".view_all_drop").show();

                    $(".screen_layout_drop").show();
                    $("#screen_layout").children().removeAttr("selected");
                    $("#screen_layout option[value='list_view']").hide();
                    $("#screen_layout option[value='portrait']").hide();
                    $("#screen_layout option[value='square']").hide();
                    $("#screen_layout option[value='playlist']").hide();
                    $("#screen_layout option[value='category']").hide();
                    $("#screen_layout option[value='language']").hide();
                    $("#screen_layout option[value='round']").hide();
                    $("#screen_layout option[value='banner_view']").show();
                    $("#screen_layout option[value='landscape']").show();
                    $("#screen_layout option[value='podcast_list_view']").show();

                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'POST',
                        url: '{{ route("section.content.data") }}',
                        data: {
                            is_home_screen: Is_home_screen,
                            content_type: Content_type,
                        },
                        success: function(resp) {
                            $('.after-add-more').html('');
                            for (var i = 0; i < resp.result.length; i++) {

                                if (resp.result[i].content_type == 1) {
                                    var content_type = "Music";
                                } else if (resp.result[i].content_type == 2) {
                                    var content_type = "Podcasts";
                                } else if (resp.result[i].content_type == 3) {
                                    var content_type = "Radio";
                                } else if (resp.result[i].content_type == 4) {
                                    var content_type = "Playlist";
                                } else if (resp.result[i].content_type == 5) {
                                    var content_type = "Category";
                                } else if (resp.result[i].content_type == 6) {
                                    var content_type = "Language";
                                } 

                                if (resp.result[i].screen_layout == "list_view") {
                                    var screen_layout = "List View";
                                } else if (resp.result[i].screen_layout == "portrait") {
                                    var screen_layout = "Portrait";
                                } else if (resp.result[i].screen_layout == "square") {
                                    var screen_layout = "Square";
                                } else if (resp.result[i].screen_layout == "playlist") {
                                    var screen_layout = "Playlist";
                                } else if (resp.result[i].screen_layout == "category") {
                                    var screen_layout = "Category";
                                } else if (resp.result[i].screen_layout == "language") {
                                    var screen_layout = "Language";
                                } else if (resp.result[i].screen_layout == "round") {
                                    var screen_layout = "Round";
                                } else if (resp.result[i].screen_layout == "banner_view") {
                                    var screen_layout = "Banner View";
                                } else if (resp.result[i].screen_layout == "landscape") {
                                    var screen_layout = "Landscape";
                                } else if (resp.result[i].screen_layout == "podcast_list_view") {
                                    var screen_layout = "Podcast List View";
                                }

                                var data = '<div class="card custom-border-card mt-3">'+
                                        '<h5 class="card-header">Edit Section</h5>'+
                                        '<div class="card-body">'+
                                            '<form id="edit_section_'+resp.result[i].id+'" enctype="multipart/form-data">'+
                                                '<input type="hidden" name="id" value="'+resp.result[i].id+'">'+
                                                '<div class="form-row">'+
                                                    '<div class="col-md-4">'+
                                                        '<div class="form-group">'+
                                                            '<label>{{__("Label.Title")}}</label>'+
                                                            '<input type="text" name="title" value="'+resp.result[i].title+'" class="form-control" readonly>'+
                                                        '</div>'+
                                                    '</div>'+
                                                    '<div class="col-md-4">'+
                                                        '<div class="form-group">'+
                                                            '<label>Short Title</label>'+
                                                            '<input type="text" name="short_title" value="'+resp.result[i].short_title+'" class="form-control" readonly>'+
                                                        '</div>'+
                                                    '</div>'+
                                                    '<div class="col-md-2">'+
                                                        '<div class="form-group">'+
                                                            '<label>Content Type</label>'+
                                                            '<input type="text" name="content_type" value="'+content_type+'" class="form-control" readonly>'+
                                                        '</div>'+
                                                    '</div>'+
                                                    '<div class="col-md-2">'+
                                                        '<div class="form-group">'+
                                                            '<label>Screen Layout</label>'+
                                                            '<input type="text" name="screen_layout" value="'+screen_layout+'" class="form-control" readonly>'+
                                                        '</div>'+
                                                    '</div>'+
                                                '</div>'+
                                                '<div class="border-top pt-3 text-right">'+
                                                    '<button type="button" data-toggle="modal" data-target="#exampleModal" class="btn btn-default mw-120" onclick="edit_section('+resp.result[i].id+')">{{__("Label.UPDATE")}}</button>'+
                                                    '<button type="button" class="btn btn-cancel mw-120 ml-2" onclick="delete_section('+resp.result[i].id+')">DELETE</button>'+
                                                    '<input type="hidden" name="_method" value="PATCH">'+
                                                '</div>'+
                                            '</form>'+
                                        '</div>'+
                                    '</div>';

                                $('.after-add-more').append(data);

                                $("#edit_screen_layout"+resp.result[i].id+"").val(resp.result[i].screen_layout).attr("selected","selected");
                                $("#edit_category_id_"+resp.result[i].id+"").val(resp.result[i].category_id).attr("selected","selected");
                                $("#edit_language_id_"+resp.result[i].id+"").val(resp.result[i].language_id).attr("selected","selected");
                                if(resp.result[i].order_by_upload == 1){
                                    $("#order_by_upload_yes_"+resp.result[i].id+"").attr('checked','checked');
                                } else {
                                    $("#order_by_upload_no_"+resp.result[i].id+"").attr('checked','checked');
                                }
                                if(resp.result[i].order_by_view == 1){
                                    $("#order_by_view_yes_"+resp.result[i].id+"").attr('checked','checked');
                                } else {
                                    $("#order_by_view_no_"+resp.result[i].id+"").attr('checked','checked');
                                }
                                if(resp.result[i].order_by_like == 1){
                                    $("#order_by_like_yes_"+resp.result[i].id+"").attr('checked','checked');
                                } else {
                                    $("#order_by_like_no_"+resp.result[i].id+"").attr('checked','checked');
                                }
                                if(resp.result[i].view_all == 1){
                                    $("#view_all_yes_"+resp.result[i].id+"").attr('checked','checked');
                                } else {
                                    $("#view_all_no_"+resp.result[i].id+"").attr('checked','checked');
                                }

                                $(".edit_category_drop_"+resp.result[i].id+"").hide();
                                $(".edit_language_drop_"+resp.result[i].id+"").hide();
                                $(".edit_no_of_content_drop_"+resp.result[i].id+"").hide();
                                $(".edit_order_by_upload_drop_"+resp.result[i].id+"").hide();
                                $(".edit_order_by_view_drop_"+resp.result[i].id+"").hide();
                                $(".edit_order_by_like_drop_"+resp.result[i].id+"").hide();
                                $(".edit_view_all_drop_"+resp.result[i].id+"").hide();
                                if(resp.result[i].content_type == 2){

                                    $(".edit_category_drop_"+resp.result[i].id+"").show();
                                    $(".edit_language_drop_"+resp.result[i].id+"").show();
                                    $(".edit_no_of_content_drop_"+resp.result[i].id+"").show();
                                    $(".edit_order_by_upload_drop_"+resp.result[i].id+"").show();
                                    $(".edit_order_by_view_drop_"+resp.result[i].id+"").show();
                                    $(".edit_order_by_like_drop_"+resp.result[i].id+"").show();
                                    $(".edit_view_all_drop_"+resp.result[i].id+"").show();
                                }
                            }
                        },
                        error: function(XMLHttpRequest, textStatus, errorThrown) {
                            toastr.error(errorThrown.msg, 'failed');
                        }
                    });

                } else if(content_type == 3){

                    $(".content_type_drop").hide();
                    $(".content_type_drop option[value='1']").hide();
                    $(".content_type_drop option[value='2']").hide();
                    $(".content_type_drop option[value='3']").show();
                    $(".content_type_drop option[value='4']").hide();
                    $(".content_type_drop option[value='5']").hide();
                    $(".content_type_drop option[value='6']").hide();

                    $(".category_drop").show();
                    $(".language_drop").show();
                    $(".artist_drop").show();
                    $(".no_of_content_drop").show();
                    $(".order_by_upload_drop").show();
                    $(".order_by_view_drop").hide();
                    $(".order_by_like_drop").hide();
                    $(".view_all_drop").show();

                    $(".screen_layout_drop").show();
                    $("#screen_layout").children().removeAttr("selected");
                    $("#screen_layout option[value='list_view']").hide();
                    $("#screen_layout option[value='portrait']").hide();
                    $("#screen_layout option[value='square']").show();
                    $("#screen_layout option[value='playlist']").hide();
                    $("#screen_layout option[value='category']").hide();
                    $("#screen_layout option[value='language']").hide();
                    $("#screen_layout option[value='round']").show();
                    $("#screen_layout option[value='banner_view']").hide();
                    $("#screen_layout option[value='landscape']").hide();
                    $("#screen_layout option[value='podcast_list_view']").hide();

                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'POST',
                        url: '{{ route("section.content.data") }}',
                        data: {
                            is_home_screen: Is_home_screen,
                            content_type: Content_type,
                        },
                        success: function(resp) {
                            $('.after-add-more').html('');
                            for (var i = 0; i < resp.result.length; i++) {

                                if (resp.result[i].content_type == 1) {
                                    var content_type = "Music";
                                } else if (resp.result[i].content_type == 2) {
                                    var content_type = "Podcasts";
                                } else if (resp.result[i].content_type == 3) {
                                    var content_type = "Radio";
                                } else if (resp.result[i].content_type == 4) {
                                    var content_type = "Playlist";
                                } else if (resp.result[i].content_type == 5) {
                                    var content_type = "Category";
                                } else if (resp.result[i].content_type == 6) {
                                    var content_type = "Language";
                                } 

                                if (resp.result[i].screen_layout == "list_view") {
                                    var screen_layout = "List View";
                                } else if (resp.result[i].screen_layout == "portrait") {
                                    var screen_layout = "Portrait";
                                } else if (resp.result[i].screen_layout == "square") {
                                    var screen_layout = "Square";
                                } else if (resp.result[i].screen_layout == "playlist") {
                                    var screen_layout = "Playlist";
                                } else if (resp.result[i].screen_layout == "category") {
                                    var screen_layout = "Category";
                                } else if (resp.result[i].screen_layout == "language") {
                                    var screen_layout = "Language";
                                } else if (resp.result[i].screen_layout == "round") {
                                    var screen_layout = "Round";
                                } else if (resp.result[i].screen_layout == "banner_view") {
                                    var screen_layout = "Banner View";
                                } else if (resp.result[i].screen_layout == "landscape") {
                                    var screen_layout = "Landscape";
                                } else if (resp.result[i].screen_layout == "podcast_list_view") {
                                    var screen_layout = "Podcast List View";
                                }

                                var data = '<div class="card custom-border-card mt-3">'+
                                        '<h5 class="card-header">Edit Section</h5>'+
                                        '<div class="card-body">'+
                                            '<form id="edit_section_'+resp.result[i].id+'" enctype="multipart/form-data">'+
                                                '<input type="hidden" name="id" value="'+resp.result[i].id+'">'+
                                                '<div class="form-row">'+
                                                    '<div class="col-md-4">'+
                                                        '<div class="form-group">'+
                                                            '<label>{{__("Label.Title")}}</label>'+
                                                            '<input type="text" name="title" value="'+resp.result[i].title+'" class="form-control" readonly>'+
                                                        '</div>'+
                                                    '</div>'+
                                                    '<div class="col-md-4">'+
                                                        '<div class="form-group">'+
                                                            '<label>Short Title</label>'+
                                                            '<input type="text" name="short_title" value="'+resp.result[i].short_title+'" class="form-control" readonly>'+
                                                        '</div>'+
                                                    '</div>'+
                                                    '<div class="col-md-2">'+
                                                        '<div class="form-group">'+
                                                            '<label>Content Type</label>'+
                                                            '<input type="text" name="content_type" value="'+content_type+'" class="form-control" readonly>'+
                                                        '</div>'+
                                                    '</div>'+
                                                    '<div class="col-md-2">'+
                                                        '<div class="form-group">'+
                                                            '<label>Screen Layout</label>'+
                                                            '<input type="text" name="screen_layout" value="'+screen_layout+'" class="form-control" readonly>'+
                                                        '</div>'+
                                                    '</div>'+
                                                '</div>'+
                                                '<div class="border-top pt-3 text-right">'+
                                                    '<button type="button" data-toggle="modal" data-target="#exampleModal" class="btn btn-default mw-120" onclick="edit_section('+resp.result[i].id+')">{{__("Label.UPDATE")}}</button>'+
                                                    '<button type="button" class="btn btn-cancel mw-120 ml-2" onclick="delete_section('+resp.result[i].id+')">DELETE</button>'+
                                                    '<input type="hidden" name="_method" value="PATCH">'+
                                                '</div>'+
                                            '</form>'+
                                        '</div>'+
                                    '</div>';

                                $('.after-add-more').append(data);

                                $("#edit_screen_layout"+resp.result[i].id+"").val(resp.result[i].screen_layout).attr("selected","selected");
                                $("#edit_category_id_"+resp.result[i].id+"").val(resp.result[i].category_id).attr("selected","selected");
                                $("#edit_language_id_"+resp.result[i].id+"").val(resp.result[i].language_id).attr("selected","selected");
                                $("#edit_artist_id_"+resp.result[i].id+"").val(resp.result[i].artist_id).attr("selected","selected");
                                if(resp.result[i].order_by_upload == 1){
                                    $("#order_by_upload_yes_"+resp.result[i].id+"").attr('checked','checked');
                                } else {
                                    $("#order_by_upload_no_"+resp.result[i].id+"").attr('checked','checked');
                                }
                                if(resp.result[i].order_by_view == 1){
                                    $("#order_by_view_yes_"+resp.result[i].id+"").attr('checked','checked');
                                } else {
                                    $("#order_by_view_no_"+resp.result[i].id+"").attr('checked','checked');
                                }
                                if(resp.result[i].order_by_like == 1){
                                    $("#order_by_like_yes_"+resp.result[i].id+"").attr('checked','checked');
                                } else {
                                    $("#order_by_like_no_"+resp.result[i].id+"").attr('checked','checked');
                                }
                                if(resp.result[i].view_all == 1){
                                    $("#view_all_yes_"+resp.result[i].id+"").attr('checked','checked');
                                } else {
                                    $("#view_all_no_"+resp.result[i].id+"").attr('checked','checked');
                                }

                                $(".edit_category_drop_"+resp.result[i].id+"").hide();
                                $(".edit_language_drop_"+resp.result[i].id+"").hide();
                                $(".edit_artist_drop_"+resp.result[i].id+"").hide();
                                $(".edit_no_of_content_drop_"+resp.result[i].id+"").hide();
                                $(".edit_order_by_upload_drop_"+resp.result[i].id+"").hide();
                                $(".edit_view_all_drop_"+resp.result[i].id+"").hide();
                                if(resp.result[i].content_type == 3){

                                    $(".edit_category_drop_"+resp.result[i].id+"").show();
                                    $(".edit_language_drop_"+resp.result[i].id+"").show();
                                    $(".edit_artist_drop_"+resp.result[i].id+"").show();
                                    $(".edit_no_of_content_drop_"+resp.result[i].id+"").show();
                                    $(".edit_order_by_upload_drop_"+resp.result[i].id+"").show();
                                    $(".edit_view_all_drop_"+resp.result[i].id+"").show();
                                } 
                            }
                        },
                        error: function(XMLHttpRequest, textStatus, errorThrown) {
                            toastr.error(errorThrown.msg, 'failed');
                        }
                    });

                } else {

                    $(".content_type_drop").hide();
                    $(".category_drop").hide();
                    $(".language_drop").hide();
                    $(".artist_drop").hide();
                    $(".no_of_content_drop").hide();
                    $(".order_by_upload_drop").hide();
                    $(".order_by_view_drop").hide();
                    $(".order_by_like_drop").hide();
                    $(".view_all_drop").hide();
                    $(".screen_layout_drop").hide();
                }
            }
        };

        // Update Section
        function edit_section(id){

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: '{{ route("section.content.edit") }}',
                data: {
                    id: id,
                },
                success: function(resp) {

                    if(resp.result != null){
                        $("#edit_id").val(resp.result.id);
                        $("#edit_is_home_screen").val(resp.result.is_home_screen);
                        $("#edit_title").val(resp.result.title);
                        $("#edit_short_title").val(resp.result.short_title);
                        $("#edit_content_type").val(resp.result.content_type).attr("selected","selected");
                        $("#edit_screen_layout").val(resp.result.screen_layout).attr("selected","selected");
                        $('#edit_category_id').val(resp.result.category_id).trigger('change');
                        $('#edit_language_id').val(resp.result.language_id).trigger('change');
                        $('#edit_artist_id').val(resp.result.artist_id).trigger('change');
                        $("#edit_no_of_content").val(resp.result.no_of_content);
                        if(resp.result.order_by_upload == 1){
                            $("#edit_order_by_upload_yes").attr('checked','checked');
                        } else {
                            $("#edit_order_by_upload_no").attr('checked','checked');
                        }
                        if(resp.result.order_by_view == 1){
                            $("#edit_order_by_view_yes").attr('checked','checked');
                        } else {
                            $("#edit_order_by_view_no").attr('checked','checked');
                        }
                        if(resp.result.order_by_like == 1){
                            $("#edit_order_by_like_yes").attr('checked','checked');
                        } else {
                            $("#edit_order_by_like_no").attr('checked','checked');
                        }
                        if(resp.result.view_all == 1){
                            $("#edit_view_all_yes").attr('checked','checked');
                        } else {
                            $("#edit_view_all_no").attr('checked','checked');
                        }

                        if(resp.result.is_home_screen == 1){
                            $(".edit_content_type_drop").show();
                            $(".edit_content_type_drop option[value='1']").show();
                            $(".edit_content_type_drop option[value='2']").show();
                            $(".edit_content_type_drop option[value='3']").show();
                            $(".edit_content_type_drop option[value='4']").show();
                            $(".edit_content_type_drop option[value='5']").show();
                            $(".edit_content_type_drop option[value='6']").show();
                        } else if(resp.result.is_home_screen == 2){

                            if(resp.result.content_type == 1 || resp.result.content_type == 4){

                                $(".edit_content_type_drop").show();
                                $(".edit_content_type_drop option[value='1']").show();
                                $(".edit_content_type_drop option[value='2']").hide();
                                $(".edit_content_type_drop option[value='3']").hide();
                                $(".edit_content_type_drop option[value='4']").show();
                                $(".edit_content_type_drop option[value='5']").hide();
                                $(".edit_content_type_drop option[value='6']").hide();
                            } else if(resp.result.content_type == 2){
                                $(".edit_content_type_drop").hide();
                            } else if(resp.result.content_type == 3){
                                $(".edit_content_type_drop").hide();
                            } else {
                                $(".edit_content_type_drop").hide();
                            }
                        } else {
                            $(".edit_content_type_drop").hide();
                        }

                        if(resp.result.content_type == 1){

                            $(".edit_category_drop").show();
                            $(".edit_language_drop").show();
                            $(".edit_artist_drop").show();
                            $(".edit_no_of_content_drop").show();
                            $(".edit_order_by_upload_drop").show();
                            $(".edit_order_by_view_drop").show();
                            $(".edit_order_by_like_drop").show();
                            $(".edit_view_all_drop").show();

                            $("#edit_screen_layout option[value='list_view']").show();
                            $("#edit_screen_layout option[value='portrait']").show();
                            $("#edit_screen_layout option[value='square']").show();
                            $("#edit_screen_layout option[value='playlist']").hide();
                            $("#edit_screen_layout option[value='category']").hide();
                            $("#edit_screen_layout option[value='language']").hide();
                            $("#edit_screen_layout option[value='round']").hide();
                            $("#edit_screen_layout option[value='banner_view']").hide();
                            $("#edit_screen_layout option[value='landscape']").hide();
                            $("#edit_screen_layout option[value='podcast_list_view']").hide();

                        } else if(resp.result.content_type == 2){

                            $(".edit_category_drop").show();
                            $(".edit_language_drop").show();
                            $(".edit_artist_drop").hide();
                            $(".edit_no_of_content_drop").show();
                            $(".edit_order_by_upload_drop").show();
                            $(".edit_order_by_view_drop").show();
                            $(".edit_order_by_like_drop").show();
                            $(".edit_view_all_drop").show();

                            $("#edit_screen_layout option[value='list_view']").hide();
                            $("#edit_screen_layout option[value='portrait']").hide();
                            $("#edit_screen_layout option[value='square']").hide();
                            $("#edit_screen_layout option[value='playlist']").hide();
                            $("#edit_screen_layout option[value='category']").hide();
                            $("#edit_screen_layout option[value='language']").hide();
                            $("#edit_screen_layout option[value='round']").hide();
                            $("#edit_screen_layout option[value='banner_view']").show();
                            $("#edit_screen_layout option[value='landscape']").show();
                            $("#edit_screen_layout option[value='podcast_list_view']").show();
                        } else if(resp.result.content_type == 3){

                            $(".edit_category_drop").show();
                            $(".edit_language_drop").show();
                            $(".edit_artist_drop").show();
                            $(".edit_no_of_content_drop").show();
                            $(".edit_order_by_upload_drop").show();
                            $(".edit_order_by_view_drop").hide();
                            $(".edit_order_by_like_drop").hide();
                            $(".edit_view_all_drop").show();

                            $("#edit_screen_layout option[value='list_view']").hide();
                            $("#edit_screen_layout option[value='portrait']").hide();
                            $("#edit_screen_layout option[value='square']").show();
                            $("#edit_screen_layout option[value='playlist']").hide();
                            $("#edit_screen_layout option[value='category']").hide();
                            $("#edit_screen_layout option[value='language']").hide();
                            $("#edit_screen_layout option[value='round']").show();
                            $("#edit_screen_layout option[value='banner_view']").hide();
                            $("#edit_screen_layout option[value='landscape']").hide();
                            $("#edit_screen_layout option[value='podcast_list_view']").hide();
                        } else if(resp.result.content_type == 4){

                            $(".edit_category_drop").show();
                            $(".edit_language_drop").hide();
                            $(".edit_artist_drop").hide();
                            $(".edit_no_of_content_drop").show();
                            $(".edit_order_by_upload_drop").show();
                            $(".edit_order_by_view_drop").hide();
                            $(".edit_order_by_like_drop").hide();
                            $(".edit_view_all_drop").show();

                            $("#edit_screen_layout option[value='list_view']").hide();
                            $("#edit_screen_layout option[value='portrait']").hide();
                            $("#edit_screen_layout option[value='square']").hide();
                            $("#edit_screen_layout option[value='playlist']").show();
                            $("#edit_screen_layout option[value='category']").hide();
                            $("#edit_screen_layout option[value='language']").hide();
                            $("#edit_screen_layout option[value='round']").hide();
                            $("#edit_screen_layout option[value='banner_view']").hide();
                            $("#edit_screen_layout option[value='landscape']").hide();
                            $("#edit_screen_layout option[value='podcast_list_view']").hide();
                        } else if(resp.result.content_type == 5){

                            $(".edit_category_drop").hide();
                            $(".edit_language_drop").hide();
                            $(".edit_artist_drop").hide();
                            $(".edit_no_of_content_drop").hide();
                            $(".edit_order_by_upload_drop").hide();
                            $(".edit_order_by_view_drop").hide();
                            $(".edit_order_by_like_drop").hide();
                            $(".edit_view_all_drop").hide();

                            $("#edit_screen_layout option[value='list_view']").hide();
                            $("#edit_screen_layout option[value='portrait']").hide();
                            $("#edit_screen_layout option[value='square']").hide();
                            $("#edit_screen_layout option[value='playlist']").hide();
                            $("#edit_screen_layout option[value='category']").show();
                            $("#edit_screen_layout option[value='language']").hide();
                            $("#edit_screen_layout option[value='round']").hide();
                            $("#edit_screen_layout option[value='banner_view']").hide();
                            $("#edit_screen_layout option[value='landscape']").hide();
                            $("#edit_screen_layout option[value='podcast_list_view']").hide();
                        } else if(resp.result.content_type == 6){

                            $(".edit_category_drop").hide();
                            $(".edit_language_drop").hide();
                            $(".edit_artist_drop").hide();
                            $(".edit_no_of_content_drop").hide();
                            $(".edit_order_by_upload_drop").hide();
                            $(".edit_order_by_view_drop").hide();
                            $(".edit_order_by_like_drop").hide();
                            $(".edit_view_all_drop").hide();

                            $("#edit_screen_layout option[value='list_view']").hide();
                            $("#edit_screen_layout option[value='portrait']").hide();
                            $("#edit_screen_layout option[value='square']").hide();
                            $("#edit_screen_layout option[value='playlist']").hide();
                            $("#edit_screen_layout option[value='category']").hide();
                            $("#edit_screen_layout option[value='language']").show();
                            $("#edit_screen_layout option[value='round']").hide();
                            $("#edit_screen_layout option[value='banner_view']").hide();
                            $("#edit_screen_layout option[value='landscape']").hide();
                            $("#edit_screen_layout option[value='podcast_list_view']").hide();
                        } else {

                            $(".edit_category_drop").hide();
                            $(".edit_language_drop").hide();
                            $(".edit_artist_drop").hide();
                            $(".edit_no_of_content_drop").hide();
                            $(".edit_order_by_upload_drop").hide();
                            $(".edit_order_by_view_drop").hide();
                            $(".edit_order_by_like_drop").hide();
                            $(".edit_view_all_drop").hide();
                            $(".edit_screen_layout_drop").hide();
                        }

                        $("#edit_content_type").change(function() {

                            var content_type = $(this).children("option:selected").val();

                            if(content_type == 1){

                                $(".edit_category_drop").show();
                                $(".edit_language_drop").show();
                                $(".edit_artist_drop").show();
                                $(".edit_no_of_content_drop").show();
                                $(".edit_order_by_upload_drop").show();
                                $(".edit_order_by_view_drop").show();
                                $(".edit_order_by_like_drop").show();
                                $(".edit_view_all_drop").show();

                                $(".edit_screen_layout_drop").show();
                                $("#edit_screen_layout").children().removeAttr("selected");
                                $("#edit_screen_layout option[value='list_view']").show();
                                $("#edit_screen_layout option[value='portrait']").show();
                                $("#edit_screen_layout option[value='square']").show();
                                $("#edit_screen_layout option[value='playlist']").hide();
                                $("#edit_screen_layout option[value='category']").hide();
                                $("#edit_screen_layout option[value='language']").hide();
                                $("#edit_screen_layout option[value='round']").hide();
                                $("#edit_screen_layout option[value='banner_view']").hide();
                                $("#edit_screen_layout option[value='landscape']").hide();
                                $("#edit_screen_layout option[value='podcast_list_view']").hide();

                            } else if(content_type == 2){

                                $(".edit_category_drop").show();
                                $(".edit_language_drop").show();
                                $(".edit_artist_drop").hide();
                                $(".edit_no_of_content_drop").show();
                                $(".edit_order_by_upload_drop").show();
                                $(".edit_order_by_view_drop").show();
                                $(".edit_order_by_like_drop").show();
                                $(".edit_view_all_drop").show();

                                $(".edit_screen_layout_drop").show();
                                $("#edit_screen_layout").children().removeAttr("selected");
                                $("#edit_screen_layout option[value='list_view']").hide();
                                $("#edit_screen_layout option[value='portrait']").hide();
                                $("#edit_screen_layout option[value='square']").hide();
                                $("#edit_screen_layout option[value='playlist']").hide();
                                $("#edit_screen_layout option[value='category']").hide();
                                $("#edit_screen_layout option[value='language']").hide();
                                $("#edit_screen_layout option[value='round']").hide();
                                $("#edit_screen_layout option[value='banner_view']").show();
                                $("#edit_screen_layout option[value='landscape']").show();
                                $("#edit_screen_layout option[value='podcast_list_view']").show();

                            } else if(content_type == 3){

                                $(".edit_category_drop").show();
                                $(".edit_language_drop").show();
                                $(".edit_artist_drop").show();
                                $(".edit_no_of_content_drop").show();
                                $(".edit_order_by_upload_drop").show();
                                $(".edit_order_by_view_drop").hide();
                                $(".edit_order_by_like_drop").hide();
                                $(".edit_view_all_drop").show();

                                $(".edit_screen_layout_drop").show();
                                $("#edit_screen_layout").children().removeAttr("selected");
                                $("#edit_screen_layout option[value='list_view']").hide();
                                $("#edit_screen_layout option[value='portrait']").hide();
                                $("#edit_screen_layout option[value='square']").show();
                                $("#edit_screen_layout option[value='playlist']").hide();
                                $("#edit_screen_layout option[value='category']").hide();
                                $("#edit_screen_layout option[value='language']").hide();
                                $("#edit_screen_layout option[value='round']").show();
                                $("#edit_screen_layout option[value='banner_view']").hide();
                                $("#edit_screen_layout option[value='landscape']").hide();
                                $("#edit_screen_layout option[value='podcast_list_view']").hide();

                            } else if(content_type == 4){

                                $(".edit_category_drop").hide();
                                $(".edit_language_drop").hide();
                                $(".edit_artist_drop").hide();
                                $(".edit_no_of_content_drop").show();
                                $(".edit_order_by_upload_drop").show();
                                $(".edit_order_by_view_drop").hide();
                                $(".edit_order_by_like_drop").hide();
                                $(".edit_view_all_drop").show();

                                $(".edit_screen_layout_drop").show();
                                $("#edit_screen_layout").children().removeAttr("selected");
                                $("#edit_screen_layout option[value='list_view']").hide();
                                $("#edit_screen_layout option[value='portrait']").hide();
                                $("#edit_screen_layout option[value='square']").hide();
                                $("#edit_screen_layout option[value='playlist']").show();
                                $("#edit_screen_layout option[value='category']").hide();
                                $("#edit_screen_layout option[value='language']").hide();
                                $("#edit_screen_layout option[value='round']").hide();
                                $("#edit_screen_layout option[value='banner_view']").hide();
                                $("#edit_screen_layout option[value='landscape']").hide();
                                $("#edit_screen_layout option[value='podcast_list_view']").hide();

                            } else if(content_type == 5){

                                $(".edit_category_drop").hide();
                                $(".edit_language_drop").hide();
                                $(".edit_artist_drop").hide();
                                $(".edit_no_of_content_drop").hide();
                                $(".edit_order_by_upload_drop").hide();
                                $(".edit_order_by_view_drop").hide();
                                $(".edit_order_by_like_drop").hide();
                                $(".edit_view_all_drop").hide();

                                $(".edit_screen_layout_drop").show();
                                $("#edit_screen_layout").children().removeAttr("selected");
                                $("#edit_screen_layout option[value='list_view']").hide();
                                $("#edit_screen_layout option[value='portrait']").hide();
                                $("#edit_screen_layout option[value='square']").hide();
                                $("#edit_screen_layout option[value='playlist']").hide();
                                $("#edit_screen_layout option[value='category']").show();
                                $("#edit_screen_layout option[value='language']").hide();
                                $("#edit_screen_layout option[value='round']").hide();
                                $("#edit_screen_layout option[value='banner_view']").hide();
                                $("#edit_screen_layout option[value='landscape']").hide();
                                $("#edit_screen_layout option[value='podcast_list_view']").hide();

                            } else if(content_type == 6){

                                $(".edit_category_drop").hide();
                                $(".edit_language_drop").hide();
                                $(".edit_artist_drop").hide();
                                $(".edit_no_of_content_drop").hide();
                                $(".edit_order_by_upload_drop").hide();
                                $(".edit_order_by_view_drop").hide();
                                $(".edit_order_by_like_drop").hide();
                                $(".edit_view_all_drop").hide();

                                $(".edit_screen_layout_drop").show();
                                $("#edit_screen_layout").children().removeAttr("selected");
                                $("#edit_screen_layout option[value='list_view']").hide();
                                $("#edit_screen_layout option[value='portrait']").hide();
                                $("#edit_screen_layout option[value='square']").hide();
                                $("#edit_screen_layout option[value='playlist']").hide();
                                $("#edit_screen_layout option[value='category']").hide();
                                $("#edit_screen_layout option[value='language']").show();
                                $("#edit_screen_layout option[value='round']").hide();
                                $("#edit_screen_layout option[value='banner_view']").hide();
                                $("#edit_screen_layout option[value='landscape']").hide();
                                $("#edit_screen_layout option[value='podcast_list_view']").hide();

                            } else {

                                $(".edit_category_drop").hide();
                                $(".edit_language_drop").hide();
                                $(".edit_artist_drop").hide();
                                $(".edit_no_of_content_drop").hide();
                                $(".edit_order_by_upload_drop").hide();
                                $(".edit_order_by_view_drop").hide();
                                $(".edit_order_by_like_drop").hide();
                                $(".edit_view_all_drop").hide();
                                $(".edit_screen_layout_drop").hide();
                            }
                        });
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    toastr.error(errorThrown.msg, 'failed');
                }
            });
        }
        function update_section(){

            var Check_Admin = '<?php echo Check_Admin_Access(); ?>';
            if(Check_Admin == 1){

                $("#dvloader").show();
                var id = $('#edit_id').val();
                var formData = new FormData($("#edit_content_section")[0]);

                var url = '{{ route("section.update", ":id") }}';
                    url = url.replace(':id', id);

                $.ajax({
                    headers: {
					    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				    },
				    enctype: 'multipart/form-data',
                    type: 'POST',
                    url: url,
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(resp) {

                        $("#dvloader").hide();
                        if(resp.status == 200){
                            $('#exampleModal').modal('toggle');
                        }
                        get_responce_message(resp, 'edit_content_section', '{{ route("section.index") }}');
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        $("#dvloader").hide();
                        toastr.error(errorThrown.msg, 'failed');
                    }
                });
            } else {
                toastr.error('You have no right to Add, Edit and Delete.');
            }
        }

        // Delete Section
        function delete_section(id){

            var Check_Admin = '<?php echo Check_Admin_Access(); ?>';
            if(Check_Admin == 1){

                var result = confirm("Are you sure !!! You want to Delete this Section ?");
                if(result){

                    $("#dvloader").show();
    
                    var url = '{{ route("section.show", ":id") }}';
                        url = url.replace(':id', id);
    
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'GET',
                        url: url,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function(resp) {
                            $("#dvloader").hide();
                            get_responce_message(resp, '', '{{ route("section.index") }}');
                        },
                        error: function(XMLHttpRequest, textStatus, errorThrown) {
                            $("#dvloader").hide();
                            toastr.error(errorThrown.msg, 'failed');
                        }
                    });
                }
            } else {
                toastr.error('You have no right to Add, Edit and Delete.');
            }
        }

        // Sortable Section
        $("#imageListId").sortable({
            update: function(event, ui) {
                getIdsOfImages();
            } //end update
        });

        function getIdsOfImages() {
            var values = [];
            $('.listitemClass').each(function(index) {
                values.push($(this).attr("id")
                    .replace("imageNo", ""));
            });
            $('#outputvalues').val(values);
        }
        function sortableBTN(){
            var Tab = $("ul.tabs li a.active");
            var Is_home_screen = Tab.data("is_home_screen");
            var Content_type = Tab.data("content_type");
            
            $("#dvloader").show();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: '{{ route("section.content.sortable") }}',
                data: {
                    is_home_screen: Is_home_screen,
                    content_type: Content_type,
                },
                success: function(resp) {
                    $("#dvloader").hide();

                    $('#imageListId').html('');
                    for (var i = 0; i < resp.result.length; i++) {

                        var data = '<div id="'+ resp.result[i].id+'" class="listitemClass mb-2" style="background-color: #e9ecef;border: 1px solid black;cursor: s-resize;">'+
                                    '<p class="m-2">'+resp.result[i].title+'</p>'+
                                '</div>';

                        $('#imageListId').append(data);
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown.msg, 'failed');
                }
            });
        }
        function save_section_sortable() {
            var Check_Admin = '<?php echo Check_Admin_Access(); ?>';
            if(Check_Admin == 1){

                $("#dvloader").show();
                var formData = new FormData($("#save_section_sortable")[0]);
                $.ajax({
                    type: 'POST',
                    url: '{{ route("section.content.sortable.save") }}',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(resp) {
                        $("#dvloader").hide();
                        get_responce_message(resp, 'save_section_sortable', '{{ route("section.index") }}');
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        $("#dvloader").hide();
                        toastr.error(errorThrown.msg, 'failed');
                    }
                });
            } else {
                toastr.error('You have no right to Add, Edit and Delete.');
            }
        }
    </script>
@endsection