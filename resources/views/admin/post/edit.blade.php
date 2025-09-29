@extends('admin.layout.page-app')
@section('page_title',  __('Label.edit_post'))

@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">{{__('Label.edit_post')}}</h1>

            <div class="border-bottom row mb-3">
                <div class="col-sm-10">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">{{__('Label.Dashboard')}}</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('post.index') }}">{{__('Label.post')}}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            {{__('Label.edit_post')}}
                        </li>
                    </ol>
                </div>
                <div class="col-sm-2 d-flex align-items-center justify-content-end">
                    <a href="{{ route('post.index') }}" class="btn btn-default mw-120" style="margin-top:-14px">{{__('Label.post_list')}}</a>
                </div>
            </div>

            <div class="card custom-border-card mt-3">
                <form id="post" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="@if($data){{$data->id}}@endif">
                    <input type="hidden" name="old_hashtag_id" value="@if($data){{$data->hashtag_id}}@endif">
                    <div class="form-row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{__('Label.Title')}}<span class="text-danger">*</span></label>
                                <input type="text" name="title" value="@if($data){{$data->title}}@endif" class="form-control" placeholder="Enter Title" autofocus>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Channel<span class="text-danger">*</span></label>
                                <select name="channel_id" class="form-control channel_id" style="width:100%!important;">
                                    <option value="">Select Channel</option>
                                    @foreach ($channel as $key => $value)
                                        <option value="{{$value->channel_id}}" {{ $data->channel_id == $value->channel_id  ? 'selected' : ''}}>
                                            {{ $value->channel_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Is Comment<span class="text-danger">*</span></label>
                                <select class="form-control" name="is_comment">
                                <option value="0" {{ $data->is_comment == 0 ? 'selected' : ''}}>No</option>
                                <option value="1" {{ $data->is_comment == 1 ? 'selected' : ''}}>Yes</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>{{__('Label.Description')}}<span class="text-danger">*</span></label>
                                <textarea name="descripation" class="form-control" rows="1" placeholder="Describe Here,">{{$data->descripation}}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="border-top pt-3 text-right">
                        <button type="button" class="btn btn-default mw-120" onclick="save_post()">{{__('Label.UPDATE')}}</button>
                        <a href="{{route('post.index')}}" class="btn btn-cancel mw-120 ml-2">{{__('Label.CANCEL')}}</a>
                        <input type="hidden" name="_method" value="PATCH">
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('pagescript')
	<script>
        $(".channel_id").select2();
        $(".category_id").select2();

		function save_post(){
            var Check_Admin = '<?php echo Check_Admin_Access(); ?>';
            if(Check_Admin == 1){

                $("#dvloader").show();
                var formData = new FormData($("#post")[0]);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    enctype: 'multipart/form-data',
                    type: 'POST',
                    url: '{{route("post.update", [$data->id])}}',
                    data: formData,
                    cache:false,
                    contentType: false,
                    processData: false,
                    success:function(resp){
                        $("#dvloader").hide();
                        get_responce_message(resp, 'post', '{{ route("post.index") }}');
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        $("#dvloader").hide();
                        toastr.error(errorThrown, textStatus);
                    }
                });
            } else {
                toastr.error('{{__("Label.you_have_no_right_to_add_edit_and_delete")}}');
            }
		}
	</script>
@endsection
