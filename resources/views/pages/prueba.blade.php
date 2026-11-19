@extends('layouts.master')

@section('title', 'Dashboard')

@section('vendor-style')
    @vite('resources/assets/vendor/libs/apex-charts/apex-charts.scss')
@endsection

@section('vendor-script')
    @vite('resources/assets/vendor/libs/apex-charts/apexcharts.js')
@endsection

@section('page-script')
    @vite('resources/assets/js/dashboards-analytics.js')
@endsection

@section('content')
    <div id="mainContent" class="main flex-grow p-10 ml-40 transition-all duration-300">

        <div class="container-xxl flex-grow-1 container-p-y">

            <div class="row g-6">
                <!-- Card Border Shadow -->
                <div class="col-lg-3 col-sm-6">
                    <div class="card card-border-shadow-primary h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-2">
                                <div class="avatar me-4">
                                    <span class="avatar-initial rounded bg-label-primary"><i
                                            class="icon-base bx bxs-truck icon-lg"></i></span>
                                </div>
                                <h4 class="mb-0">42</h4>
                            </div>
                            <p class="mb-2">On route vehicles</p>
                            <p class="mb-0">
                                <span class="text-heading fw-medium me-2">+18.2%</span>
                                <span class="text-body-secondary">than last week</span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="card card-border-shadow-warning h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-2">
                                <div class="avatar me-4">
                                    <span class="avatar-initial rounded bg-label-warning"><i
                                            class="icon-base bx bx-error icon-lg"></i></span>
                                </div>
                                <h4 class="mb-0">8</h4>
                            </div>
                            <p class="mb-2">Vehicles with errors</p>
                            <p class="mb-0">
                                <span class="text-heading fw-medium me-2">-8.7%</span>
                                <span class="text-body-secondary">than last week</span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="card card-border-shadow-danger h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-2">
                                <div class="avatar me-4">
                                    <span class="avatar-initial rounded bg-label-danger"><i
                                            class="icon-base bx bx-git-repo-forked icon-lg"></i></span>
                                </div>
                                <h4 class="mb-0">27</h4>
                            </div>
                            <p class="mb-2">Deviated from route</p>
                            <p class="mb-0">
                                <span class="text-heading fw-medium me-2">+4.3%</span>
                                <span class="text-body-secondary">than last week</span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="card card-border-shadow-info h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-2">
                                <div class="avatar me-4">
                                    <span class="avatar-initial rounded bg-label-info"><i
                                            class="icon-base bx bx-time-five icon-lg"></i></span>
                                </div>
                                <h4 class="mb-0">13</h4>
                            </div>
                            <p class="mb-2">Late vehicles</p>
                            <p class="mb-0">
                                <span class="text-heading fw-medium me-2">-2.5%</span>
                                <span class="text-body-secondary">than last week</span>
                            </p>
                        </div>
                    </div>
                </div>
                <!--/ Card Border Shadow -->
                <!-- Vehicles overview -->
                <div class="col-xxl-6">
                    <div class="card h-100">
                        <div class="card-header d-flex align-items-center justify-content-between gap-2">
                            <div class="card-title mb-0">
                                <h5 class="m-0 me-2">Vehicles Overview</h5>
                            </div>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="vehiclesOverview" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="icon-base bx bx-dots-vertical-rounded icon-lg text-body-secondary"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="vehiclesOverview">
                                    <a class="dropdown-item" href="javascript:void(0);">Select All</a>
                                    <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                                    <a class="dropdown-item" href="javascript:void(0);">Share</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-none d-lg-flex vehicles-progress-labels mb-6">
                                <div class="vehicles-progress-label on-the-way-text" style="width: 39.7%;">On the way</div>
                                <div class="vehicles-progress-label unloading-text" style="width: 28.3%;">Unloading</div>
                                <div class="vehicles-progress-label loading-text" style="width: 17.4%;">Loading</div>
                                <div class="vehicles-progress-label waiting-text text-nowrap" style="width: 14.6%;">Waiting
                                </div>
                            </div>
                            <div class="vehicles-overview-progress progress rounded-3 mb-6 bg-transparent overflow-hidden"
                                style="height: 46px;">
                                <div class="progress-bar fw-medium text-start shadow-none bg-lighter text-heading px-4 rounded-0"
                                    role="progressbar" style="width: 39.7%" aria-valuenow="39.7" aria-valuemin="0"
                                    aria-valuemax="100">39.7%</div>
                                <div class="progress-bar fw-medium text-start shadow-none bg-primary px-4"
                                    role="progressbar" style="width: 28.3%" aria-valuenow="28.3" aria-valuemin="0"
                                    aria-valuemax="100">28.3%</div>
                                <div class="progress-bar fw-medium text-start shadow-none text-bg-info px-2 px-sm-4"
                                    role="progressbar" style="width: 17.4%" aria-valuenow="17.4" aria-valuemin="0"
                                    aria-valuemax="100">17.4%</div>
                                <div class="progress-bar fw-medium text-start shadow-none snackbar text-paper px-1 px-sm-3 rounded-0 px-lg-4"
                                    role="progressbar" style="width: 14.6%" aria-valuenow="14.6" aria-valuemin="0"
                                    aria-valuemax="100">14.6%</div>
                            </div>
                            <div class="table-responsive">
                                <table class="table card-table table-border-top-0">
                                    <tbody class="table-border-bottom-0">
                                        <tr>
                                            <td class="w-50 ps-0">
                                                <div class="d-flex justify-content-start align-items-center">
                                                    <div class="me-2">
                                                        <i class="icon-base bx bx-car icon-lg text-heading"></i>
                                                    </div>
                                                    <h6 class="mb-0 fw-normal">On the way</h6>
                                                </div>
                                            </td>
                                            <td class="text-end pe-0 text-nowrap">
                                                <h6 class="mb-0">2hr 10min</h6>
                                            </td>
                                            <td class="text-end pe-0">
                                                <span>39.7%</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="w-50 ps-0">
                                                <div class="d-flex justify-content-start align-items-center">
                                                    <div class="me-2">
                                                        <i
                                                            class="icon-base bx bx-down-arrow-circle icon-lg text-heading"></i>
                                                    </div>
                                                    <h6 class="mb-0 fw-normal">Unloading</h6>
                                                </div>
                                            </td>
                                            <td class="text-end pe-0 text-nowrap">
                                                <h6 class="mb-0">3hr 15min</h6>
                                            </td>
                                            <td class="text-end pe-0">
                                                <span>28.3%</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="w-50 ps-0">
                                                <div class="d-flex justify-content-start align-items-center">
                                                    <div class="me-2">
                                                        <i
                                                            class="icon-base bx bx-up-arrow-circle icon-lg text-heading"></i>
                                                    </div>
                                                    <h6 class="mb-0 fw-normal">Loading</h6>
                                                </div>
                                            </td>
                                            <td class="text-end pe-0 text-nowrap">
                                                <h6 class="mb-0">1hr 24min</h6>
                                            </td>
                                            <td class="text-end pe-0">
                                                <span>17.4%</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="w-50 ps-0">
                                                <div class="d-flex justify-content-start align-items-center">
                                                    <div class="me-2">
                                                        <i class="icon-base bx bx-time-five icon-lg text-heading"></i>
                                                    </div>
                                                    <h6 class="mb-0 fw-normal">Waiting</h6>
                                                </div>
                                            </td>
                                            <td class="text-end pe-0 text-nowrap">
                                                <h6 class="mb-0">5hr 19min</h6>
                                            </td>
                                            <td class="text-end pe-0">
                                                <span>14.6%</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/ Vehicles overview -->
                <!-- Shipment statistics-->
                <div class="col-xxl-6 col-lg-7">
                    <div class="card h-100">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <div class="card-title mb-0">
                                <h5 class="mb-1">Shipment statistics</h5>
                                <p class="card-subtitle">Total number of deliveries 23.8k</p>
                            </div>
                            <div class="btn-group">
                                <button type="button" class="btn btn-label-primary">January</button>
                                <button type="button" class="btn btn-label-primary dropdown-toggle dropdown-toggle-split"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="visually-hidden">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="javascript:void(0);">January</a></li>
                                    <li><a class="dropdown-item" href="javascript:void(0);">February</a></li>
                                    <li><a class="dropdown-item" href="javascript:void(0);">March</a></li>
                                    <li><a class="dropdown-item" href="javascript:void(0);">April</a></li>
                                    <li><a class="dropdown-item" href="javascript:void(0);">May</a></li>
                                    <li><a class="dropdown-item" href="javascript:void(0);">June</a></li>
                                    <li><a class="dropdown-item" href="javascript:void(0);">July</a></li>
                                    <li><a class="dropdown-item" href="javascript:void(0);">August</a></li>
                                    <li><a class="dropdown-item" href="javascript:void(0);">September</a></li>
                                    <li><a class="dropdown-item" href="javascript:void(0);">October</a></li>
                                    <li><a class="dropdown-item" href="javascript:void(0);">November</a></li>
                                    <li><a class="dropdown-item" href="javascript:void(0);">December</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="shipmentStatisticsChart" style="min-height: 320px;" class="">
                                <div id="apexchartshm32fhmq"
                                    class="apexcharts-canvas apexchartshm32fhmq apexcharts-theme-"
                                    style="width: 485px; height: 320px;"><svg xmlns="http://www.w3.org/2000/svg"
                                        version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" class="apexcharts-svg"
                                        xmlns:data="ApexChartsNS" transform="translate(0, 0)" width="485"
                                        height="320">
                                        <foreignObject x="0" y="0" width="485" height="320">
                                            <div class="apexcharts-legend apexcharts-align-center apx-legend-position-bottom"
                                                xmlns="http://www.w3.org/1999/xhtml"
                                                style="height: 40px; right: 0px; position: absolute; left: 0px; top: 280px;">
                                                <div class="apexcharts-legend-series" rel="1"
                                                    seriesname="Shipment" data:collapsed="false"
                                                    style="margin: 0px 10px;"><span class="apexcharts-legend-marker"
                                                        rel="1" data:collapsed="false"
                                                        style="height: 8px; width: 8px; left: -3px; top: 0px;"><svg
                                                            xmlns="http://www.w3.org/2000/svg" version="1.1"
                                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="100%"
                                                            height="100%">
                                                            <path d="M 0, 0
               m -4, 0
               a 4,4 0 1,0 8,0
               a 4,4 0 1,0 -8,0" fill="var(--bs-warning)" fill-opacity="1" stroke="var(--bs-primary)"
                                                                stroke-opacity="0.9" stroke-linecap="round"
                                                                stroke-width="0" stroke-dasharray="0" cx="0"
                                                                cy="0" shape="circle"
                                                                class="apexcharts-legend-marker apexcharts-marker apexcharts-marker-circle"
                                                                style="transform: translate(50%, 50%);"></path>
                                                        </svg></span><span class="apexcharts-legend-text" rel="1"
                                                        i="0" data:default-text="Shipment" data:collapsed="false"
                                                        style="color: var(--bs-heading-color); font-size: 13px; font-weight: 400; font-family: var(--bs-font-family-base);">Shipment</span>
                                                </div>
                                                <div class="apexcharts-legend-series" rel="2"
                                                    seriesname="Delivery" data:collapsed="false"
                                                    style="margin: 0px 10px;"><span class="apexcharts-legend-marker"
                                                        rel="2" data:collapsed="false"
                                                        style="height: 8px; width: 8px; left: -3px; top: 0px;"><svg
                                                            xmlns="http://www.w3.org/2000/svg" version="1.1"
                                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="100%"
                                                            height="100%">
                                                            <path d="M 0, 0
               m -4, 0
               a 4,4 0 1,0 8,0
               a 4,4 0 1,0 -8,0" fill="var(--bs-primary)" fill-opacity="1" stroke="var(--bs-primary)"
                                                                stroke-opacity="0.9" stroke-linecap="round"
                                                                stroke-width="0" stroke-dasharray="0" cx="0"
                                                                cy="0" shape="circle"
                                                                class="apexcharts-legend-marker apexcharts-marker apexcharts-marker-circle"
                                                                style="transform: translate(50%, 50%);"></path>
                                                        </svg></span><span class="apexcharts-legend-text" rel="2"
                                                        i="1" data:default-text="Delivery" data:collapsed="false"
                                                        style="color: var(--bs-heading-color); font-size: 13px; font-weight: 400; font-family: var(--bs-font-family-base);">Delivery</span>
                                                </div>
                                            </div>
                                            <style type="text/css">
                                                .apexcharts-flip-y {
                                                    transform: scaleY(-1) translateY(-100%);
                                                    transform-origin: top;
                                                    transform-box: fill-box;
                                                }

                                                .apexcharts-flip-x {
                                                    transform: scaleX(-1);
                                                    transform-origin: center;
                                                    transform-box: fill-box;
                                                }

                                                .apexcharts-legend {
                                                    display: flex;
                                                    overflow: auto;
                                                    padding: 0 10px;
                                                }

                                                .apexcharts-legend.apexcharts-legend-group-horizontal {
                                                    flex-direction: column;
                                                }

                                                .apexcharts-legend-group {
                                                    display: flex;
                                                }

                                                .apexcharts-legend-group-vertical {
                                                    flex-direction: column-reverse;
                                                }

                                                .apexcharts-legend.apx-legend-position-bottom,
                                                .apexcharts-legend.apx-legend-position-top {
                                                    flex-wrap: wrap
                                                }

                                                .apexcharts-legend.apx-legend-position-right,
                                                .apexcharts-legend.apx-legend-position-left {
                                                    flex-direction: column;
                                                    bottom: 0;
                                                }

                                                .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-left,
                                                .apexcharts-legend.apx-legend-position-top.apexcharts-align-left,
                                                .apexcharts-legend.apx-legend-position-right,
                                                .apexcharts-legend.apx-legend-position-left {
                                                    justify-content: flex-start;
                                                    align-items: flex-start;
                                                }

                                                .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-center,
                                                .apexcharts-legend.apx-legend-position-top.apexcharts-align-center {
                                                    justify-content: center;
                                                    align-items: center;
                                                }

                                                .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-right,
                                                .apexcharts-legend.apx-legend-position-top.apexcharts-align-right {
                                                    justify-content: flex-end;
                                                    align-items: flex-end;
                                                }

                                                .apexcharts-legend-series {
                                                    cursor: pointer;
                                                    line-height: normal;
                                                    display: flex;
                                                    align-items: center;
                                                }

                                                .apexcharts-legend-text {
                                                    position: relative;
                                                    font-size: 14px;
                                                }

                                                .apexcharts-legend-text *,
                                                .apexcharts-legend-marker * {
                                                    pointer-events: none;
                                                }

                                                .apexcharts-legend-marker {
                                                    position: relative;
                                                    display: flex;
                                                    align-items: center;
                                                    justify-content: center;
                                                    cursor: pointer;
                                                    margin-right: 1px;
                                                }

                                                .apexcharts-legend-series.apexcharts-no-click {
                                                    cursor: auto;
                                                }

                                                .apexcharts-legend .apexcharts-hidden-zero-series,
                                                .apexcharts-legend .apexcharts-hidden-null-series {
                                                    display: none !important;
                                                }

                                                .apexcharts-inactive-legend {
                                                    opacity: 0.45;
                                                }
                                            </style>
                                        </foreignObject>
                                        <rect width="0" height="0" x="0" y="0" rx="0" ry="0"
                                            opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0"
                                            fill="#fefefe"></rect>
                                        <g class="apexcharts-datalabels-group" transform="translate(0, 0) scale(1)"></g>
                                        <g class="apexcharts-datalabels-group" transform="translate(0, 0) scale(1)"></g>
                                        <g class="apexcharts-yaxis" rel="0"
                                            transform="translate(34.44219970703125, 0)">
                                            <g class="apexcharts-yaxis-texts-g"><text x="20" y="34.333333333333336"
                                                    text-anchor="end" dominant-baseline="auto" font-size="13px"
                                                    font-family="var(--bs-font-family-base)" font-weight="400"
                                                    fill="var(--bs-secondary-color)"
                                                    class="apexcharts-text apexcharts-yaxis-label "
                                                    style="font-family: var(--bs-font-family-base);">
                                                    <tspan>50%</tspan>
                                                    <title>50%</title>
                                                </text><text x="20" y="88.5529334104856" text-anchor="end"
                                                    dominant-baseline="auto" font-size="13px"
                                                    font-family="var(--bs-font-family-base)" font-weight="400"
                                                    fill="var(--bs-secondary-color)"
                                                    class="apexcharts-text apexcharts-yaxis-label "
                                                    style="font-family: var(--bs-font-family-base);">
                                                    <tspan>37.5%</tspan>
                                                    <title>37.5%</title>
                                                </text><text x="20" y="142.77253348763784" text-anchor="end"
                                                    dominant-baseline="auto" font-size="13px"
                                                    font-family="var(--bs-font-family-base)" font-weight="400"
                                                    fill="var(--bs-secondary-color)"
                                                    class="apexcharts-text apexcharts-yaxis-label "
                                                    style="font-family: var(--bs-font-family-base);">
                                                    <tspan>25%</tspan>
                                                    <title>25%</title>
                                                </text><text x="20" y="196.9921335647901" text-anchor="end"
                                                    dominant-baseline="auto" font-size="13px"
                                                    font-family="var(--bs-font-family-base)" font-weight="400"
                                                    fill="var(--bs-secondary-color)"
                                                    class="apexcharts-text apexcharts-yaxis-label "
                                                    style="font-family: var(--bs-font-family-base);">
                                                    <tspan>12.5%</tspan>
                                                    <title>12.5%</title>
                                                </text><text x="20" y="251.21173364194235" text-anchor="end"
                                                    dominant-baseline="auto" font-size="13px"
                                                    font-family="var(--bs-font-family-base)" font-weight="400"
                                                    fill="var(--bs-secondary-color)"
                                                    class="apexcharts-text apexcharts-yaxis-label "
                                                    style="font-family: var(--bs-font-family-base);">
                                                    <tspan>0%</tspan>
                                                    <title>0%</title>
                                                </text></g>
                                        </g>
                                        <g class="apexcharts-inner apexcharts-graphical"
                                            transform="translate(77.59307820002238, 30)">
                                            <defs>
                                                <clipPath id="gridRectMaskhm32fhmq">
                                                    <rect width="368.22459780375164" height="216.878400308609" x="0" y="0"
                                                        rx="0" ry="0" opacity="1" stroke-width="0"
                                                        stroke="none" stroke-dasharray="0" fill="#fff"></rect>
                                                </clipPath>
                                                <clipPath id="gridRectBarMaskhm32fhmq">
                                                    <rect width="401.5263547897339" height="223.878400308609"
                                                        x="-16.650878492991133" y="-3.5" rx="0" ry="0"
                                                        opacity="1" stroke-width="0" stroke="none"
                                                        stroke-dasharray="0" fill="#fff"></rect>
                                                </clipPath>
                                                <clipPath id="gridRectMarkerMaskhm32fhmq">
                                                    <rect width="380.22459780375164" height="228.878400308609" x="-6"
                                                        y="-6" rx="0" ry="0" opacity="1"
                                                        stroke-width="0" stroke="none" stroke-dasharray="0"
                                                        fill="#fff"></rect>
                                                </clipPath>
                                                <clipPath id="forecastMaskhm32fhmq"></clipPath>
                                                <clipPath id="nonForecastMaskhm32fhmq"></clipPath>
                                            </defs>
                                            <line x1="0" y1="0" x2="0" y2="216.878400308609"
                                                stroke="#b6b6b6" stroke-dasharray="3" stroke-linecap="butt"
                                                class="apexcharts-xcrosshairs" x="0" y="0" width="1"
                                                height="216.878400308609" fill="#b1b9c4" filter="none"
                                                fill-opacity="0.9" stroke-width="1"></line>
                                            <g class="apexcharts-grid">
                                                <g class="apexcharts-gridlines-horizontal">
                                                    <line x1="-13.150878492991131" y1="54.21960007715225"
                                                        x2="381.37547629674276" y2="54.21960007715225"
                                                        stroke="var(--bs-border-color)" stroke-dasharray="8"
                                                        stroke-linecap="butt" class="apexcharts-gridline"></line>
                                                    <line x1="-13.150878492991131" y1="108.4392001543045"
                                                        x2="381.37547629674276" y2="108.4392001543045"
                                                        stroke="var(--bs-border-color)" stroke-dasharray="8"
                                                        stroke-linecap="butt" class="apexcharts-gridline"></line>
                                                    <line x1="-13.150878492991131" y1="162.65880023145675"
                                                        x2="381.37547629674276" y2="162.65880023145675"
                                                        stroke="var(--bs-border-color)" stroke-dasharray="8"
                                                        stroke-linecap="butt" class="apexcharts-gridline"></line>
                                                </g>
                                                <g class="apexcharts-gridlines-vertical"></g>
                                                <line x1="0" y1="216.878400308609" x2="368.22459780375164"
                                                    y2="216.878400308609" stroke="transparent" stroke-dasharray="0"
                                                    stroke-linecap="butt"></line>
                                                <line x1="0" y1="1" x2="0" y2="216.878400308609"
                                                    stroke="transparent" stroke-dasharray="0" stroke-linecap="butt">
                                                </line>
                                            </g>
                                            <g class="apexcharts-grid-borders">
                                                <line x1="-13.150878492991131" y1="0" x2="381.37547629674276"
                                                    y2="0" stroke="var(--bs-border-color)" stroke-dasharray="8"
                                                    stroke-linecap="butt" class="apexcharts-gridline"></line>
                                                <line x1="-13.150878492991131" y1="216.878400308609"
                                                    x2="381.37547629674276" y2="216.878400308609"
                                                    stroke="var(--bs-border-color)" stroke-dasharray="8"
                                                    stroke-linecap="butt" class="apexcharts-gridline"></line>
                                            </g>
                                            <g class="apexcharts-bar-series apexcharts-plot-series">
                                                <g class="apexcharts-series" rel="1" seriesName="Shipment"
                                                    data:realIndex="0">
                                                    <path
                                                        d="M -6.137076630062527 212.879400308609 L -6.137076630062527 56.051816074066174 C -6.137076630062527 54.051816074066174 -4.137076630062527 52.051816074066174 -2.137076630062527 52.051816074066174 L 2.137076630062527 52.051816074066174 C 4.137076630062527 52.051816074066174 6.137076630062527 54.051816074066174 6.137076630062527 56.051816074066174 L 6.137076630062527 212.879400308609 C 6.137076630062527 214.879400308609 4.137076630062527 216.879400308609 2.137076630062527 216.879400308609 L -2.137076630062527 216.879400308609 C -4.137076630062527 216.879400308609 -6.137076630062527 214.879400308609 -6.137076630062527 212.879400308609 Z "
                                                        fill="var(--bs-warning)" fill-opacity="1"
                                                        stroke="var(--bs-warning)" stroke-opacity="1"
                                                        stroke-linecap="round" stroke-width="0" stroke-dasharray="0"
                                                        class="apexcharts-bar-area undefined" index="0"
                                                        clip-path="url(#gridRectBarMaskhm32fhmq)"
                                                        pathTo="M -6.137076630062527 212.879400308609 L -6.137076630062527 56.051816074066174 C -6.137076630062527 54.051816074066174 -4.137076630062527 52.051816074066174 -2.137076630062527 52.051816074066174 L 2.137076630062527 52.051816074066174 C 4.137076630062527 52.051816074066174 6.137076630062527 54.051816074066174 6.137076630062527 56.051816074066174 L 6.137076630062527 212.879400308609 C 6.137076630062527 214.879400308609 4.137076630062527 216.879400308609 2.137076630062527 216.879400308609 L -2.137076630062527 216.879400308609 C -4.137076630062527 216.879400308609 -6.137076630062527 214.879400308609 -6.137076630062527 212.879400308609 Z "
                                                        pathFrom="M -6.137076630062527 216.879400308609 L -6.137076630062527 216.879400308609 L 6.137076630062527 216.879400308609 L 6.137076630062527 216.879400308609 L 6.137076630062527 216.879400308609 L 6.137076630062527 216.879400308609 L 6.137076630062527 216.879400308609 L -6.137076630062527 216.879400308609 Z"
                                                        cy="52.05081607406618" cx="6.137076630062527" j="0"
                                                        val="38" barHeight="164.82758423454283"
                                                        barWidth="12.274153260125054"></path>
                                                    <path
                                                        d="M 34.77676757035432 212.879400308609 L 34.77676757035432 25.688840030860913 C 34.77676757035432 23.688840030860913 36.77676757035432 21.688840030860913 38.77676757035432 21.688840030860913 L 43.05092083047938 21.688840030860913 C 45.05092083047938 21.688840030860913 47.05092083047938 23.688840030860913 47.05092083047938 25.688840030860913 L 47.05092083047938 212.879400308609 C 47.05092083047938 214.879400308609 45.05092083047938 216.879400308609 43.05092083047938 216.879400308609 L 38.77676757035432 216.879400308609 C 36.77676757035432 216.879400308609 34.77676757035432 214.879400308609 34.77676757035432 212.879400308609 Z "
                                                        fill="var(--bs-warning)" fill-opacity="1"
                                                        stroke="var(--bs-warning)" stroke-opacity="1"
                                                        stroke-linecap="round" stroke-width="0" stroke-dasharray="0"
                                                        class="apexcharts-bar-area undefined" index="0"
                                                        clip-path="url(#gridRectBarMaskhm32fhmq)"
                                                        pathTo="M 34.77676757035432 212.879400308609 L 34.77676757035432 25.688840030860913 C 34.77676757035432 23.688840030860913 36.77676757035432 21.688840030860913 38.77676757035432 21.688840030860913 L 43.05092083047938 21.688840030860913 C 45.05092083047938 21.688840030860913 47.05092083047938 23.688840030860913 47.05092083047938 25.688840030860913 L 47.05092083047938 212.879400308609 C 47.05092083047938 214.879400308609 45.05092083047938 216.879400308609 43.05092083047938 216.879400308609 L 38.77676757035432 216.879400308609 C 36.77676757035432 216.879400308609 34.77676757035432 214.879400308609 34.77676757035432 212.879400308609 Z "
                                                        pathFrom="M 34.77676757035432 216.879400308609 L 34.77676757035432 216.879400308609 L 47.05092083047938 216.879400308609 L 47.05092083047938 216.879400308609 L 47.05092083047938 216.879400308609 L 47.05092083047938 216.879400308609 L 47.05092083047938 216.879400308609 L 34.77676757035432 216.879400308609 Z"
                                                        cy="21.68784003086091" cx="47.05092083047938" j="1"
                                                        val="45" barHeight="195.1905602777481"
                                                        barWidth="12.274153260125054"></path>
                                                    <path
                                                        d="M 75.69061177077117 212.879400308609 L 75.69061177077117 77.73965610492706 C 75.69061177077117 75.73965610492706 77.69061177077117 73.73965610492706 79.69061177077117 73.73965610492706 L 83.96476503089622 73.73965610492706 C 85.96476503089622 73.73965610492706 87.96476503089622 75.73965610492706 87.96476503089622 77.73965610492706 L 87.96476503089622 212.879400308609 C 87.96476503089622 214.879400308609 85.96476503089622 216.879400308609 83.96476503089622 216.879400308609 L 79.69061177077117 216.879400308609 C 77.69061177077117 216.879400308609 75.69061177077117 214.879400308609 75.69061177077117 212.879400308609 Z "
                                                        fill="var(--bs-warning)" fill-opacity="1"
                                                        stroke="var(--bs-warning)" stroke-opacity="1"
                                                        stroke-linecap="round" stroke-width="0" stroke-dasharray="0"
                                                        class="apexcharts-bar-area undefined" index="0"
                                                        clip-path="url(#gridRectBarMaskhm32fhmq)"
                                                        pathTo="M 75.69061177077117 212.879400308609 L 75.69061177077117 77.73965610492706 C 75.69061177077117 75.73965610492706 77.69061177077117 73.73965610492706 79.69061177077117 73.73965610492706 L 83.96476503089622 73.73965610492706 C 85.96476503089622 73.73965610492706 87.96476503089622 75.73965610492706 87.96476503089622 77.73965610492706 L 87.96476503089622 212.879400308609 C 87.96476503089622 214.879400308609 85.96476503089622 216.879400308609 83.96476503089622 216.879400308609 L 79.69061177077117 216.879400308609 C 77.69061177077117 216.879400308609 75.69061177077117 214.879400308609 75.69061177077117 212.879400308609 Z "
                                                        pathFrom="M 75.69061177077117 216.879400308609 L 75.69061177077117 216.879400308609 L 87.96476503089622 216.879400308609 L 87.96476503089622 216.879400308609 L 87.96476503089622 216.879400308609 L 87.96476503089622 216.879400308609 L 87.96476503089622 216.879400308609 L 75.69061177077117 216.879400308609 Z"
                                                        cy="73.73865610492706" cx="87.96476503089622" j="2"
                                                        val="33" barHeight="143.13974420368194"
                                                        barWidth="12.274153260125054"></path>
                                                    <path
                                                        d="M 116.60445597118802 212.879400308609 L 116.60445597118802 56.051816074066174 C 116.60445597118802 54.051816074066174 118.60445597118802 52.051816074066174 120.60445597118802 52.051816074066174 L 124.87860923131308 52.051816074066174 C 126.87860923131308 52.051816074066174 128.87860923131308 54.051816074066174 128.87860923131308 56.051816074066174 L 128.87860923131308 212.879400308609 C 128.87860923131308 214.879400308609 126.87860923131308 216.879400308609 124.87860923131308 216.879400308609 L 120.60445597118802 216.879400308609 C 118.60445597118802 216.879400308609 116.60445597118802 214.879400308609 116.60445597118802 212.879400308609 Z "
                                                        fill="var(--bs-warning)" fill-opacity="1"
                                                        stroke="var(--bs-warning)" stroke-opacity="1"
                                                        stroke-linecap="round" stroke-width="0" stroke-dasharray="0"
                                                        class="apexcharts-bar-area undefined" index="0"
                                                        clip-path="url(#gridRectBarMaskhm32fhmq)"
                                                        pathTo="M 116.60445597118802 212.879400308609 L 116.60445597118802 56.051816074066174 C 116.60445597118802 54.051816074066174 118.60445597118802 52.051816074066174 120.60445597118802 52.051816074066174 L 124.87860923131308 52.051816074066174 C 126.87860923131308 52.051816074066174 128.87860923131308 54.051816074066174 128.87860923131308 56.051816074066174 L 128.87860923131308 212.879400308609 C 128.87860923131308 214.879400308609 126.87860923131308 216.879400308609 124.87860923131308 216.879400308609 L 120.60445597118802 216.879400308609 C 118.60445597118802 216.879400308609 116.60445597118802 214.879400308609 116.60445597118802 212.879400308609 Z "
                                                        pathFrom="M 116.60445597118802 216.879400308609 L 116.60445597118802 216.879400308609 L 128.87860923131308 216.879400308609 L 128.87860923131308 216.879400308609 L 128.87860923131308 216.879400308609 L 128.87860923131308 216.879400308609 L 128.87860923131308 216.879400308609 L 116.60445597118802 216.879400308609 Z"
                                                        cy="52.05081607406618" cx="128.87860923131308" j="3"
                                                        val="38" barHeight="164.82758423454283"
                                                        barWidth="12.274153260125054"></path>
                                                    <path
                                                        d="M 157.51830017160486 212.879400308609 L 157.51830017160486 82.07722411109924 C 157.51830017160486 80.07722411109924 159.51830017160486 78.07722411109924 161.51830017160486 78.07722411109924 L 165.7924534317299 78.07722411109924 C 167.7924534317299 78.07722411109924 169.7924534317299 80.07722411109924 169.7924534317299 82.07722411109924 L 169.7924534317299 212.879400308609 C 169.7924534317299 214.879400308609 167.7924534317299 216.879400308609 165.7924534317299 216.879400308609 L 161.51830017160486 216.879400308609 C 159.51830017160486 216.879400308609 157.51830017160486 214.879400308609 157.51830017160486 212.879400308609 Z "
                                                        fill="var(--bs-warning)" fill-opacity="1"
                                                        stroke="var(--bs-warning)" stroke-opacity="1"
                                                        stroke-linecap="round" stroke-width="0" stroke-dasharray="0"
                                                        class="apexcharts-bar-area undefined" index="0"
                                                        clip-path="url(#gridRectBarMaskhm32fhmq)"
                                                        pathTo="M 157.51830017160486 212.879400308609 L 157.51830017160486 82.07722411109924 C 157.51830017160486 80.07722411109924 159.51830017160486 78.07722411109924 161.51830017160486 78.07722411109924 L 165.7924534317299 78.07722411109924 C 167.7924534317299 78.07722411109924 169.7924534317299 80.07722411109924 169.7924534317299 82.07722411109924 L 169.7924534317299 212.879400308609 C 169.7924534317299 214.879400308609 167.7924534317299 216.879400308609 165.7924534317299 216.879400308609 L 161.51830017160486 216.879400308609 C 159.51830017160486 216.879400308609 157.51830017160486 214.879400308609 157.51830017160486 212.879400308609 Z "
                                                        pathFrom="M 157.51830017160486 216.879400308609 L 157.51830017160486 216.879400308609 L 169.7924534317299 216.879400308609 L 169.7924534317299 216.879400308609 L 169.7924534317299 216.879400308609 L 169.7924534317299 216.879400308609 L 169.7924534317299 216.879400308609 L 157.51830017160486 216.879400308609 Z"
                                                        cy="78.07622411109924" cx="169.7924534317299" j="4"
                                                        val="32" barHeight="138.80217619750977"
                                                        barWidth="12.274153260125054"></path>
                                                    <path
                                                        d="M 198.43214437202172 212.879400308609 L 198.43214437202172 4.001 C 198.43214437202172 2.0010000000000003 200.43214437202172 0.001 202.43214437202172 0.001 L 206.70629763214677 0.001 C 208.70629763214677 0.001 210.70629763214677 2.001 210.70629763214677 4.001 L 210.70629763214677 212.879400308609 C 210.70629763214677 214.879400308609 208.70629763214677 216.879400308609 206.70629763214677 216.879400308609 L 202.43214437202172 216.879400308609 C 200.43214437202172 216.879400308609 198.43214437202172 214.879400308609 198.43214437202172 212.879400308609 Z "
                                                        fill="var(--bs-warning)" fill-opacity="1"
                                                        stroke="var(--bs-warning)" stroke-opacity="1"
                                                        stroke-linecap="round" stroke-width="0" stroke-dasharray="0"
                                                        class="apexcharts-bar-area undefined" index="0"
                                                        clip-path="url(#gridRectBarMaskhm32fhmq)"
                                                        pathTo="M 198.43214437202172 212.879400308609 L 198.43214437202172 4.001 C 198.43214437202172 2.0010000000000003 200.43214437202172 0.001 202.43214437202172 0.001 L 206.70629763214677 0.001 C 208.70629763214677 0.001 210.70629763214677 2.001 210.70629763214677 4.001 L 210.70629763214677 212.879400308609 C 210.70629763214677 214.879400308609 208.70629763214677 216.879400308609 206.70629763214677 216.879400308609 L 202.43214437202172 216.879400308609 C 200.43214437202172 216.879400308609 198.43214437202172 214.879400308609 198.43214437202172 212.879400308609 Z "
                                                        pathFrom="M 198.43214437202172 216.879400308609 L 198.43214437202172 216.879400308609 L 210.70629763214677 216.879400308609 L 210.70629763214677 216.879400308609 L 210.70629763214677 216.879400308609 L 210.70629763214677 216.879400308609 L 210.70629763214677 216.879400308609 L 198.43214437202172 216.879400308609 Z"
                                                        cy="0" cx="210.70629763214677" j="5" val="50"
                                                        barHeight="216.878400308609" barWidth="12.274153260125054"></path>
                                                    <path
                                                        d="M 239.34598857243856 212.879400308609 L 239.34598857243856 12.676136012344353 C 239.34598857243856 10.676136012344353 241.34598857243856 8.676136012344353 243.34598857243856 8.676136012344353 L 247.6201418325636 8.676136012344353 C 249.6201418325636 8.676136012344353 251.6201418325636 10.676136012344353 251.6201418325636 12.676136012344353 L 251.6201418325636 212.879400308609 C 251.6201418325636 214.879400308609 249.6201418325636 216.879400308609 247.6201418325636 216.879400308609 L 243.34598857243856 216.879400308609 C 241.34598857243856 216.879400308609 239.34598857243856 214.879400308609 239.34598857243856 212.879400308609 Z "
                                                        fill="var(--bs-warning)" fill-opacity="1"
                                                        stroke="var(--bs-warning)" stroke-opacity="1"
                                                        stroke-linecap="round" stroke-width="0" stroke-dasharray="0"
                                                        class="apexcharts-bar-area undefined" index="0"
                                                        clip-path="url(#gridRectBarMaskhm32fhmq)"
                                                        pathTo="M 239.34598857243856 212.879400308609 L 239.34598857243856 12.676136012344353 C 239.34598857243856 10.676136012344353 241.34598857243856 8.676136012344353 243.34598857243856 8.676136012344353 L 247.6201418325636 8.676136012344353 C 249.6201418325636 8.676136012344353 251.6201418325636 10.676136012344353 251.6201418325636 12.676136012344353 L 251.6201418325636 212.879400308609 C 251.6201418325636 214.879400308609 249.6201418325636 216.879400308609 247.6201418325636 216.879400308609 L 243.34598857243856 216.879400308609 C 241.34598857243856 216.879400308609 239.34598857243856 214.879400308609 239.34598857243856 212.879400308609 Z "
                                                        pathFrom="M 239.34598857243856 216.879400308609 L 239.34598857243856 216.879400308609 L 251.6201418325636 216.879400308609 L 251.6201418325636 216.879400308609 L 251.6201418325636 216.879400308609 L 251.6201418325636 216.879400308609 L 251.6201418325636 216.879400308609 L 239.34598857243856 216.879400308609 Z"
                                                        cy="8.675136012344353" cx="251.6201418325636" j="6"
                                                        val="48" barHeight="208.20326429626465"
                                                        barWidth="12.274153260125054"></path>
                                                    <path
                                                        d="M 280.25983277285536 212.879400308609 L 280.25983277285536 47.37668006172179 C 280.25983277285536 45.37668006172179 282.25983277285536 43.37668006172179 284.25983277285536 43.37668006172179 L 288.5339860329804 43.37668006172179 C 290.5339860329804 43.37668006172179 292.5339860329804 45.37668006172179 292.5339860329804 47.37668006172179 L 292.5339860329804 212.879400308609 C 292.5339860329804 214.879400308609 290.5339860329804 216.879400308609 288.5339860329804 216.879400308609 L 284.25983277285536 216.879400308609 C 282.25983277285536 216.879400308609 280.25983277285536 214.879400308609 280.25983277285536 212.879400308609 Z "
                                                        fill="var(--bs-warning)" fill-opacity="1"
                                                        stroke="var(--bs-warning)" stroke-opacity="1"
                                                        stroke-linecap="round" stroke-width="0" stroke-dasharray="0"
                                                        class="apexcharts-bar-area undefined" index="0"
                                                        clip-path="url(#gridRectBarMaskhm32fhmq)"
                                                        pathTo="M 280.25983277285536 212.879400308609 L 280.25983277285536 47.37668006172179 C 280.25983277285536 45.37668006172179 282.25983277285536 43.37668006172179 284.25983277285536 43.37668006172179 L 288.5339860329804 43.37668006172179 C 290.5339860329804 43.37668006172179 292.5339860329804 45.37668006172179 292.5339860329804 47.37668006172179 L 292.5339860329804 212.879400308609 C 292.5339860329804 214.879400308609 290.5339860329804 216.879400308609 288.5339860329804 216.879400308609 L 284.25983277285536 216.879400308609 C 282.25983277285536 216.879400308609 280.25983277285536 214.879400308609 280.25983277285536 212.879400308609 Z "
                                                        pathFrom="M 280.25983277285536 216.879400308609 L 280.25983277285536 216.879400308609 L 292.5339860329804 216.879400308609 L 292.5339860329804 216.879400308609 L 292.5339860329804 216.879400308609 L 292.5339860329804 216.879400308609 L 292.5339860329804 216.879400308609 L 280.25983277285536 216.879400308609 Z"
                                                        cy="43.375680061721795" cx="292.5339860329804" j="7"
                                                        val="40" barHeight="173.5027202468872"
                                                        barWidth="12.274153260125054"></path>
                                                    <path
                                                        d="M 321.1736769732722 212.879400308609 L 321.1736769732722 38.70154404937744 C 321.1736769732722 36.70154404937744 323.1736769732722 34.70154404937744 325.1736769732722 34.70154404937744 L 329.44783023339727 34.70154404937744 C 331.44783023339727 34.70154404937744 333.44783023339727 36.70154404937744 333.44783023339727 38.70154404937744 L 333.44783023339727 212.879400308609 C 333.44783023339727 214.879400308609 331.44783023339727 216.879400308609 329.44783023339727 216.879400308609 L 325.1736769732722 216.879400308609 C 323.1736769732722 216.879400308609 321.1736769732722 214.879400308609 321.1736769732722 212.879400308609 Z "
                                                        fill="var(--bs-warning)" fill-opacity="1"
                                                        stroke="var(--bs-warning)" stroke-opacity="1"
                                                        stroke-linecap="round" stroke-width="0" stroke-dasharray="0"
                                                        class="apexcharts-bar-area undefined" index="0"
                                                        clip-path="url(#gridRectBarMaskhm32fhmq)"
                                                        pathTo="M 321.1736769732722 212.879400308609 L 321.1736769732722 38.70154404937744 C 321.1736769732722 36.70154404937744 323.1736769732722 34.70154404937744 325.1736769732722 34.70154404937744 L 329.44783023339727 34.70154404937744 C 331.44783023339727 34.70154404937744 333.44783023339727 36.70154404937744 333.44783023339727 38.70154404937744 L 333.44783023339727 212.879400308609 C 333.44783023339727 214.879400308609 331.44783023339727 216.879400308609 329.44783023339727 216.879400308609 L 325.1736769732722 216.879400308609 C 323.1736769732722 216.879400308609 321.1736769732722 214.879400308609 321.1736769732722 212.879400308609 Z "
                                                        pathFrom="M 321.1736769732722 216.879400308609 L 321.1736769732722 216.879400308609 L 333.44783023339727 216.879400308609 L 333.44783023339727 216.879400308609 L 333.44783023339727 216.879400308609 L 333.44783023339727 216.879400308609 L 333.44783023339727 216.879400308609 L 321.1736769732722 216.879400308609 Z"
                                                        cy="34.70054404937744" cx="333.44783023339727" j="8"
                                                        val="42" barHeight="182.17785625923156"
                                                        barWidth="12.274153260125054"></path>
                                                    <path
                                                        d="M 362.0875211736891 212.879400308609 L 362.0875211736891 60.38938408023835 C 362.0875211736891 58.38938408023835 364.0875211736891 56.38938408023835 366.0875211736891 56.38938408023835 L 370.36167443381413 56.38938408023835 C 372.36167443381413 56.38938408023835 374.36167443381413 58.38938408023835 374.36167443381413 60.38938408023835 L 374.36167443381413 212.879400308609 C 374.36167443381413 214.879400308609 372.36167443381413 216.879400308609 370.36167443381413 216.879400308609 L 366.0875211736891 216.879400308609 C 364.0875211736891 216.879400308609 362.0875211736891 214.879400308609 362.0875211736891 212.879400308609 Z "
                                                        fill="var(--bs-warning)" fill-opacity="1"
                                                        stroke="var(--bs-warning)" stroke-opacity="1"
                                                        stroke-linecap="round" stroke-width="0" stroke-dasharray="0"
                                                        class="apexcharts-bar-area undefined" index="0"
                                                        clip-path="url(#gridRectBarMaskhm32fhmq)"
                                                        pathTo="M 362.0875211736891 212.879400308609 L 362.0875211736891 60.38938408023835 C 362.0875211736891 58.38938408023835 364.0875211736891 56.38938408023835 366.0875211736891 56.38938408023835 L 370.36167443381413 56.38938408023835 C 372.36167443381413 56.38938408023835 374.36167443381413 58.38938408023835 374.36167443381413 60.38938408023835 L 374.36167443381413 212.879400308609 C 374.36167443381413 214.879400308609 372.36167443381413 216.879400308609 370.36167443381413 216.879400308609 L 366.0875211736891 216.879400308609 C 364.0875211736891 216.879400308609 362.0875211736891 214.879400308609 362.0875211736891 212.879400308609 Z "
                                                        pathFrom="M 362.0875211736891 216.879400308609 L 362.0875211736891 216.879400308609 L 374.36167443381413 216.879400308609 L 374.36167443381413 216.879400308609 L 374.36167443381413 216.879400308609 L 374.36167443381413 216.879400308609 L 374.36167443381413 216.879400308609 L 362.0875211736891 216.879400308609 Z"
                                                        cy="56.38838408023835" cx="374.36167443381413" j="9"
                                                        val="37" barHeight="160.49001622837065"
                                                        barWidth="12.274153260125054"></path>
                                                    <g class="apexcharts-bar-goals-markers">
                                                        <g className="apexcharts-bar-goals-groups"
                                                            class="apexcharts-hidden-element-shown"
                                                            clip-path="url(#gridRectMarkerMaskhm32fhmq)"></g>
                                                        <g className="apexcharts-bar-goals-groups"
                                                            class="apexcharts-hidden-element-shown"
                                                            clip-path="url(#gridRectMarkerMaskhm32fhmq)"></g>
                                                        <g className="apexcharts-bar-goals-groups"
                                                            class="apexcharts-hidden-element-shown"
                                                            clip-path="url(#gridRectMarkerMaskhm32fhmq)"></g>
                                                        <g className="apexcharts-bar-goals-groups"
                                                            class="apexcharts-hidden-element-shown"
                                                            clip-path="url(#gridRectMarkerMaskhm32fhmq)"></g>
                                                        <g className="apexcharts-bar-goals-groups"
                                                            class="apexcharts-hidden-element-shown"
                                                            clip-path="url(#gridRectMarkerMaskhm32fhmq)"></g>
                                                        <g className="apexcharts-bar-goals-groups"
                                                            class="apexcharts-hidden-element-shown"
                                                            clip-path="url(#gridRectMarkerMaskhm32fhmq)"></g>
                                                        <g className="apexcharts-bar-goals-groups"
                                                            class="apexcharts-hidden-element-shown"
                                                            clip-path="url(#gridRectMarkerMaskhm32fhmq)"></g>
                                                        <g className="apexcharts-bar-goals-groups"
                                                            class="apexcharts-hidden-element-shown"
                                                            clip-path="url(#gridRectMarkerMaskhm32fhmq)"></g>
                                                        <g className="apexcharts-bar-goals-groups"
                                                            class="apexcharts-hidden-element-shown"
                                                            clip-path="url(#gridRectMarkerMaskhm32fhmq)"></g>
                                                        <g className="apexcharts-bar-goals-groups"
                                                            class="apexcharts-hidden-element-shown"
                                                            clip-path="url(#gridRectMarkerMaskhm32fhmq)"></g>
                                                    </g>
                                                    <g class="apexcharts-bar-shadows apexcharts-hidden-element-shown"></g>
                                                </g>
                                            </g>
                                            <g class="apexcharts-line-series apexcharts-plot-series">
                                                <g class="apexcharts-series" zIndex="1" seriesName="Delivery"
                                                    data:longestSeries="true" rel="1" data:realIndex="1">
                                                    <path
                                                        d="M 0 117.11433616664887C 14.319845470145895 117.11433616664887 26.593998730270954 95.42649613578796 40.91384420041685 95.42649613578796C 55.23368967056274 95.42649613578796 67.5078429306878 117.11433616664887 81.8276884008337 117.11433616664887C 96.14753387097959 117.11433616664887 108.42168713110465 78.07622411109924 122.74153260125054 78.07622411109924C 137.06137807139643 78.07622411109924 149.33553133152148 95.42649613578796 163.6553768016674 95.42649613578796C 177.9752222718133 95.42649613578796 190.24937553193834 26.02540803703309 204.56922100208425 26.02540803703309C 218.88906647223013 26.02540803703309 231.1632197323552 78.07622411109924 245.48306520250108 78.07622411109924C 259.802910672647 78.07622411109924 272.07706393277203 52.05081607406618 286.3969094029179 52.05081607406618C 300.7167548730638 52.05081607406618 312.9909081331889 104.10163214813232 327.3107536033348 104.10163214813232C 341.63059907348065 104.10163214813232 353.90475233360576 69.40108809875488 368.22459780375164 69.40108809875488"
                                                        fill="none" fill-opacity="1" stroke="var(--bs-primary)"
                                                        stroke-opacity="1" stroke-linecap="round" stroke-width="3"
                                                        stroke-dasharray="0" class="apexcharts-line" index="1"
                                                        clip-path="url(#gridRectBarMaskhm32fhmq)"
                                                        pathTo="M 0 117.11433616664887C 14.319845470145895 117.11433616664887 26.593998730270954 95.42649613578796 40.91384420041685 95.42649613578796C 55.23368967056274 95.42649613578796 67.5078429306878 117.11433616664887 81.8276884008337 117.11433616664887C 96.14753387097959 117.11433616664887 108.42168713110465 78.07622411109924 122.74153260125054 78.07622411109924C 137.06137807139643 78.07622411109924 149.33553133152148 95.42649613578796 163.6553768016674 95.42649613578796C 177.9752222718133 95.42649613578796 190.24937553193834 26.02540803703309 204.56922100208425 26.02540803703309C 218.88906647223013 26.02540803703309 231.1632197323552 78.07622411109924 245.48306520250108 78.07622411109924C 259.802910672647 78.07622411109924 272.07706393277203 52.05081607406618 286.3969094029179 52.05081607406618C 300.7167548730638 52.05081607406618 312.9909081331889 104.10163214813232 327.3107536033348 104.10163214813232C 341.63059907348065 104.10163214813232 353.90475233360576 69.40108809875488 368.22459780375164 69.40108809875488"
                                                        pathFrom="M 0 216.878400308609 L 0 216.878400308609 L 40.91384420041685 216.878400308609 L 81.8276884008337 216.878400308609 L 122.74153260125054 216.878400308609 L 163.6553768016674 216.878400308609 L 204.56922100208425 216.878400308609 L 245.48306520250108 216.878400308609 L 286.3969094029179 216.878400308609 L 327.3107536033348 216.878400308609 L 368.22459780375164 216.878400308609"
                                                        fill-rule="evenodd"></path>
                                                    <g class="apexcharts-series-markers-wrap apexcharts-hidden-element-shown"
                                                        data:realIndex="1">
                                                        <g class="apexcharts-series-markers"
                                                            clip-path="url(#gridRectMarkerMaskhm32fhmq)">
                                                            <path d="M 0, 117.11433616664887
               m -5, 0
               a 5,5 0 1,0 10,0
               a 5,5 0 1,0 -10,0" fill="var(--bs-white)" fill-opacity="1" stroke="var(--bs-primary)"
                                                                stroke-opacity="0.9" stroke-linecap="round"
                                                                stroke-width="2" stroke-dasharray="0" cx="0"
                                                                cy="117.11433616664887" shape="circle"
                                                                class="apexcharts-marker w1qoxohfq" rel="0" j="0"
                                                                index="1" default-marker-size="5"></path>
                                                            <path d="M 40.91384420041685, 95.42649613578796
               m -5, 0
               a 5,5 0 1,0 10,0
               a 5,5 0 1,0 -10,0" fill="var(--bs-white)" fill-opacity="1" stroke="var(--bs-primary)"
                                                                stroke-opacity="0.9" stroke-linecap="round"
                                                                stroke-width="2" stroke-dasharray="0"
                                                                cx="40.91384420041685" cy="95.42649613578796"
                                                                shape="circle" class="apexcharts-marker whlaq5q7f"
                                                                rel="1" j="1" index="1"
                                                                default-marker-size="5"></path>
                                                        </g>
                                                        <g class="apexcharts-series-markers"
                                                            clip-path="url(#gridRectMarkerMaskhm32fhmq)">
                                                            <path d="M 81.8276884008337, 117.11433616664887
               m -5, 0
               a 5,5 0 1,0 10,0
               a 5,5 0 1,0 -10,0" fill="var(--bs-white)" fill-opacity="1" stroke="var(--bs-primary)"
                                                                stroke-opacity="0.9" stroke-linecap="round"
                                                                stroke-width="2" stroke-dasharray="0"
                                                                cx="81.8276884008337" cy="117.11433616664887"
                                                                shape="circle" class="apexcharts-marker wjymp7ggw"
                                                                rel="2" j="2" index="1"
                                                                default-marker-size="5"></path>
                                                        </g>
                                                        <g class="apexcharts-series-markers"
                                                            clip-path="url(#gridRectMarkerMaskhm32fhmq)">
                                                            <path d="M 122.74153260125054, 78.07622411109924
               m -5, 0
               a 5,5 0 1,0 10,0
               a 5,5 0 1,0 -10,0" fill="var(--bs-white)" fill-opacity="1" stroke="var(--bs-primary)"
                                                                stroke-opacity="0.9" stroke-linecap="round"
                                                                stroke-width="2" stroke-dasharray="0"
                                                                cx="122.74153260125054" cy="78.07622411109924"
                                                                shape="circle" class="apexcharts-marker wn0iziyskg"
                                                                rel="3" j="3" index="1"
                                                                default-marker-size="5"></path>
                                                        </g>
                                                        <g class="apexcharts-series-markers"
                                                            clip-path="url(#gridRectMarkerMaskhm32fhmq)">
                                                            <path d="M 163.6553768016674, 95.42649613578796
               m -5, 0
               a 5,5 0 1,0 10,0
               a 5,5 0 1,0 -10,0" fill="var(--bs-white)" fill-opacity="1" stroke="var(--bs-primary)"
                                                                stroke-opacity="0.9" stroke-linecap="round"
                                                                stroke-width="2" stroke-dasharray="0"
                                                                cx="163.6553768016674" cy="95.42649613578796"
                                                                shape="circle" class="apexcharts-marker wp5uf5lf2"
                                                                rel="4" j="4" index="1"
                                                                default-marker-size="5"></path>
                                                        </g>
                                                        <g class="apexcharts-series-markers"
                                                            clip-path="url(#gridRectMarkerMaskhm32fhmq)">
                                                            <path d="M 204.56922100208425, 26.02540803703309
               m -5, 0
               a 5,5 0 1,0 10,0
               a 5,5 0 1,0 -10,0" fill="var(--bs-white)" fill-opacity="1" stroke="var(--bs-primary)"
                                                                stroke-opacity="0.9" stroke-linecap="round"
                                                                stroke-width="2" stroke-dasharray="0"
                                                                cx="204.56922100208425" cy="26.02540803703309"
                                                                shape="circle" class="apexcharts-marker w3gps5wqa"
                                                                rel="5" j="5" index="1"
                                                                default-marker-size="5"></path>
                                                        </g>
                                                        <g class="apexcharts-series-markers"
                                                            clip-path="url(#gridRectMarkerMaskhm32fhmq)">
                                                            <path d="M 245.48306520250108, 78.07622411109924
               m -5, 0
               a 5,5 0 1,0 10,0
               a 5,5 0 1,0 -10,0" fill="var(--bs-white)" fill-opacity="1" stroke="var(--bs-primary)"
                                                                stroke-opacity="0.9" stroke-linecap="round"
                                                                stroke-width="2" stroke-dasharray="0"
                                                                cx="245.48306520250108" cy="78.07622411109924"
                                                                shape="circle" class="apexcharts-marker wt3uapnlhl"
                                                                rel="6" j="6" index="1"
                                                                default-marker-size="5"></path>
                                                        </g>
                                                        <g class="apexcharts-series-markers"
                                                            clip-path="url(#gridRectMarkerMaskhm32fhmq)">
                                                            <path d="M 286.3969094029179, 52.05081607406618
               m -5, 0
               a 5,5 0 1,0 10,0
               a 5,5 0 1,0 -10,0" fill="var(--bs-white)" fill-opacity="1" stroke="var(--bs-primary)"
                                                                stroke-opacity="0.9" stroke-linecap="round"
                                                                stroke-width="2" stroke-dasharray="0"
                                                                cx="286.3969094029179" cy="52.05081607406618"
                                                                shape="circle" class="apexcharts-marker w630w8ghu"
                                                                rel="7" j="7" index="1"
                                                                default-marker-size="5"></path>
                                                        </g>
                                                        <g class="apexcharts-series-markers"
                                                            clip-path="url(#gridRectMarkerMaskhm32fhmq)">
                                                            <path d="M 327.3107536033348, 104.10163214813232
               m -5, 0
               a 5,5 0 1,0 10,0
               a 5,5 0 1,0 -10,0" fill="var(--bs-white)" fill-opacity="1" stroke="var(--bs-primary)"
                                                                stroke-opacity="0.9" stroke-linecap="round"
                                                                stroke-width="2" stroke-dasharray="0"
                                                                cx="327.3107536033348" cy="104.10163214813232"
                                                                shape="circle" class="apexcharts-marker w9ftol5ngg"
                                                                rel="8" j="8" index="1"
                                                                default-marker-size="5"></path>
                                                        </g>
                                                        <g class="apexcharts-series-markers"
                                                            clip-path="url(#gridRectMarkerMaskhm32fhmq)">
                                                            <path d="M 368.22459780375164, 69.40108809875488
               m -5, 0
               a 5,5 0 1,0 10,0
               a 5,5 0 1,0 -10,0" fill="var(--bs-white)" fill-opacity="1" stroke="var(--bs-primary)"
                                                                stroke-opacity="0.9" stroke-linecap="round"
                                                                stroke-width="2" stroke-dasharray="0"
                                                                cx="368.22459780375164" cy="69.40108809875488"
                                                                shape="circle" class="apexcharts-marker wr9v6l62m"
                                                                rel="9" j="9" index="1"
                                                                default-marker-size="5"></path>
                                                        </g>
                                                    </g>
                                                </g>
                                                <g class="apexcharts-datalabels apexcharts-hidden-element-shown"
                                                    data:realIndex="0"></g>
                                                <g class="apexcharts-datalabels" data:realIndex="1"></g>
                                            </g>
                                            <line x1="-13.150878492991131" y1="0" x2="381.37547629674276"
                                                y2="0" stroke="#b6b6b6" stroke-dasharray="0" stroke-width="1"
                                                stroke-linecap="butt" class="apexcharts-ycrosshairs"></line>
                                            <line x1="-13.150878492991131" y1="0" x2="381.37547629674276"
                                                y2="0" stroke="#b6b6b6" stroke-dasharray="0" stroke-width="0"
                                                stroke-linecap="butt" class="apexcharts-ycrosshairs-hidden"></line>
                                            <g class="apexcharts-xaxis" transform="translate(0, 0)">
                                                <g class="apexcharts-xaxis-texts-g" transform="translate(0, -4)"><text
                                                        x="0" y="244.878400308609" text-anchor="middle"
                                                        dominant-baseline="auto" font-size="10px"
                                                        font-family="var(--bs-font-family-base)" font-weight="400"
                                                        fill="var(--bs-secondary-color)"
                                                        class="apexcharts-text apexcharts-xaxis-label "
                                                        style="font-family: var(--bs-font-family-base);">
                                                        <tspan>1 Jan</tspan>
                                                        <title>1 Jan</title>
                                                    </text><text x="40.91384420041685" y="244.878400308609"
                                                        text-anchor="middle" dominant-baseline="auto" font-size="10px"
                                                        font-family="var(--bs-font-family-base)" font-weight="400"
                                                        fill="var(--bs-secondary-color)"
                                                        class="apexcharts-text apexcharts-xaxis-label "
                                                        style="font-family: var(--bs-font-family-base);">
                                                        <tspan>2 Jan</tspan>
                                                        <title>2 Jan</title>
                                                    </text><text x="81.8276884008337" y="244.878400308609"
                                                        text-anchor="middle" dominant-baseline="auto" font-size="10px"
                                                        font-family="var(--bs-font-family-base)" font-weight="400"
                                                        fill="var(--bs-secondary-color)"
                                                        class="apexcharts-text apexcharts-xaxis-label "
                                                        style="font-family: var(--bs-font-family-base);">
                                                        <tspan>3 Jan</tspan>
                                                        <title>3 Jan</title>
                                                    </text><text x="122.74153260125053" y="244.878400308609"
                                                        text-anchor="middle" dominant-baseline="auto" font-size="10px"
                                                        font-family="var(--bs-font-family-base)" font-weight="400"
                                                        fill="var(--bs-secondary-color)"
                                                        class="apexcharts-text apexcharts-xaxis-label "
                                                        style="font-family: var(--bs-font-family-base);">
                                                        <tspan>4 Jan</tspan>
                                                        <title>4 Jan</title>
                                                    </text><text x="163.6553768016674" y="244.878400308609"
                                                        text-anchor="middle" dominant-baseline="auto" font-size="10px"
                                                        font-family="var(--bs-font-family-base)" font-weight="400"
                                                        fill="var(--bs-secondary-color)"
                                                        class="apexcharts-text apexcharts-xaxis-label "
                                                        style="font-family: var(--bs-font-family-base);">
                                                        <tspan>5 Jan</tspan>
                                                        <title>5 Jan</title>
                                                    </text><text x="204.56922100208425" y="244.878400308609"
                                                        text-anchor="middle" dominant-baseline="auto" font-size="10px"
                                                        font-family="var(--bs-font-family-base)" font-weight="400"
                                                        fill="var(--bs-secondary-color)"
                                                        class="apexcharts-text apexcharts-xaxis-label "
                                                        style="font-family: var(--bs-font-family-base);">
                                                        <tspan>6 Jan</tspan>
                                                        <title>6 Jan</title>
                                                    </text><text x="245.4830652025011" y="244.878400308609"
                                                        text-anchor="middle" dominant-baseline="auto" font-size="10px"
                                                        font-family="var(--bs-font-family-base)" font-weight="400"
                                                        fill="var(--bs-secondary-color)"
                                                        class="apexcharts-text apexcharts-xaxis-label "
                                                        style="font-family: var(--bs-font-family-base);">
                                                        <tspan>7 Jan</tspan>
                                                        <title>7 Jan</title>
                                                    </text><text x="286.39690940291797" y="244.878400308609"
                                                        text-anchor="middle" dominant-baseline="auto" font-size="10px"
                                                        font-family="var(--bs-font-family-base)" font-weight="400"
                                                        fill="var(--bs-secondary-color)"
                                                        class="apexcharts-text apexcharts-xaxis-label "
                                                        style="font-family: var(--bs-font-family-base);">
                                                        <tspan>8 Jan</tspan>
                                                        <title>8 Jan</title>
                                                    </text><text x="327.31075360333483" y="244.878400308609"
                                                        text-anchor="middle" dominant-baseline="auto" font-size="10px"
                                                        font-family="var(--bs-font-family-base)" font-weight="400"
                                                        fill="var(--bs-secondary-color)"
                                                        class="apexcharts-text apexcharts-xaxis-label "
                                                        style="font-family: var(--bs-font-family-base);">
                                                        <tspan>9 Jan</tspan>
                                                        <title>9 Jan</title>
                                                    </text><text x="368.2245978037517" y="244.878400308609"
                                                        text-anchor="middle" dominant-baseline="auto" font-size="10px"
                                                        font-family="var(--bs-font-family-base)" font-weight="400"
                                                        fill="var(--bs-secondary-color)"
                                                        class="apexcharts-text apexcharts-xaxis-label "
                                                        style="font-family: var(--bs-font-family-base);">
                                                        <tspan>10 Jan</tspan>
                                                        <title>10 Jan</title>
                                                    </text></g>
                                            </g>
                                            <g class="apexcharts-yaxis-annotations"></g>
                                            <g class="apexcharts-xaxis-annotations"></g>
                                            <g class="apexcharts-point-annotations"></g>
                                        </g>
                                    </svg>
                                    <div class="apexcharts-tooltip apexcharts-theme-light">
                                        <div class="apexcharts-tooltip-title"
                                            style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;"></div>
                                        <div class="apexcharts-tooltip-series-group apexcharts-tooltip-series-group-0"
                                            style="order: 1;"><span class="apexcharts-tooltip-marker"
                                                style="background-color: var(--bs-warning);"></span>
                                            <div class="apexcharts-tooltip-text"
                                                style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                                <div class="apexcharts-tooltip-y-group"><span
                                                        class="apexcharts-tooltip-text-y-label"></span><span
                                                        class="apexcharts-tooltip-text-y-value"></span></div>
                                                <div class="apexcharts-tooltip-goals-group"><span
                                                        class="apexcharts-tooltip-text-goals-label"></span><span
                                                        class="apexcharts-tooltip-text-goals-value"></span></div>
                                                <div class="apexcharts-tooltip-z-group"><span
                                                        class="apexcharts-tooltip-text-z-label"></span><span
                                                        class="apexcharts-tooltip-text-z-value"></span></div>
                                            </div>
                                        </div>
                                        <div class="apexcharts-tooltip-series-group apexcharts-tooltip-series-group-1"
                                            style="order: 2;"><span class="apexcharts-tooltip-marker"
                                                style="background-color: var(--bs-primary);"></span>
                                            <div class="apexcharts-tooltip-text"
                                                style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                                <div class="apexcharts-tooltip-y-group"><span
                                                        class="apexcharts-tooltip-text-y-label"></span><span
                                                        class="apexcharts-tooltip-text-y-value"></span></div>
                                                <div class="apexcharts-tooltip-goals-group"><span
                                                        class="apexcharts-tooltip-text-goals-label"></span><span
                                                        class="apexcharts-tooltip-text-goals-value"></span></div>
                                                <div class="apexcharts-tooltip-z-group"><span
                                                        class="apexcharts-tooltip-text-z-label"></span><span
                                                        class="apexcharts-tooltip-text-z-value"></span></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div
                                        class="apexcharts-xaxistooltip apexcharts-xaxistooltip-bottom apexcharts-theme-light">
                                        <div class="apexcharts-xaxistooltip-text"
                                            style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;"></div>
                                    </div>
                                    <div
                                        class="apexcharts-yaxistooltip apexcharts-yaxistooltip-0 apexcharts-yaxistooltip-left apexcharts-theme-light">
                                        <div class="apexcharts-yaxistooltip-text"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/ Shipment statistics -->
                <!-- Delivery Performance -->
                <div class="col-xxl-4 col-lg-5">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between">
                            <div class="card-title mb-0">
                                <h5 class="mb-1 me-2">Delivery Performance</h5>
                                <p class="card-subtitle">12% increase in this month</p>
                            </div>
                            <div class="dropdown">
                                <button class="btn text-body-secondary p-0" type="button" id="deliveryPerformance"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="icon-base bx bx-dots-vertical-rounded icon-lg"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="deliveryPerformance">
                                    <a class="dropdown-item" href="javascript:void(0);">Select All</a>
                                    <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                                    <a class="dropdown-item" href="javascript:void(0);">Share</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <ul class="p-0 m-0">
                                <li class="d-flex mb-6 align-items-center">
                                    <div class="avatar flex-shrink-0 me-4">
                                        <span class="avatar-initial rounded bg-label-primary"><i
                                                class="icon-base bx bx-cube icon-lg"></i></span>
                                    </div>
                                    <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                        <div class="me-2">
                                            <h6 class="mb-1 fw-normal">Packages in transit</h6>
                                            <p class="text-success mb-0">
                                                <i class="icon-base bx bx-chevron-up icon-lg me-1"></i>
                                                25.8%
                                            </p>
                                        </div>
                                        <div class="user-progress">
                                            <h6 class="mb-0">10k</h6>
                                        </div>
                                    </div>
                                </li>
                                <li class="d-flex mb-6 align-items-center">
                                    <div class="avatar flex-shrink-0 me-4">
                                        <span class="avatar-initial rounded bg-label-info"><i
                                                class="icon-base bx bxs-truck icon-lg"></i></span>
                                    </div>
                                    <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                        <div class="me-2">
                                            <h6 class="mb-1 fw-normal">Packages out for delivery</h6>
                                            <p class="text-success mb-0">
                                                <i class="icon-base bx bx-chevron-up icon-lg me-1"></i>
                                                4.3%
                                            </p>
                                        </div>
                                        <div class="user-progress">
                                            <h6 class="mb-0">5k</h6>
                                        </div>
                                    </div>
                                </li>
                                <li class="d-flex mb-6 align-items-center">
                                    <div class="avatar flex-shrink-0 me-4">
                                        <span class="avatar-initial rounded bg-label-success"><i
                                                class="icon-base bx bx-check-circle icon-lg"></i></span>
                                    </div>
                                    <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                        <div class="me-2">
                                            <h6 class="mb-1 fw-normal">Packages delivered</h6>
                                            <p class="text-danger mb-0">
                                                <i class="icon-base bx bx-chevron-down icon-lg me-1"></i>
                                                12.5
                                            </p>
                                        </div>
                                        <div class="user-progress">
                                            <h6 class="mb-0">15k</h6>
                                        </div>
                                    </div>
                                </li>
                                <li class="d-flex mb-6 align-items-center">
                                    <div class="avatar flex-shrink-0 me-4">
                                        <span class="avatar-initial rounded bg-label-warning"><i
                                                class="icon-base bx bxs-offer icon-lg"></i></span>
                                    </div>
                                    <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                        <div class="me-2">
                                            <h6 class="mb-1 fw-normal">Delivery success rate</h6>
                                            <p class="text-success mb-0">
                                                <i class="icon-base bx bx-chevron-up icon-lg me-1"></i>
                                                35.6%
                                            </p>
                                        </div>
                                        <div class="user-progress">
                                            <h6 class="mb-0">95%</h6>
                                        </div>
                                    </div>
                                </li>
                                <li class="d-flex mb-6 align-items-center">
                                    <div class="avatar flex-shrink-0 me-4">
                                        <span class="avatar-initial rounded bg-label-secondary"><i
                                                class="icon-base bx bx-time-five icon-lg"></i></span>
                                    </div>
                                    <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                        <div class="me-2">
                                            <h6 class="mb-1 fw-normal">Average delivery time</h6>
                                            <p class="text-danger mb-0">
                                                <i class="icon-base bx bx-chevron-down icon-lg me-1"></i>
                                                2.15
                                            </p>
                                        </div>
                                        <div class="user-progress">
                                            <h6 class="mb-0">2.5 Days</h6>
                                        </div>
                                    </div>
                                </li>
                                <li class="d-flex align-items-center">
                                    <div class="avatar flex-shrink-0 me-4">
                                        <span class="avatar-initial rounded bg-label-danger"><i
                                                class="icon-base bx bx-group icon-lg"></i></span>
                                    </div>
                                    <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                        <div class="me-2">
                                            <h6 class="mb-1 fw-normal">Customer satisfaction</h6>
                                            <p class="text-success mb-0">
                                                <i class="icon-base bx bx-chevron-up icon-lg me-1"></i>
                                                5.7%
                                            </p>
                                        </div>
                                        <div class="user-progress">
                                            <h6 class="mb-0">4.5/5</h6>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!--/ Delivery Performance -->
                <!-- Reasons for delivery exceptions -->
                <div class="col-xxl-4 col-lg-6">
                    <div class="card h-100">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <div class="card-title mb-0">
                                <h5 class="m-0 me-2">Reasons for delivery exceptions</h5>
                            </div>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="deliveryExceptions"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="icon-base bx bx-dots-vertical-rounded icon-lg text-body-secondary"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="deliveryExceptions">
                                    <a class="dropdown-item" href="javascript:void(0);">Select All</a>
                                    <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                                    <a class="dropdown-item" href="javascript:void(0);">Share</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="deliveryExceptionsChart" style="min-height: 401px;">
                                <div id="apexchartsvjljtqj6"
                                    class="apexcharts-canvas apexchartsvjljtqj6 apexcharts-theme-"
                                    style="width: 406px; height: 401px;"><svg xmlns="http://www.w3.org/2000/svg"
                                        version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        class="apexcharts-svg" xmlns:data="ApexChartsNS" transform="translate(0, 0)"
                                        width="406" height="401">
                                        <foreignObject x="0" y="0" width="406" height="401">
                                            <div class="apexcharts-legend apexcharts-align-center apx-legend-position-bottom"
                                                xmlns="http://www.w3.org/1999/xhtml"
                                                style="right: 0px; position: absolute; left: 0px; top: 351px; max-height: 198px;">
                                                <div class="apexcharts-legend-series" rel="1"
                                                    seriesname="Incorrectxaddress" data:collapsed="false"
                                                    style="margin: 5px 15px;"><span class="apexcharts-legend-marker"
                                                        rel="1" data:collapsed="false"
                                                        style="height: 12px; width: 12px; left: 0px; top: 0px;"><svg
                                                            xmlns="http://www.w3.org/2000/svg" version="1.1"
                                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="100%"
                                                            height="100%">
                                                            <path d="M 0, 0
               m -5, 0
               a 5,5 0 1,0 10,0
               a 5,5 0 1,0 -10,0" fill="var(--bs-success)" fill-opacity="1" stroke="#ffffff" stroke-opacity="0.9"
                                                                stroke-linecap="butt" stroke-width="1"
                                                                stroke-dasharray="0" cx="0" cy="0"
                                                                shape="circle"
                                                                class="apexcharts-legend-marker apexcharts-marker apexcharts-marker-circle"
                                                                style="transform: translate(50%, 50%);"></path>
                                                        </svg></span><span class="apexcharts-legend-text" rel="1"
                                                        i="0" data:default-text="Incorrect%20address"
                                                        data:collapsed="false"
                                                        style="color: var(--bs-heading-color); font-size: 13px; font-weight: 400; font-family: var(--bs-font-family-base);">Incorrect
                                                        address</span></div>
                                                <div class="apexcharts-legend-series" rel="2"
                                                    seriesname="Weatherxconditions" data:collapsed="false"
                                                    style="margin: 5px 15px;"><span class="apexcharts-legend-marker"
                                                        rel="2" data:collapsed="false"
                                                        style="height: 12px; width: 12px; left: 0px; top: 0px;"><svg
                                                            xmlns="http://www.w3.org/2000/svg" version="1.1"
                                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="100%"
                                                            height="100%">
                                                            <path d="M 0, 0
               m -5, 0
               a 5,5 0 1,0 10,0
               a 5,5 0 1,0 -10,0" fill="color-mix(in sRGB, var(--bs-success) 80%, var(--bs-paper-bg))" fill-opacity="1"
                                                                stroke="#ffffff" stroke-opacity="0.9"
                                                                stroke-linecap="butt" stroke-width="1"
                                                                stroke-dasharray="0" cx="0" cy="0"
                                                                shape="circle"
                                                                class="apexcharts-legend-marker apexcharts-marker apexcharts-marker-circle"
                                                                style="transform: translate(50%, 50%);"></path>
                                                        </svg></span><span class="apexcharts-legend-text" rel="2"
                                                        i="1" data:default-text="Weather%20conditions"
                                                        data:collapsed="false"
                                                        style="color: var(--bs-heading-color); font-size: 13px; font-weight: 400; font-family: var(--bs-font-family-base);">Weather
                                                        conditions</span></div>
                                                <div class="apexcharts-legend-series" rel="3"
                                                    seriesname="FederalxHolidays" data:collapsed="false"
                                                    style="margin: 5px 15px;"><span class="apexcharts-legend-marker"
                                                        rel="3" data:collapsed="false"
                                                        style="height: 12px; width: 12px; left: 0px; top: 0px;"><svg
                                                            xmlns="http://www.w3.org/2000/svg" version="1.1"
                                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="100%"
                                                            height="100%">
                                                            <path d="M 0, 0
               m -5, 0
               a 5,5 0 1,0 10,0
               a 5,5 0 1,0 -10,0" fill="color-mix(in sRGB, var(--bs-success) 60%, var(--bs-paper-bg))" fill-opacity="1"
                                                                stroke="#ffffff" stroke-opacity="0.9"
                                                                stroke-linecap="butt" stroke-width="1"
                                                                stroke-dasharray="0" cx="0" cy="0"
                                                                shape="circle"
                                                                class="apexcharts-legend-marker apexcharts-marker apexcharts-marker-circle"
                                                                style="transform: translate(50%, 50%);"></path>
                                                        </svg></span><span class="apexcharts-legend-text" rel="3"
                                                        i="2" data:default-text="Federal%20Holidays"
                                                        data:collapsed="false"
                                                        style="color: var(--bs-heading-color); font-size: 13px; font-weight: 400; font-family: var(--bs-font-family-base);">Federal
                                                        Holidays</span></div>
                                                <div class="apexcharts-legend-series" rel="4"
                                                    seriesname="Damagexduringxtransit" data:collapsed="false"
                                                    style="margin: 5px 15px;"><span class="apexcharts-legend-marker"
                                                        rel="4" data:collapsed="false"
                                                        style="height: 12px; width: 12px; left: 0px; top: 0px;"><svg
                                                            xmlns="http://www.w3.org/2000/svg" version="1.1"
                                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="100%"
                                                            height="100%">
                                                            <path d="M 0, 0
               m -5, 0
               a 5,5 0 1,0 10,0
               a 5,5 0 1,0 -10,0" fill="color-mix(in sRGB, var(--bs-success) 40%, var(--bs-paper-bg))" fill-opacity="1"
                                                                stroke="#ffffff" stroke-opacity="0.9"
                                                                stroke-linecap="butt" stroke-width="1"
                                                                stroke-dasharray="0" cx="0" cy="0"
                                                                shape="circle"
                                                                class="apexcharts-legend-marker apexcharts-marker apexcharts-marker-circle"
                                                                style="transform: translate(50%, 50%);"></path>
                                                        </svg></span><span class="apexcharts-legend-text" rel="4"
                                                        i="3" data:default-text="Damage%20during%20transit"
                                                        data:collapsed="false"
                                                        style="color: var(--bs-heading-color); font-size: 13px; font-weight: 400; font-family: var(--bs-font-family-base);">Damage
                                                        during transit</span></div>
                                            </div>
                                            <style type="text/css">
                                                .apexcharts-flip-y {
                                                    transform: scaleY(-1) translateY(-100%);
                                                    transform-origin: top;
                                                    transform-box: fill-box;
                                                }

                                                .apexcharts-flip-x {
                                                    transform: scaleX(-1);
                                                    transform-origin: center;
                                                    transform-box: fill-box;
                                                }

                                                .apexcharts-legend {
                                                    display: flex;
                                                    overflow: auto;
                                                    padding: 0 10px;
                                                }

                                                .apexcharts-legend.apexcharts-legend-group-horizontal {
                                                    flex-direction: column;
                                                }

                                                .apexcharts-legend-group {
                                                    display: flex;
                                                }

                                                .apexcharts-legend-group-vertical {
                                                    flex-direction: column-reverse;
                                                }

                                                .apexcharts-legend.apx-legend-position-bottom,
                                                .apexcharts-legend.apx-legend-position-top {
                                                    flex-wrap: wrap
                                                }

                                                .apexcharts-legend.apx-legend-position-right,
                                                .apexcharts-legend.apx-legend-position-left {
                                                    flex-direction: column;
                                                    bottom: 0;
                                                }

                                                .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-left,
                                                .apexcharts-legend.apx-legend-position-top.apexcharts-align-left,
                                                .apexcharts-legend.apx-legend-position-right,
                                                .apexcharts-legend.apx-legend-position-left {
                                                    justify-content: flex-start;
                                                    align-items: flex-start;
                                                }

                                                .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-center,
                                                .apexcharts-legend.apx-legend-position-top.apexcharts-align-center {
                                                    justify-content: center;
                                                    align-items: center;
                                                }

                                                .apexcharts-legend.apx-legend-position-bottom.apexcharts-align-right,
                                                .apexcharts-legend.apx-legend-position-top.apexcharts-align-right {
                                                    justify-content: flex-end;
                                                    align-items: flex-end;
                                                }

                                                .apexcharts-legend-series {
                                                    cursor: pointer;
                                                    line-height: normal;
                                                    display: flex;
                                                    align-items: center;
                                                }

                                                .apexcharts-legend-text {
                                                    position: relative;
                                                    font-size: 14px;
                                                }

                                                .apexcharts-legend-text *,
                                                .apexcharts-legend-marker * {
                                                    pointer-events: none;
                                                }

                                                .apexcharts-legend-marker {
                                                    position: relative;
                                                    display: flex;
                                                    align-items: center;
                                                    justify-content: center;
                                                    cursor: pointer;
                                                    margin-right: 1px;
                                                }

                                                .apexcharts-legend-series.apexcharts-no-click {
                                                    cursor: auto;
                                                }

                                                .apexcharts-legend .apexcharts-hidden-zero-series,
                                                .apexcharts-legend .apexcharts-hidden-null-series {
                                                    display: none !important;
                                                }

                                                .apexcharts-inactive-legend {
                                                    opacity: 0.45;
                                                }
                                            </style>
                                        </foreignObject>
                                        <g class="apexcharts-inner apexcharts-graphical" transform="translate(0, 15)">
                                            <defs>
                                                <clipPath id="gridRectMaskvjljtqj6">
                                                    <rect width="406" height="321" x="0" y="0" rx="0"
                                                        ry="0" opacity="1" stroke-width="0"
                                                        stroke="none" stroke-dasharray="0" fill="#fff"></rect>
                                                </clipPath>
                                                <clipPath id="gridRectBarMaskvjljtqj6">
                                                    <rect width="410" height="325" x="-2" y="-2" rx="0"
                                                        ry="0" opacity="1" stroke-width="0"
                                                        stroke="none" stroke-dasharray="0" fill="#fff"></rect>
                                                </clipPath>
                                                <clipPath id="gridRectMarkerMaskvjljtqj6">
                                                    <rect width="406" height="321" x="0" y="0" rx="0"
                                                        ry="0" opacity="1" stroke-width="0"
                                                        stroke="none" stroke-dasharray="0" fill="#fff"></rect>
                                                </clipPath>
                                                <clipPath id="forecastMaskvjljtqj6"></clipPath>
                                                <clipPath id="nonForecastMaskvjljtqj6"></clipPath>
                                            </defs>
                                            <g class="apexcharts-pie">
                                                <g transform="translate(0, 0) scale(1)">
                                                    <circle r="117.49073170731708" cx="203" cy="160.5"
                                                        fill="transparent"></circle>
                                                    <g class="apexcharts-slices">
                                                        <g class="apexcharts-series apexcharts-pie-series"
                                                            seriesName="Incorrectxaddress" rel="1"
                                                            data:realIndex="0">
                                                            <path
                                                                d="M 203 7.914634146341456 A 152.58536585365854 152.58536585365854 0 0 1 314.2299447109354 56.0481293978079 L 288.64705742742024 80.07205963631208 A 117.49073170731708 117.49073170731708 0 0 0 203 43.00926829268292 L 203 7.914634146341456 z "
                                                                fill="var(--bs-success)" fill-opacity="1"
                                                                stroke="#ffffff" stroke-opacity="1"
                                                                stroke-linecap="butt" stroke-width="0"
                                                                stroke-dasharray="0"
                                                                class="apexcharts-pie-area apexcharts-donut-slice-0"
                                                                index="0" j="0" data:angle="46.8"
                                                                data:startAngle="0" data:strokeWidth="0"
                                                                data:value="13"
                                                                data:pathOrig="M 203 7.914634146341456 A 152.58536585365854 152.58536585365854 0 0 1 314.2299447109354 56.0481293978079 L 288.64705742742024 80.07205963631208 A 117.49073170731708 117.49073170731708 0 0 0 203 43.00926829268292 L 203 7.914634146341456 z ">
                                                            </path>
                                                        </g>
                                                        <g class="apexcharts-series apexcharts-pie-series"
                                                            seriesName="Weatherxconditions" rel="2"
                                                            data:realIndex="1">
                                                            <path
                                                                d="M 314.2299447109354 56.0481293978079 A 152.58536585365854 152.58536585365854 0 0 1 307.4518706021921 271.7299447109354 L 283.4279403636879 246.14705742742024 A 117.49073170731708 117.49073170731708 0 0 0 288.64705742742024 80.07205963631208 L 314.2299447109354 56.0481293978079 z "
                                                                fill="color-mix(in sRGB, var(--bs-success) 80%, var(--bs-paper-bg))"
                                                                fill-opacity="1" stroke="#ffffff" stroke-opacity="1"
                                                                stroke-linecap="butt" stroke-width="0"
                                                                stroke-dasharray="0"
                                                                class="apexcharts-pie-area apexcharts-donut-slice-1"
                                                                index="0" j="1" data:angle="90.00000000000001"
                                                                data:startAngle="46.8" data:strokeWidth="0"
                                                                data:value="25"
                                                                data:pathOrig="M 314.2299447109354 56.0481293978079 A 152.58536585365854 152.58536585365854 0 0 1 307.4518706021921 271.7299447109354 L 283.4279403636879 246.14705742742024 A 117.49073170731708 117.49073170731708 0 0 0 288.64705742742024 80.07205963631208 L 314.2299447109354 56.0481293978079 z ">
                                                            </path>
                                                        </g>
                                                        <g class="apexcharts-series apexcharts-pie-series"
                                                            seriesName="FederalxHolidays" rel="3"
                                                            data:realIndex="2">
                                                            <path
                                                                d="M 307.4518706021921 271.7299447109354 A 152.58536585365854 152.58536585365854 0 0 1 113.31257223556801 283.9441540685286 L 133.94068062138737 255.551998632767 A 117.49073170731708 117.49073170731708 0 0 0 283.4279403636879 246.14705742742024 L 307.4518706021921 271.7299447109354 z "
                                                                fill="color-mix(in sRGB, var(--bs-success) 60%, var(--bs-paper-bg))"
                                                                fill-opacity="1" stroke="#ffffff" stroke-opacity="1"
                                                                stroke-linecap="butt" stroke-width="0"
                                                                stroke-dasharray="0"
                                                                class="apexcharts-pie-area apexcharts-donut-slice-2"
                                                                index="0" j="2" data:angle="79.19999999999999"
                                                                data:startAngle="136.8" data:strokeWidth="0"
                                                                data:value="22"
                                                                data:pathOrig="M 307.4518706021921 271.7299447109354 A 152.58536585365854 152.58536585365854 0 0 1 113.31257223556801 283.9441540685286 L 133.94068062138737 255.551998632767 A 117.49073170731708 117.49073170731708 0 0 0 283.4279403636879 246.14705742742024 L 307.4518706021921 271.7299447109354 z ">
                                                            </path>
                                                        </g>
                                                        <g class="apexcharts-series apexcharts-pie-series"
                                                            seriesName="Damagexduringxtransit" rel="4"
                                                            data:realIndex="3">
                                                            <path
                                                                d="M 113.31257223556801 283.9441540685286 A 152.58536585365854 152.58536585365854 0 0 1 202.97336882989012 7.91463647034945 L 202.97949399901538 43.00927008216908 A 117.49073170731708 117.49073170731708 0 0 0 133.94068062138737 255.551998632767 L 113.31257223556801 283.9441540685286 z "
                                                                fill="color-mix(in sRGB, var(--bs-success) 40%, var(--bs-paper-bg))"
                                                                fill-opacity="1" stroke="#ffffff" stroke-opacity="1"
                                                                stroke-linecap="butt" stroke-width="0"
                                                                stroke-dasharray="0"
                                                                class="apexcharts-pie-area apexcharts-donut-slice-3"
                                                                index="0" j="3" data:angle="144"
                                                                data:startAngle="216" data:strokeWidth="0"
                                                                data:value="40"
                                                                data:pathOrig="M 113.31257223556801 283.9441540685286 A 152.58536585365854 152.58536585365854 0 0 1 202.97336882989012 7.91463647034945 L 202.97949399901538 43.00927008216908 A 117.49073170731708 117.49073170731708 0 0 0 133.94068062138737 255.551998632767 L 113.31257223556801 283.9441540685286 z ">
                                                            </path>
                                                        </g>
                                                    </g>
                                                </g>
                                                <g class="apexcharts-datalabels-group"
                                                    transform="translate(0, 0) scale(1)"><text x="203" y="190.5"
                                                        text-anchor="middle" dominant-baseline="auto" font-size="15px"
                                                        font-family="var(--bs-font-family-base)" font-weight="400"
                                                        fill="var(--bs-body-color)"
                                                        class="apexcharts-text apexcharts-datalabel-label"
                                                        style="font-family: var(--bs-font-family-base);">AVG.
                                                        Exceptions</text><text x="203" y="156.5" text-anchor="middle"
                                                        dominant-baseline="auto" font-size="24px"
                                                        font-family="var(--bs-font-family-base)" font-weight="500"
                                                        fill="var(--bs-heading-color)"
                                                        class="apexcharts-text apexcharts-datalabel-value"
                                                        style="font-family: var(--bs-font-family-base);">30%</text></g>
                                            </g>
                                            <line x1="0" y1="0" x2="406" y2="0"
                                                stroke="#b6b6b6" stroke-dasharray="0" stroke-width="1"
                                                stroke-linecap="butt" class="apexcharts-ycrosshairs"></line>
                                            <line x1="0" y1="0" x2="406" y2="0"
                                                stroke="#b6b6b6" stroke-dasharray="0" stroke-width="0"
                                                stroke-linecap="butt" class="apexcharts-ycrosshairs-hidden"></line>
                                        </g>
                                        <g class="apexcharts-datalabels-group" transform="translate(0, 0) scale(1)"></g>
                                    </svg>
                                    <div class="apexcharts-tooltip apexcharts-theme-false">
                                        <div class="apexcharts-tooltip-series-group apexcharts-tooltip-series-group-0"
                                            style="order: 1;"><span class="apexcharts-tooltip-marker"
                                                style="background-color: var(--bs-success);"></span>
                                            <div class="apexcharts-tooltip-text"
                                                style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                                <div class="apexcharts-tooltip-y-group"><span
                                                        class="apexcharts-tooltip-text-y-label"></span><span
                                                        class="apexcharts-tooltip-text-y-value"></span></div>
                                                <div class="apexcharts-tooltip-goals-group"><span
                                                        class="apexcharts-tooltip-text-goals-label"></span><span
                                                        class="apexcharts-tooltip-text-goals-value"></span></div>
                                                <div class="apexcharts-tooltip-z-group"><span
                                                        class="apexcharts-tooltip-text-z-label"></span><span
                                                        class="apexcharts-tooltip-text-z-value"></span></div>
                                            </div>
                                        </div>
                                        <div class="apexcharts-tooltip-series-group apexcharts-tooltip-series-group-1"
                                            style="order: 2;"><span class="apexcharts-tooltip-marker"
                                                style="background-color: color-mix(in sRGB, var(--bs-success) 80%, var(--bs-paper-bg));"></span>
                                            <div class="apexcharts-tooltip-text"
                                                style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                                <div class="apexcharts-tooltip-y-group"><span
                                                        class="apexcharts-tooltip-text-y-label"></span><span
                                                        class="apexcharts-tooltip-text-y-value"></span></div>
                                                <div class="apexcharts-tooltip-goals-group"><span
                                                        class="apexcharts-tooltip-text-goals-label"></span><span
                                                        class="apexcharts-tooltip-text-goals-value"></span></div>
                                                <div class="apexcharts-tooltip-z-group"><span
                                                        class="apexcharts-tooltip-text-z-label"></span><span
                                                        class="apexcharts-tooltip-text-z-value"></span></div>
                                            </div>
                                        </div>
                                        <div class="apexcharts-tooltip-series-group apexcharts-tooltip-series-group-2"
                                            style="order: 3;"><span class="apexcharts-tooltip-marker"
                                                style="background-color: color-mix(in sRGB, var(--bs-success) 60%, var(--bs-paper-bg));"></span>
                                            <div class="apexcharts-tooltip-text"
                                                style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                                <div class="apexcharts-tooltip-y-group"><span
                                                        class="apexcharts-tooltip-text-y-label"></span><span
                                                        class="apexcharts-tooltip-text-y-value"></span></div>
                                                <div class="apexcharts-tooltip-goals-group"><span
                                                        class="apexcharts-tooltip-text-goals-label"></span><span
                                                        class="apexcharts-tooltip-text-goals-value"></span></div>
                                                <div class="apexcharts-tooltip-z-group"><span
                                                        class="apexcharts-tooltip-text-z-label"></span><span
                                                        class="apexcharts-tooltip-text-z-value"></span></div>
                                            </div>
                                        </div>
                                        <div class="apexcharts-tooltip-series-group apexcharts-tooltip-series-group-3"
                                            style="order: 4;"><span class="apexcharts-tooltip-marker"
                                                style="background-color: color-mix(in sRGB, var(--bs-success) 40%, var(--bs-paper-bg));"></span>
                                            <div class="apexcharts-tooltip-text"
                                                style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                                <div class="apexcharts-tooltip-y-group"><span
                                                        class="apexcharts-tooltip-text-y-label"></span><span
                                                        class="apexcharts-tooltip-text-y-value"></span></div>
                                                <div class="apexcharts-tooltip-goals-group"><span
                                                        class="apexcharts-tooltip-text-goals-label"></span><span
                                                        class="apexcharts-tooltip-text-goals-value"></span></div>
                                                <div class="apexcharts-tooltip-z-group"><span
                                                        class="apexcharts-tooltip-text-z-label"></span><span
                                                        class="apexcharts-tooltip-text-z-value"></span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/ Reasons for delivery exceptions -->
                <!-- Orders by Countries -->
                <div class="col-xxl-4 col-lg-6">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between">
                            <div class="card-title mb-0">
                                <h5 class="mb-1">Orders by Countries</h5>
                                <p class="card-subtitle">62 deliveries in progress</p>
                            </div>
                            <div class="dropdown">
                                <button class="btn text-body-secondary p-0" type="button" id="ordersCountries"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="icon-base bx bx-dots-vertical-rounded icon-lg"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="ordersCountries">
                                    <a class="dropdown-item" href="javascript:void(0);">Select All</a>
                                    <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                                    <a class="dropdown-item" href="javascript:void(0);">Share</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="nav-align-top">
                                <ul class="nav nav-tabs nav-fill rounded-0 timeline-indicator-advanced" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button type="button" class="nav-link active" role="tab"
                                            data-bs-toggle="tab" data-bs-target="#navs-justified-new"
                                            aria-controls="navs-justified-new" aria-selected="true">New</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                            data-bs-target="#navs-justified-link-preparing"
                                            aria-controls="navs-justified-link-preparing" aria-selected="false"
                                            tabindex="-1">Preparing</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                            data-bs-target="#navs-justified-link-shipping"
                                            aria-controls="navs-justified-link-shipping" aria-selected="false"
                                            tabindex="-1">Shipping</button>
                                    </li>
                                </ul>
                                <div class="tab-content border-0  mx-1">
                                    <div class="tab-pane fade show active" id="navs-justified-new" role="tabpanel">
                                        <ul class="timeline mb-0">
                                            <li class="timeline-item ps-6 border-left-dashed">
                                                <span
                                                    class="timeline-indicator-advanced timeline-indicator-success border-0 shadow-none">
                                                    <i class="icon-base bx bx-check-circle"></i>
                                                </span>
                                                <div class="timeline-event ps-1">
                                                    <div class="timeline-header">
                                                        <small class="text-success text-uppercase">sender</small>
                                                    </div>
                                                    <h6 class="my-50">Myrtle Ullrich</h6>
                                                    <p class="text-body mb-0">101 Boulder, California(CA), 95959</p>
                                                </div>
                                            </li>
                                            <li class="timeline-item ps-6 border-transparent">
                                                <span
                                                    class="timeline-indicator-advanced timeline-indicator-primary border-0 shadow-none">
                                                    <i class="icon-base bx bx-map"></i>
                                                </span>
                                                <div class="timeline-event ps-1">
                                                    <div class="timeline-header">
                                                        <small class="text-primary text-uppercase">Receiver</small>
                                                    </div>
                                                    <h6 class="my-50">Barry Schowalter</h6>
                                                    <p class="text-body mb-0">939 Orange, California(CA), 92118</p>
                                                </div>
                                            </li>
                                        </ul>
                                        <div class="border-1 border-light border-dashed my-4"></div>
                                        <ul class="timeline mb-0">
                                            <li class="timeline-item ps-6 border-left-dashed">
                                                <span
                                                    class="timeline-indicator-advanced timeline-indicator-success border-0 shadow-none">
                                                    <i class="icon-base bx bx-check-circle"></i>
                                                </span>
                                                <div class="timeline-event ps-1">
                                                    <div class="timeline-header">
                                                        <small class="text-success text-uppercase">sender</small>
                                                    </div>
                                                    <h6 class="my-50">Veronica Herman</h6>
                                                    <p class="text-body mb-0">162 Windsor, California(CA), 95492</p>
                                                </div>
                                            </li>
                                            <li class="timeline-item ps-6 border-transparent">
                                                <span
                                                    class="timeline-indicator-advanced timeline-indicator-primary border-0 shadow-none">
                                                    <i class="icon-base bx bx-map"></i>
                                                </span>
                                                <div class="timeline-event ps-1">
                                                    <div class="timeline-header">
                                                        <small class="text-primary text-uppercase">Receiver</small>
                                                    </div>
                                                    <h6 class="my-50">Helen Jacobs</h6>
                                                    <p class="text-body mb-0">487 Sunset, California(CA), 94043</p>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="tab-pane fade" id="navs-justified-link-preparing" role="tabpanel">
                                        <ul class="timeline mb-0">
                                            <li class="timeline-item ps-6 border-left-dashed">
                                                <span
                                                    class="timeline-indicator-advanced timeline-indicator-success border-0 shadow-none">
                                                    <i class="icon-base bx bx-check-circle"></i>
                                                </span>
                                                <div class="timeline-event ps-1">
                                                    <div class="timeline-header">
                                                        <small class="text-success text-uppercase">sender</small>
                                                    </div>
                                                    <h6 class="my-50">Barry Schowalter</h6>
                                                    <p class="text-body mb-0">939 Orange, California(CA), 92118</p>
                                                </div>
                                            </li>
                                            <li class="timeline-item ps-6 border-transparent border-left-dashed">
                                                <span
                                                    class="timeline-indicator-advanced timeline-indicator-primary border-0 shadow-none">
                                                    <i class="icon-base bx bx-map"></i>
                                                </span>
                                                <div class="timeline-event ps-1">
                                                    <div class="timeline-header">
                                                        <small class="text-primary text-uppercase">Receiver</small>
                                                    </div>
                                                    <h6 class="my-50">Myrtle Ullrich</h6>
                                                    <p class="text-body mb-0">101 Boulder, California(CA), 95959</p>
                                                </div>
                                            </li>
                                        </ul>
                                        <div class="border-1 border-light border-dashed my-4"></div>
                                        <ul class="timeline mb-0">
                                            <li class="timeline-item ps-6 border-left-dashed">
                                                <span
                                                    class="timeline-indicator-advanced timeline-indicator-success border-0 shadow-none">
                                                    <i class="icon-base bx bx-check-circle"></i>
                                                </span>
                                                <div class="timeline-event ps-1">
                                                    <div class="timeline-header">
                                                        <small class="text-success text-uppercase">sender</small>
                                                    </div>
                                                    <h6 class="my-50">Veronica Herman</h6>
                                                    <p class="text-body mb-0">162 Windsor, California(CA), 95492</p>
                                                </div>
                                            </li>
                                            <li class="timeline-item ps-6 border-transparent">
                                                <span
                                                    class="timeline-indicator-advanced timeline-indicator-primary border-0 shadow-none">
                                                    <i class="icon-base bx bx-map"></i>
                                                </span>
                                                <div class="timeline-event ps-1">
                                                    <div class="timeline-header">
                                                        <small class="text-primary text-uppercase">Receiver</small>
                                                    </div>
                                                    <h6 class="my-50">Helen Jacobs</h6>
                                                    <p class="text-body mb-0">487 Sunset, California(CA), 94043</p>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="tab-pane fade" id="navs-justified-link-shipping" role="tabpanel">
                                        <ul class="timeline mb-0">
                                            <li class="timeline-item ps-6 border-left-dashed">
                                                <span
                                                    class="timeline-indicator-advanced timeline-indicator-success border-0 shadow-none">
                                                    <i class="icon-base bx bx-check-circle"></i>
                                                </span>
                                                <div class="timeline-event ps-1">
                                                    <div class="timeline-header">
                                                        <small class="text-success text-uppercase">sender</small>
                                                    </div>
                                                    <h6 class="my-50">Veronica Herman</h6>
                                                    <p class="text-body mb-0">101 Boulder, California(CA), 95959</p>
                                                </div>
                                            </li>
                                            <li class="timeline-item ps-6 border-transparent">
                                                <span
                                                    class="timeline-indicator-advanced timeline-indicator-primary border-0 shadow-none">
                                                    <i class="icon-base bx bx-map"></i>
                                                </span>
                                                <div class="timeline-event ps-1">
                                                    <div class="timeline-header">
                                                        <small class="text-primary text-uppercase">Receiver</small>
                                                    </div>
                                                    <h6 class="my-50">Barry Schowalter</h6>
                                                    <p class="text-body mb-0">939 Orange, California(CA), 92118</p>
                                                </div>
                                            </li>
                                        </ul>
                                        <div class="border-1 border-light border-dashed my-4"></div>
                                        <ul class="timeline mb-0">
                                            <li class="timeline-item ps-6 border-left-dashed">
                                                <span
                                                    class="timeline-indicator-advanced timeline-indicator-success border-0 shadow-none">
                                                    <i class="icon-base bx bx-check-circle"></i>
                                                </span>
                                                <div class="timeline-event ps-1">
                                                    <div class="timeline-header">
                                                        <small class="text-success text-uppercase">sender</small>
                                                    </div>
                                                    <h6 class="my-50">Myrtle Ullrich</h6>
                                                    <p class="text-body mb-0">162 Windsor, California(CA), 95492</p>
                                                </div>
                                            </li>
                                            <li class="timeline-item ps-6 border-transparent">
                                                <span
                                                    class="timeline-indicator-advanced timeline-indicator-primary border-0 shadow-none">
                                                    <i class="icon-base bx bx-map"></i>
                                                </span>
                                                <div class="timeline-event ps-1">
                                                    <div class="timeline-header">
                                                        <small class="text-primary text-uppercase">Receiver</small>
                                                    </div>
                                                    <h6 class="my-50">Helen Jacobs</h6>
                                                    <p class="text-body mb-0">487 Sunset, California(CA), 94043</p>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/ Orders by Countries -->
                <!-- On route vehicles Table -->
                <div class="col-12 order-5">
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <div class="card-title mb-0">
                                <h5 class="m-0 me-2">On route vehicles</h5>
                            </div>
                            <div class="dropdown">
                                <button class="btn text-body-secondary p-0" type="button" id="routeVehicles"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="icon-base bx bx-dots-vertical-rounded icon-lg"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="routeVehicles">
                                    <a class="dropdown-item" href="javascript:void(0);">Select All</a>
                                    <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                                    <a class="dropdown-item" href="javascript:void(0);">Share</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-datatable border-top">
                            <div id="DataTables_Table_0_wrapper" class="dt-container dt-bootstrap5 dt-empty-footer">
                                <div class="row mt-2 justify-content-between">
                                    <div
                                        class="d-md-flex justify-content-between align-items-center dt-layout-start col-md-auto me-auto my-0">
                                    </div>
                                    <div
                                        class="d-md-flex justify-content-between align-items-center dt-layout-end col-md-auto ms-auto my-0">
                                    </div>
                                </div>
                                <div class="justify-content-between dt-layout-table mt-n2">
                                    <div
                                        class="d-md-flex justify-content-between align-items-center col-12 dt-layout-full col-md">
                                        <table class="dt-route-vehicles table dataTable dtr-column"
                                            id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info"
                                            style="width: 100%;">
                                            <colgroup>
                                                <col data-dt-column="1" style="width: 58px;">
                                                <col data-dt-column="2" style="width: 178.288px;">
                                                <col data-dt-column="3" style="width: 158.012px;">
                                                <col data-dt-column="4" style="width: 136.413px;">
                                                <col data-dt-column="5" style="width: 214.25px;">
                                                <col data-dt-column="6" style="width: 186.238px;">
                                            </colgroup>
                                            <thead>
                                                <tr>
                                                    <th data-dt-column="0" class="control dt-orderable-none dtr-hidden"
                                                        rowspan="1" colspan="1" aria-label=""
                                                        style="display: none;"><span
                                                            class="dt-column-title"></span><span
                                                            class="dt-column-order"></span></th>
                                                    <th data-dt-column="1" rowspan="1" colspan="1"
                                                        class="dt-select dt-orderable-none" aria-label=""><span
                                                            class="dt-column-title"></span><span
                                                            class="dt-column-order"></span><input
                                                            class="form-check-input" type="checkbox"
                                                            aria-label="Select all rows"></th>
                                                    <th data-dt-column="2" rowspan="1" colspan="1"
                                                        class="dt-orderable-asc dt-orderable-desc dt-ordering-asc"
                                                        aria-sort="ascending"
                                                        aria-label="location: Activate to invert sorting"
                                                        tabindex="0"><span class="dt-column-title"
                                                            role="button">location</span><span
                                                            class="dt-column-order"></span></th>
                                                    <th data-dt-column="3" rowspan="1" colspan="1"
                                                        class="dt-orderable-asc dt-orderable-desc"
                                                        aria-label="starting route: Activate to sort" tabindex="0">
                                                        <span class="dt-column-title" role="button">starting
                                                            route</span><span class="dt-column-order"></span></th>
                                                    <th data-dt-column="4" rowspan="1" colspan="1"
                                                        class="dt-orderable-asc dt-orderable-desc"
                                                        aria-label="ending route: Activate to sort" tabindex="0"><span
                                                            class="dt-column-title" role="button">ending
                                                            route</span><span class="dt-column-order"></span></th>
                                                    <th data-dt-column="5" rowspan="1" colspan="1"
                                                        class="dt-orderable-asc dt-orderable-desc"
                                                        aria-label="warnings: Activate to sort" tabindex="0"><span
                                                            class="dt-column-title" role="button">warnings</span><span
                                                            class="dt-column-order"></span></th>
                                                    <th class="w-20 dt-orderable-asc dt-orderable-desc dt-type-numeric"
                                                        data-dt-column="6" rowspan="1" colspan="1"
                                                        aria-label="progress: Activate to sort" tabindex="0"><span
                                                            class="dt-column-title" role="button">progress</span><span
                                                            class="dt-column-order"></span></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="control dtr-hidden" tabindex="0"
                                                        style="display: none;"></td>
                                                    <td class="dt-select"><input aria-label="Select row"
                                                            class="form-check-input" type="checkbox"></td>
                                                    <td class="sorting_1">
                                                        <div
                                                            class="d-flex justify-content-start align-items-center user-name">
                                                            <div class="avatar-wrapper">
                                                                <div class="avatar me-4">
                                                                    <span
                                                                        class="avatar-initial rounded-circle bg-label-secondary">
                                                                        <i class="icon-base bx bxs-truck icon-lg"></i>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="d-flex flex-column">
                                                                <a class="text-heading text-nowrap fw-medium"
                                                                    href="app-logistics-fleet.html">VOL-159145</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="text-body">
                                                            Paris 19, France
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="text-body">
                                                            Dresden, Germany
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge rounded bg-label-success">
                                                            No Warnings
                                                        </span>
                                                    </td>
                                                    <td class="dt-type-numeric">
                                                        <div class="d-flex align-items-center">
                                                            <div class="progress w-100" style="height: 8px;">
                                                                <div class="progress-bar" role="progressbar"
                                                                    style="width: 60%" aria-valuenow="60"
                                                                    aria-valuemin="0" aria-valuemax="100">
                                                                </div>
                                                            </div>
                                                            <div class="text-body ms-3">60%</div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="control dtr-hidden" tabindex="0"
                                                        style="display: none;"></td>
                                                    <td class="dt-select"><input aria-label="Select row"
                                                            class="form-check-input" type="checkbox"></td>
                                                    <td class="sorting_1">
                                                        <div
                                                            class="d-flex justify-content-start align-items-center user-name">
                                                            <div class="avatar-wrapper">
                                                                <div class="avatar me-4">
                                                                    <span
                                                                        class="avatar-initial rounded-circle bg-label-secondary">
                                                                        <i class="icon-base bx bxs-truck icon-lg"></i>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="d-flex flex-column">
                                                                <a class="text-heading text-nowrap fw-medium"
                                                                    href="app-logistics-fleet.html">VOL-182964</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="text-body">
                                                            Saintes, France
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="text-body">
                                                            Roma, Italy
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge rounded bg-label-primary">
                                                            Fuel Problems
                                                        </span>
                                                    </td>
                                                    <td class="dt-type-numeric">
                                                        <div class="d-flex align-items-center">
                                                            <div class="progress w-100" style="height: 8px;">
                                                                <div class="progress-bar" role="progressbar"
                                                                    style="width: 82%" aria-valuenow="82"
                                                                    aria-valuemin="0" aria-valuemax="100">
                                                                </div>
                                                            </div>
                                                            <div class="text-body ms-3">82%</div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="control dtr-hidden" tabindex="0"
                                                        style="display: none;"></td>
                                                    <td class="dt-select"><input aria-label="Select row"
                                                            class="form-check-input" type="checkbox"></td>
                                                    <td class="sorting_1">
                                                        <div
                                                            class="d-flex justify-content-start align-items-center user-name">
                                                            <div class="avatar-wrapper">
                                                                <div class="avatar me-4">
                                                                    <span
                                                                        class="avatar-initial rounded-circle bg-label-secondary">
                                                                        <i class="icon-base bx bxs-truck icon-lg"></i>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="d-flex flex-column">
                                                                <a class="text-heading text-nowrap fw-medium"
                                                                    href="app-logistics-fleet.html">VOL-276904</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="text-body">
                                                            Aulnay-sous-Bois, France
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="text-body">
                                                            Torino, Italy
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge rounded bg-label-warning">
                                                            Temperature Not Optimal
                                                        </span>
                                                    </td>
                                                    <td class="dt-type-numeric">
                                                        <div class="d-flex align-items-center">
                                                            <div class="progress w-100" style="height: 8px;">
                                                                <div class="progress-bar" role="progressbar"
                                                                    style="width: 30%" aria-valuenow="30"
                                                                    aria-valuemin="0" aria-valuemax="100">
                                                                </div>
                                                            </div>
                                                            <div class="text-body ms-3">30%</div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="control dtr-hidden" tabindex="0"
                                                        style="display: none;"></td>
                                                    <td class="dt-select"><input aria-label="Select row"
                                                            class="form-check-input" type="checkbox"></td>
                                                    <td class="sorting_1">
                                                        <div
                                                            class="d-flex justify-content-start align-items-center user-name">
                                                            <div class="avatar-wrapper">
                                                                <div class="avatar me-4">
                                                                    <span
                                                                        class="avatar-initial rounded-circle bg-label-secondary">
                                                                        <i class="icon-base bx bxs-truck icon-lg"></i>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="d-flex flex-column">
                                                                <a class="text-heading text-nowrap fw-medium"
                                                                    href="app-logistics-fleet.html">VOL-300198</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="text-body">
                                                            West Palm Beach, USA
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="text-body">
                                                            Dresden, Germany
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge rounded bg-label-danger">
                                                            Ecu Not Responding
                                                        </span>
                                                    </td>
                                                    <td class="dt-type-numeric">
                                                        <div class="d-flex align-items-center">
                                                            <div class="progress w-100" style="height: 8px;">
                                                                <div class="progress-bar" role="progressbar"
                                                                    style="width: 90%" aria-valuenow="90"
                                                                    aria-valuemin="0" aria-valuemax="100">
                                                                </div>
                                                            </div>
                                                            <div class="text-body ms-3">90%</div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="control dtr-hidden" tabindex="0"
                                                        style="display: none;"></td>
                                                    <td class="dt-select"><input aria-label="Select row"
                                                            class="form-check-input" type="checkbox"></td>
                                                    <td class="sorting_1">
                                                        <div
                                                            class="d-flex justify-content-start align-items-center user-name">
                                                            <div class="avatar-wrapper">
                                                                <div class="avatar me-4">
                                                                    <span
                                                                        class="avatar-initial rounded-circle bg-label-secondary">
                                                                        <i class="icon-base bx bxs-truck icon-lg"></i>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="d-flex flex-column">
                                                                <a class="text-heading text-nowrap fw-medium"
                                                                    href="app-logistics-fleet.html">VOL-302781</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="text-body">
                                                            Köln, Germany
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="text-body">
                                                            Laspezia, Italy
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge rounded bg-label-info">
                                                            Oil Leakage
                                                        </span>
                                                    </td>
                                                    <td class="dt-type-numeric">
                                                        <div class="d-flex align-items-center">
                                                            <div class="progress w-100" style="height: 8px;">
                                                                <div class="progress-bar" role="progressbar"
                                                                    style="width: 24%" aria-valuenow="24"
                                                                    aria-valuemin="0" aria-valuemax="100">
                                                                </div>
                                                            </div>
                                                            <div class="text-body ms-3">24%</div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                            <tfoot></tfoot>
                                        </table>
                                    </div>
                                </div>
                                <div class="row mx-3 justify-content-between">
                                    <div
                                        class="d-md-flex justify-content-between align-items-center dt-layout-start col-md-auto me-auto my-0">
                                        <div class="dt-info" aria-live="polite" id="DataTables_Table_0_info"
                                            role="status">Showing 1 to 5 of 25 entries</div>
                                    </div>
                                    <div
                                        class="d-md-flex justify-content-between align-items-center dt-layout-end col-md-auto ms-auto my-0">
                                        <div class="dt-paging">
                                            <nav aria-label="pagination">
                                                <ul class="pagination">
                                                    <li class="dt-paging-button page-item disabled"><button
                                                            class="page-link previous" role="link" type="button"
                                                            aria-controls="DataTables_Table_0" aria-disabled="true"
                                                            aria-label="Previous" data-dt-idx="previous"
                                                            tabindex="-1"><i
                                                                class="icon-base bx bx-chevron-left scaleX-n1-rtl icon-18px"></i></button>
                                                    </li>
                                                    <li class="dt-paging-button page-item active"><button
                                                            class="page-link" role="link" type="button"
                                                            aria-controls="DataTables_Table_0" aria-current="page"
                                                            data-dt-idx="0">1</button></li>
                                                    <li class="dt-paging-button page-item"><button class="page-link"
                                                            role="link" type="button"
                                                            aria-controls="DataTables_Table_0"
                                                            data-dt-idx="1">2</button></li>
                                                    <li class="dt-paging-button page-item"><button class="page-link"
                                                            role="link" type="button"
                                                            aria-controls="DataTables_Table_0"
                                                            data-dt-idx="2">3</button></li>
                                                    <li class="dt-paging-button page-item"><button class="page-link"
                                                            role="link" type="button"
                                                            aria-controls="DataTables_Table_0"
                                                            data-dt-idx="3">4</button></li>
                                                    <li class="dt-paging-button page-item"><button class="page-link"
                                                            role="link" type="button"
                                                            aria-controls="DataTables_Table_0"
                                                            data-dt-idx="4">5</button></li>
                                                    <li class="dt-paging-button page-item"><button
                                                            class="page-link next" role="link" type="button"
                                                            aria-controls="DataTables_Table_0" aria-label="Next"
                                                            data-dt-idx="next"><i
                                                                class="icon-base bx bx-chevron-right scaleX-n1-rtl icon-18px"></i></button>
                                                    </li>
                                                </ul>
                                            </nav>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/ On route vehicles Table -->
            </div>

        </div>
    @endsection
