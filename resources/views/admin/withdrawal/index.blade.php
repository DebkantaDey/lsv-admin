@extends('admin.layout.page-app')
@section('page_title', 'Withdrawal')

@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">Withdrawal</h1>

            <div class="border-bottom row mb-3">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('Label.Dashboard')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Withdrawal</li>
                    </ol>
                </div>
            </div>

            <!-- Min Withdrawal Amount -->
            <div class="card custom-border-card">
                <h5 class="card-header">Minimum Withdrawal Amount</h5>
                <div class="card-body">
                    <form id="save_min_withdrawal_amoun">
                        <div class="row col-lg-12">
                            <div class="form-group col-lg-3">
                                <label>Amount</label>
                                <input type="number" name="min_withdrawal_amount" class="form-control" value="{{$setting['min_withdrawal_amount']}}" min="0" placeholder="Enter Minimun Withdrawal Amount" autofocus>
                            </div>
                        </div>
                        <div class="border-top pt-3 text-right">
                            <button type="button" class="btn btn-default mw-120" onclick="save_min_withdrawal_amoun()">{{__('Label.SAVE')}}</button>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        </div>
                    </form>
                </div>
            </div>

            <!-- Search -->
            <div class="page-search mb-3">
                <div class="input-group">
                    <label class="text-gray pt-2 font-weight-bold">
                        <i class="fa-solid fa-circle-info fa-2xl mr-3"></i>
                        A withdrawal request will be automatically added at the end of the month.
                    </label>
                </div>
                <div class="sorting mr-2" style="width: 50%;">
                    <label>Sort by :</label>
                    <select class="form-control" name="input_user" id="input_user">
                        <option value="0" selected>All User</option>
                        @for ($i = 0; $i < count($user); $i++) 
                            <option value="{{ $user[$i]['id'] }}" @if(isset($_GET['input_user'])){{ $_GET['input_user'] == $user[$i]['id'] ? 'selected' : ''}} @endif>
                                {{ $user[$i]['channel_name'] }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="sorting mr-2" style="width: 30%;">
                    <label>Sort by :</label>
                    <select class="form-control" name="input_status" id="input_status">
                        <option>All Status</option>
                        <option value="0" @if(isset($_GET['input_status'])){{ $_GET['input_status'] == 0 ? 'selected' : ''}} @endif>Pending</option>
                        <option value="1" @if(isset($_GET['input_status'])){{ $_GET['input_status'] == 1 ? 'selected' : ''}} @endif>Completed</option>
                    </select>
                </div>  
            </div>

            <div class="table-responsive">
                <table class="table table-striped text-center table-bordered" id="datatable">
                    <thead>
                        <tr style="background: #F9FAFF;">
                            <th>{{__('Label.#')}}</th>
                            <th>Channel</th>
                            <th>{{__('Label.Name')}}</th>
                            <th>{{__('Label.Email')}}</th>
                            <th>{{__('Label.Mobile')}}</th>
                            <th>{{__('Label.Amount')}}</th>
                            <th>{{__('Label.Type')}}</th>
                            <th>Detail</th>
                            <th>{{__('Label.Date')}}</th>
                            <th>{{__('Label.Action')}}</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('pagescript')
    <script>

        // Sidebar Scroll Down
        let sidebarHeight = $('.sidebar')[0].scrollHeight;
        sidebar_down(sidebarHeight);
        
        $("#input_user").select2();

        $(document).ready(function() {
            var table = $('#datatable').DataTable({
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
                ajax: {
                    url: "{{ route('withdrawal.index') }}",
                    data: function(d) {
                        d.input_user = $('#input_user').val();
                        d.input_status = $('#input_status').val();
                    },
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'user.channel_name',
                        name: 'user.channel_name',
                        orderable: false,
                        render: function(data, type, full, meta) {
                            if (data) {
                                return data;
                            } else {
                                return "-";
                            }
                        },
                    },
                    {
                        data: 'user.full_name',
                        name: 'user.full_name',
                        orderable: false,
                        render: function(data, type, full, meta) {
                            if (data) {
                                return data;
                            } else {
                                return "-";
                            }
                        },
                    },
                    {
                        data: 'user.email',
                        name: 'user.email',
                        orderable: false,
                        render: function(data, type, full, meta) {
                            if (data) {
                                return data;
                            } else {
                                return "-";
                            }
                        },
                    },
                    {
                        data: 'user.mobile_number',
                        name: 'user.mobile_number',
                        orderable: false,
                        render: function(data, type, full, meta) {
                            if (data) {
                                return data;
                            } else {
                                return "-";
                            }
                        },
                    },
                    {
                        data: 'amount',
                        name: 'amount'
                    },
                    {
                        data: 'payment_type',
                        name: 'payment_type',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, full, meta) {
                            if (data) {
                                return data;
                            } else {
                                return "-";
                            }
                        },
                    },
                    {
                        data: 'payment_detail',
                        name: 'payment_detail',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, full, meta) {
                            if (data) {
                                return data;
                            } else {
                                return "-";
                            }
                        },
                    },
                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'action',
                        searchable: false,
                    },
                ],
            });

            $('#input_user, #input_status').change(function() {
                table.draw();
            });
        });

        function change_status(id, status) {
            var Check_Admin = '<?php echo Check_Admin_Access(); ?>';
            if(Check_Admin == 1){
                $("#dvloader").show();
                var url = "{{route('withdrawal.show', '')}}" + "/" + id;
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

                                $('#' + id).text('Completed');
                                $('#' + id).css({
                                    "background": "#058f00",
                                    "font-weight": "bold",
                                    "border": "none",
                                    "color": "white",
                                    "outline": "none",
                                    "border-radius": "5px",
                                    "cursor": "pointer",
                                    "padding": "4px 10px",
                                });
                            } else {

                                $('#' + id).text('Pending');
                                $('#' + id).css({
                                    "background": "#e3000b",
                                    "font-weight": "bold",
                                    "border": "none",
                                    "color": "white",
                                    "padding": "4px 20px",
                                    "outline": "none",
                                    "border-radius": "5px",
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

        function save_min_withdrawal_amoun() {
            var Check_Admin = '<?php echo Check_Admin_Access(); ?>';
            if(Check_Admin == 1){

                var formData = new FormData($("#save_min_withdrawal_amoun")[0]);
                $("#dvloader").show();
                $.ajax({
                    type: 'POST',
                    url: '{{ route("withdrawal.save.amount") }}',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(resp) {
                        $("#dvloader").hide();
                        $("html, body").animate({scrollTop: 0}, "swing");
                        get_responce_message(resp);
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