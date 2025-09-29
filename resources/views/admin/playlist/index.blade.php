@extends('admin.layout.page-app')
@section('page_title', 'Playlist')

@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">Playlist</h1>

            <div class="border-bottom row mb-3">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('Label.Dashboard')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Playlist</li>
                    </ol>
                </div>
            </div>

            <!-- Add Playlist -->
            <div class="card custom-border-card mt-3">
                <h5 class="card-header">Add Playlist</h5>
                <div class="card-body">
                    <form id="playlist" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="">
                        <div class="form-row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{__('Label.Title')}}<span class="text-danger">*</span></label>
                                    <input type="text" name="title" class="form-control" placeholder="Enter Title" autofocus>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Channel<span class="text-danger">*</span></label>
                                    <select name="channel_id" class="form-control channel_id" style="width:100%!important;">
                                        <option value="">Select Channel</option>
                                        @foreach ($channel as $key => $value)
                                            <option value="{{$value->channel_id}}">
                                                {{ $value->channel_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Type<span class="text-danger">*</span></label>
                                    <select name="playlist_type" class="form-control">
                                        <option value="1">Public</option>
                                        <option value="2">Private</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{__('Label.Description')}}</label>
                                    <input type="text" name="description" class="form-control" placeholder="Enter Description">
                                </div>
                            </div>
                        </div>
                        <div class="border-top pt-3 text-right">
                            <button type="button" class="btn btn-default mw-120" onclick="save_playlist()">{{__('Label.SAVE')}}</button>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        </div>
                    </form>
                </div>
            </div>

            <!-- Search && Table -->
            <div class="card custom-border-card mt-3">
                <div class="page-search mb-3">
                    <div class="input-group" title="Search">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-magnifying-glass fa-xl light-gray"></i></span>
                        </div>
                        <input type="text" id="input_search" class="form-control" placeholder="Search Playlist" aria-label="Search" aria-describedby="basic-addon1">
                    </div>  
                    <div class="sorting mr-2" style="width: 50%;">
                        <label>Sort by :</label>
                        <select class="form-control channel_id" name="input_channel" id="input_channel">
                            <option value="0" selected>All Channel</option>
                            @foreach ($channel as $key => $value)
                                <option value="{{$value->channel_id}}">
                                    {{ $value->channel_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="sorting" style="width: 30%;">
                        <label>Sort by :</label>
                        <select class="form-control" name="input_type" id="input_type">
                            <option value="0" selected>All Type</option>
                            <option value="1">Public</option>
                            <option value="2">Private</option>
                        </select>
                    </div>  
                </div>

                <div class="table-responsive table">
                    <table class="table table-striped text-center table-bordered" id="datatable">
                        <thead>
                            <tr style="background: #F9FAFF;">
                                <th>{{__('Label.#')}}</th>
                                <th>Channel</th>
                                <th>{{__('Label.Title')}}</th>
                                <th>Type</th>
                                <th>Content</th>
                                <th>Status</th>
                                <th>{{__('Label.Action')}}</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>

            <!-- Edit Model -->
            <div class="modal fade" id="EditModel" data-backdrop="static" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Edit Playlist</h5>
                            <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="edit_playlist" autocomplete="off">
                            <div class="modal-body">
                                <input type="hidden" name="id" id="edit_id">
                                <div class="form-row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>{{__('Label.Title')}}<span class="text-danger">*</span></label>
                                            <input type="text" name="title" id="edit_title" class="form-control" placeholder="Enter Title">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Channel<span class="text-danger">*</span></label>
                                            <select name="channel_id" id="edit_channel_id" class="form-control channel_id" style="width:100%!important;">
                                                <option value="">Select Channel</option>
                                                @foreach ($channel as $key => $value)
                                                    <option value="{{$value->channel_id}}">
                                                        {{ $value->channel_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Type<span class="text-danger">*</span></label>
                                            <select name="playlist_type" class="form-control" id="edit_playlist_type">
                                                <option value="1">Public</option>
                                                <option value="2">Private</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>{{__('Label.Description')}}</label>
                                            <textarea name="description" id="edit_description" class="form-control" rows="2" placeholder="Describe Here,"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default mw-120" onclick="update_playlist()">Update</button>
                                <button type="button" class="btn btn-cancel mw-120" data-dismiss="modal">Close</button>
                                <input type="hidden" name="_method" value="PATCH">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('pagescript')
    <script>
        $(".channel_id").select2();

        $(document).ready(function() {
            var table = $('#datatable').DataTable({
                dom: "<'top'f>rt<'row'<'col-2'i><'col-1'l><'col-9'p>>",
                searching: false,
                responsive: true,
                autoWidth: false,
                processing: true,
                serverSide: true,
                lengthMenu: [
                    [10, 100, 500, -1],
                    [10, 100, 500, "All"]
                ],
                language: {
                    paginate: {
                        previous: "<i class='fa-solid fa-chevron-left'></i>",
                        next: "<i class='fa-solid fa-chevron-right'></i>"
                    }
                },
                ajax:
                    {
                    url: "{{ route('playlist.index') }}",
                    data: function(d){
                        d.input_search = $('#input_search').val();
                        d.input_type = $('#input_type').val();
                        d.input_channel = $('#input_channel').val();
                    },
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'channel.channel_name',
                        name: 'channel.channel_name',
                        render: function(data, type, full, meta) {
                            if (data) {
                                return data;
                            } else {
                                return "-";
                            }
                        }
                    },
                    {
                        data: 'title',
                        name: 'title',
                        render: function(data, type, full, meta) {
                            if (data) {
                                return data;
                            } else {
                                return "-";
                            }
                        }
                    },
                    {
                        data: 'playlist_type',
                        name: 'playlist_type',
                        render: function(data, type, full, meta) {
                            if (data == 1) {
                                return "Public";
                            } else if(data == 2){
                                return "Private";
                            } else {
                                return "-";
                            }
                        }
                    },
                    {
                        data: 'content',
                        name: 'content',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
            });

            $('#input_channel, #input_type').change(function() {
                table.draw();
            });
            $('#input_search').keyup(function() {
                table.draw();
            });
        });

        function save_playlist(){
            var Check_Admin = '<?php echo Check_Admin_Access(); ?>';
            if(Check_Admin == 1){

                $("#dvloader").show();
                var formData = new FormData($("#playlist")[0]);
                $.ajax({
                    type:'POST',
                    url:'{{ route("playlist.store") }}',
                    data:formData,
                    cache:false,
                    contentType: false,
                    processData: false,
                    success:function(resp){
                        $("#dvloader").hide();
                        get_responce_message(resp, 'playlist', '{{ route("playlist.index") }}');
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

        $(document).on("click", ".edit_playlist", function() {
            var id = $(this).data('id');
            var channel_id = $(this).data('channel_id');
            var title = $(this).data('title');
            var description = $(this).data('description');
            var playlist_type = $(this).data('playlist_type');

            $(".modal-body #edit_id").val(id);
            $(".modal-body #edit_title").val(title);
            $(".modal-body #edit_description").val(description);
            $(".modal-body #edit_channel_id").val(channel_id).change();
            $(".modal-body #edit_playlist_type").val(playlist_type).change();
        });
        function update_playlist() {

            var Check_Admin = '<?php echo Check_Admin_Access(); ?>';
            if(Check_Admin == 1){

                $("#dvloader").show();
                var formData = new FormData($("#edit_playlist")[0]);

                var Edit_Id = $("#edit_id").val();

                var url = '{{ route("playlist.update", ":id") }}';
                    url = url.replace(':id', Edit_Id);

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
                            $('#EditModel').modal('toggle');
                        }
                        get_responce_message(resp, 'edit_playlist', '{{ route("playlist.index") }}');
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

        function change_status(id, status) {

            var Check_Admin = '<?php echo Check_Admin_Access(); ?>';
            if(Check_Admin == 1){

                $("#dvloader").show();
                var url = "{{route('playlist.show', '')}}" + "/" + id;
                $.ajax({
                    type: "GET",
                    url: url,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: id,
                    success: function(resp) {
                        $("#dvloader").hide();
                        if (resp.status == 200) {
                            if (resp.Status_Code == 1) {

                                $('#' + id).text('Show');
                                $('#' + id).css({
                                    "background": "#058f00",
                                    "font-weight":"bold",
                                    "color": "white",
                                    "border": "none",
                                    "outline": "none",
                                    "padding": "5px 15px",
                                    "border-radius": "5px",
                                    "cursor": "pointer",
                                });
                            } else {

                                $('#' + id).text('Hide');
                                $('#' + id).css({
                                    "background": "#e3000b",
                                    "color": "white",
                                    "border": "none",
                                    "outline": "none",
                                    "padding": "5px 20px",
                                    "border-radius": "5px",
                                    "cursor": "pointer",
                                });
                            }
                        } else {
                            toastr.error(resp.errors);
                        }
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        $("#dvloader").hide();
                        toastr.error(errorThrown.msg, 'failed');
                    }
                });
            } else {
                toastr.error('You have no right to Add, Edit and Delete.');
            }
        };
    </script>
@endsection