@extends('admin.layout.page-app')
@section('page_title', 'Add Custom Ads')

@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">Add Custom Ads</h1>

            <div class="border-bottom row mb-3">
                <div class="col-sm-10">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('Label.Dashboard')}}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('ads.index') }}">Custom Ads</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add Custom Ads</li>
                    </ol>
                </div>
                <div class="col-sm-2 d-flex align-items-center justify-content-end">
                    <a href="{{ route('ads.index') }}" class="btn btn-default mw-120" style="margin-top:-14px">Custom Ads List</a>
                </div>
            </div>

            <div class="card custom-border-card mt-3">
                <form id="ads" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="">
                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{__('Label.Title')}}<span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control" placeholder="Enter Title" autofocus>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>User<span class="text-danger">*</span></label>
                                <select name="user_id" class="form-control user_id" style="width:100%!important;">
                                    <option value="">Select User</option>
                                    @foreach ($user as $key => $value)
                                        <option value="{{$value->id}}">
                                            {{$value->channel_name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Redirect URL<span class="text-danger">*</span></label>
                                <input type="url" name="redirect_uri" class="form-control" placeholder="Enter Redirect URL">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Ads Budget<span class="text-danger">*</span></label>
                                <input type="number" name="budget" min="0" class="form-control" placeholder="Enter Ads Budget">
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Type<span class="text-danger">*</span></label>
                                <select name="type" class="form-control" id="type">
                                    <option value="1">Banner Ads</option>
                                    <option value="2">Interstital Ads</option>
                                    <option value="3">Reward Ads</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 video_box">
                            <div class="form-group">
                                <div style="display: block;">
                                    <label>{{__('Label.Upload_Video')}}</label>
                                    <div id="filelist2"></div>
                                    <div id="container2" style="position: relative;">
                                        <div class="form-group">
                                            <input type="file" id="uploadFile2" name="uploadFile2" class="form-control import-file p-2">
                                        </div>
                                        <input type="hidden" name="video" id="mp3_file_name2" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-1 mt-4 video_box">
                            <div class="form-group mt-3">
                                <a id="upload2" class="btn text-white" style="background-color:#4e45b8;">{{__('Label.Upload_Files')}}</a>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group ml-5">
                                <label>Image<span class="text-danger">*</span></label>
                                <div class="avatar-upload">
                                    <div class="avatar-edit">
                                        <input type='file' name="image" id="imageUpload" accept=".png, .jpg, .jpeg" />
                                        <label for="imageUpload" title="Select File"></label>
                                    </div>
                                    <div class="avatar-preview">
                                        <img src="{{asset('assets/imgs/upload_img.png')}}" alt="upload_img.png" id="imagePreview">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="border-top pt-3 text-right">
                        <button type="button" class="btn btn-default mw-120" onclick="save_ads()">{{__('Label.SAVE')}}</button>
                        <a href="{{route('ads.index')}}" class="btn btn-cancel mw-120 ml-2">{{__('Label.CANCEL')}}</a>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    </div>
                </form>  
            </div>

            <div class="card custom-border-card mt-3">
                <h5 class="card-header">Ads Statistics</h5>
                <div class="card-body">
                    <div class="form-row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Type</label>
                                <input type="text" readonly class="form-control" value="Banner Ads">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label>Ads Status</label>
                                <?php $banner_status = $ads_setting['banner_ads_status'];?>
                                <input type="text" readonly class="form-control" value="@if($banner_status == 1){{'On'}}@else{{'Off'}}@endif">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label>Cost per View</label>
                                <input type="text" readonly class="form-control" value="{{$ads_setting['banner_ads_cpv']}}">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label>Cost per Click</label>
                                <input type="text" readonly class="form-control" value="{{$ads_setting['banner_ads_cpc']}}">
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <input type="text" readonly class="form-control" value="Interstital Ads">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <?php $interstital_status = $ads_setting['interstital_ads_status'];?>
                                <input type="text" readonly class="form-control" value="@if($interstital_status == 1){{'On'}}@else{{'Off'}}@endif">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <input type="text" readonly class="form-control" value="{{$ads_setting['interstital_ads_cpv']}}">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <input type="text" readonly class="form-control" value="{{$ads_setting['interstital_ads_cpc']}}">
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <input type="text" readonly class="form-control" value="Reward Ads">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <?php $reward_status = $ads_setting['reward_ads_status'];?>
                                <input type="text" readonly class="form-control" value="@if($reward_status == 1){{'On'}}@else{{'Off'}}@endif">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <input type="text" readonly class="form-control" value="{{$ads_setting['reward_ads_cpv']}}">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <input type="text" readonly class="form-control" value="{{$ads_setting['reward_ads_cpc']}}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('pagescript')
	<script>

        // Sidebar Scroll Down
        let sidebarHeight = $('.sidebar')[0].scrollHeight;
        sidebar_down(sidebarHeight);
 
        $(document).ready(function() {
            $(".video_box").hide();
            $('#type').change(function() {
                var optionValue = $(this).val();

                if (optionValue == 3) {
                    $(".video_box").show();
                } else {
                    $(".video_box").hide();
                }
            });
        });

		function save_ads(){
            var Check_Admin = '<?php echo Check_Admin_Access(); ?>';
            if(Check_Admin == 1){

                $("#dvloader").show();
                var formData = new FormData($("#ads")[0]);
                $.ajax({
                    type:'POST',
                    url:'{{ route("ads.store") }}',
                    data:formData,
                    cache:false,
                    contentType: false,
                    processData: false,
                    success:function(resp){
                        $("#dvloader").hide();
                        get_responce_message(resp, 'ads', '{{ route("ads.index") }}');
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