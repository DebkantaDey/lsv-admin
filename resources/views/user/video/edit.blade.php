@extends('user.layout.page-app')
@section('page_title',  __('Label.Edit_Video'))

@section('content')
    @include('user.layout.sidebar')

    <div class="right-content">
        @include('user.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">{{__('Label.Edit_Video')}}</h1>
    
            <div class="border-bottom row mb-3">
                <div class="col-sm-10">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('user.dashboard') }}">{{__('Label.Dashboard')}}</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('uvideo.index') }}">{{__('Label.Videos')}}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            {{__('Label.Edit_Video')}}
                        </li>
                    </ol>
                </div>
                <div class="col-sm-2 d-flex align-items-center justify-content-end">
                    <a href="{{ route('uvideo.index') }}" class="btn btn-default mw-120" style="margin-top:-14px">{{__('Label.Video_List')}}</a>
                </div>
            </div>

            <form id="video" enctype="multipart/form-data">
                <input type="hidden" name="id" value="@if($data){{$data->id}}@endif">
                <input type="hidden" name="old_hashtag_id" value="@if($data){{$data->hashtag_id}}@endif">
                <input type="hidden" name="old_portrait_img" value="@if($data){{$data->portrait_img}}@endif">
                <input type="hidden" name="old_landscape_img" value="@if($data){{$data->landscape_img}}@endif">
                <input type="hidden" name="old_content" value="@if($data){{$data->content}}@endif">
                <input type="hidden" name="old_content_upload_type" value="@if($data){{$data->content_upload_type}}@endif">
                <div class="card custom-border-card mt-3">
                    <div class="form-row">
                        <div class="col-md-9">
                            <div class="form-row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label>{{__('Label.Title')}}<span class="text-danger">*</span></label>
                                        <input type="text" name="title" value="@if($data){{$data->title}}@endif" class="form-control" placeholder="Enter Title" autofocus>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Category<span class="text-danger">*</span></label>
                                        <select name="category_id" class="form-control category_id" style="width:100%!important;">
                                            <option value="">Select Category</option>
                                            @foreach ($category as $key => $value)
                                                <option value="{{$value->id}}" {{ $data->category_id == $value->id  ? 'selected' : ''}}>
                                                    {{ $value->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label>{{__('Label.Description')}}<span class="text-danger">*</span></label>
                                        <textarea name="description" class="form-control" rows="3" placeholder="Describe Here,">@if($data){{$data->description}}@endif</textarea>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Language<span class="text-danger">*</span></label>
                                        <select name="language_id" class="form-control language_id" style="width:100%!important;">
                                            <option value="">Select Language</option>
                                            @foreach ($language as $key => $value)
                                                <option value="{{$value->id}}" {{ $data->language_id == $value->id  ? 'selected' : ''}}>
                                                    {{ $value->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group ml-5">
                                <label>Portrait Image<span class="text-danger">*</span></label>
                                <div class="avatar-upload">
                                    <div class="avatar-edit">
                                        <input type='file' name="portrait_img" id="imageUpload" accept=".png, .jpg, .jpeg" />
                                        <label for="imageUpload" title="Select File"></label>
                                    </div>
                                    <div class="avatar-preview">
                                        <img src="{{$data->portrait_img}}" alt="upload_img.png" id="imagePreview">
                                    </div>
                                </div>
                                <label class="mt-3 text-gray">Maximum size 2MB.</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card custom-border-card mt-3">
                    <div class="form-row">
                        <div class="col-md-9">
                            <div class="form-row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Upload Type<span class="text-danger">*</span></label>
                                        <select class="form-control" name="content_upload_type" id="content_upload_type">
                                            <option value="server_video" {{ $data->content_upload_type == "server_video" ? 'selected' : ''}}>Server Video</option>
                                            <option value="external_url" {{ $data->content_upload_type == "external_url" ? 'selected' : ''}}>External URL</option>
                                            <option value="youtube" {{ $data->content_upload_type == "youtube" ? 'selected' : ''}}>Youtube</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 video_box">
                                    <div class="form-group">
                                        <div style="display: block;">
                                            <label>{{__('Label.Upload_Video')}}</label>
                                            <div id="filelist"></div>
                                            <div id="container" style="position: relative;">
                                                <div class="form-group">
                                                    <input type="file" id="uploadFile" name="uploadFile" class="form-control import-file p-2">
                                                </div>
                                                <input type="hidden" name="video" id="mp3_file_name" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2 mt-4 video_box">
                                    <div class="form-group mt-3">
                                        <a id="upload" class="btn text-white" style="background-color:#4e45b8;">{{__('Label.Upload_Files')}}</a>
                                    </div>
                                </div>
                                <div class="col-md-6 url_box">
                                    <div class="form-group">
                                        <label>URL<span class="text-danger">*</span></label>
                                        <input type="text" name="url" value="@if($data->content_upload_type != 'server_video'){{{$data->content}}}@endif" class="form-control" placeholder="Enter URL">
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Video Duration</label>
                                        <input type="text" id="timePicker" name="content_duration" placeholder="Video Duration" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="form-row mt-4">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Rent<span class="text-danger">*</span></label>
                                        <div class="radio-group">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" name="is_rent" id="is_rent_yes" class="custom-control-input" value="1" {{ $data->is_rent == 1 ? 'checked' : ''}}>
                                                <label class="custom-control-label" for="is_rent_yes">{{__('Label.Yes')}}</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" name="is_rent" id="is_rent_no" class="custom-control-input" value="0" {{ $data->is_rent == 0 ? 'checked' : ''}}>
                                                <label class="custom-control-label" for="is_rent_no">{{__('Label.No')}}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Comment<span class="text-danger">*</span></label>
                                        <div class="radio-group">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" name="is_comment" id="is_comment_yes" class="custom-control-input" value="1" {{ $data->is_comment == 1 ? 'checked' : ''}}>
                                                <label class="custom-control-label" for="is_comment_yes">{{__('Label.Yes')}}</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" name="is_comment" id="is_comment_no" class="custom-control-input" value="0" {{ $data->is_comment == 0 ? 'checked' : ''}}>
                                                <label class="custom-control-label" for="is_comment_no">{{__('Label.No')}}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Download<span class="text-danger">*</span></label>
                                        <div class="radio-group">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" name="is_download" id="is_download_yes" class="custom-control-input" value="1" {{ $data->is_download == 1 ? 'checked' : ''}}>
                                                <label class="custom-control-label" for="is_download_yes">{{__('Label.Yes')}}</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" name="is_download" id="is_download_no" class="custom-control-input" value="0" {{ $data->is_download == 0 ? 'checked' : ''}}>
                                                <label class="custom-control-label" for="is_download_no">{{__('Label.No')}}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div> -->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Like<span class="text-danger">*</span></label>
                                        <div class="radio-group">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" name="is_like" id="is_like_yes" class="custom-control-input" value="1" {{ $data->is_like == 1 ? 'checked' : ''}}>
                                                <label class="custom-control-label" for="is_like_yes">{{__('Label.Yes')}}</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" name="is_like" id="is_like_no" class="custom-control-input" value="0" {{ $data->is_like == 0 ? 'checked' : ''}}>
                                                <label class="custom-control-label" for="is_like_no">{{__('Label.No')}}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row rent_price">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Price<span class="text-danger">*</span></label>
                                        <input type="number" name="rent_price" value="@if($data->is_rent == '1'){{{$data->rent_price}}}@endif" class="form-control" placeholder="Enter Price" min="0">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group ml-5">
                                <label>Landscape Image<span class="text-danger">*</span></label>
                                <div class="avatar-upload-landscape">
                                    <div class="avatar-edit-landscape">
                                        <input type='file' name="landscape_img" id="imageUploadLandscape" accept=".png, .jpg, .jpeg" />
                                        <label for="imageUploadLandscape" title="Select File"></label>
                                    </div>
                                    <div class="avatar-preview-landscape">
                                        <img src="{{$data->landscape_img}}" alt="upload_img.png" id="imagePreviewLandscape">
                                    </div>
                                </div>
                                <label class="mt-3 text-gray">Maximum size 2MB.</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pt-3 text-right">
                    <button type="button" class="btn btn-default mw-120" onclick="save_video()">{{__('Label.SAVE')}}</button>
                    <a href="{{route('uvideo.index')}}" class="btn btn-cancel mw-120 ml-2">{{__('Label.CANCEL')}}</a>
					<input type="hidden" name="_method" value="PATCH">
                </div>
            </form>
        </div>
    </div>
@endsection

@section('pagescript')
	<script>
        $(".category_id").select2();
        $(".language_id").select2();

        var duration = '<?php echo $data->content_duration; ?>';
        function msToHours(duration) {
            var hours = Math.floor((duration / (1000 * 60 * 60)) % 24);
                hours = (hours < 10) ? "0" + hours : hours;
                return hours;
        }
        function msToMinutes(duration) {
            var minutes = Math.floor((duration / (1000 * 60)) % 60),
                minutes = (minutes < 10) ? "0" + minutes : minutes;
                return minutes;
        }
        function msToSeconds(duration) {
            var seconds = Math.floor((duration / 1000) % 60),
                seconds = (seconds < 10) ? "0" + seconds : seconds;
                return seconds;
        }
        let hours = msToHours(duration);
        let minutes = msToMinutes(duration);
        let seconds = msToSeconds(duration);
        var date = new Date();
            date.setHours(hours,minutes,seconds);

        $('#timePicker').datetimepicker({
            useCurrent: false,
            format:'HH:mm:ss',
            defaultDate: date,
            showClose:true,
            showTodayButton: true,
            icons: {
                up: "fa fa-chevron-up",
                down: "fa fa-chevron-down",
                today: "fa fa-clock fa-regular",
                close: "fa fa-times",
            }
        })

        $(document).ready(function() {

            var content_upload_type = "<?php echo $data->content_upload_type; ?>";
            if (content_upload_type == "server_video") {
                $(".url_box").hide();
            } else {
                $(".video_box").hide();
            }
            $('#content_upload_type').change(function() {
                var optionValue = $(this).val();

                if (optionValue == 'server_video') {
                    $(".video_box").show();
                    $(".url_box").hide();
                } else {
                    $(".url_box").show();
                    $(".video_box").hide();
                }
            });

            var is_rent = "<?php echo $data->is_rent; ?>";
            if(is_rent == 1){
                $(".rent_price").show();
            } else {
                $(".rent_price").hide();
            }
            $('input[type=radio][name=is_rent]').change(function() {
                if (this.value == 1) {
                    $(".rent_price").show();
                }
                else if (this.value == 0) {
                    $(".rent_price").hide();
                }
            });
        });

		function save_video(){
			$("#dvloader").show();
			var formData = new FormData($("#video")[0]);

			$.ajax({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				enctype: 'multipart/form-data',
				type: 'POST',
				url: '{{route("uvideo.update", [$data->id])}}',
				data: formData,
				cache:false,
				contentType: false,
				processData: false,
				success:function(resp){
					$("#dvloader").hide();
					get_responce_message(resp, 'video', '{{ route("uvideo.index") }}');
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					$("#dvloader").hide();
					toastr.error(errorThrown.msg,'failed');
				}
			});
		}
	</script>
@endsection
