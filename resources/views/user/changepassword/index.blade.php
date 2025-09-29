@extends('user.layout.page-app')
@section('page_title',  'Change Password')

@section('content')
	@include('user.layout.sidebar')

	<div class="right-content">
		@include('user.layout.header')

		<div class="body-content">
			<!-- mobile title -->
			<h1 class="page-title-sm">Change Password</h1>

			<div class="border-bottom row mb-3">
				<div class="col-sm-12">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">{{__('Label.Dashboard')}}</a></li>
						<li class="breadcrumb-item active" aria-current="page">Change Password</li>
					</ol>
				</div>
			</div>

            <form id="change_password" enctype="multipart/form-data" autocomplete="off">
                <input type="hidden" name="id" value="{{$user_id}}">
                <div class="card custom-border-card">
                    <div class="card-body">
                        <div class="form-row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Current Password<span class="text-danger">*</span></label>
                                    <input type="password" name="current_password" class="form-control" placeholder="Enter Current Password" autofocus>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>New Password<span class="text-danger">*</span></label>
                                    <input type="password" name="new_password" class="form-control" placeholder="Enter New Password">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Confirm Password<span class="text-danger">*</span></label>
                                    <input type="password" name="confirm_password" class="form-control" placeholder="Enter Confirm Password">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="border-top pt-3 text-right">
                        <button type="button" class="btn btn-default mw-120" onclick="update_password()">{{__('Label.UPDATE')}}</button>
                        <input type="hidden" name="_method" value="PATCH">
                    </div>
                </div>
            </form>
		</div>
	</div>
@endsection

@section('pagescript')
	<script>
		function update_password(){
			$("#dvloader").show();
			var formData = new FormData($("#change_password")[0]);

			$.ajax({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				enctype: 'multipart/form-data',
				type: 'POST',
				url: '{{route("uchangepassword.update", [$user_id])}}',
				data: formData,
				cache:false,
				contentType: false,
				processData: false,
				success:function(resp){
					$("#dvloader").hide();
					get_responce_message(resp, 'change_password', '{{ route("uchangepassword.index") }}');
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					$("#dvloader").hide();
					toastr.error(errorThrown.msg,'failed');         
				}
			});
		}
	</script>
@endsection