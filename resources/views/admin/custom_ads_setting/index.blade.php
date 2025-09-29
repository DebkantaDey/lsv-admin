@extends('admin.layout.page-app')
@section('page_title', 'Custom Ads Settings')

@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">Custom Ads Settings</h1>

            <div class="border-bottom row mb-3">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('Label.Dashboard')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Custom Ads Settings</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-4">
                    <div class="card custom-border-card">
                        <h5 class="card-header">Banner Ads</h5>
                        <div class="card-body pb-0">
                            <form id="banner_ads">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>{{__('Label.Banner Ad')}}</label>
                                            <div class="radio-group">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" id="banner_ads_status_no" name="banner_ads_status" class="custom-control-input" {{ $result['banner_ads_status'] == '0' ? "checked" : "" }} value="0">
                                                    <label class="custom-control-label" for="banner_ads_status_no">{{__('Label.No')}}</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" id="banner_ads_status_yes" name="banner_ads_status" class="custom-control-input" {{ $result['banner_ads_status'] == '1' ? "checked" : "" }} value="1">
                                                    <label class="custom-control-label" for="banner_ads_status_yes">{{__('Label.Yes')}}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>Cost Per View</label>
                                            <input type="number" name="banner_ads_cpv" value="{{$result['banner_ads_cpv']}}" min="0" class="form-control" placeholder="Enter Cost Per View Coin">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>Cost Per Click</label>
                                            <input type="number" name="banner_ads_cpc" value="{{$result['banner_ads_cpc']}}" min="0" class="form-control" placeholder="Enter Cost Per Click Coin">
                                        </div>
                                    </div>
                                </div>
                                <div class="border-top pt-3 text-right">
                                    <button type="button" class="btn btn-default mw-120" onclick="banner_ads()">{{__('Label.SAVE')}}</button>
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="card custom-border-card">
                        <h5 class="card-header">Interstital Ads</h5>
                        <div class="card-body pb-0">
                            <form id="interstital_ads">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>Interstital Ad</label>
                                            <div class="radio-group">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" id="interstital_ads_status_no" name="interstital_ads_status" class="custom-control-input" {{ $result['interstital_ads_status'] == '0' ? "checked" : "" }} value="0">
                                                    <label class="custom-control-label" for="interstital_ads_status_no">{{__('Label.No')}}</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" id="interstital_ads_status_yes" name="interstital_ads_status" class="custom-control-input" {{ $result['interstital_ads_status'] == '1' ? "checked" : "" }} value="1">
                                                    <label class="custom-control-label" for="interstital_ads_status_yes">{{__('Label.Yes')}}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>Cost Per View</label>
                                            <input type="number" name="interstital_ads_cpv" value="{{$result['interstital_ads_cpv']}}" min="0" class="form-control" placeholder="Enter Cost Per View Coin">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>Cost Per Click</label>
                                            <input type="number" name="interstital_ads_cpc" value="{{$result['interstital_ads_cpc']}}" min="0" class="form-control" placeholder="Enter Cost Per Click Coin">
                                        </div>
                                    </div>
                                </div>
                                <div class="border-top pt-3 text-right">
                                    <button type="button" class="btn btn-default mw-120" onclick="interstital_ads()">{{__('Label.SAVE')}}</button>
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="card custom-border-card">
                        <h5 class="card-header">Reward Ads</h5>
                        <div class="card-body pb-0">
                            <form id="reward_ads">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>Reward Ad</label>
                                            <div class="radio-group">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" id="reward_ads_status_no" name="reward_ads_status" class="custom-control-input" {{ $result['reward_ads_status'] == '0' ? "checked" : "" }} value="0">
                                                    <label class="custom-control-label" for="reward_ads_status_no">{{__('Label.No')}}</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" id="reward_ads_status_yes" name="reward_ads_status" class="custom-control-input" {{ $result['reward_ads_status'] == '1' ? "checked" : "" }} value="1">
                                                    <label class="custom-control-label" for="reward_ads_status_yes">{{__('Label.Yes')}}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>Cost Per View</label>
                                            <input type="number" name="reward_ads_cpv" value="{{$result['reward_ads_cpv']}}" min="0" class="form-control" placeholder="Enter Cost Per View Coin">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>Cost Per Click</label>
                                            <input type="number" name="reward_ads_cpc" value="{{$result['reward_ads_cpc']}}" min="0" class="form-control" placeholder="Enter Cost Per Click Coin">
                                        </div>
                                    </div>
                                </div>
                                <div class="border-top pt-3 text-right">
                                    <button type="button" class="btn btn-default mw-120" onclick="reward_ads()">{{__('Label.SAVE')}}</button>
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="card custom-border-card">
                        <h5 class="card-header">Admin Ads Commission</h5>
                        <div class="card-body">
                            <form id="save_ads_commission">
                                @csrf
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>Commission</label>
                                        <input type="number" name="ads_commission" class="form-control" value="{{$result['ads_commission']}}" min="0" max="100" placeholder="Enter Ads Commission">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Percentage</label>
                                        <input type="text" readonly class="form-control" value="%">
                                    </div>
                                </div>
                                <div class="border-top pt-3 text-right">
                                    <button type="button" class="btn btn-default mw-120" onclick="save_ads_commission()">{{__('Label.SAVE')}}</button>
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
        let sidebarHeight = $('.sidebar')[0].scrollHeight;
        sidebar_down(sidebarHeight);

        function save_ads_commission() {

            var Check_Admin = '<?php echo Check_Admin_Access(); ?>';
            if(Check_Admin == 1){

                var formData = new FormData($("#save_ads_commission")[0]);
                $("#dvloader").show();
                $.ajax({
                    type: 'POST',
                    url: '{{ route("customAdsCommission") }}',
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
        function banner_ads() {

            var Check_Admin = '<?php echo Check_Admin_Access(); ?>';
            if(Check_Admin == 1){

                var formData = new FormData($("#banner_ads")[0]);
                $("#dvloader").show();
                $.ajax({
                    type: 'POST',
                    url: '{{ route("customBannerAds") }}',
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
        function interstital_ads() {

            var Check_Admin = '<?php echo Check_Admin_Access(); ?>';
            if(Check_Admin == 1){

                var formData = new FormData($("#interstital_ads")[0]);
                $("#dvloader").show();
                $.ajax({
                    type: 'POST',
                    url: '{{ route("customInterstitalAds") }}',
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
        function reward_ads() {

            var Check_Admin = '<?php echo Check_Admin_Access(); ?>';
            if(Check_Admin == 1){

                var formData = new FormData($("#reward_ads")[0]);
                $("#dvloader").show();
                $.ajax({
                    type: 'POST',
                    url: '{{ route("customRewardAds") }}',
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