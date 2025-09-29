@extends('user.layout.page-app')
@section('page_title',  'Profile')

@section('content')
	@include('user.layout.sidebar')

	<div class="right-content">
		@include('user.layout.header')

		<div class="body-content">
			<!-- mobile title -->
			<h1 class="page-title-sm">Profile</h1>

			<div class="border-bottom row mb-3">
				<div class="col-sm-12">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">{{__('Label.Dashboard')}}</a></li>
						<li class="breadcrumb-item active" aria-current="page">Profile</li>
					</ol>
				</div>
			</div>

            <form id="profile" enctype="multipart/form-data" autocomplete="off">
                <input type="hidden" name="id" value="@if($data){{$data->id}}@endif">
				<div class="card custom-border-card">
                    <h5 class="card-header">Channel Info</h5>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Channel Name<span class="text-danger">*</span></label>
                                    <input type="text" name="channel_name" value="@if($data){{$data->channel_name}}@endif" class="form-control" placeholder="Enter Your Channel Name" autofocus>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>{{__('Label.Description')}}<span class="text-danger">*</span></label>
                                    <textarea name="description" rows="1" class="form-control" placeholder="Describe Your Channel...">@if($data){{$data->description}}@endif</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card custom-border-card">
                    <h5 class="card-header">Personal Info</h5>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="col-md-6">
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__('Label.Full_Name')}}<span class="text-danger">*</span></label>
                                            <input type="text" name="full_name" value="@if($data){{$data->full_name}}@endif" class="form-control" placeholder="Enter Your Full Name" autofocus>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__('Label.Mobile Number')}}<span class="text-danger">*</span></label>
                                            <input type="text" name="mobile_number" value="@if($data){{$data->mobile_number}}@endif" class="form-control" placeholder="Enter Mobile Number">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__('Label.Email')}}<span class="text-danger">*</span></label>
                                            <input type="email" name="email" value="@if($data){{$data->email}}@endif" class="form-control" placeholder="Enter Your Email">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group ml-5">
                                    <label>Thumbnail image<span class="text-danger">*</span></label>
                                    <div class="avatar-upload">
                                        <div class="avatar-edit">
                                            <input type='file' name="image" id="imageUpload" accept=".png, .jpg, .jpeg" />
                                            <label for="imageUpload" title="Select File"></label>
                                        </div>
                                        <div class="avatar-preview">
                                            <img src="{{$data->image}}" alt="upload_img.png" id="imagePreview">
                                        </div>
                                    </div>
									<input type="hidden" name="old_image" value="@if($data){{$data->image}}@endif">
                                    <label class="mt-3 text-gray">Maximum size 2MB.</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Cover image</label>
                                    <div class="avatar-upload-landscape">
                                        <div class="avatar-edit-landscape">
                                            <input type='file' name="cover_img" id="imageUploadLandscape" accept=".png, .jpg, .jpeg" />
                                            <label for="imageUploadLandscape" title="Select File"></label>
                                        </div>
                                        <div class="avatar-preview-landscape">
                                            <img src="{{$data->cover_img}}" alt="upload_img.png" id="imagePreviewLandscape">
                                        </div>
                                    </div>
									<input type="hidden" name="old_cover_img" value="@if($data){{$data->cover_img}}@endif">
                                    <label class="mt-3 text-gray">Maximum size 2MB.</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card custom-border-card">
                    <h5 class="card-header">Address Info</h5>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Address<span class="text-danger">*</span></label>
                                    <textarea name="address" rows="1" class="form-control" placeholder="Enter Address">@if($data){{$data->address}}@endif</textarea>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>City<span class="text-danger">*</span></label>
                                            <input type="text" name="city" value="@if($data){{$data->city}}@endif" class="form-control" placeholder="Enter City">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>State<span class="text-danger">*</span></label>
                                            <input type="text" name="state" value="@if($data){{$data->state}}@endif" class="form-control" placeholder="Enter State">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Country<span class="text-danger">*</span></label>
                                            <input type="text" name="country" value="@if($data){{$data->country}}@endif" class="form-control" placeholder="Enter Country">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Pincode<span class="text-danger">*</span></label>
                                            <input type="number" name="pincode" value="@if($data){{$data->pincode}}@endif" class="form-control" placeholder="Enter Pincode">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card custom-border-card">
                    <h5 class="card-header">Social Info</h5>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Website</label>
                                    <input type="text" name="website" value="@if($data){{$data->website}}@endif" class="form-control" placeholder="Enter Website">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Facebook URL</label>
                                    <input type="text" name="facebook_url" value="@if($data){{$data->facebook_url}}@endif" class="form-control" placeholder="Enter Facebook URL">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Instagram URL</label>
                                    <input type="text" name="instagram_url" value="@if($data){{$data->instagram_url}}@endif" class="form-control" placeholder="Enter Instagram URL">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Twitter URL</label>
                                    <input type="text" name="twitter_url" value="@if($data){{$data->twitter_url}}@endif" class="form-control" placeholder="Enter Twitter URL">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card custom-border-card">
                    <h5 class="card-header">Banking Info</h5>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="col-md-9">
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Bank Name</label>
                                            <input type="text" value="@if($data){{$data->bank_name}}@endif" name="bank_name" class="form-control required" placeholder="Enter Bank Name">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Bank Address</label>
                                            <input type="text" value="@if($data){{$data->bank_address}}@endif" name="bank_address" class="form-control required" placeholder="Enter Bank Address">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Account No</label>
                                            <input type="text" value="@if($data){{$data->account_no}}@endif" name="account_no" class="form-control required" placeholder="Enter Account Number">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>IFSC No</label>
                                            <input type="text" value="@if($data){{$data->ifsc_no}}@endif" name="ifsc_no" class="form-control required" placeholder="Enter Bank IFSC Number">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Bank Code</label>
                                            <input type="text" value="@if($data){{$data->bank_code}}@endif" name="bank_code" class="form-control required" placeholder="Enter Bank Code">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group ml-5">
                                    <label>Id Proof</label>
                                    <div class="avatar-upload ">
                                        <div class="avatar-edit">
                                            <input type='file' name="id_proof" id="imageUploadModel" accept=".png, .jpg, .jpeg" />
                                            <label for="imageUploadModel" title="Select File"></label>
                                        </div>
                                        <div class="avatar-preview">
                                            <img src="{{$data->id_proof}}" alt="upload_img.png" id="imagePreviewModel">
                                        </div>
                                    </div>
									<input type="hidden" name="old_id_proof" value="@if($data){{$data->id_proof}}@endif">
                                    <label class="mt-3 text-gray">Maximum size 2MB.</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
				<div class="text-right">
					<button type="button" class="btn btn-default mw-120" onclick="update_profile()">{{__('Label.UPDATE')}}</button>
					<input type="hidden" name="_method" value="PATCH">
				</div>
            </form>
		</div>
	</div>
@endsection

@section('pagescript')
	<script>
		function update_profile(){
			$("#dvloader").show();
			var formData = new FormData($("#profile")[0]);

			$.ajax({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				enctype: 'multipart/form-data',
				type: 'POST',
				url: '{{route("uprofile.update", [$data->id])}}',
				data: formData,
				cache:false,
				contentType: false,
				processData: false,
				success:function(resp){
					$("#dvloader").hide();
					get_responce_message(resp, 'profile', '{{ route("uprofile.index") }}');
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					$("#dvloader").hide();
					toastr.error(errorThrown.msg,'failed');         
				}
			});
		}
	</script>
@endsection