@extends('admin.layout.page-app')
@section('page_title', 'Wallet')

@section('content')
	@include('admin.layout.sidebar')

	<div class="right-content">
		@include('admin.layout.header')

		<div class="body-content">
			<!-- mobile title -->
			<h1 class="page-title-sm">Wallet</h1>

			<div class="border-bottom row mb-3">
				<div class="col-sm-10">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('Label.Dashboard')}}</a></li>
						<li class="breadcrumb-item"><a href="{{ route('user.index') }}">{{__('Label.Users')}}</a></li>
						<li class="breadcrumb-item active" aria-current="page">Wallet</li>
					</ol>
				</div>
				<div class="col-sm-2 d-flex align-items-center justify-content-end">
					<a href="{{ route('user.index') }}" class="btn btn-default mw-120" style="margin-top:-14px">{{__('Label.Users List')}}</a>
				</div>
			</div>

            <!-- Counter -->
            <div class="row counter-row">
                <div class="col-6 col-sm-4 col-lg-4">
                    <div class="db-color-card color6-card">
                        <i class="fa-solid fa-wallet fa-4x card-icon"></i>
                        <h2 class="counter">
                            <p class="p-0 m-0">{{$data['wallet_balance'] ?? 0}}</p>
                            <span>Wallet Balance</span>
                        </h2>
                    </div>
                </div>
                <div class="col-6 col-sm-4 col-lg-4">
                    <div class="db-color-card color9-card">
                        <i class="fa-solid fa-hand-holding-dollar fa-4x card-icon"></i>
                        <h2 class="counter">
                            <p class="p-0 m-0">{{$data['wallet_earning'] ?? 0}}</p>
                            <span>Total Earning</span>
                        </h2>
                    </div>
                </div>
                <div class="col-6 col-sm-4 col-lg-4">
                    <div class="db-color-card color8-card">
                        <i class="fa-solid fa-right-left fa-4x card-icon"></i>
                        <h2 class="counter mt-4">
                            <p class="p-0 m-0">{{$total_withdral_amount ?? 0}}</p>
                            <span>Total Withdrawal Amount</span>
                        </h2>
                    </div>
                </div>
            </div>

            <!-- Personal & Bank Info -->
            <div class="row">
                <div class="col-6">
                    <div class="card custom-border-card">
                        <h5 class="card-header">Personal Info</h5>
                        <div class="card-body">
                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Channel Name</label>
                                        <input type="text" value="@if($data){{$data['channel_name']}}@endif" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{__('Label.Full_Name')}}</label>
                                        <input type="text" value="@if($data){{$data->full_name}}@endif" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{__('Label.Mobile Number')}}</label>
                                        <input type="text" value="@if($data){{$data->country_code}} {{$data->mobile_number}}@endif" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{__('Label.Email')}}</label>
                                        <input type="email" value="@if($data){{$data->email}}@endif" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card custom-border-card">
                        <h5 class="card-header">Banking Info</h5>
                        <div class="card-body">
                            <div class="form-row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Bank Name</label>
                                        <input type="text" value="@if($data){{$data['bank_name']}}@endif" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Account No</label>
                                        <input type="text" value="@if($data){{$data['account_no']}}@endif" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>IFSC No</label>
                                        <input type="text" value="@if($data){{$data['ifsc_no']}}@endif" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
                    url: "{{ route('user.wallet', $id) }}",
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