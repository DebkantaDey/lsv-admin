@extends('user.layout.page-app')
@section('page_title', 'Withdrawal')

@section('content')
    @include('user.layout.sidebar')

    <div class="right-content">
        @include('user.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">Withdrawal</h1>

            <div class="border-bottom row mb-3">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">{{__('Label.Dashboard')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Withdrawal</li>
                    </ol>
                </div>
            </div>

            <!-- Counter -->
            <div class="row counter-row">
                <div class="col-6 col-sm-4 col-lg-4">
                    <div class="db-color-card color6-card">
                        <i class="fa-solid fa-wallet fa-4x card-icon"></i>
                        <h2 class="counter">
                            <p class="p-0 m-0 counting">{{$user['wallet_balance'] ?? 0}}</p>
                            <span>Wallet Balance</span>
                        </h2>
                    </div>
                </div>
                <div class="col-6 col-sm-4 col-lg-4">
                    <div class="db-color-card color9-card">
                        <i class="fa-solid fa-hand-holding-dollar fa-4x card-icon"></i>
                        <h2 class="counter">
                            <p class="p-0 m-0 counting">{{$user['wallet_earning'] ?? 0}}</p>
                            <span>Total Earning</span>
                        </h2>
                    </div>
                </div>
                <div class="col-6 col-sm-4 col-lg-4">
                    <div class="db-color-card color8-card">
                        <i class="fa-solid fa-hand-holding-dollar fa-4x card-icon"></i>
                        <h2 class="counter">
                            <p class="p-0 m-0 counting">{{$user['total_withdral_amount'] ?? 0}}</p>
                            <span>Total Withdrawal Amount</span>
                        </h2>
                    </div>
                </div>
            </div>

            <!-- Bank Info -->
            <div class="card custom-border-card">
                <h5 class="card-header">Banking Info</h5>
                <div class="card-body">
                    <div class="form-row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Bank Name</label>
                                <input type="text" value="@if($user){{$user['bank_name']}}@endif" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Bank Address</label>
                                <input type="text" value="@if($user){{$user['bank_address']}}@endif" class="form-control" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Account No</label>
                                <input type="text" value="@if($user){{$user['account_no']}}@endif" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>IFSC No</label>
                                <input type="text" value="@if($user){{$user['ifsc_no']}}@endif" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Bank Code</label>
                                <input type="text" value="@if($user){{$user['bank_code']}}@endif" class="form-control" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Min Withdrawal Amount -->
            <div class="page-search mb-3">
                <div class="input-group">
                    <label class="text-gray pt-2 font-weight-bold">
                        <i class="fa-solid fa-circle-info fa-2xl mr-3"></i>
                        Minimum Withdrawal Amount :- {{$setting['min_withdrawal_amount']}}
                    </label>
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
                            <th>{{__('Label.Date')}}</th>
                            <th>{{__('Label.Amount')}}</th>
                            <th>{{__('Label.Type')}}</th>
                            <th>Detail</th>
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
                    url: "{{ route('uwithdrawal.index') }}",
                    data: function(d) {
                        d.input_status = $('#input_status').val();
                    },
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'date',
                        name: 'date'
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
                        data: 'action',
                        searchable: false,
                    },
                ],
            });

            $('#input_status').change(function() {
                table.draw();
            });
        });
    </script>
@endsection