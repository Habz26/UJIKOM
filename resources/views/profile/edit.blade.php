@extends('layouts.library')

@section('title', 'Edit Profile')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-5">
                    <h2 class="text-center mb-5">
                        <i class="bi bi-person-circle text-primary fs-1 d-block mb-3"></i>
                        Edit Profile
                    </h2>
                    
                    <!-- Tabs -->
                    <ul class="nav nav-tabs nav-fill mb-4" id="profileTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info-tab-pane" type="button" role="tab">
                                <i class="bi bi-person me-1"></i> Profile Info
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="password-tab" data-bs-toggle="tab" data-bs-target="#password-tab-pane" type="button" role="tab">
                                <i class="bi bi-lock me-1"></i> Password
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="role-tab" data-bs-toggle="tab" data-bs-target="#role-tab-pane" type="button" role="tab">
                                <i class="bi bi-person-badge me-1"></i> Role
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="delete-tab" data-bs-toggle="tab" data-bs-target="#delete-tab-pane" type="button" role="tab">
                                <i class="bi bi-trash me-1"></i> Delete Account
                            </button>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="info-tab-pane" role="tabpanel">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                        <div class="tab-pane fade" id="password-tab-pane" role="tabpanel">
                            @include('profile.partials.update-password-form')
                        </div>
                        <div class="tab-pane fade" id="role-tab-pane" role="tabpanel">
                            @include('profile.partials.update-role-form')
                        </div>
                        <div class="tab-pane fade" id="delete-tab-pane" role="tabpanel">
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

