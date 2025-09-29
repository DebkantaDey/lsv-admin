@extends('admin.layout.page-app')
@section('page_title', 'Earning Dashboard')

@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">{{__('Label.Dashboard')}}</h1>

            <!-- First Counter -->
            <div class="row counter-row">
                <div class="col-6 col-sm-4 col-md col-lg-4 col-xl">
                    <div class="db-color-card color1-card">
                        <i class="fa-solid fa-money-bill-1 fa-4x card-icon"></i>
                        <div class="dropdown dropright">
                            <a href="#" class="btn head-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa-solid fa-ellipsis-vertical fa-xl text-dark dot-icon mr-2"></i>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item color1-viewall" href="{{ route('transaction.index') }}">{{__('Label.View_All')}}</a>
                            </div>
                        </div>
                        <h2 class="counter mt-0">
                            <p class="p-0 m-0 counting" data-count="{{No_Format($CurrentMounthCount ?? 00)}}">{{No_Format($CurrentMounthCount ?? 00)}}</p>
                            <span style="font-size: 20px;">Month Earnings({{Currency_Code()}})</span>
                        </h2>
                    </div>
                </div>
                <div class="col-6 col-sm-4 col-md col-lg-4 col-xl">
                    <div class="db-color-card color2-card">
                        <i class="fa-solid fa-money-bill fa-4x card-icon"></i>
                        <div class="dropdown dropright">
                            <a href="#" class="btn head-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa-solid fa-ellipsis-vertical fa-xl text-dark dot-icon mr-2"></i>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item color2-viewall" href="{{ route('transaction.index') }}">{{__('Label.View_All')}}</a>
                            </div>
                        </div>
                        <h2 class="counter mt-4">
                            <p class="p-0 m-0 counting" data-count="{{No_Format($TransactionCount ?? 00)}}">{{No_Format($TransactionCount ?? 00)}}</p>
                            <span>Earnings ({{Currency_Code()}})</span>
                        </h2>
                    </div>
                </div>
                <div class="col-6 col-sm-4 col-md col-lg-4 col-xl">
                    <div class="db-color-card color3-card">
                        <i class="fa-solid fa-box-archive fa-4x card-icon"></i>
                        <div class="dropdown dropright">
                            <a href="#" class="btn head-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa-solid fa-ellipsis-vertical fa-xl text-dark dot-icon mr-2"></i>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item color3-viewall" href="{{ route('package.index') }}">{{__('Label.View_All')}}</a>
                            </div>
                        </div>
                        <h2 class="counter">
                            <p class="p-0 m-0 counting" data-count="{{No_Format($PackageCount ?? 00)}}">{{No_Format($PackageCount ?? 00)}}</p>
                            <span>Package</span>
                        </h2>
                    </div>
                </div>
                <div class="col-6 col-sm-4 col-md col-lg-4 col-xl">
                    <div class="db-color-card color4-card">
                        <i class="fa-solid fa-money-bill-1-wave fa-4x card-icon"></i>
                        <div class="dropdown dropright">
                            <a href="#" class="btn head-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa-solid fa-ellipsis-vertical fa-xl text-dark dot-icon mr-2"></i>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item color4-viewall" href="{{route('renttransaction.index')}}">{{__('Label.View_All')}}</a>
                            </div>
                        </div>
                        <h2 class="counter mt-0">
                            <p class="p-0 m-0 counting" data-count="{{No_Format($CurrentMounthRentCount ?? 0)}}">{{No_Format($CurrentMounthRentCount ?? 0)}}</p>
                            <span style="font-size: 20px;">Month Rent Earning({{Currency_Code()}})</span>
                        </h2>
                    </div>
                </div>
                <div class="col-6 col-sm-4 col-md col-lg-4 col-xl">
                    <div class="db-color-card color5-card">
                        <i class="fa-solid fa-money-bill-wave fa-4x card-icon"></i>
                        <div class="dropdown dropright">
                            <a href="#" class="btn head-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa-solid fa-ellipsis-vertical fa-xl text-dark dot-icon mr-2"></i>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item color5-viewall" href="{{route('renttransaction.index')}}">{{__('Label.View_All')}}</a>
                            </div>
                        </div>
                        <h2 class="counter mt-4">
                            <p class="p-0 m-0 counting" data-count="{{No_Format($RentTransactionCount ?? 0)}}">{{No_Format($RentTransactionCount ?? 0)}}</p>
                            <span style="font-size: 20px;">Rent Earning({{Currency_Code()}})</span>
                        </h2>
                    </div>
                </div>
            </div>
            <!-- Second Counter -->
            <div class="row counter-row">
                <div class="col-6 col-sm-4 col-md col-lg-4 col-xl">
                    <div class="db-color-card color6-card">
                        <i class="fa-solid fa-rectangle-ad fa-4x card-icon"></i>
                        <div class="dropdown dropright">
                            <a href="#" class="btn head-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa-solid fa-ellipsis-vertical fa-xl text-dark dot-icon mr-2"></i>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item color6-viewall" href="{{ route('adtransaction.index') }}">{{__('Label.View_All')}}</a>
                            </div>
                        </div>
                        <h2 class="counter mt-0">
                            <p class="p-0 m-0 counting" data-count="{{No_Format($CurrentMounthAdsCount ?? 00)}}">{{No_Format($CurrentMounthAdsCount ?? 00)}}</p>
                            <span style="font-size: 20px;">Month Ads Earnings({{Currency_Code()}})</span>
                        </h2>
                    </div>
                </div>
                <div class="col-6 col-sm-4 col-md col-lg-4 col-xl">
                    <div class="db-color-card color7-card">
                        <i class="fa-brands fa-adversal fa-4x card-icon"></i>
                        <div class="dropdown dropright">
                            <a href="#" class="btn head-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa-solid fa-ellipsis-vertical fa-xl text-dark dot-icon mr-2"></i>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item color7-viewall" href="{{ route('adtransaction.index') }}">{{__('Label.View_All')}}</a>
                            </div>
                        </div>
                        <h2 class="counter mt-4">
                            <p class="p-0 m-0 counting" data-count="{{No_Format($AdsTransactionCount ?? 00)}}">{{No_Format($AdsTransactionCount ?? 00)}}</p>
                            <span>Ads Earnings({{Currency_Code()}})</span>
                        </h2>
                    </div>
                </div>
                <div class="col-6 col-sm-4 col-md col-lg-4 col-xl">
                    <div class="db-color-card color8-card">
                        <i class="fa-solid fa-circle-dollar-to-slot fa-4x card-icon"></i>
                        <div class="dropdown dropright">
                            <a href="#" class="btn head-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa-solid fa-ellipsis-vertical fa-xl text-dark dot-icon mr-2"></i>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item color8-viewall" href="{{ route('adpackage.index') }}">{{__('Label.View_All')}}</a>
                            </div>
                        </div>
                        <h2 class="counter">
                            <p class="p-0 m-0 counting" data-count="{{No_Format($AdsPackageCount ?? 00)}}">{{No_Format($AdsPackageCount ?? 00)}}</p>
                            <span style="font-size: 20px;">Ads Package</span>
                        </h2>
                    </div>
                </div>
                <div class="col-6 col-sm-4 col-md col-lg-4 col-xl">
                    <div class="db-color-card color9-card">
                        <i class="fa-solid fa-arrow-trend-up fa-4x card-icon"></i>
                        <div class="dropdown dropright">
                            <a href="#" class="btn head-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa-solid fa-ellipsis-vertical fa-xl text-dark dot-icon mr-2"></i>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item color9-viewall" href="{{ route('withdrawal.index') }}">{{__('Label.View_All')}}</a>
                            </div>
                        </div>
                        <h2 class="counter mt-0">
                            <p class="p-0 m-0 counting" data-count="{{No_Format($PendingWithdrawalCount ?? 00)}}">{{No_Format($PendingWithdrawalCount?? 00)}}</p>
                            <span>Pending Withdrawal({{Currency_Code()}})</span>
                        </h2>
                    </div>
                </div>
                <div class="col-6 col-sm-4 col-md col-lg-4 col-xl">
                    <div class="db-color-card color10-card">
                        <i class="fa-solid fa-arrow-trend-down fa-4x card-icon"></i>
                        <div class="dropdown dropright">
                            <a href="#" class="btn head-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa-solid fa-ellipsis-vertical fa-xl text-dark dot-icon mr-2"></i>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item color10-viewall" href="{{ route('withdrawal.index') }}">{{__('Label.View_All')}}</a>
                            </div>
                        </div>
                        <h2 class="counter mt-0">
                            <p class="p-0 m-0 counting" data-count="{{No_Format($CompletedWithdrawalCount ?? 00)}}">{{No_Format($CompletedWithdrawalCount ?? 00)}}</p>
                            <span>Completed Withdrawal</span>
                        </h2>
                    </div>
                </div>
            </div> 

            <!-- Plan Earning Statistice && Rent Earning Statistice -->
            <div class="row mb-2">
                <div class="col-12 col-xl-8 cart-bg">
                    <div class="box-title">
                        <h2 class="title"><i class="fa-solid fa-chart-column fa-lg mr-2"></i>Plan Earning Statistice (Current Year)</h2>
                        <a href="{{ route('transaction.index') }}" class="btn btn-link">{{__('Label.View_All')}}</a>
                    </div>
                    <div class="row">
                        <div class="col-12 col-sm-12">
                            <canvas id="MyChart" width="100%" height="40px"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-xl-4">
                    <div class="video-box pb-2">
                        <div class="box-title mt-0">
                            <h2 class="title"><i class="fa-solid fa-chart-pie fa-lg mr-2"></i>Rent Earning (Current Year)</h2>
                            <a href="{{ route('renttransaction.index') }}" class="btn btn-link">{{__('Label.View_All')}}</a>
                        </div>
                        <div class="summary-table-card mt-2">
                            <canvas id="rent_earning" width="566" height="800" style="display: block; width: 283px; height: 400px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ads Plan Earning Statistice && Withdrawal Statistice -->
            <div class="row mb-2">
                <div class="col-12 col-xl-8 cart-bg">
                    <div class="box-title">
                        <h2 class="title"><i class="fa-solid fa-chart-column fa-lg mr-2"></i>Ads Plan Earning Statistice (Current Year)</h2>
                        <a href="{{ route('adtransaction.index') }}" class="btn btn-link">{{__('Label.View_All')}}</a>
                    </div>
                    <div class="row">
                        <div class="col-12 col-sm-12">
                            <canvas id="MyAdsChart" width="100%" height="40px"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-xl-4">
                    <div class="video-box pb-2">
                        <div class="box-title mt-0">
                            <h2 class="title"><i class="fa-solid fa-chart-pie fa-lg mr-2"></i>Withdrawal (Current Year)</h2>
                            <a href="{{ route('withdrawal.index') }}" class="btn btn-link">{{__('Label.View_All')}}</a>
                        </div>
                        <div class="summary-table-card mt-2">
                            <canvas id="withdrawal_earning" width="566" height="800" style="display: block; width: 283px; height: 400px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('pagescript')
    <script>
        var month = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

        // Plan Earning Statistice
        $(function() {
            //get the pie chart canvas
            var cData = JSON.parse(`<?php echo $package; ?>`);
            var ctx = $("#MyChart");
            var backcolor = ["#4e45b8", "#0b284d", "#173325", "#360331", "#2A445E", "#9b19f5", "#00bfa0", "#6D3A74", "#0a3603",  "#441552", "#349beb", "#b30000"];
            const datasetValue = [];
            for (let i = 0; i < cData['label'].length; i++) {
                datasetValue[i] = {
                    label: cData['label'][i],
                    data: cData['sum'][i],
                    backgroundColor: backcolor[i],
                }
            }
            //bar chart data
            var data = {
                labels: month,
                datasets: datasetValue
            };
            //options
            var options = {
                responsive: true,
                legend: {
                    title: "text",
                    display: true,
                    position: 'top',
                    labels: {
                        fontSize: 16,
                        fontColor: "#000000",
                    }
                },
                scales: {
                    yAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: 'Amount',
                            fontSize: 16,
                            fontColor: "#000000",
                        },
                    }],
                    xAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: 'Month',
                            fontSize: 16,
                            fontColor: "#000000",
                        }
                    }]
                }
            };
            //create bar Chart class object
            var chart1 = new Chart(ctx, {
                type: "bar",
                data: data,
                options: options
            });
        });

        // Rent Earning Statistice
        var rent_ctx = document.getElementById("rent_earning");
        var rent_cData = JSON.parse(`<?php echo $rent_earning; ?>`);
        var withdrawal_Chart = new Chart(rent_ctx, {
            type: 'doughnut',
            data: {
                labels: month,
                datasets: [{    
                    data: rent_cData['sum'], // Specify the data values array
                    backgroundColor: [ 'rgb(255, 99, 132)', 'rgb(75, 192, 192)', 'rgb(255, 205, 86)', '#b04645', 'rgb(201, 203, 207)', 'rgb(54, 162, 235)', 'rgb(153, 102, 255)','rgb(255, 159, 64)', '#a7e0a4', '#e876d3', '#35b03b', '#a19135'], // Add custom color background (Points and Fill)
                    borderWidth: 1 // Specify bar border width
                }]},         
            options: {
                responsive: true, // Instruct chart js to respond nicely.
                maintainAspectRatio: false, // Add to prevent default behaviour of full-width/height 
                legend: {
                    title: "text",
                    display: true,
                    position: 'bottom',
                    labels: {
                        fontSize: 11,
                        fontColor: "#000000",
                    }
                },
            }
        });

        // Ads Plan Earning Statistice
        $(function() {
            //get the pie chart canvas
            var ads_cData = JSON.parse(`<?php echo $ads_package; ?>`);
            var ads_ctx = $("#MyAdsChart");
            var backcolor = ["#37064f", "#064a4f", "#b30000", "#033614", "#701711", "#9b3eb5", "#360312", "#687011", "#42b53e", "#362503", "#441552", "#349beb"];
            const datasetValue = [];
            for (let i = 0; i < ads_cData['label'].length; i++) {
                datasetValue[i] = {
                    label: ads_cData['label'][i],
                    data: ads_cData['sum'][i],
                    backgroundColor: backcolor[i],
                }
            }
            //bar chart data
            var ads_data = {
                labels: month,
                datasets: datasetValue
            };
            //options
            var ads_options = {
                responsive: true,
                legend: {
                    title: "text",
                    display: true,
                    position: 'top',
                    labels: {
                        fontSize: 16,
                        fontColor: "#000000",
                    }
                },
                scales: {
                    yAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: 'Amount',
                            fontSize: 16,
                            fontColor: "#000000",
                        },
                    }],
                    xAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: 'Month',
                            fontSize: 16,
                            fontColor: "#000000",
                        }
                    }]
                }
            };
            //create bar Chart class object
            var chart2 = new Chart(ads_ctx, {
                type: "bar",
                data: ads_data,
                options: ads_options
            });
        });

        // Withdrawal Earning Statistice
        var withdrawal_ctx = document.getElementById("withdrawal_earning");
        var withdrawal_cData = JSON.parse(`<?php echo $withdrawal_earning; ?>`);
        var withdrawal_Chart = new Chart(withdrawal_ctx, {
            type: 'doughnut',
            data: {
                labels: ["Pending", "Completed"],
                datasets: [{    
                    data: withdrawal_cData['sum'], // Specify the data values array
                    backgroundColor: ['#c93636', '#37b337'], // Add custom color background (Points and Fill)
                    borderWidth: 1 // Specify bar border width
                }]},         
            options: {
                responsive: true, // Instruct chart js to respond nicely.
                maintainAspectRatio: false, // Add to prevent default behaviour of full-width/height 
                legend: {
                    title: "text",
                    display: true,
                    position: 'bottom',
                    labels: {
                        fontSize: 11,
                        fontColor: "#000000",
                    }
                },
            }
        });
    </script>
@endsection