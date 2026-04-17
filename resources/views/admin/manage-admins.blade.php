@extends('layouts.app')

@section('title', 'Manage Admins - GSC Cinemas')

@section('content')
<div class="container-fluid bg-danger py-4 mb-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 col-md-8">
                <h1 class="text-white fw-bold mb-2">Manage Admin Privileges</h1>
                <p class="text-white-50 mb-0">Assign or revoke administrator rights</p>
            </div>
            <div class="col-12 col-md-4 text-md-end mt-3 mt-md-0">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-light fw-bold text-danger">
                    <i class="bi bi-check-circle me-1"></i> Done
                </a>
            </div>
        </div>
    </div>
</div>

<div class="container mb-5">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- Normal Users Side -->
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-person me-2"></i>Normal Users</h5>
                </div>
                <div class="card-body">
                    <!-- User Search Form -->
                    <form method="GET" action="{{ route('admin.manage-admins') }}" class="mb-3">
                        <div class="input-group">
                            <input type="text" name="search_user" class="form-control" placeholder="Search user by name or email..." value="{{ $searchUser }}">
                            <input type="hidden" name="search_admin" value="{{ $searchAdmin }}">
                            <button class="btn btn-outline-secondary" type="submit">Search</button>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td><small class="text-muted">{{ $user->email }}</small></td>
                                    <td>
                                        <form action="{{ route('admin.toggle-admin', $user) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-success">
                                                <i class="bi bi-shield-plus me-1"></i> Add Admin
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted">No normal users found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination (uses separate param 'users_page') -->
                    <div class="d-flex justify-content-center mt-3">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Administrators Side -->
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-danger bg-opacity-10 py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-danger"><i class="bi bi-shield-check me-2"></i>Administrators</h5>
                </div>
                <div class="card-body">
                    <!-- Admin Search Form -->
                    <form method="GET" action="{{ route('admin.manage-admins') }}" class="mb-3">
                        <div class="input-group">
                            <input type="text" name="search_admin" class="form-control" placeholder="Search admin by name or email..." value="{{ $searchAdmin }}">
                            <input type="hidden" name="search_user" value="{{ $searchUser }}">
                            <button class="btn btn-outline-danger" type="submit">Search</button>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($admins as $admin)
                                <tr>
                                    <td>{{ $admin->name }}</td>
                                    <td><small class="text-muted">{{ $admin->email }}</small></td>
                                    <td>
                                        @if(auth()->id() !== $admin->id)
                                            <form action="{{ route('admin.toggle-admin', $admin) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to remove admin rights for {{ $admin->name }}?')">
                                                    <i class="bi bi-shield-minus me-1"></i> Remove Admin
                                                </button>
                                            </form>
                                        @else
                                            <span class="badge bg-secondary">It's You</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted">No admins found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination (uses separate param 'admins_page') -->
                    <div class="d-flex justify-content-center mt-3">
                        {{ $admins->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
