@extends('admin.layout.page-app')
@section('page_title', 'App Settings')

@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">App Settings</h1>

            <div class="border-bottom row mb-3">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('Label.Dashboard')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">App Settings</li>
                    </ol>
                </div>
            </div>

            <ul class="nav nav-pills custom-tabs inline-tabs" id="pills-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="app-tab" data-toggle="tab" href="#app" role="tab" aria-controls="app" aria-selected="true">{{__('Label.APP SETTINGS')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="social-tab" data-toggle="tab" href="#social" role="tab" aria-controls="social" aria-selected="false">SOCIAl SETTING</a>
                </li>
                @if( env('DEMO_MODE') == 'OFF')
                <li class="nav-item">
                    <a class="nav-link" id="smtp-tab" data-toggle="tab" href="#smtp" role="tab" aria-controls="smtp" aria-selected="false">SMTP</a>
                </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link" id="onboarding-tab" data-toggle="tab" href="#onboarding" role="tab" aria-controls="onboarding" aria-selected="false">Onboarding Screen</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="live-streaming-tab" data-toggle="tab" href="#live-streaming" role="tab"aria-controls="live-streaming" aria-selected="false">Live Streaming</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="deepar-tab" data-toggle="tab" href="#deepar" role="tab"aria-controls="deepar" aria-selected="false">DeepAR</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="sight-engine-tab" data-toggle="tab" href="#sight-engine" role="tab"aria-controls="sight-engine" aria-selected="false">Sight Engine</a>
                </li>
            </ul>

            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="app" role="tabpanel" aria-labelledby="app-tab">
                    <div class="card custom-border-card">
                        <h5 class="card-header">{{__('Label.App Settings')}}</h5>
                        <div class="card-body">
                            <form id="app_setting" enctype="multipart/form-data">
                                @csrf
                                <div class="form-row">
                                    <div class="col-md-9">
                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <label>{{__('Label.App Name')}}</label>
                                                <input type="text" name="app_name" value="@if($result && isset($result['app_name'])){{$result['app_name']}}@endif" class="form-control" placeholder="Enter App Name" autofocus>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label>{{__('Label.Host Email')}}</label>
                                                <input type="email" name="host_email" value="@if($result && isset($result['host_email'])){{$result['host_email']}}@endif" class="form-control" placeholder="Enter Host Email">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label>{{__('Label.App Version')}}</label>
                                                <input type="text" name="app_version" value="@if($result && isset($result['app_version'])){{$result['app_version']}}@endif" class="form-control" placeholder="Enter App Version">
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <label>{{__('Label.Author')}}</label>
                                                <input type="text" name="author" value=" @if($result && isset($result['author'])){{$result['author']}}@endif" class="form-control" placeholder="Enter Author">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label>{{__('Label.Email')}} </label>
                                                <input type="email" name="email"  value="@if($result && isset($result['email'])){{$result['email']}}@endif" class="form-control" placeholder="Enter Email">
                                            </div>
                                            <div class="form-group  col-md-4">
                                                <label> {{__('Label.Contact')}} </label>
                                                <input type="text" name="contact" value="@if($result && isset($result['contact'])){{$result['contact']}}@endif" class="form-control" placeholder="Enter Contact">
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <label>{{__('Label.WEBSITE')}}</label>
                                                <input type="text" name="website" value="@if($result && isset($result['website'])){{$result['website']}}@endif" class="form-control" placeholder="Enter Your Website">
                                            </div>
                                            <div class="form-group col-md-8">
                                                <label>{{__('Label.APP DESCRIPATION')}}</label>
                                                <textarea name="app_desripation" rows="1" class="form-control" placeholder="Enter App Desripation">@if($result && isset($result['app_desripation'])){{$result['app_desripation']}}@endif</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group ml-5">
                                            <label class="ml-5">App Icon</label>
                                            <div class="avatar-upload ml-5">
                                                <div class="avatar-edit">
                                                    <input type='file' name="app_logo" id="imageUpload" accept=".png, .jpg, .jpeg" />
                                                    <label for="imageUpload" title="Select File"></label>
                                                </div>
                                                <div class="avatar-preview">
                                                    <img src="{{$result['app_logo']}}" alt="upload_img.png" id="imagePreview">
                                                </div>
                                            </div>
                                            <input type="hidden" name="old_app_logo" value="{{$result['app_logo']}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="border-top pt-3 text-right">
                                    <button type="button" class="btn btn-default mw-120" onclick="app_setting()">{{__('Label.SAVE')}}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-6">
                            <div class="card custom-border-card">
                                <h5 class="card-header">API Configrations</h5>
                                <div class="card-body">
                                    <div class="input-group">
                                        <div class="col-2">
                                            <label class="pt-3" style="font-size:16px; font-weight:500; color:#1b1b1b">{{__('Label.API Path')}}</label>
                                        </div>
                                        <input type="text" readonly value="{{url('/')}}/api/" name="api_path" class="form-control" id="api_path">
                                        <div class="input-group-text ml-2" onclick="Function_Api_path()" title="Copy">
                                            <i class="fa-solid fa-copy fa-2xl"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card custom-border-card">
                                <h5 class="card-header">{{__('Label.purchase_code')}}</h5>
                                <div class="card-body">
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>{{__('Label.purchase_code')}}</label>
                                            <input type="text" class="form-control" value="{{env('PURCHASE_CODE')}}" readonly>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label> {{__('Label.envato_name')}}</label>
                                            <input type="text" class="form-control" value="{{env('BUYER_USERNAME')}}" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="card custom-border-card">
                                <h5 class="card-header">Delete Reels ( In Days)</h5>
                                <div class="card-body">
                                    <form id="save_after_day_delete_reels">
                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <input type="text" name="after_day_delete_reels" class="form-control" value="{{$result['after_day_delete_reels']}}" placeholder="Enter Day">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <input type="text" name="" class="form-control" placeholder="Days" readonly>
                                            </div>
                                        </div>
                                        <div class="border-top pt-3 text-right">
                                            <button type="button" class="btn btn-default mw-120" onclick="save_after_day_delete_reels()">{{__('Label.SAVE')}}</button>
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        </div>
                                    </form>
                                </div>
                            </div> -->
                        </div>
                        <div class="col-6">
                            <div class="card custom-border-card">
                                <h5 class="card-header">{{__('Label.Currency Settings')}}</h5>
                                <div class="card-body">
                                    <form id="save_currency">
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label>{{__('Label.Currency Name')}} </label>
                                                <input type="text" name="currency" class="form-control" value="{{$result['currency']}}" placeholder="Enter Currency Name">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label> {{__('Label.Currency Code')}} </label>
                                                <input type="text" name="currency_code" class="form-control" value="{{$result['currency_code']}}" placeholder="Enter Currency Code">
                                            </div>
                                        </div>
                                        <div class="border-top pt-3 text-right">
                                            <button type="button" class="btn btn-default mw-120" onclick="save_currency()">{{__('Label.SAVE')}}</button>
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="card custom-border-card">
                                <h5 class="card-header">Vap Id Key</h5>
                                <div class="card-body">
                                    <form id="save_vap_id_key">
                                        <div class="form-row">
                                            <div class="form-group col-md-12">
                                                <input type="text" name="vap_id_key" class="form-control" value="{{$result['vap_id_key']}}" placeholder="Enter Vap Id Key">
                                            </div>
                                        </div>
                                        <div class="border-top pt-3 text-right">
                                            <button type="button" class="btn btn-default mw-120" onclick="save_vap_id_key()">{{__('Label.SAVE')}}</button>
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                 <!-- sight engine -->
                 <div class="tab-pane fade" id="sight-engine" role="tabpanel" aria-labelledby="sight-engine-tab">
                    <div class="card custom-border-card">
                        <h5 class="card-header">Sight Engine</h5>
                        <div class="card-body">
                            <form id="sight_engine">
                                @csrf 
                                <div class="row">
                                    <div class="col-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label for="sight_engine_status">Sight Engine Status</label>
                                            <div class="radio-group">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" id="sight_engine_status" name="sight_engine_status" class="custom-control-input" {{ ($result['sight_engine_status'] == '1') ? "checked" : "" }} value="1">
                                                    <label class="custom-control-label" for="sight_engine_status">{{ __('Label.Yes') }}</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" id="sight_engine_status1" name="sight_engine_status" class="custom-control-input" {{ ($result['sight_engine_status'] == '0') ? "checked" : "" }} value="0">
                                                    <label class="custom-control-label" for="sight_engine_status1">{{ __('Label.No') }}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row sight_engine_content">
                                    <div class="col-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label>API User Key</label>
                                            <input type="text" value="{{ $result['sight_engine_user_key'] }}" name="sight_engine_user_key" class="form-control" placeholder="Enter API User">
                                            <label class="mt-1 text-gray">Search for better result <a href="https://dashboard.sightengine.com/api-credentials" target="_blank" class="btn-link">Click Here</a></label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label>API Secret Key</label>
                                            <input type="text" value="{{ $result['sight_engine_secret_key'] }}" name="sight_engine_secret_key" class="form-control" placeholder="Enter API Secret">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-12">
                                        <div class="form-group">
                                            <?php $x = explode(",", $result['sight_engine_concepts']); ?>
                                            <label>Concepts<span class="text-danger">*</span></label>
                                            <select class="form-control" style="width:100%!important;" name="sight_engine_concepts[]" multiple id="sight_engine_concepts">
                                                <option value="nudity" {{ in_array("nudity", $x) ? 'selected' : '' }}>Nudity</option>
                                                <option value="nudity-raw" {{ in_array("nudity-raw", $x) ? 'selected' : '' }}>Nudity-Raw</option>
                                                <option value="face" {{ in_array("face", $x) ? 'selected' : '' }}>Face</option>
                                                <option value="face-minor" {{ in_array("face-minor", $x) ? 'selected' : '' }}>Face-Minor</option>
                                                <option value="license-plate" {{ in_array("license-plate", $x) ? 'selected' : '' }}>License-Plate</option>
                                                <option value="offensive" {{ in_array("offensive", $x) ? 'selected' : '' }}>Offensive</option>
                                                <option value="weapon" {{ in_array("weapon", $x) ? 'selected' : '' }}>Weapon</option>
                                                <option value="alcohol" {{ in_array("alcohol", $x) ? 'selected' : '' }}>Alcohol</option>
                                                <option value="recreational-drug" {{ in_array("recreational-drug", $x) ? 'selected' : '' }}>Recreational-Drug</option>
                                                <option value="medical-drug" {{ in_array("medical-drug", $x) ? 'selected' : '' }}>Medical-Drug</option>
                                                <option value="gore" {{ in_array("gore", $x) ? 'selected' : '' }}>Gore</option>
                                                <option value="text-natural" {{ in_array("text-natural", $x) ? 'selected' : '' }}>Text-Natural</option>
                                                <option value="text-embedded" {{ in_array("text-embedded", $x) ? 'selected' : '' }}>Text-Embedded</option>
                                                <option value="profanity" {{ in_array("profanity", $x) ? 'selected' : '' }}>Profanity</option>
                                                <option value="link" {{ in_array("link", $x) ? 'selected' : '' }}>Link</option>
                                                <option value="email" {{ in_array("email", $x) ? 'selected' : '' }}>Email</option>
                                                <option value="phone" {{ in_array("phone", $x) ? 'selected' : '' }}>Phone</option>
                                                <option value="social" {{ in_array("social", $x) ? 'selected' : '' }}>Social</option>
                                                <option value="qr" {{ in_array("qr", $x) ? 'selected' : '' }}>QR</option>
                                            </select>
                                            <label class="mt-1 text-gray">For Better Understanding of Concepts <a href="https://sightengine.com/docs/video-redaction-and-anonymization" target="_blank" class="btn-link">Click Here</a></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="border-top pt-3 text-right">
                                    <button type="button" class="btn btn-default mw-120" onclick="sight_engine()">{{__('Label.SAVE')}}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="social" role="tabpanel" aria-labelledby="social-tab">
                    <div class="card custom-border-card">
                        <h5 class="card-header">Social Links</h5>
                        <div class="card-body">
                            <form id="social_link" enctype="multipart/form-data">
                                @csrf
                                <div class="row col-md-12">
                                    <div class="form-group col-md-3">
                                        <label>Name</label>
                                        <input type="text" name="name[]" class="form-control" placeholder="Enter URL Name">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>URL</label>
                                        <input type="url" name="url[]" class="form-control" placeholder="Enter URL">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Icon</label>
                                        <input type="file" name="image[]" class="form-control import-file social_img" id="social_img" accept=".png, .jpg, .jpeg">
                                        <input type="hidden" name="old_image[]" value="">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <div class="custom-file">
                                            <img src="{{asset('assets/imgs/upload_img.png')}}" style="height: 90px; width: 90px;" class="img-thumbnail" id="link_img_social_img">
                                        </div>
                                    </div>
                                    <div class="col-md-1 mt-2">
                                        <div class="flex-grow-1 px-5 d-inline-flex">
                                            <div class="change mr-3 mt-4" id="add_btn" title="Add More">
                                                <a class="btn btn-success add-more text-white" onclick="add_more_link()">+</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @for ($i=0; $i < count($social_link); $i++)
                                    <div class="social_part">
                                        <div class="row col-lg-12">
                                            <div class="form-group col-md-3">
                                                <label>Name</label>
                                                <input type="text" name="name[]" value="{{ $social_link[$i]['name'] }}" class="form-control" placeholder="Enter URL Name">
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label>URL</label>
                                                <input type="url" name="url[]" value="{{ $social_link[$i]['url'] }}" class="form-control" placeholder="Enter URL">
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label>Icon</label>
                                                <input type="file" name="image[]" class="form-control import-file social_img" id="social_img_{{$i}}" accept=".png, .jpg, .jpeg">
                                                <input type="hidden" name="old_image[]" value="{{ basename($social_link[$i]['image']) }}">
                                            </div>
                                            <div class="form-group col-md-2">
                                                <div class="custom-file">
                                                    <img src="{{$social_link[$i]['image']}}" style="height: 90px; width: 90px;" class="img-thumbnail" id="link_img_social_img_{{$i}}">
                                                </div>
                                            </div>
                                            <div class="col-md-1 mt-2">
                                                <div class="flex-grow-1 px-5 d-inline-flex">
                                                    <div class="change mr-3 mt-4" id="add_btn" title="Remove">
                                                        <a class="btn btn-danger text-white remove_link">-</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endfor               
                                
                                <div class="after-add-more"></div>

                                <div class="border-top pt-3 text-right">
                                    <button type="button" class="btn btn-default mw-120" onclick="social_link()">{{__('Label.SAVE')}}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="smtp" role="tabpanel" aria-labelledby="smtp-tab">
                    <div class="card custom-border-card">
                        <h5 class="card-header">{{__('Label.Email Setting [SMTP]')}}</h5>
                        <div class="card-body">
                            <form id="smtp_setting">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="id" value="@if($smtp){{$smtp->id}}@endif">
                                <div class="form-row">
                                    <div class="form-group  col-md-3">
                                        <label>{{__('Label.IS SMTP Active')}}</label>
                                        <select name="status" class="form-control">
                                            <option value="">Select Status</option>
                                            <option value="0" @if($smtp){{ $smtp->status == 0  ? 'selected' : ''}}@endif>{{__('Label.No')}}</option>
                                            <option value="1" @if($smtp){{ $smtp->status == 1  ? 'selected' : ''}}@endif>{{__('Label.Yes')}}</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>{{__('Label.Host')}}</label>
                                        <input type="text" name="host" class="form-control" value="@if($smtp){{$smtp->host}}@endif" placeholder="Enter Host">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>{{__('Label.Port')}}</label>
                                        <input type="text" name="port" class="form-control" value="@if($smtp){{$smtp->port}}@endif" placeholder="Enter Port">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>{{__('Label.Protocol')}}</label>
                                        <input type="text" name="protocol" class="form-control" value="@if($smtp){{$smtp->protocol}}@endif" placeholder="Enter Protocol">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-3">
                                        <label>{{__('Label.User name')}}</label>
                                        <input type="text" name="user" class="form-control" value="@if($smtp){{$smtp->user}}@endif" placeholder="Enter User Name">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>{{__('Label.Password')}}</label>
                                        <input type="password" name="pass" class="form-control" value="@if($smtp){{$smtp->pass}}@endif" placeholder="Enter Password">
                                        <label class="mt-1 text-gray">Search for better result <a href="https://support.google.com/mail/answer/185833?hl=en" target="_blank" class="btn-link">Click Here</a></label>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>{{__('Label.From name')}}</label>
                                        <input type="text" name="from_name" class="form-control" value="@if($smtp){{$smtp->from_name}}@endif" placeholder="Enter From Name">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>{{__('Label.From Email')}}</label>
                                        <input type="text" name="from_email" class="form-control" value="@if($smtp){{$smtp->from_email}}@endif" placeholder="Enter From Email">
                                    </div>
                                </div>
                                <div class="border-top pt-3 text-right">
                                    <button type="button" class="btn btn-default mw-120" onclick="smtp_setting()">{{__('Label.SAVE')}}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="onboarding" role="tabpanel" aria-labelledby="onboarding-tab">
                    <div class="card custom-border-card">
                        <h5 class="card-header">Onboarding Screen</h5>

                        <div class="card-body">
                            <form id="onboarding_form" enctype="multipart/form-data">
                                <div class="row col-md-12">
                                    <div class="form-group col-md-6">
                                        <label>Title<span class="text-danger">*</span></label>
                                        <input type="text" name="title[]" class="form-control" placeholder="Enter Title">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Image<span class="text-danger">*</span></label>
                                        <input type="file" name="image[]" class="form-control import-file on_boarding_img" id="on_boarding_img" accept=".png, .jpg, .jpeg">
                                        <input type="hidden" name="old_image[]" value="">
                                    </div>
                                    <div class="form-group col-md-1">
                                        <div class="custom-file">
                                            <img src="{{asset('assets/imgs/upload_img.png')}}" style="height: 90px; width: 90px;" class="img-thumbnail" id="link_img_on_boarding_img">
                                        </div>
                                    </div>
                                    <div class="col-md-1 mt-2">
                                        <div class="flex-grow-1 px-5 d-inline-flex">
                                            <div class="change mr-3 mt-4" id="add_btn" title="Add More">
                                                <a class="btn btn-success add-more text-white" onclick="add_more_screen()">+</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @for ($i=0; $i < count($onboarding_screen); $i++)
                                    <div class="onboarding_part">
                                        <div class="row col-lg-12">
                                            <div class="form-group col-md-6">
                                                <label>Title<span class="text-danger">*</span></label>
                                                <input type="text" name="title[]" value="{{ $onboarding_screen[$i]['title'] }}" class="form-control" placeholder="Enter Title">
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label>Image<span class="text-danger">*</span></label>
                                                <input type="file" name="image[]" class="form-control import-file on_boarding_img" id="on_boarding_img{{$i}}" accept=".png, .jpg, .jpeg">
                                                <input type="hidden" name="old_image[]" value="{{ basename($onboarding_screen[$i]['image']) }}">
                                            </div>
                                            <div class="form-group col-md-1">
                                                <div class="custom-file">
                                                    <img src="{{$onboarding_screen[$i]['image']}}" style="height: 90px; width: 90px;" class="img-thumbnail" id="link_img_on_boarding_img{{$i}}">
                                                </div>
                                            </div>
                                            <div class="col-md-1 mt-2">
                                                <div class="flex-grow-1 px-5 d-inline-flex">
                                                    <div class="change mr-3 mt-4" id="add_btn" title="Remove">
                                                        <a class="btn btn-danger text-white remove_on_boarding">-</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endfor

                                <div class="after-add-more-on-boarding"></div>

                                <div class="border-top pt-3 text-right">
                                    <button type="button" class="btn btn-default mw-120" onclick="onboarding()">{{__('Label.SAVE')}}</button>
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="live-streaming" role="tabpanel" aria-labelledby="live-streaming-tab">
                    <div class="card custom-border-card">
                        <h5 class="card-header">Live Streaming</h5>
                        <div class="card-body">
                            <form id="live_streaming" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="is_live_streaming_fake">Is Live Streaming Fake</label>
                                            <div class="radio-group">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" id="is_live_streaming_fake" name="is_live_streaming_fake" class="custom-control-input" {{ ($result['is_live_streaming_fake']=='1')? "checked" : "" }} value="1">
                                                    <label class="custom-control-label" for="is_live_streaming_fake">{{__('Label.Yes')}}</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" id="is_live_streaming_fake1" name="is_live_streaming_fake" class="custom-control-input" {{ ($result['is_live_streaming_fake']=='0')? "checked" : "" }} value="0">
                                                    <label class="custom-control-label" for="is_live_streaming_fake1">{{__('Label.No')}}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-4">
                                        <label>App Id</label>
                                        <input type="text" value="{{$result['live_appid']}}" name="live_appid" class="form-control" placeholder="Enter App Id">
                                        <label class="mt-1 text-gray">Search for Better Result <a href="https://console.zegocloud.com/account/login" target="_blank" class="btn-link">Click Here</a></label>
                                    </div>
                                    <div class="form-group col-lg-4">
                                        <label>App Sign</label>
                                        <input type="text" value="{{$result['live_appsign']}}" name="live_appsign" class="form-control" placeholder="Enter App Sign Key">
                                    </div>
                                    <div class="form-group col-lg-4">
                                        <label>Server Secret</label>
                                        <input type="text" value="{{$result['live_serversecret']}}" name="live_serversecret" class="form-control" placeholder="Enter Server Secret Key">
                                    </div>
                                </div>
                                <div class="border-top pt-3 text-right">
                                    <button type="button" class="btn btn-default mw-120" onclick="live_streaming()">{{__('Label.SAVE')}}</button>
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="deepar" role="tabpanel" aria-labelledby="deepar-tab">
                    <div class="card custom-border-card">
                        <h5 class="card-header">DeepAR</h5>
                        <div class="card-body">
                            <form id="deepar_save" enctype="multipart/form-data" autocomplete="off">
                                <div class="row">
                                    <div class="form-group col-lg-6">
                                        <label>Deepar Android Key</label>
                                        <input type="text" value="{{$result['deepar_android_key']}}" name="deepar_android_key" class="form-control" placeholder="Enter Deepar Android License Key">
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label>Deepar IOS Key</label>
                                        <input type="text" value="{{$result['deepar_ios_key']}}" name="deepar_ios_key" class="form-control" placeholder="Enter Deepar IOS License Key">
                                    </div>
                                    <label class="mt-1 text-gray ml-4">Search for Better Result <a href="https://developer.deepar.ai/" target="_blank" class="btn-link">Click Here</a></label>
                                </div>
                                <div class="border-top pt-3 text-right">
                                    <button type="button" class="btn btn-default mw-120" onclick="deeparsave()">{{__('Label.SAVE')}}</button>
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
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

        function Function_Api_path() {
            /* Get the text field */
            var copyText = document.getElementById("api_path");

            /* Select the text field */
            copyText.select();
            copyText.setSelectionRange(0, 99999); /* For mobile devices */

            document.execCommand('copy');

            /* Alert the copied text */
            alert("Copied the API Path: " + copyText.value);
        }

        function app_setting() {
            var Check_Admin = '<?php echo Check_Admin_Access(); ?>';
            if(Check_Admin == 1){

                var formData = new FormData($("#app_setting")[0]);
                $("#dvloader").show();
                $.ajax({
                    type: 'POST',
                    url: '{{ route("setting.app") }}',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(resp) {
                        $("#dvloader").hide();
                        get_responce_message(resp, 'app_setting', '{{ route("setting") }}');
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
        function save_currency() {
            var Check_Admin = '<?php echo Check_Admin_Access(); ?>';
            if(Check_Admin == 1){

                var formData = new FormData($("#save_currency")[0]);
                $("#dvloader").show();
                $.ajax({
                    type: 'POST',
                    url: '{{ route("setting.currency") }}',
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
        function save_vap_id_key() {
            var Check_Admin = '<?php echo Check_Admin_Access(); ?>';
            if(Check_Admin == 1){

                var formData = new FormData($("#save_vap_id_key")[0]);
                $("#dvloader").show();
                $.ajax({
                    type: 'POST',
                    url: '{{ route("setting.vapidkey") }}',
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
        function save_after_day_delete_reels() {
            var Check_Admin = '<?php echo Check_Admin_Access(); ?>';
            if(Check_Admin == 1){

                var formData = new FormData($("#save_after_day_delete_reels")[0]);
                $("#dvloader").show();
                $.ajax({
                    type: 'POST',
                    url: '{{ route("setting.deletereels") }}',
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
                        toastr.error(errorThrown.msg, 'Failed');
                    }
                }); 
            } else {
                toastr.error('You have no right to Add, Edit and Delete.');
            }
        }
        function smtp_setting() {

            var formData = new FormData($("#smtp_setting")[0]);
            $.ajax({
                type: 'POST',
                url: '{{ route("smtp.save") }}',
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
                    toastr.error(errorThrown.msg, 'failed');
                }
            });
        }
        function sight_engine() {
            var Check_Admin = '<?php echo Check_Admin_Access(); ?>';
            if(Check_Admin == 1){
                    
                var formData = new FormData($("#sight_engine")[0]);
                $("#dvloader").show();
                $.ajax({
                    type: 'POST',
                    url: '{{ route("settingSightengine") }}',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(resp) {
                        $("#dvloader").hide();
                        $("html, body").animate({
                            scrollTop: 0
                        }, "swing");
                        get_responce_message(resp);
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        $("#dvloader").hide();
                        toastr.error(errorThrown, textStatus);
                    }
                });
            } else {
                toastr.error('{{__("Label.you_have_no_right_to_add_edit_and_delete")}}');
            }
        }
         // Live Streaming
         function live_streaming() {

            var Check_Admin = '<?php echo Check_Admin_Access(); ?>';
            if(Check_Admin == 1){

                var formData = new FormData($("#live_streaming")[0]);
                $("#dvloader").show();
                $.ajax({
                    type: 'POST',
                    url: '{{ route("settingLiveStreaming") }}',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(resp) {
                        $("#dvloader").hide();
                        $("html, body").animate({
                            scrollTop: 0
                        }, "swing");
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
        // DeepAR
        function deeparsave() {

            var Check_Admin = '<?php echo Check_Admin_Access(); ?>';
            if(Check_Admin == 1){

                var formData = new FormData($("#deepar_save")[0]);
                $("#dvloader").show();
                $.ajax({
                    type: 'POST',
                    url: '{{ route("settingDeepAR") }}',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(resp) {
                        $("#dvloader").hide();
                        $("html, body").animate({
                            scrollTop: 0
                        }, "swing");
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
        $(document).ready(function() {

            $("#sight_engine_concepts").select2({
                placeholder: "Select Concepts"
            });

            var sight_engine_status = $('input[type=radio][name=sight_engine_status]:checked').val();
            if (sight_engine_status == 1) {

                $(".sight_engine_content").show();
            } else if (sight_engine_status == 0) {

                $(".sight_engine_content").hide();
            }
            $('input[type=radio][name=sight_engine_status]').change(function() {
                if (this.value == 1) {
                    $(".sight_engine_content").show();
                } else if (this.value == 0) {
                    $(".sight_engine_content").hide();
                }
            });
        });
        
        // Multipal Img Show 
        $(document).on('change', '.social_img', function(){
            readURL(this, this.id);
        });
        $(document).on('change', '.on_boarding_img', function(){
            readURL(this, this.id);
        });
        function readURL(input, id) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                 
                reader.onload = function (e) {
                    $('#link_img_'+id).attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Add Link Part
        var i = -1;
        function add_more_link(){

            var data = '<div class="social_part">';
                data += '<div class="row col-md-12">';
                data += '<div class="form-group col-md-3">';
                data += '<label>Name</label>';
                data += '<input type="text" name="name[]" class="form-control" placeholder="Enter URL Name">';
                data += '</div>';
                data += '<div class="form-group col-md-3">';
                data += '<label>URL</label>';
                data += '<input type="url" name="url[]" class="form-control" placeholder="Enter URL">';
                data += '</div>';
                data += '<div class="form-group col-lg-3">';
                data += '<label>Icon</label>';
                data += '<input type="file" name="image[]" class="form-control import-file social_img" id="social_img_'+i+'" accept=".png, .jpg, .jpeg">';
                data += '<input type="hidden" name="old_image[]" value="">';
                data += '</div>';
                data += '<div class="form-group col-md-2">';
                data += '<div class="custom-file">';
                data += '<img src="{{asset("assets/imgs/upload_img.png")}}" style="height: 90px; width: 90px;" class="img-thumbnail" id="link_img_social_img_'+i+'">';
                data += '</div>';
                data += '</div>';
                data += '<div class="col-md-1 mt-2">';
                data += '<div class="flex-grow-1 px-5 d-inline-flex">';
                data += '<div class="change mr-3 mt-4" id="add_btn" title="Remove">';
                data += '<a class="btn btn-danger add-more text-white remove_link">-</a>';
                data += '</div>';
                data += '</div>';
                data += '</div>';
                data += '</div>';
                data += '</div>';

            $('.after-add-more').append(data);
            i--;
            $("html, body").animate({ scrollTop: $(document).height() }, "slow");
        }
        // Remove Link Part
        $("body").on("click", ".remove_link", function(e) {
            $(this).parents('.social_part').remove();
        });

        // Save Social Link
        function social_link() {
            var Check_Admin = '<?php echo Check_Admin_Access(); ?>';
            if(Check_Admin == 1){
                var formData = new FormData($("#social_link")[0]);
                $("#dvloader").show();
                $.ajax({
                    type: 'POST',
                    url: '{{ route("settingSocialLink") }}',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(resp) {
                        $("#dvloader").hide();
                        get_responce_message(resp, 'app_setting', '{{ route("setting") }}');
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

        // OnBoarding Screen Add-Remove Link Part
        var i = -1;
        function add_more_screen(){

            var data = '<div class="onboarding_part">';
                data += '<div class="row col-md-12">';
                data += '<div class="form-group col-md-6">';
                data += '<label>Title<span class="text-danger">*</span></label>';
                data += '<input type="text" name="title[]" class="form-control" placeholder="Enter Title">';
                data += '</div>';
                data += '<div class="form-group col-lg-3">';
                data += '<label>Image<span class="text-danger">*</span></label>';
                data += '<input type="file" name="image[]" class="form-control import-file on_boarding_img" id="on_boarding_img_'+i+'" accept=".png, .jpg, .jpeg">';
                data += '<input type="hidden" name="old_image[]" value="">';
                data += '</div>';
                data += '<div class="form-group col-md-1">';
                data += '<div class="custom-file">';
                data += '<img src="{{asset("assets/imgs/upload_img.png")}}" style="height: 90px; width: 90px;" class="img-thumbnail" id="link_img_on_boarding_img_'+i+'">';
                data += '</div>';
                data += '</div>';
                data += '<div class="col-md-1 mt-2">';
                data += '<div class="flex-grow-1 px-5 d-inline-flex">';
                data += '<div class="change mr-3 mt-4" id="add_btn" title="Remove">';
                data += '<a class="btn btn-danger add-more text-white remove_on_boarding">-</a>';
                data += '</div>';
                data += '</div>';
                data += '</div>';
                data += '</div>';
                data += '</div>';

            $('.after-add-more-on-boarding').append(data);
            i--;
            $("html, body").animate({ scrollTop: $(document).height() }, "slow");
        }
        $("body").on("click", ".remove_on_boarding", function(e) {
            $(this).parents('.onboarding_part').remove();
        });
        // OnBoarding Screen Save
        function onboarding() {

            var Check_Admin = '<?php echo Check_Admin_Access(); ?>';
            if(Check_Admin == 1){

                $("#dvloader").show();
                var formData = new FormData($("#onboarding_form")[0]);
                $.ajax({
                    type: 'POST',
                    url: '{{ route("settingOnBoardingScreen") }}',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(resp) {
                        $("#dvloader").hide();
                        get_responce_message(resp, 'onboarding_form', '{{ route("setting") }}');
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        $("#dvloader").hide();
                        toastr.error(errorThrown.msg, 'Failed');
                    }
                });
            } else {
                toastr.error('You have no right to Add, Edit and Delete.');
            }
        }


       
    </script>
@endsection