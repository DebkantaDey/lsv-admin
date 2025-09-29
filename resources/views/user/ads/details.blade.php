@extends('user.layout.page-app')
@section('page_title', 'Ads Details')

@section('content')
    @include('user.layout.sidebar')

    <div class="right-content">
        @include('user.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">Ads</h1>

            <div class="border-bottom row mb-3">
                <div class="col-sm-10">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">{{__('Label.Dashboard')}}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('uads.index') }}">Ads</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Details</li>
                    </ol>
                </div>
                <div class="col-sm-2 d-flex align-items-center justify-content-end">
                    <a href="{{ route('uads.index') }}" class="btn btn-default mw-150" style="margin-top: -14px;">Ads List</a>
                </div>
            </div>

            <div class="card custom-border-card">
                <table class="table table-striped table-hover w-50 table-bordered text-center ml-auto mr-auto">
                    <thead>
                        <tr class="table-info">
                            <th colspan="2">Ads Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Title</td>
                            <td>{{$data->title}}</td>
                        </tr>
                        <tr>
                            <td>Budget</td>
                            <td>{{$data->budget}}</td>
                        </tr>
                        <tr>
                            <td>Total View</td>
                            <td>{{$total_ads_cpv}}</td>
                        </tr>
                        <tr>
                            <td>Total Click</td>
                            <td>{{$total_ads_cpc}}</td>
                        </tr>
                        <tr>
                            <td>Total View Coin</td>
                            <td>{{$total_ads_cpv_coin}}</td>
                        </tr>
                        <tr>
                            <td>Total Click Coin</td>
                            <td>{{$total_ads_cpc_coin}}</td>
                        </tr>
                        <tr>
                            <td>Total Used Budget</td>
                            <td>{{$total_use_budget}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection