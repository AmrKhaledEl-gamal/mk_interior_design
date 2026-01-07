@extends('admin.layout.layout')

@php
    $title = 'Dashboard';
    $subTitle = 'Home';
@endphp

@section('content')
    <main class="main-content">
        <!-- Top Bar -->
        <header class="top-bar">
            <div style="display: flex; gap: 1rem; align-items: center;">
                <button class="icon-btn" id="sidebar-toggle" style="display: none;">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <h2 style="font-size: 1.5rem; font-weight: 600;">Dashboard</h2>
            </div>
        </header>

        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card animate-fade-in" style="animation-delay: 0.1s">
                <div class="stat-header">
                    <div class="stat-icon bg-purple">
                        <i class="fa-solid fa-briefcase"></i>
                    </div>
                </div>
                <div class="stat-value">{{ $stats['active_projects'] }}</div>
                <div class="stat-label">Active Projects</div>

            </div>

            <div class="stat-card animate-fade-in" style="animation-delay: 0.2s">
                <div class="stat-header">
                    <div class="stat-icon bg-blue">
                        <i class="fa-solid fa-users"></i>
                    </div>
                </div>
                <div class="stat-value">{{ $stats['total_clients'] }}</div>
                <div class="stat-label">Total Clients</div>
            </div>
        </div>



        <!-- Recent Inquiries -->
        <div class="orders-section animate-fade-in" style="animation-delay: 0.6s">
            <div class="card-header">
                <h3>Recent Inquiries</h3>
                <button class="btn-sm">View All</button>
            </div>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Inquiry ID</th>
                            <th>Client</th>
                            <th>Date</th>
                            <th>Service Interest</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 2rem; color: var(--text-secondary);">No
                                recent
                                inquiries found</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection
