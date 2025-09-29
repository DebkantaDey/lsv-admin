@extends('admin.layout.page-app')
@section('page_title', __('Label.Add_Users'))

@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">{{__('Label.Add_Users')}}</h1>

            <div class="border-bottom row mb-3">
                <div class="col-sm-10">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">{{__('Label.Dashboard')}}</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('user.index') }}">{{__('Label.Users')}}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            {{__('Label.Add_User')}}
                        </li>
                    </ol>
                </div>
                <div class="col-sm-2 d-flex align-items-center justify-content-end">
                    <a href="{{ route('user.index') }}" class="btn btn-default mw-120" style="margin-top:-14px">{{__('Label.Users_List')}}</a>
                </div>
            </div>

            <form name="user" id="user" autocomplete="off" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" value="">
                <div class="card custom-border-card">
                    <h5 class="card-header">Channel Info</h5>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Channel Name<span class="text-danger">*</span></label>
                                    <input type="text" name="channel_name" class="form-control" placeholder="Enter Your Channel Name" autofocus>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>{{__('Label.Description')}}<span class="text-danger">*</span></label>
                                    <textarea name="description" rows="1" class="form-control" placeholder="Describe Your Channel..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card custom-border-card">
                    <h5 class="card-header">Personal Info</h5>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="col-md-8">
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__('Label.Full_Name')}}<span class="text-danger">*</span></label>
                                            <input type="text" name="full_name" class="form-control" placeholder="Enter Your Full Name" autofocus>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__('Label.Email')}}<span class="text-danger">*</span></label>
                                            <input type="email" value="" name="email" class="form-control" placeholder="Enter Your Email">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Country Code<span class="text-danger">*</span></label>
                                            <input type="text" name="country_code" class="form-control" placeholder="+91">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{__('Label.Mobile Number')}}<span class="text-danger">*</span></label>
                                            <input type="text" name="mobile_number" class="form-control" placeholder="Enter Mobile Number">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__('Label.Password')}}<span class="text-danger">*</span></label>
                                            <input type="password" value="" name="password" class="form-control" placeholder="Enter Your Password">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Country Name<span class="text-danger">*</span></label>
                                            <input type="text" name="country_name" class="form-control" placeholder="IN">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Thumbnail image<span class="text-danger">*</span></label>
                                    <div class="avatar-upload">
                                        <div class="avatar-edit">
                                            <input type='file' name="image" id="imageUpload" accept=".png, .jpg, .jpeg" />
                                            <label for="imageUpload" title="Select File"></label>
                                        </div>
                                        <div class="avatar-preview">
                                            <img src="{{asset('assets/imgs/upload_img.png')}}" alt="upload_img.png" id="imagePreview">
                                        </div>
                                    </div>
                                    <label class="mt-3 text-gray">Maximum size 2MB.</label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Cover image</label>
                                    <div class="avatar-upload-landscape">
                                        <div class="avatar-edit-landscape">
                                            <input type='file' name="cover_img" id="imageUploadLandscape" accept=".png, .jpg, .jpeg" />
                                            <label for="imageUploadLandscape" title="Select File"></label>
                                        </div>
                                        <div class="avatar-preview-landscape">
                                            <img src="{{asset('assets/imgs/upload_img.png')}}" alt="upload_img.png" id="imagePreviewLandscape">
                                        </div>
                                    </div>
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
                                    <textarea name="address" rows="1" class="form-control" placeholder="Enter Address"></textarea>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>City<span class="text-danger">*</span></label>
                                            <input type="text" value="" name="city" class="form-control" placeholder="Enter City">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>State<span class="text-danger">*</span></label>
                                            <input type="text" value="" name="state" class="form-control" placeholder="Enter State">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Country<span class="text-danger">*</span></label>
                                            <input type="text" value="" name="country" class="form-control" placeholder="Enter Country">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Pincode<span class="text-danger">*</span></label>
                                            <input type="number" value="" name="pincode" class="form-control" placeholder="Enter Pincode">
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
                                    <input type="text" name="website" class="form-control" placeholder="Enter Website">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Facebook URL</label>
                                    <input type="text" name="facebook_url" class="form-control" placeholder="Enter Facebook URL">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Instagram URL</label>
                                    <input type="text" name="instagram_url" class="form-control" placeholder="Enter Instagram URL">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Twitter URL</label>
                                    <input type="text" name="twitter_url" class="form-control" placeholder="Enter Twitter URL">
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
                                            <input type="text" value="" name="bank_name" class="form-control required" placeholder="Enter Bank Name">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Bank Address</label>
                                            <input type="text" value="" name="bank_address" class="form-control required" placeholder="Enter Bank Address">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Account No</label>
                                            <input type="text" value="" name="account_no" class="form-control required" placeholder="Enter Account Number">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>IFSC No</label>
                                            <input type="text" value="" name="ifsc_no" class="form-control required" placeholder="Enter Bank IFSC Number">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Bank Code</label>
                                            <input type="text" value="" name="bank_code" class="form-control required" placeholder="Enter Bank Code">
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
                                            <img src="{{asset('assets/imgs/upload_img.png')}}" alt="upload_img.png" id="imagePreviewModel">
                                        </div>
                                    </div>
                                    <label class="mt-3 text-gray">Maximum size 2MB.</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pt-3 text-right">
                    <button type="button" class="btn btn-default mw-120" onclick="save_user()">{{__('Label.SAVE')}}</button>
                    <a href="{{route('user.index')}}" class="btn btn-cancel mw-120 ml-2">{{__('Label.CANCEL')}}</a>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                </div>
            </form>
        </div>
    </div>
@endsection

@section('pagescript')
    <script>
        function save_user() {
            var Check_Admin = '<?php echo Check_Admin_Access(); ?>';
            if(Check_Admin == 1){

                $("#dvloader").show();
                var formData = new FormData($("#user")[0]);
                $.ajax({
                    type: 'POST',
                    url: '{{ route("user.store") }}',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(resp) {
                        $("#dvloader").hide();
                        get_responce_message(resp, 'user', '{{ route("user.index") }}');
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