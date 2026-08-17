@extends('users.layouts.main')

@section('content')
    <div class="container">
        <!-- Profile Wrapper-->
        <div class="profile-wrapper-area py-3">
            <!-- User Information-->
            <div class="card user-info-card">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="user-profile me-3"><img src="{{ asset('users/img/bg-img/9.jpg') }}" alt=""></div>
                    <div class="user-info">
                        <p class="mb-0 text-white">@designing-world</p>
                        <h5 class="mb-0">Suha Jannat</h5>
                    </div>
                </div>
            </div>
            <!-- User Meta Data-->
            <div class="card user-data-card">
                <div class="card-body">
                    <div class="single-profile-data d-flex align-items-center justify-content-between">
                        <div class="title d-flex align-items-center"><i class="lni lni-user"></i><span>Username</span></div>
                        <div class="data-content">@designing-world</div>
                    </div>
                    <div class="single-profile-data d-flex align-items-center justify-content-between">
                        <div class="title d-flex align-items-center"><i class="lni lni-user"></i><span>Full Name</span>
                        </div>
                        <div class="data-content">SUHA JANNAT</div>
                    </div>
                    <div class="single-profile-data d-flex align-items-center justify-content-between">
                        <div class="title d-flex align-items-center"><i class="lni lni-phone"></i><span>Phone</span></div>
                        <div class="data-content">+880 000 111 222</div>
                    </div>
                    <div class="single-profile-data d-flex align-items-center justify-content-between">
                        <div class="title d-flex align-items-center"><i class="lni lni-envelope"></i><span>Email
                                Address</span></div>
                        <div class="data-content">care@example.com </div>
                    </div>
                    <div class="single-profile-data d-flex align-items-center justify-content-between">
                        <div class="title d-flex align-items-center"><i class="lni lni-map-marker"></i><span>Shipping</span>
                        </div>
                        <div class="data-content">28/C Green Road, BD</div>
                    </div>
                    <div class="single-profile-data d-flex align-items-center justify-content-between">
                        <div class="title d-flex align-items-center"><i class="lni lni-star"></i><span>My Order</span></div>
                        <div class="data-content"><a class="btn btn-danger btn-sm" href="my-order.html">View</a></div>
                    </div>
                    <!-- Edit Profile-->
                    <div class="edit-profile-btn mt-3"><a class="btn btn-info w-100" href="edit-profile.html"><i
                                class="lni lni-pencil me-2"></i>Edit Profile</a></div>
                </div>
            </div>
        </div>
    </div>
@endsection
