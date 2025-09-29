@extends('user.layout.page-app')
@section('page_title',  'Add Reels')

@section('content')
    @include('user.layout.sidebar')

    <div class="right-content">
        @include('user.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">Add Reels</h1>

            <div class="border-bottom row mb-3">
                <div class="col-sm-10">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('user.dashboard') }}">{{__('Label.Dashboard')}}</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('ureels.index') }}">Reels</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Add Reels
                        </li>
                    </ol>
                </div>
                <div class="col-sm-2 d-flex align-items-center justify-content-end">
                    <a href="{{ route('ureels.index') }}" class="btn btn-default mw-120" style="margin-top:-14px">Reels List</a>
                </div>
            </div>

            <div class="card custom-border-card mt-3">
                <form id="reels" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="">
                    <div class="form-row">
                        <div class="col-md-9">
                            <div class="form-row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Title<span class="text-danger">*</span></label>
                                        <textarea name="title" class="form-control" rows="1" placeholder="Captions Here," autofocus></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                            <div class="col-md-6">
                                    <div class="form-group">
                                        <div style="display: block;">
                                            <label>{{__('Label.Upload_Video')}}</label>
                                            <div id="filelist4"></div>
                                            <div id="container4" style="position: relative;">
                                                <div class="form-group">
                                                    <input type="file" id="uploadFile4" name="uploadFile" class="form-control import-file p-2">
                                                </div>
                                                <input type="hidden" name="video" id="mp3_file_name4" class="form-control">
                                            </div>
                                        </div>
                                        <label class="mt-3 text-gray">Maximum size 50MB.</label>
                                    </div>
                                </div>
                                <div class="col-md-2 mt-4">
                                    <div class="form-group mt-3">
                                        <a id="upload4" class="btn text-white" style="background-color:#4e45b8;">{{__('Label.Upload_Files')}}</a>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row mt-4">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Comment<span class="text-danger">*</span></label>
                                        <div class="radio-group">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" name="is_comment" id="is_comment_yes" class="custom-control-input" value="1" checked>
                                                <label class="custom-control-label" for="is_comment_yes">{{__('Label.Yes')}}</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" name="is_comment" id="is_comment_no" class="custom-control-input" value="0">
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
                                                <input type="radio" name="is_download" id="is_download_yes" class="custom-control-input" value="1" checked>
                                                <label class="custom-control-label" for="is_download_yes">{{__('Label.Yes')}}</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" name="is_download" id="is_download_no" class="custom-control-input" value="0">
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
                                                <input type="radio" name="is_like" id="is_like_yes" class="custom-control-input" value="1" checked>
                                                <label class="custom-control-label" for="is_like_yes">{{__('Label.Yes')}}</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" name="is_like" id="is_like_no" class="custom-control-input" value="0">
                                                <label class="custom-control-label" for="is_like_no">{{__('Label.No')}}</label>
                                            </div>
                                        </div>
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
                                        <img src="{{asset('assets/imgs/upload_img.png')}}" alt="upload_img.png" id="imagePreview">
                                    </div>
                                </div>
                                <label class="mt-3 text-gray">Maximum size 2MB.</label>
                            </div>
                        </div>
                    </div>
                    <div class="border-top pt-3 text-right">
                        <button type="button" class="btn btn-default mw-120" onclick="save_reels()">{{__('Label.SAVE')}}</button>
                        <a href="{{route('ureels.index')}}" class="btn btn-cancel mw-120 ml-2">{{__('Label.CANCEL')}}</a>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    </div>
                </form>  
            </div>
        </div>
    </div>
@endsection

@section('pagescript')
	<script>
		function save_reels(){
			$("#dvloader").show();
			var formData = new FormData($("#reels")[0]);
			$.ajax({
				type:'POST',
				url:'{{ route("ureels.store") }}',
				data:formData,
				cache:false,
				contentType: false,
				processData: false,
				success:function(resp){
					$("#dvloader").hide();
					get_responce_message(resp, 'reels', '{{ route("ureels.index") }}');
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					$("#dvloader").hide();
					toastr.error(errorThrown.msg,'failed');         
				}
			});
		}
	</script>
@endsection