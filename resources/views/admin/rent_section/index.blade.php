@extends('admin.layout.page-app')
@section('page_title', 'Rent Section')

@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">Rent Section</h1>

            <div class="border-bottom row mb-3">
                <div class="col-sm-11">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('Label.Dashboard')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Rent Section</li>
                    </ol>
                </div>
                <div class="col-sm-1 d-flex justify-content-start mb-3" title="Sortable">
                    <button type="button" data-toggle="modal" data-target="#sortableModal" onclick="sortableBTN()" class="btn btn-default" style="border-radius: 10px;">
                        <i class="fa-solid fa-sort fa-1x"></i>
                    </button>
                </div>
            </div>

            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="app" role="tabpanel" aria-labelledby="app-tab">
                    <div class="card custom-border-card">
                        <h5 class="card-header">Rent Section</h5>
                        <div class="card-body">
                            <form id="save_content_section" enctype="multipart/form-data">
                                <input type="hidden" name="id" value="">
                                <div class="form-row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>{{__('Label.Title')}}<span class="text-danger">*</span></label>
                                            <input type="text" name="title" class="form-control" placeholder="Enter Title" autofocus>
                                        </div>
                                    </div>
                                    <div class="col-md-3 category_drop">
                                        <div class="form-group">
                                            <label>Category<span class="text-danger">*</span></label>
                                            <select name="category_id" class="form-control" id="category_id">
                                                <option value="">Select Category</option>
                                                @for ($i = 0; $i < count($category); $i++) 
                                                <option value="{{ $category[$i]['id'] }}">
                                                    {{ $category[$i]['name'] }}
                                                </option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 no_of_content_drop">
                                        <div class="form-group">
                                            <label>No of Content<span class="text-danger">*</span></label>
                                            <input type="number" min="1" name="no_of_content" class="form-control" placeholder="Enter Number Of Content" value="1">
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
                                    <button type="button" class="btn btn-default mw-120" onclick="save_section()">{{__('Label.SAVE')}}</button>
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
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__('Label.Title')}}<span class="text-danger">*</span></label>
                                            <input type="text" name="title" id="edit_title" class="form-control" placeholder="Enter Title">
                                        </div>
                                    </div>
                                    <div class="col-md-6 edit_category_drop">
                                        <div class="form-group">
                                            <label>Category<span class="text-danger">*</span></label>
                                            <select name="category_id" class="form-control" id="edit_category_id" style="width:100%!important;">
                                                <option value="">Select Category</option>
                                                @for ($i = 0; $i < count($category); $i++) 
                                                <option value="{{ $category[$i]['id'] }}">
                                                    {{ $category[$i]['name'] }}
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

        // Sidebar Scroll Down
        sidebar_down(700);

        $("#category_id").select2();
        $("#edit_category_id").select2();

        // Save Section
        function save_section(){
            var Check_Admin = '<?php echo Check_Admin_Access(); ?>';
            if(Check_Admin == 1){

                $("#dvloader").show();

                var formData = new FormData($("#save_content_section")[0]);
                $.ajax({
                    type:'POST',
                    url:'{{ route("rentsection.store") }}',
                    data:formData,
                    cache:false,
                    contentType: false,
                    processData: false,
                    success:function(resp){
                        $("#dvloader").hide();
                        get_responce_message(resp, 'save_content_section', '{{ route("rentsection.index") }}');
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
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: '{{ route("rentsection.content.data") }}',
            success: function(resp) {
                $('.after-add-more').html('');
                for (var i = 0; i < resp.result.length; i++) {

                    if (resp.result[i].category != null) {
                        var category_name = resp.result[i].category.name;
                    } else {
                        var category_name = "-";
                    }

                    var data = '<div class="card custom-border-card mt-3">'+
                            '<h5 class="card-header">Edit Section</h5>'+
                            '<div class="card-body">'+
                                '<form id="edit_section_'+resp.result[i].id+'" enctype="multipart/form-data">'+
                                    '<input type="hidden" name="id" value="'+resp.result[i].id+'">'+
                                    '<div class="form-row">'+
                                        '<div class="col-md-3">'+
                                            '<div class="form-group">'+
                                                '<label>{{__("Label.Title")}}</label>'+
                                                '<input type="text" name="title" value="'+resp.result[i].title+'" class="form-control" readonly>'+
                                            '</div>'+
                                        '</div>'+
                                        '<div class="col-md-3">'+
                                            '<div class="form-group">'+
                                                '<label>Category</label>'+
                                                '<input type="text" name="short_title" value="'+category_name+'" class="form-control" readonly>'+
                                            '</div>'+
                                        '</div>'+
                                        '<div class="col-md-2">'+
                                            '<div class="form-group">'+
                                                '<label>No of Content</label>'+
                                                '<input type="text" name="content_type" value="'+resp.result[i].no_of_content+'" class="form-control" readonly>'+
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

                    $("#edit_category_id_"+resp.result[i].id+"").val(resp.result[i].category_id).attr("selected","selected");
                    if(resp.result[i].view_all == 1){
                        $("#view_all_yes_"+resp.result[i].id+"").attr('checked','checked');
                    } else {
                        $("#view_all_no_"+resp.result[i].id+"").attr('checked','checked');
                    }
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                toastr.error(errorThrown.msg, 'failed');
            }
        });

        // Update Section
        function edit_section(id){

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: '{{ route("rentsection.content.edit") }}',
                data: {
                    id: id,
                },
                success: function(resp) {

                    if(resp.result != null){

                        $("#edit_id").val(resp.result.id);                        
                        $("#edit_title").val(resp.result.title);
                        $('#edit_category_id').val(resp.result.category_id).trigger('change');
                        $("#edit_no_of_content").val(resp.result.no_of_content);
                        if(resp.result.view_all == 1){
                            $("#edit_view_all_yes").attr('checked','checked');
                        } else {
                            $("#edit_view_all_no").attr('checked','checked');
                        }
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

                var url = '{{ route("rentsection.update", ":id") }}';
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
                        get_responce_message(resp, 'edit_content_section', '{{ route("rentsection.index") }}');
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
    
                    var url = '{{ route("rentsection.show", ":id") }}';
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
                            get_responce_message(resp, '', '{{ route("rentsection.index") }}');
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
         
            $("#dvloader").show();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: '{{ route("rentsection.content.sortable") }}',
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
                    url: '{{ route("rentsection.content.sortable.save") }}',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(resp) {
                        $("#dvloader").hide();
                        get_responce_message(resp, 'save_section_sortable', '{{ route("rentsection.index") }}');
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