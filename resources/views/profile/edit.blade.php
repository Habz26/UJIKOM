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
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    const urlParams = new URLSearchParams(window.location.search);
                                    const tab = urlParams.get('tab') || '{{ $activeTab ?? 'info' }}';
                                    const tabButton = document.querySelector('[data-bs-target="#' + tab + '-tab-pane"]');
                                    if (tabButton) {
                                        tabButton.click();
                                    }
                                });
                            </script>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="info-tab" data-bs-toggle="tab"
                                    data-bs-target="#info-tab-pane" type="button" role="tab">
                                    <i class="bi bi-person me-1"></i> Profile Info
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="password-tab" data-bs-toggle="tab"
                                    data-bs-target="#password-tab-pane" type="button" role="tab">
                                    <i class="bi bi-lock me-1"></i> Password
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="delete-tab" data-bs-toggle="tab"
                                    data-bs-target="#delete-tab-pane" type="button" role="tab">
                                    <i class="bi bi-trash me-1"></i> Delete Account
                                </button>
                            </li>
                            @if (auth()->user()->role === 'admin')
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="categories-tab" data-bs-toggle="tab"
                                        data-bs-target="#categories-tab-pane" type="button" role="tab">
                                        <i class="bi bi-tags me-1"></i> Master Kategori
                                    </button>
                                </li>
                            @endif
                        </ul>

                        <!-- Tab Content -->
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="info-tab-pane" role="tabpanel">
@include('profile.partials.update-profile-information-form')
                            <!-- Cropper.js CDN -->
                            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
                            <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
                            </div>

                            <div class="tab-pane fade" id="password-tab-pane" role="tabpanel">
                                @include('profile.partials.update-password-form')
                            </div>
                            <div class="tab-pane fade" id="delete-tab-pane" role="tabpanel">
                                @include('profile.partials.delete-user-form')
                            </div>
                            @if (auth()->user()->role === 'admin')
                                <div class="tab-pane fade" id="categories-tab-pane" role="tabpanel">
                                    @include('profile.partials.category-management')
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
