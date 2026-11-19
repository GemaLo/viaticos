@extends('layouts.master')

@section('title', 'Dashboard')

@section('vendor-style')
    @vite('resources/assets/vendor/libs/apex-charts/apex-charts.scss')
@endsection

@section('vendor-script')
    @vite('resources/assets/vendor/libs/apex-charts/apexcharts.js')
@endsection

@section('page-script')
    @vite(['resources/js/app.js', 'resources/assets/js/dashboards-analytics.js'])
@endsection


@section('content')
    <div id="mainContent" class="main flex-grow p-10 ml-40 transition-all duration-300">
        <div class="card" >
            <h5 class="card-header pb-0 text-md-start text-center display-4 mb-4">Lista de Empleados Por Validar</h5>
            <div class="card-datatable" style="">
                <div id="DataTables_Table_1_wrapper" class="dt-container dt-bootstrap5">
                    <div class="row mx-3 my-0 justify-content-between">
                        <div
                            class="d-md-flex justify-content-between align-items-center dt-layout-start col-md-auto me-auto">
                            <div class="dt-length"><label for="dt-length-1">Show<select name="DataTables_Table_1_length"
                                        aria-controls="DataTables_Table_1" class="form-select" id="dt-length-1">
                                        <option value="7">7</option>
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>entries</label></div>
                        </div>
                        <div
                            class="d-md-flex justify-content-between align-items-center dt-layout-end col-md-auto ms-auto mt-0">
                            <div class="dt-search mt-0 mt-md-6"><label for="dt-search-1">Search:</label><input
                                    type="search" class="form-control ms-4" id="dt-search-1" placeholder=""
                                    aria-controls="DataTables_Table_1"></div>
                        </div>
                    </div>
                    <div class="justify-content-between dt-layout-table">
                        <div class="d-md-flex justify-content-between align-items-center col-12 dt-layout-full col-md">
                            <table
                                class="dt-fixedheader table table-bordered table-responsive dataTable dtr-column collapsed"
                                id="DataTables_Table_1" aria-describedby="DataTables_Table_1_info" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th data-dt-column="0" class="control dt-orderable-none" rowspan="1"
                                            colspan="1" aria-label="" style=""><span
                                                class="dt-column-title"></span><span class="dt-column-order"></span></th>
                                        <th data-dt-column="1" rowspan="1" colspan="1"
                                            class="dt-select dt-orderable-none" aria-label=""><span
                                                class="dt-column-title"></span><span class="dt-column-order"></span><input
                                                class="form-check-input" type="checkbox" aria-label="Select all rows"></th>
                                        <th data-dt-column="3" rowspan="1" colspan="1"
                                            class="dt-orderable-asc dt-orderable-desc" aria-label="Name: Activate to sort"
                                            tabindex="0"><span class="dt-column-title" role="button">Nombre</span><span
                                                class="dt-column-order"></span></th>
                                        <th data-dt-column="4" rowspan="1" colspan="1"
                                            class="dt-orderable-asc dt-orderable-desc" aria-label="Email: Activate to sort"
                                            tabindex="0"><span class="dt-column-title" role="button">Correo</span><span
                                                class="dt-column-order"></span></th>
                                        <th data-dt-column="5" rowspan="1" colspan="1"
                                            class="dt-orderable-asc dt-orderable-desc" aria-label="Date: Activate to sort"
                                            tabindex="0"><span class="dt-column-title" role="button">Fecha</span><span
                                                class="dt-column-order"></span></th>
                                        <th data-dt-column="6" rowspan="1" colspan="1"
                                            class="dt-orderable-asc dt-orderable-desc dt-type-numeric"
                                            aria-label="Salary: Activate to sort" tabindex="0"><span
                                                class="dt-column-title" role="button">Salary</span><span
                                                class="dt-column-order"></span></th>
                                        <th data-dt-column="7" rowspan="1" colspan="1"
                                            class="dt-orderable-asc dt-orderable-desc dtr-hidden"
                                            aria-label="Status: Activate to sort" tabindex="0" style="display: none;">
                                            <span class="dt-column-title" role="button">Estatus</span><span
                                                class="dt-column-order"></span></th>
                                        <th data-dt-column="8"
                                            class="d-flex align-items-center dt-orderable-none dtr-hidden" rowspan="1"
                                            colspan="1" aria-label="Actions" style="display: none;"><span
                                                class="dt-column-title">Action</span><span
                                                class="dt-column-order"></span></th>
                                    </tr>
                                </thead>
                                <colgroup>
                                    <col data-dt-column="0" style="width: 48px;">
                                    <col data-dt-column="1" style="width: 58px;">
                                    <col data-dt-column="3" style="width: 286.888px;">
                                    <col data-dt-column="4" style="width: 274.125px;">
                                    <col data-dt-column="5" style="width: 117.975px;">
                                    <col data-dt-column="6" style="width: 117.925px;">
                                </colgroup>
                                <tbody>
                                    <tr>
                                        <td class="control" tabindex="0" style=""></td>
                                        <td class="dt-select"><input aria-label="Select row" class="form-check-input"
                                                type="checkbox"></td>
                                        <td>
                                            <div class="d-flex justify-content-start align-items-center">
                                                <div class="avatar-wrapper">
                                                    <div class="avatar me-2">
                                                        <span
                                                            class="avatar-initial rounded-circle bg-label-danger">GG</span>
                                                    </div>
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <span class="emp_name text-truncate">Glyn Giacoppo</span>
                                                    <small class="emp_post text-truncate text-body-secondary">Software Test
                                                        Engineer</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>ggiacoppo2r@apache.org</td>
                                        <td>04/15/2021</td>
                                        <td class="dt-type-numeric"><span class="badge bg-label-danger">Por validar</span></td>
                                        <td class="dtr-hidden" style="display: none;">
                                            <span class="badge bg-label-success">
                                                Professional
                                            </span>
                                        </td>
                                        <td class="d-flex align-items-center dtr-hidden" style="display: none;">
                                            <div class="d-inline-block"><a href="javascript:;"
                                                    class="btn btn-icon dropdown-toggle hide-arrow"
                                                    data-bs-toggle="dropdown"><i
                                                        class="bx bx-dots-vertical-rounded icon-base"></i></a>
                                                <div class="dropdown-menu dropdown-menu-end m-0"><a href="javascript:;"
                                                        class="dropdown-item">Details</a><a href="javascript:;"
                                                        class="dropdown-item">Archive</a>
                                                    <div class="dropdown-divider"></div><a href="javascript:;"
                                                        class="dropdown-item text-danger delete-record">Delete</a>
                                                </div>
                                            </div><a href="javascript:;" class="btn btn-icon item-edit"><i
                                                    class="bx bxs-edit icon-base"></i></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="control" tabindex="0" style=""></td>
                                        <td class="dt-select"><input aria-label="Select row" class="form-check-input"
                                                type="checkbox"></td>
                                        <td>
                                            <div class="d-flex justify-content-start align-items-center">
                                                <div class="avatar-wrapper">
                                                    <div class="avatar me-2">
                                                        <img src="../../assets/img/avatars/10.png" alt="Avatar"
                                                            class="rounded-circle">
                                                    </div>
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <span class="emp_name text-truncate">Evangelina Carnock</span>
                                                    <small class="emp_post text-truncate text-body-secondary">Cost
                                                        Accountant</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>ecarnock2q@washington.edu</td>
                                        <td>01/26/2021</td>
                                        <td class="dt-type-numeric">$23704.82</td>
                                        <td class="dtr-hidden" style="display: none;">
                                            <span class="badge bg-label-warning">
                                                Resigned
                                            </span>
                                        </td>
                                        <td class="d-flex align-items-center dtr-hidden" style="display: none;">
                                            <div class="d-inline-block"><a href="javascript:;"
                                                    class="btn btn-icon dropdown-toggle hide-arrow"
                                                    data-bs-toggle="dropdown"><i
                                                        class="bx bx-dots-vertical-rounded icon-base"></i></a>
                                                <div class="dropdown-menu dropdown-menu-end m-0"><a href="javascript:;"
                                                        class="dropdown-item">Details</a><a href="javascript:;"
                                                        class="dropdown-item">Archive</a>
                                                    <div class="dropdown-divider"></div><a href="javascript:;"
                                                        class="dropdown-item text-danger delete-record">Delete</a>
                                                </div>
                                            </div><a href="javascript:;" class="btn btn-icon item-edit"><i
                                                    class="bx bxs-edit icon-base"></i></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="control" tabindex="0" style=""></td>
                                        <td class="dt-select"><input aria-label="Select row" class="form-check-input"
                                                type="checkbox"></td>
                                        <td>
                                            <div class="d-flex justify-content-start align-items-center">
                                                <div class="avatar-wrapper">
                                                    <div class="avatar me-2">
                                                        <img src="../../assets/img/avatars/7.png" alt="Avatar"
                                                            class="rounded-circle">
                                                    </div>
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <span class="emp_name text-truncate">Olivette Gudgin</span>
                                                    <small
                                                        class="emp_post text-truncate text-body-secondary">Paralegal</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>ogudgin2p@gizmodo.com</td>
                                        <td>04/09/2021</td>
                                        <td class="dt-type-numeric">$15211.60</td>
                                        <td class="dtr-hidden" style="display: none;">
                                            <span class="badge bg-label-success">
                                                Professional
                                            </span>
                                        </td>
                                        <td class="d-flex align-items-center dtr-hidden" style="display: none;">
                                            <div class="d-inline-block"><a href="javascript:;"
                                                    class="btn btn-icon dropdown-toggle hide-arrow"
                                                    data-bs-toggle="dropdown"><i
                                                        class="bx bx-dots-vertical-rounded icon-base"></i></a>
                                                <div class="dropdown-menu dropdown-menu-end m-0"><a href="javascript:;"
                                                        class="dropdown-item">Details</a><a href="javascript:;"
                                                        class="dropdown-item">Archive</a>
                                                    <div class="dropdown-divider"></div><a href="javascript:;"
                                                        class="dropdown-item text-danger delete-record">Delete</a>
                                                </div>
                                            </div><a href="javascript:;" class="btn btn-icon item-edit"><i
                                                    class="bx bxs-edit icon-base"></i></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="control" tabindex="0" style=""></td>
                                        <td class="dt-select"><input aria-label="Select row" class="form-check-input"
                                                type="checkbox"></td>
                                        <td>
                                            <div class="d-flex justify-content-start align-items-center">
                                                <div class="avatar-wrapper">
                                                    <div class="avatar me-2">
                                                        <span class="avatar-initial rounded-circle bg-label-info">RP</span>
                                                    </div>
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <span class="emp_name text-truncate">Reina Peckett</span>
                                                    <small class="emp_post text-truncate text-body-secondary">Quality
                                                        Control Specialist</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>rpeckett2o@timesonline.co.uk</td>
                                        <td>05/20/2021</td>
                                        <td class="dt-type-numeric">$16619.40</td>
                                        <td class="dtr-hidden" style="display: none;">
                                            <span class="badge bg-label-warning">
                                                Resigned
                                            </span>
                                        </td>
                                        <td class="d-flex align-items-center dtr-hidden" style="display: none;">
                                            <div class="d-inline-block"><a href="javascript:;"
                                                    class="btn btn-icon dropdown-toggle hide-arrow"
                                                    data-bs-toggle="dropdown"><i
                                                        class="bx bx-dots-vertical-rounded icon-base"></i></a>
                                                <div class="dropdown-menu dropdown-menu-end m-0"><a href="javascript:;"
                                                        class="dropdown-item">Details</a><a href="javascript:;"
                                                        class="dropdown-item">Archive</a>
                                                    <div class="dropdown-divider"></div><a href="javascript:;"
                                                        class="dropdown-item text-danger delete-record">Delete</a>
                                                </div>
                                            </div><a href="javascript:;" class="btn btn-icon item-edit"><i
                                                    class="bx bxs-edit icon-base"></i></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="control" tabindex="0" style=""></td>
                                        <td class="dt-select"><input aria-label="Select row" class="form-check-input"
                                                type="checkbox"></td>
                                        <td>
                                            <div class="d-flex justify-content-start align-items-center">
                                                <div class="avatar-wrapper">
                                                    <div class="avatar me-2">
                                                        <span
                                                            class="avatar-initial rounded-circle bg-label-warning">AB</span>
                                                    </div>
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <span class="emp_name text-truncate">Alaric Beslier</span>
                                                    <small class="emp_post text-truncate text-body-secondary">Tax
                                                        Accountant</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>abeslier2n@zimbio.com</td>
                                        <td>04/16/2021</td>
                                        <td class="dt-type-numeric">$19366.53</td>
                                        <td class="dtr-hidden" style="display: none;">
                                            <span class="badge bg-label-warning">
                                                Resigned
                                            </span>
                                        </td>
                                        <td class="d-flex align-items-center dtr-hidden" style="display: none;">
                                            <div class="d-inline-block"><a href="javascript:;"
                                                    class="btn btn-icon dropdown-toggle hide-arrow"
                                                    data-bs-toggle="dropdown"><i
                                                        class="bx bx-dots-vertical-rounded icon-base"></i></a>
                                                <div class="dropdown-menu dropdown-menu-end m-0"><a href="javascript:;"
                                                        class="dropdown-item">Details</a><a href="javascript:;"
                                                        class="dropdown-item">Archive</a>
                                                    <div class="dropdown-divider"></div><a href="javascript:;"
                                                        class="dropdown-item text-danger delete-record">Delete</a>
                                                </div>
                                            </div><a href="javascript:;" class="btn btn-icon item-edit"><i
                                                    class="bx bxs-edit icon-base"></i></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="control" tabindex="0" style=""></td>
                                        <td class="dt-select"><input aria-label="Select row" class="form-check-input"
                                                type="checkbox"></td>
                                        <td>
                                            <div class="d-flex justify-content-start align-items-center">
                                                <div class="avatar-wrapper">
                                                    <div class="avatar me-2">
                                                        <img src="../../assets/img/avatars/2.png" alt="Avatar"
                                                            class="rounded-circle">
                                                    </div>
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <span class="emp_name text-truncate">Edwina Ebsworth</span>
                                                    <small class="emp_post text-truncate text-body-secondary">Human
                                                        Resources Assistant</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>eebsworth2m@sbwire.com</td>
                                        <td>09/27/2021</td>
                                        <td class="dt-type-numeric">$19586.23</td>
                                        <td class="dtr-hidden" style="display: none;">
                                            <span class="badge bg-label-primary">
                                                Current
                                            </span>
                                        </td>
                                        <td class="d-flex align-items-center dtr-hidden" style="display: none;">
                                            <div class="d-inline-block"><a href="javascript:;"
                                                    class="btn btn-icon dropdown-toggle hide-arrow"
                                                    data-bs-toggle="dropdown"><i
                                                        class="bx bx-dots-vertical-rounded icon-base"></i></a>
                                                <div class="dropdown-menu dropdown-menu-end m-0"><a href="javascript:;"
                                                        class="dropdown-item">Details</a><a href="javascript:;"
                                                        class="dropdown-item">Archive</a>
                                                    <div class="dropdown-divider"></div><a href="javascript:;"
                                                        class="dropdown-item text-danger delete-record">Delete</a>
                                                </div>
                                            </div><a href="javascript:;" class="btn btn-icon item-edit"><i
                                                    class="bx bxs-edit icon-base"></i></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="control" tabindex="0" style=""></td>
                                        <td class="dt-select"><input aria-label="Select row" class="form-check-input"
                                                type="checkbox"></td>
                                        <td>
                                            <div class="d-flex justify-content-start align-items-center">
                                                <div class="avatar-wrapper">
                                                    <div class="avatar me-2">
                                                        <span
                                                            class="avatar-initial rounded-circle bg-label-warning">RH</span>
                                                    </div>
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <span class="emp_name text-truncate">Ronica Hasted</span>
                                                    <small class="emp_post text-truncate text-body-secondary">Software
                                                        Consultant</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>rhasted2l@hexun.com</td>
                                        <td>07/04/2021</td>
                                        <td class="dt-type-numeric">$24866.66</td>
                                        <td class="dtr-hidden" style="display: none;">
                                            <span class="badge bg-label-warning">
                                                Resigned
                                            </span>
                                        </td>
                                        <td class="d-flex align-items-center dtr-hidden" style="display: none;">
                                            <div class="d-inline-block"><a href="javascript:;"
                                                    class="btn btn-icon dropdown-toggle hide-arrow"
                                                    data-bs-toggle="dropdown"><i
                                                        class="bx bx-dots-vertical-rounded icon-base"></i></a>
                                                <div class="dropdown-menu dropdown-menu-end m-0"><a href="javascript:;"
                                                        class="dropdown-item">Details</a><a href="javascript:;"
                                                        class="dropdown-item">Archive</a>
                                                    <div class="dropdown-divider"></div><a href="javascript:;"
                                                        class="dropdown-item text-danger delete-record">Delete</a>
                                                </div>
                                            </div><a href="javascript:;" class="btn btn-icon item-edit"><i
                                                    class="bx bxs-edit icon-base"></i></a>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th class="control" data-dt-column="0" rowspan="1" colspan="1"
                                            style=""><span class="dt-column-title"></span></th>
                                        <th data-dt-column="1" rowspan="1" colspan="1" class="dt-select"><span
                                                class="dt-column-title"></span></th>
                                        <th data-dt-column="3" rowspan="1" colspan="1"><span
                                                class="dt-column-title">Name</span></th>
                                        <th data-dt-column="4" rowspan="1" colspan="1"><span
                                                class="dt-column-title">Email</span></th>
                                        <th data-dt-column="5" rowspan="1" colspan="1"><span
                                                class="dt-column-title">Date</span></th>
                                        <th data-dt-column="6" rowspan="1" colspan="1" class="dt-type-numeric">
                                            <span class="dt-column-title">Salary</span></th>
                                        <th data-dt-column="7" rowspan="1" colspan="1" class="dtr-hidden"
                                            style="display: none;"><span class="dt-column-title">Status</span></th>
                                        <th class="d-flex align-items-center dtr-hidden" data-dt-column="8"
                                            rowspan="1" colspan="1" style="display: none;"><span
                                                class="dt-column-title">Action</span></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="row mx-3 justify-content-between">
                        <div
                            class="d-md-flex justify-content-between align-items-center dt-layout-start col-md-auto me-auto">
                            <div class="dt-info" aria-live="polite" id="DataTables_Table_1_info" role="status">Showing
                                1 to 7 of 100 entries</div>
                        </div>
                        <div
                            class="d-md-flex justify-content-between align-items-center dt-layout-end col-md-auto ms-auto mt-0">
                            <div class="dt-paging">
                                <nav aria-label="pagination">
                                    <ul class="pagination">
                                        <li class="dt-paging-button page-item disabled"><button class="page-link previous"
                                                role="link" type="button" aria-controls="DataTables_Table_1"
                                                aria-disabled="true" aria-label="Previous" data-dt-idx="previous"
                                                tabindex="-1"><i
                                                    class="icon-base bx bx-chevron-left scaleX-n1-rtl icon-sm"></i></button>
                                        </li>
                                        <li class="dt-paging-button page-item active"><button class="page-link"
                                                role="link" type="button" aria-controls="DataTables_Table_1"
                                                aria-current="page" data-dt-idx="0">1</button></li>
                                        <li class="dt-paging-button page-item"><button class="page-link" role="link"
                                                type="button" aria-controls="DataTables_Table_1"
                                                data-dt-idx="1">2</button></li>
                                        <li class="dt-paging-button page-item"><button class="page-link" role="link"
                                                type="button" aria-controls="DataTables_Table_1"
                                                data-dt-idx="2">3</button></li>
                                        <li class="dt-paging-button page-item"><button class="page-link" role="link"
                                                type="button" aria-controls="DataTables_Table_1"
                                                data-dt-idx="3">4</button></li>
                                        <li class="dt-paging-button page-item"><button class="page-link" role="link"
                                                type="button" aria-controls="DataTables_Table_1"
                                                data-dt-idx="4">5</button></li>
                                        <li class="dt-paging-button page-item disabled"><button class="page-link ellipsis"
                                                role="link" type="button" aria-controls="DataTables_Table_1"
                                                aria-disabled="true" data-dt-idx="ellipsis" tabindex="-1">…</button>
                                        </li>
                                        <li class="dt-paging-button page-item"><button class="page-link" role="link"
                                                type="button" aria-controls="DataTables_Table_1"
                                                data-dt-idx="14">15</button></li>
                                        <li class="dt-paging-button page-item"><button class="page-link next"
                                                role="link" type="button" aria-controls="DataTables_Table_1"
                                                aria-label="Next" data-dt-idx="next"><i
                                                    class="icon-base bx bx-chevron-right scaleX-n1-rtl icon-sm"></i></button>
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
@endsection
