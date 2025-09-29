@extends('admin.layout.page-app')
@section('page_title', 'Content')

@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">Content</h1>

            <div class="border-bottom row mb-3">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('Label.Dashboard')}}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('playlist.index') }}">Playlist</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Content</li>
                    </ol>
                </div>
            </div>

            <!-- Playlist Name -->
            <div class="card custom-border-card mt-3">
                <div class="form-row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Playlist</label>
                            <input type="text" value="@if($playlist_name){{$playlist_name}}@endif" class="form-control" readonly>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add Content -->
            <div class="card custom-border-card mt-3">
                <h5 class="card-header">Add Content</h5>
                <div class="card-body">
                    <form id="content" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="">
                        <input type="hidden" name="playlist_id" value="@if($playlist_id){{$playlist_id}}@endif">
                        <div class="form-row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Type<span class="text-danger">*</span></label>
                                    <select name="content_type" class="form-control" id="content_type">
                                        <option value="">Select Content Type</option>
                                        <option value="1">Video</option>
                                        <option value="2">Music</option>
                                        <option value="4">Podcasts</option>
                                        <option value="6">Radio</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-10">
                                <div class="form-group">
                                    <label>Content<span class="text-danger">*</span></label>
                                    <select name="content[]" class="form-control" id="content_id" style="width:100%!important;" multiple></select>
                                </div>
                            </div>
                        </div>
                        <div class="border-top pt-3 text-right">
                            <button type="button" class="btn btn-default mw-120" onclick="save_content()">{{__('Label.SAVE')}}</button>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        </div>
                    </form>
                </div>
            </div>

            <!-- List-->
            <div class="card custom-border-card mt-3">
                <h5 class="card-header mb-3">Content List</h5>
                @if(count($data) > 0 && $data != null)
                    <div id="ListId">
                        @foreach ($data as $key => $value)
                            @if($value->content != null && isset($value->content))
                                <div id="{{$value->id}}" class="row listitemClass mb-2" style="background-color: #e9ecef;border: 1px solid black;cursor: s-resize;">
                                    <div class="col-md-10 mt-2">
                                        <h6>
                                            <i class="fa-solid fa-bars fa-2xl mr-5"></i>
                                            <img src="{{$value->content->portrait_img}}" width="60px" height="40px" class="mr-3" style="border-radius: 10%;">
                                            {{String_Cut($value->content->title, 130)}}
                                        </h6>
                                    </div>
                                    <div class="col-md-2 mt-3 text-right">
                                        <h6>
                                            @if($value->content_type == 1)
                                                Video
                                            @elseif ($value->content_type == 2)
                                                Music
                                            @elseif ($value->content_type == 4)
                                                Podcasts
                                            @elseif ($value->content_type == 6)
                                                Radio
                                            @else
                                                -
                                            @endif
                                            <i class="fa-solid fa-trash-can fa-2xl ml-2" style="cursor: pointer;" onclick="delete_content('{{$value->id}}')"></i>
                                        </h6>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @else
                    <div class="text-center p-5" style="background-color: #e9ecef;">
                        <h2>!!! Data Not Available !!!</h2>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('pagescript')
    <script>
        $("#content_id").select2({placeholder: "Select Content"});

        // get data
        $("#content_type").change(function() {

            var content_type = $(this).children("option:selected").val();

            $("#content_id").empty();
            if(content_type == 1 || content_type == 2 || content_type == 4 || content_type == 6){

                playlist_id = '<?php echo $playlist_id; ?>';

                $.ajax({
                    headers: {
					    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				    },
				    enctype: 'multipart/form-data',
                    type: 'post',
                    url: '{{ route("playlist.get.content") }}',
                    data: {content_type:content_type, playlist_id:playlist_id},
                    success: function(resp) {
                        for (var i = 0; i < resp.data.length; i++) {
                            $('#content_id').append(
                                `<option value="${resp.data[i].id}">${resp.data[i].title}</option>`
                            );
                        }
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        toastr.error(errorThrown.msg, 'failed');
                    }
                });
            }
        });
        // save data
        function save_content(){
            var Check_Admin = '<?php echo Check_Admin_Access(); ?>';
            if(Check_Admin == 1){

                $("#dvloader").show();
                var formData = new FormData($("#content")[0]);
                $.ajax({
                    type:'POST',
                    url:'{{ route("playlist.content.save") }}',
                    data:formData,
                    cache:false,
                    contentType: false,
                    processData: false,
                    success:function(resp){
                        $("#dvloader").hide();
                        get_responce_message(resp, 'content', '{{ route("playlist.content.index", $playlist_id) }}');
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
        // delete data
        function delete_content(id){
            var Check_Admin = '<?php echo Check_Admin_Access(); ?>';
            if(Check_Admin == 1){

                var playlist_id = '<?php echo $playlist_id; ?>';

                $.ajax({
                    headers: {
					    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				    },
				    enctype: 'multipart/form-data',
                    type: 'POST',
                    url: '{{ route("playlist.content.delete") }}',
                    data: {id:id, playlist_id:playlist_id},
                    success: function(resp) {

                        toastr.success(resp.success);
                        $('#' + resp.id).remove();
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        toastr.error(errorThrown.msg, 'failed');
                    }
                });
            } else {
                toastr.error('You have no right to Add, Edit and Delete.');
            }
        }
        // sortable
        $("#ListId").sortable({
            update: function(event, ui) {
               getIdsOfList();
            }
        });
        function getIdsOfList() {
            var values = [];
            $('.listitemClass').each(function(index) {
                values.push($(this).attr("id")
                    .replace("imageNo", ""));
            });

            var Check_Admin = '<?php echo Check_Admin_Access(); ?>';
            if(Check_Admin == 1){

                $.ajax({
                    headers: {
					    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				    },
				    enctype: 'multipart/form-data',
                    type: 'POST',
                    url: '{{ route("playlist.content.sortable") }}',
                    data: {ids:values},
                    success: function(resp) {
                        // toastr.success(resp.success);
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        toastr.error(errorThrown.msg, 'failed');
                    }
                });
            } else {
                toastr.error('You have no right to Add, Edit and Delete.');
            }
        }
    </script>
@endsection