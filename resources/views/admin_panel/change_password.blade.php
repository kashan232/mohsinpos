@include('admin_panel.include.header_include')

<body>
    <!-- page-wrapper start -->
    <div class="page-wrapper default-version">

        <!-- sidebar start -->

        @include('admin_panel.include.sidebar_include')
        <!-- sidebar end -->

        <!-- navbar-wrapper start -->
        @include('admin_panel.include.navbar_include')
        <!-- navbar-wrapper end -->
        <div class="body-wrapper">
            <div class="bodywrapper__inner">

                <div class="d-flex mb-30 flex-wrap gap-3 justify-content-between align-items-center">
                    <h6 class="page-title">Password Setting</h6>
                </div>
                <div class="row mb-none-30">
                    <div class="col-lg-3 col-md-3 mb-30">

                        <div class="card b-radius--5 overflow-hidden">
                            <div class="card-body p-0">
                                <div class="d-flex p-3 bg--primary align-items-center">
                                    <div class="avatar avatar--lg">
                                        <img src="{{ asset('assets/admin/images/user.png') }}" alt="Image">
                                    </div>
                                    <div class="ps-3">
                                        <h4 class="text--white">Super Admin</h4>
                                    </div>
                                </div>
                                <ul class="list-group">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Name <span class="fw-bold">{{ Auth::user()->name }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Username <span class="fw-bold">{{ Auth::user()->username }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Email <span class="fw-bold">{{ Auth::user()->email  }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-9 col-md-9 mb-30">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-4 border-bottom pb-2">Change Password</h5>

                                <form action="{{ route('updte-change-Password') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <label for="old_password" class="required">Password</label>
                                        <input class="form-control" type="password" name="old_password" id="old_password">
                                    </div>

                                    <div class="form-group">
                                        <label for="password" class="required">New Password</label>
                                        <input class="form-control" type="password" name="new_password" id="password">
                                    </div>

                                    <div class="form-group">
                                        <label for="password_confirmation" class="required">Confirm Password</label>
                                        <input class="form-control" type="password" name="retype_new_password" id="password_confirmation">
                                    </div>
                                    <button type="submit" class="btn btn--primary w-100 btn-lg h-45">Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>



            </div>
        </div><!-- body-wrapper end -->

    </div>
    @include('admin_panel.include.footer_include')