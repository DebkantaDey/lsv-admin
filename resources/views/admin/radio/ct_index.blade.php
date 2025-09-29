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
                    <li class="breadcrumb-item"><a href="{{ route('radio.index') }}">Radio</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Content</li>
                    </ol>
                </div>
            </div>

            <!-- Radio Name -->
            <div class="card custom-border-card mt-3">
                <div class="form-row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Radio</label>
                            <input type="text" value="@if($radio_name){{$radio_name}}@endif" class="form-control" readonly>
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
                        <input type="hidden" name="radio_id" value="@if($radio_id){{$radio_id}}@endif">
                        <div class="form-row">
                            <div class="col-md-10">
                                <div class="form-group">
                                    <label>Content<span class="text-danger">*</span></label>
                                    <select name="content[]" class="form-control" id="content_id" style="width:100%!important;" multiple>
                                        <option value="">Select Content</option>
                                        @foreach ($content as $key => $value)
                                            <option value="{{$value->id}}">
                                                {{ $value->title }}
                                            </option>
                                        @endforeach
                                    </select>
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
                            <div id="{{$value->id}}" class="row listitemClass mb-2" style="background-color: #e9ecef;border: 1px solid black;cursor: s-resize;">
                                <div class="col-md-11 mt-2">
                                    <h6>
                                        <i class="fa-solid fa-bars fa-2xl mr-5"></i>
                                        <img src="{{$value->content->portrait_img}}" width="60px" height="40px" class="mr-3" style="border-radius: 10%;">
                                        {{String_Cut($value->content->title, 130)}}
                                    </h6>
                                </div>
                                <div class="col-md-1 mt-3 text-right">
                                    <h6><i class="fa-solid fa-trash-can fa-2xl ml-2" style="cursor: pointer;" onclick="delete_content('{{$value->id}}')"></i></h6>
                                </div>
                            </div>
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

        // save data
        function save_content(){
            var Check_Admin = '<?php echo Check_Admin_Access(); ?>';
            if(Check_Admin == 1){

                $("#dvloader").show();
                var formData = new FormData($("#content")[0]);
                $.ajax({
                    type:'POST',
                    url:'{{ route("radio.content.save") }}',
                    data:formData,
                    cache:false,
                    contentType: false,
                    processData: false,
                    success:function(resp){
                        $("#dvloader").hide();
                        get_responce_message(resp, 'content', '{{ route("radio.content.index", $radio_id) }}');
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

                var radio_id = '<?php echo $radio_id; ?>';

                $.ajax({
                    headers: {
					    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				    },
				    enctype: 'multipart/form-data',
                    type: 'POST',
                    url: '{{ route("radio.content.delete") }}',
                    data: {id:id, radio_id:radio_id},
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
                    url: '{{ route("radio.content.sortable") }}',
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