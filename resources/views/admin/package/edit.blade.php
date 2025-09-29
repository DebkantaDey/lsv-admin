@extends('admin.layout.page-app')
@section('page_title', __('Label.Edit_Package'))

@section('content')
	@include('admin.layout.sidebar')

	<div class="right-content">
		@include('admin.layout.header')

		<div class="body-content">
			<!-- mobile title -->
			<h1 class="page-title-sm">{{__('Label.Edit_Package')}}</h1>

			<div class="border-bottom row mb-3">
				<div class="col-sm-10">
					<ol class="breadcrumb">
						<li class="breadcrumb-item">
							<a href="{{ route('admin.dashboard') }}">{{__('Label.Dashboard')}}</a>
						</li>
						<li class="breadcrumb-item">
							<a href="{{ route('package.index') }}">{{__('Label.Package')}}</a>
						</li>
						<li class="breadcrumb-item active" aria-current="page">
							{{__('Label.Edit Package')}}
						</li>
					</ol>
				</div>
				<div class="col-sm-2 d-flex align-items-center justify-content-end">
					<a href="{{ route('package.index') }}" class="btn btn-default mw-120" style="margin-top:-14px">{{__('Label.Package List')}}</a>
				</div>
			</div>

			<div class="card custom-border-card">
				<form id="package_update" enctype="multipart/form-data">
					<input type="hidden" name="id" value="@if($data){{$data->id}}@endif">
					<div class="form-row">
                        <div class="col-md-9">
                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{__('Label.NAME')}}<span class="text-danger">*</span></label>
                                        <input type="text" name="name" value="@if($data){{$data->name}}@endif" class="form-control" placeholder="{{__('Label.Please Enter Name')}}" autofocus>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Package Time<span class="text-danger">*</span></label>
                                        <select class="form-control" id="validity_type" name="type">
                                        <option value="">Select Type</option>
											<option value="Day" {{$data->type == 'Day' ? 'selected' : ''}}>Day</option>
											<option value="Week" {{$data->type == 'Week' ? 'selected' : ''}}>Week</option>
											<option value="Month" {{$data->type == 'Month' ? 'selected' : ''}}>Month</option>
											<option value="Year" {{$data->type == 'Year' ? 'selected' : ''}}>Year</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 mt-4">
                                    <div class="form-group mt-2">
                                        <select class="form-control" id="time" name="time">
                                            <option value="">Select Number</option>
                                            @for($i=1; $i<=31; $i++)
                                            	<option value="{{$i}}" {{$data->time == $i ? 'selected' : ''}}>{{$i}}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>{{__('Label.Price')}}<span class="text-danger">*</span></label>
                                        <input type="number" value="@if($data){{$data->price}}@endif" name="price" min="0" class="form-control" placeholder="Enter Price">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Android Product Package</label>
                                        <input name="android_product_package" value="@if($data){{$data->android_product_package}}@endif" type="text" class="form-control" placeholder="Enter Android Product Package">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>IOS Product Package</label>
                                        <input name="ios_product_package" value="@if($data){{$data->ios_product_package}}@endif" type="text" class="form-control" placeholder="Enter IOS Product Package">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Web Product Package</label>
                                        <input name="web_product_package" value="@if($data){{$data->web_product_package}}@endif" type="text" class="form-control" placeholder="Enter Web Product Package">
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="form-row mt-4">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>No of Device Login<span class="text-danger">*</span></label>
                                        <input type="number" value="@if($data){{$data->no_of_device}}@endif" name="no_of_device" min="1" class="form-control" placeholder="Enter Device Number">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Size of Data Upload (MB)<span class="text-danger">*</span></label>
                                        <input type="number" value="@if($data){{$data->size_of_data_upload}}@endif" name="size_of_data_upload" min="0" class="form-control" placeholder="Enter Size Number">
                                    </div>
                                </div>
                            </div> -->
                        </div>
                        <div class="col-md-3">
							<div class="form-group ml-5">
								<label class="ml-5">{{__('Label.Image')}}<span class="text-danger">*</span></label>
								<div class="avatar-upload ml-5">
									<div class="avatar-edit">
										<input type='file' name="image" id="imageUpload" accept=".png, .jpg, .jpeg" />
										<label for="imageUpload" title="Select File"></label>
									</div>
									<div class="avatar-preview">
										<img src="{{$data->image}}" alt="upload_img.png" id="imagePreview">
									</div>
								</div>
								<input type="hidden" name="old_image" value="@if($data){{$data->image}}@endif">
								<label class="mt-3 ml-5 text-gray">Maximum size 2MB.</label>
							</div>
						</div>
                    </div>
                    <div class="form-row mt-3">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Ads Free<span class="text-danger">*</span></label>
                                <div class="radio-group">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" name="ads_free" id="ads_free_yes" class="custom-control-input" value="1" {{ $data->ads_free == 1 ? 'checked' : ''}}>
                                        <label class="custom-control-label" for="ads_free_yes">{{__('Label.Yes')}}</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" name="ads_free" id="ads_free_no" class="custom-control-input" value="0" {{ $data->ads_free == 0 ? 'checked' : ''}}>
                                        <label class="custom-control-label" for="ads_free_no">{{__('Label.No')}}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Download Content<span class="text-danger">*</span></label>
                                <div class="radio-group">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" name="download" id="download_yes" class="custom-control-input" value="1" {{ $data->download == 1 ? 'checked' : ''}}>
                                        <label class="custom-control-label" for="download_yes">{{__('Label.Yes')}}</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" name="download" id="download_no" class="custom-control-input" value="0" {{ $data->download == 0 ? 'checked' : ''}}>
                                        <label class="custom-control-label" for="download_no">{{__('Label.No')}}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="col-md-2">
                            <div class="form-group">
                                <label>Background Play Content<span class="text-danger">*</span></label>
                                <div class="radio-group">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" name="background_play" id="background_play_yes" class="custom-control-input" value="1" {{ $data->background_play == 1 ? 'checked' : ''}}>
                                        <label class="custom-control-label" for="background_play_yes">{{__('Label.Yes')}}</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" name="background_play" id="background_play_no" class="custom-control-input" value="0" {{ $data->background_play == 0 ? 'checked' : ''}}>
                                        <label class="custom-control-label" for="background_play_no">{{__('Label.No')}}</label>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Verifly Artist<span class="text-danger">*</span></label>
                                <div class="radio-group">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" name="verifly_artist" id="verifly_artist_yes" class="custom-control-input" value="1" {{ $data->verifly_artist == 1 ? 'checked' : ''}}>
                                        <label class="custom-control-label" for="verifly_artist_yes">{{__('Label.Yes')}}</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" name="verifly_artist" id="verifly_artist_no" class="custom-control-input" value="0" {{ $data->verifly_artist == 0 ? 'checked' : ''}}>
                                        <label class="custom-control-label" for="verifly_artist_no">{{__('Label.No')}}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Verifly Account<span class="text-danger">*</span></label>
                                <div class="radio-group">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" name="verifly_account" id="verifly_account_yes" class="custom-control-input" value="1" {{ $data->verifly_account == 1 ? 'checked' : ''}}>
                                        <label class="custom-control-label" for="verifly_account_yes">{{__('Label.Yes')}}</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" name="verifly_account" id="verifly_account_no" class="custom-control-input" value="0" {{ $data->verifly_account == 0 ? 'checked' : ''}}>
                                        <label class="custom-control-label" for="verifly_account_no">{{__('Label.No')}}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
					<div class="border-top pt-3 text-right">
						<button type="button" class="btn btn-default mw-120" onclick="update_package()">{{__('Label.UPDATE')}}</button>
						<a href="{{route('package.index')}}" class="btn btn-cancel mw-120 ml-2">{{__('Label.CANCEL')}}</a>
						<input type="hidden" name="_method" value="PATCH">
					</div>
				</form>
			</div>
		</div>
	</div>
@endsection

@section('pagescript')
	<script>

        // Sidebar Scroll Down
        let sidebarHeight = $('.sidebar')[0].scrollHeight;
        sidebar_down(sidebarHeight);

		function update_package() {
            var Check_Admin = '<?php echo Check_Admin_Access(); ?>';
            if(Check_Admin == 1){

                $("#dvloader").show();
                var formData = new FormData($("#package_update")[0]);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    enctype: 'multipart/form-data',
                    type: 'POST',
                    url: '{{route("package.update", [$data->id])}}',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(resp) {
                        $("#dvloader").hide();
                        get_responce_message(resp, 'package_update', '{{ route("package.index") }}');
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
		$(document).ready(function() {

			var validity_type = "<?php echo $data->type; ?>";
			if (validity_type == "Day") {
				for (let i = 8; i <= 31; i++) {
					$("#time option[value=" + i + "]").hide();
				}
			} else if (validity_type == "Week") {
				for (let i = 5; i <= 31; i++) {
					$("#time option[value=" + i + "]").hide();
				}
			} else if (validity_type == "Month") {
				for (let i = 13; i <= 31; i++) {
					$("#time option[value=" + i + "]").hide();
				}
			} else if (validity_type == "Year") {
				for (let i = 2; i <= 31; i++) {
					$("#time option[value=" + i + "]").hide();
				}
			} else {
				$('#time').hide();
			}
		});

		$('#validity_type').on('click', function() {
			$('#time').show();
			var type = $("#validity_type").val()

			for (let i = 1; i <= 31; i++) {
				$("#time option[value=" + i + "]").show();
				$("#time option[value=" + i + "]").attr("selected", false);
			}

			if (type == "Day") {
				for (let i = 8; i <= 31; i++) {
					$("#time option[value=" + i + "]").hide();
				}
			} else if (type == "Week") {
				for (let i = 5; i <= 31; i++) {
					$("#time option[value=" + i + "]").hide();
				}
			} else if (type == "Month") {
				for (let i = 13; i <= 31; i++) {
					$("#time option[value=" + i + "]").hide();
				}
			} else if (type == "Year") {
				for (let i = 2; i <= 31; i++) {
					$("#time option[value=" + i + "]").hide();
				}
			} else {
				$('#time').hide();
			}
		})
	</script>
@endsection