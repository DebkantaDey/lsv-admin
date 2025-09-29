@extends('user.layout.page-app')
@section('page_title', __('Label.post_content'))

@section('content')
    @include('user.layout.sidebar')

    <div class="right-content">
        @include('user.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm"> {{__('Label.post_content')}} </h1>

            <div class="border-bottom row mb-3">
                <div class="col-sm-10">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">{{__('Label.Dashboard')}}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('upost.index') }}">{{__('Label.post')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('Label.post_content')}}</li>
                    </ol>
                </div>
                <div class="col-sm-2 d-flex align-items-center justify-content-end">
                    <a href="{{ route('upost.index') }}" class="btn btn-default mw-120" style="margin-top:-14px">{{__('Label.post_list')}}</a>
                </div>
            </div>

            <div class="card custom-border-card mt-3">
                <form id="content" enctype="multipart/form-data">
                <input type="hidden" name="id" value="">
                    <input type="hidden" name="post_id" value="{{$post->id}}">
                    <div class="form-row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Type</label>
                                <select class="form-control" name="content_type" id="content_type">
                                    <option value="1">Image</option>
                                    <option value="2">Vidoe</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 video_box">
                            <div class="form-group">
                                <div style="display: block;">
                                    <label>{{__('Label.Upload_Video')}}</label>
                                    <div id="filelist3"></div>
                                    <div id="container3" style="position: relative;">
                                        <div class="form-group">
                                            <input type="file" id="uploadFile3" name="uploadFile" class="form-control import-file p-2">
                                        </div>
                                        <input type="hidden" name="video" id="mp3_file_name3" class="form-control">
                                        <label class="mt-3 text-gray">Maximum size 50MB.</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 video_box">
                            <div class="form-group mt-4">
                                <a id="upload3" class="btn text-white mt-3" style="background-color:#4e45b8;">{{__('Label.Upload_Files')}}</a>
                            </div>
                        </div>
                        <div class="col-md-3 image_box">
                            <div class="form-group ml-5">
                                <label>Portrait Image<span class="text-danger">*</span></label>
                                <div class="avatar-upload">
                                    <div class="avatar-edit">
                                        <input type='file' name="image" id="imageUpload" accept=".png, .jpg, .jpeg" />
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
                        <button type="button" class="btn btn-default mw-120" onclick="save_content()">{{__('Label.SAVE')}}</button>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    </div>
                </form>
            </div>
            
            <div class="row">
                @foreach ($data as $key => $value)
                    <div class="col-12 col-sm-6 col-md-4 col-xl-3">
                        <div class="card video-card">
                            <div class="position-relative">
                                @if($value->content_type == 1)
                                    <img class="card-img-top" src="{{$value->image}}" alt="" onclick="openImageModal('{{$value->image}}')">
                                @elseif($value->content_type == 2) 
                                    <video class="card-img-top" width="100%" height="200" preload="metadata">
                                        <source src="{{$value->video}}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                    <button class="btn play-btn-top video mt-1 mr-1" data-toggle="modal" data-target="#videoModal" data-video="{{$value->video}}" data-image="{{ $value->image}}">
                                        <i class="fa-regular fa-circle-play text-white fa-4x"></i>
                                    </button>
                                @endif
                                <ul class="list-inline overlap-control" aria-labelledby="dropdownMenuLink">
                                    <a class="btn" title="Delete" onclick="delete_content('{{$value->id}}')">
                                        <i class="fa-solid fa-trash-can fa-xl" class="dot-icon" style="color: #4e45b8;"></i>
                                    </a>
                                </ul>
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- Video Modal -->
                <div class="modal fade" id="videoModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <div class="modal-body p-0 bg-transparent">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true" class="text-dark">&times;</span>
                                </button>
                                <video controls width="800" height="500" preload='none' id="theVideo" controlsList="nodownload noplaybackrate" disablepictureinpicture>
                                    <source src="" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Image Modal -->
                <div class="modal fade" id="imageModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <div class="modal-body p-0 bg-transparent">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeImageModal()">
                                    <span aria-hidden="true" class="text-dark">&times;</span>
                                </button>
                                <img src="" id="modalImage" style="width: 800px; height: 500px;" class="img-fluid" alt="Modal Image">
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

        function openImageModal(imageSrc) {
            $('#modalImage').attr('src', imageSrc); // Set image source in modal
            $('#imageModal').modal('show'); // Show the modal
        }

        function closeImageModal() {
            $('#imageModal').modal('hide'); // Hide the image modal
        }
        
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

      

        $(document).ready(function() {
        $(".video_box").hide();
            $('#content_type').change(function() {
                var optionValue = $(this).val();

                if (optionValue == '1') {
                    $(".image_box").show();
                    $(".video_box").hide();
                } else {
                    $(".video_box").show();
                    $(".image_box").hide();
                }
            });
        });

        function save_content(){

            $("#dvloader").show();
            var formData = new FormData($("#content")[0]);
            $.ajax({
                type:'POST',
                url:'{{ route("upostcontent.store") }}',
                data:formData,
                cache:false,
                contentType: false,
                processData: false,
                success:function(resp){
                    $("#dvloader").hide();
                    get_responce_message(resp, 'content', '{{ route("upostcontent.index", [$post->id]) }}');
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown, textStatus);         
                }
            });
		}

        function delete_content(id) {

            var result = confirm("Are you sure !!! You want to Delete this Content ?");
            if(result){

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    enctype: 'multipart/form-data',
                    type: 'POST',
                    url: '{{ route("upostcontent.destroy") }}',
                    data: {id:id},
                    success: function(resp) {
                        
                        get_responce_message(resp);
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        toastr.error(errorThrown.msg, 'failed');
                    }
                });
            }
        }
    </script>
@endsection