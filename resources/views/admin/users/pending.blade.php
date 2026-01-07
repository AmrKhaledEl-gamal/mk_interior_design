@extends('admin.layout.layout')

@section('content')
    <main class="main-content">
        <!-- Top Bar -->
        <header class="top-bar">
            <div style="display: flex; gap: 1rem; align-items: center;">
                <button class="icon-btn" id="sidebar-toggle" style="display: none;">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <div class="search-bar">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" placeholder="Search users...">
                </div>
            </div>

            {{-- <div class="header-actions">
                <a href="{{ route('admin.users.create') }}" class="btn-primary" style="text-decoration: none;">
                    <i class="fa-solid fa-plus"></i> Add User
                </a>

            </div> --}}
        </header>

        <!-- Users Index Table -->
        <div class="orders-section animate-fade-in" style="animation-delay: 0.1s">
            <div class="card-header">
                <h3>Pending Users</h3>
                {{-- <div style="display: flex; gap: 0.5rem;">
                    <button class="btn-sm"><i class="fa-solid fa-filter"></i> Filter</button>
                    <button class="btn-sm"><i class="fa-solid fa-download"></i> Export</button>
                </div> --}}
            </div>
            @if (session('success'))
                <div style="color: green; padding: 1rem;">{{ session('success') }}</div>
            @endif
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>image</th>
                            <th>User</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Joined</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>

                                    <img src="{{ $user->getFirstMediaUrl('avatars') }}" alt="{{ $user->first_name }}"
                                        style="width: 32px; height: 32px; border-radius: 50%;">
                                </td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                                        <div>
                                            <div style="font-weight: 500;">{{ $user->first_name }} {{ $user->last_name }}
                                            </div>
                                            <div style="font-size: 0.75rem; color: var(--text-secondary);">
                                                {{ $user->email }}</div>
                                            <div style="font-size: 0.75rem; color: var(--text-secondary);">
                                                {{ $user->phone }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td><span
                                        style="background: rgba(99, 102, 241, 0.1); color: #818cf8; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.75rem;">User</span>
                                </td>
                                <td>
                                    <div>
                                        <div
                                            style="font-weight: 500; font-size: 0.75rem; color: var(--text-secondary); margin-bottom: 0.2rem;">
                                            Account</div>
                                        @if ($user->active)
                                            <span
                                                style="background: rgba(34, 197, 94, 0.1); color: #22c55e; padding: 0.1rem 0.5rem; border-radius: 9999px; font-size: 0.75rem;">Active</span>
                                        @else
                                            <span
                                                style="background: rgba(239, 68, 68, 0.1); color: #ef4444; padding: 0.1rem 0.5rem; border-radius: 9999px; font-size: 0.75rem;">Inactive</span>
                                        @endif
                                    </div>
                                    <div style="margin-top: 0.5rem;">
                                        <div
                                            style="font-weight: 500; font-size: 0.75rem; color: var(--text-secondary); margin-bottom: 0.2rem;">
                                            Dashboard</div>
                                        @if ($user->is_approved)
                                            <span
                                                style="background: rgba(99, 102, 241, 0.1); color: #6366f1; padding: 0.1rem 0.5rem; border-radius: 9999px; font-size: 0.75rem;">Approved</span>
                                        @else
                                            <span
                                                style="background: rgba(245, 158, 11, 0.1); color: #f59e0b; padding: 0.1rem 0.5rem; border-radius: 9999px; font-size: 0.75rem;">Pending</span>
                                        @endif
                                    </div>
                                </td>
                                <td>{{ $user->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div style="display: flex; gap: 0.5rem;">
                                        <a href="{{ route('admin.users.edit', $user) }}" class="action-btn"><i
                                                class="fa-solid fa-pen"></i></a>
                                        <a href="{{ route('admin.users.show', $user) }}" class="action-btn"><i
                                                class="fa-solid fa-eye"></i></a>
                                        <form action="{{ route('admin.users.approve', $user) }}" method="POST"
                                            style="display:inline;" class="approve-form">
                                            @csrf
                                            <button type="button" class="action-btn approve-btn"
                                                style="color: var(--success); background: rgba(34, 197, 94, 0.1); width: auto; padding: 0 0.5rem; font-size: 0.8rem;">
                                                <i class="fa-solid fa-check"></i> Approve
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="pagination">
            {{ $users->links() }}
        </div>

    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const approveButtons = document.querySelectorAll('.approve-btn');

            approveButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const form = this.closest('form');

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "Are you sure you want to approve this user?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, approve it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
@endsection
