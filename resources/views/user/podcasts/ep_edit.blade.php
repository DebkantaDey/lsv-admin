@extends('user.layout.page-app')
@section('page_title',  'Edit Episode')

@section('content')
    @include('user.layout.sidebar')

    <div class="right-content">
        @include('user.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">Edit Episode</h1>

            <div class="border-bottom row mb-3">
                <div class="col-sm-10">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('user.dashboard') }}">{{__('Label.Dashboard')}}</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('upodcast.episode.index', $podcasts_id) }}">Episodes</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Edit Episode
                        </li>
                    </ol>
                </div>
                <div class="col-sm-2 d-flex align-items-center justify-content-end">
                    <a href="{{ route('upodcast.episode.index', $podcasts_id) }}" class="btn btn-default mw-120" style="margin-top:-14px">Episodes List</a>
                </div>
            </div>

            <div class="card custom-border-card mt-3">
                <form id="episode" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="@if($data){{$data->id}}@endif">
                    <input type="hidden" name="podcasts_id" value="@if($data){{$data->podcasts_id}}@endif">
                    <input type="hidden" name="old_portrait_img" value="@if($data){{$data->portrait_img}}@endif">
                    <input type="hidden" name="old_landscape_img" value="@if($data){{$data->landscape_img}}@endif">
                    <input type="hidden" name="old_episode_upload_type" value="@if($data){{$data->episode_upload_type}}@endif">
                    <input type="hidden" name="old_episode_audio" value="@if($data){{$data->episode_audio}}@endif">
                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="form-row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{__('Label.Name')}}<span class="text-danger">*</span></label>
                                        <input type="text" name="name" value="@if($data){{$data->name}}@endif" class="form-control" placeholder="Enter Name" autofocus>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{__('Label.Description')}}<span class="text-danger">*</span></label>
                                        <textarea name="description" class="form-control" rows="2" placeholder="Describe Here,">@if($data){{$data->description}}@endif</textarea>
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
                    <div class="form-row">
                        <div class="col-md-9">
                            <div class="form-row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Upload Type<span class="text-danger">*</span></label>
                                        <select class="form-control" name="episode_upload_type" id="episode_upload_type">
                                            <option value="server_video" {{ $data->episode_upload_type == "server_video" ? 'selected' : ''}}>Server Audio</option>
                                            <option value="external_url" {{ $data->episode_upload_type == "external_url" ? 'selected' : ''}}>External URL</option>
                                            <!-- <option value="youtube" {{ $data->episode_upload_type == "youtube" ? 'selected' : ''}}>Youtube</option> -->
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 video_box">
                                    <div class="form-group">
                                        <div style="display: block;">
                                            <label>Upload Audio</label>
                                            <div id="filelist1"></div>
                                            <div id="container1" style="position: relative;">
                                                <div class="form-group">
                                                    <input type="file" id="uploadFile1" name="uploadFile1" class="form-control import-file p-2">
                                                </div>
                                                <input type="hidden" name="music" id="mp3_file_name1" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2 mt-4 video_box">
                                    <div class="form-group mt-3">
                                        <a id="upload1" class="btn text-white" style="background-color:#4e45b8;">{{__('Label.Upload_Files')}}</a>
                                    </div>
                                </div>
                                <div class="col-md-6 url_box">
                                    <div class="form-group">
                                        <label>URL<span class="text-danger">*</span></label>
                                        <input type="text" name="url" value="@if($data->episode_upload_type != 'server_video'){{{$data->episode_audio}}}@endif" class="form-control" placeholder="Enter URL">
                                    </div>
                                </div>
                            </div>
                            <div class="form-row mt-4">
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
                        </div>
                    </div>
                    <div class="border-top pt-3 text-right">
                        <button type="button" class="btn btn-default mw-120" onclick="save_episode()">{{__('Label.SAVE')}}</button>
                        <a href="{{ route('upodcast.episode.index', $podcasts_id) }}" class="btn btn-cancel mw-120 ml-2">{{__('Label.CANCEL')}}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('pagescript')
	<script>
        $(document).ready(function() {

            var episode_upload_type = "<?php echo $data->episode_upload_type; ?>";
            if (episode_upload_type == "server_video") {
                $(".url_box").hide();
            } else {
                $(".video_box").hide();
            }
            $('#episode_upload_type').change(function() {
                var optionValue = $(this).val();

                if (optionValue == 'server_video') {
                    $(".video_box").show();
                    $(".url_box").hide();
                } else {
                    $(".url_box").show();
                    $(".video_box").hide();
                }
            });
        });

		function save_episode(){
			$("#dvloader").show();
			var formData = new FormData($("#episode")[0]);

			$.ajax({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				enctype: 'multipart/form-data',
				type: 'POST',
				url: '{{route("upodcast.episode.update", [$podcasts_id, $data->id])}}',
				data: formData,
				cache:false,
				contentType: false,
				processData: false,
				success:function(resp){
					$("#dvloader").hide();
					get_responce_message(resp, 'episode', '{{ route("upodcast.episode.index", $podcasts_id) }}');
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					$("#dvloader").hide();
					toastr.error(errorThrown.msg,'failed');
				}
			});
		}
	</script>
@endsection
