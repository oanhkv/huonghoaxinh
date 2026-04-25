@extends('admin.layouts.admin')

@php
    $isAdminList = ($accountType ?? 'customers') === 'admins';
    $currentListRoute = $isAdminList ? 'admin.users.admins' : 'admin.users.index';
@endphp

@section('title', $isAdminList ? 'Quản lý Admin' : 'Quản lý Khách hàng')

@section('content')
<div class="user-page">

    {{-- ============ HEADER BAR ============ --}}
    <div class="user-header">
        <div class="d-flex align-items-center gap-3 flex-wrap">
            <div class="user-header-icon {{ $isAdminList ? 'is-admin' : '' }}">
                <i class="fas fa-{{ $isAdminList ? 'user-shield' : 'users' }}"></i>
            </div>
            <div>
                <h2 class="user-header-title mb-1">{{ $isAdminList ? 'Quản lý Admin' : 'Quản lý Khách hàng' }}</h2>
                <div class="user-header-stats">
                    <span class="user-header-chip"><i class="fas fa-database me-1"></i> Tổng <strong>{{ $users->total() }}</strong> {{ $isAdminList ? 'admin' : 'khách hàng' }}</span>
                    <span class="user-header-chip"><i class="fas fa-eye me-1"></i> Hiển thị {{ $users->count() }} dòng</span>
                </div>
            </div>
        </div>
        @if($isAdminList)
            <a href="{{ route('admin.users.create') }}" class="btn btn-success">
                <i class="fas fa-user-plus me-1"></i> Thêm Admin
            </a>
        @endif
    </div>

    {{-- ============ SEARCH BAR ============ --}}
    <div class="user-search-card">
        <form method="GET" action="{{ route($currentListRoute) }}" class="user-search-form">
            <div class="user-search-input">
                <i class="fas fa-magnifying-glass"></i>
                <input type="text" name="search" placeholder="Tìm theo tên, email hoặc số điện thoại..." value="{{ request('search') }}">
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search me-1"></i> Tìm kiếm
            </button>
            @if(request('search'))
                <a href="{{ route($currentListRoute) }}" class="btn btn-outline-secondary">
                    <i class="fas fa-rotate-left me-1"></i> Làm mới
                </a>
            @endif
        </form>
    </div>

    {{-- ============ DATA TABLE ============ --}}
    <div class="admin-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th width="32%">{{ $isAdminList ? 'Tài khoản admin' : 'Khách hàng' }}</th>
                        <th>Liên hệ</th>
                        <th>Ngày {{ $isAdminList ? 'tạo' : 'đăng ký' }}</th>
                        <th>Trạng thái</th>
                        @if(! $isAdminList)
                            <th class="text-end" width="170px">Hành động</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        @php
                            $initial = strtoupper(mb_substr($user->name ?? '?', 0, 1));
                            $isLocked = (bool) ($user->is_locked ?? false);
                            $isActive = (bool) ($user->is_active ?? true);
                        @endphp
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="user-avatar {{ $isAdminList ? 'is-admin' : '' }}">{{ $initial }}</div>
                                    <div class="min-w-0">
                                        <div class="user-name">{{ $user->name }}</div>
                                        <div class="user-id">#{{ str_pad((string) $user->id, 4, '0', STR_PAD_LEFT) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-1">
                                    <span class="user-contact"><i class="fas fa-envelope text-muted me-2"></i>{{ $user->email }}</span>
                                    <span class="user-contact"><i class="fas fa-phone text-muted me-2"></i>{{ $user->phone ?? 'Chưa cập nhật' }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $user->created_at->format('d/m/Y') }}</div>
                                <div class="text-muted small">{{ $user->created_at->diffForHumans() }}</div>
                            </td>
                            <td>
                                @if($isAdminList)
                                    @if($isActive)
                                        <span class="user-status-pill is-active"><i class="fas fa-circle-check me-1"></i>Đang hoạt động</span>
                                    @else
                                        <span class="user-status-pill is-inactive"><i class="fas fa-pause-circle me-1"></i>Tạm khoá</span>
                                    @endif
                                @else
                                    @if($isLocked)
                                        <span class="user-status-pill is-locked"><i class="fas fa-lock me-1"></i>Đã khoá</span>
                                    @else
                                        <span class="user-status-pill is-active"><i class="fas fa-circle-check me-1"></i>Bình thường</span>
                                    @endif
                                @endif
                            </td>
                            @if(! $isAdminList)
                                <td class="text-end">
                                    <div class="d-inline-flex gap-1">
                                        <a href="{{ route('admin.users.show', $user) }}" class="user-action-btn" data-bs-toggle="tooltip" title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.users.edit', $user) }}" class="user-action-btn is-warning" data-bs-toggle="tooltip" title="Chỉnh sửa">
                                            <i class="fas fa-pen"></i>
                                        </a>
                                        <button type="button" class="user-action-btn is-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $user->id }}" title="Xoá">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            @endif
                        </tr>

                        @if(! $isAdminList)
                            <div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content rounded-3 border-0 shadow">
                                        <div class="modal-header border-0">
                                            <h5 class="modal-title fw-bold"><i class="fas fa-triangle-exclamation text-danger me-2"></i>Xác nhận xoá</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            Bạn có chắc muốn xoá tài khoản <strong>"{{ $user->name }}"</strong> không? Hành động này <span class="text-danger fw-bold">không thể hoàn tác</span>.
                                        </div>
                                        <div class="modal-footer border-0">
                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Huỷ</button>
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger"><i class="fas fa-trash me-1"></i> Xoá vĩnh viễn</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @empty
                        <tr>
                            <td colspan="{{ $isAdminList ? 4 : 5 }}" class="text-center py-5">
                                <div class="user-empty">
                                    <i class="fas fa-{{ $isAdminList ? 'user-shield' : 'users-slash' }} fa-3x mb-3"></i>
                                    <h5 class="fw-bold mb-1">{{ $isAdminList ? 'Chưa có tài khoản admin nào' : 'Chưa có khách hàng nào' }}</h5>
                                    <p class="text-muted small mb-0">
                                        {{ $isAdminList ? 'Hãy thêm admin đầu tiên để cùng vận hành.' : 'Khách hàng sẽ xuất hiện khi có người đăng ký mới.' }}
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="user-pagination">
                {{ $users->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>

<style>
    /* === Header bar === */
    .user-header {
        background: linear-gradient(135deg, rgba(214, 51, 132, 0.08), rgba(56, 189, 248, 0.08));
        border: 1px solid var(--hhx-border);
        border-radius: 18px;
        padding: 22px 24px;
        margin-bottom: 22px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
    }
    .user-header-icon {
        width: 56px; height: 56px;
        border-radius: 16px;
        background: linear-gradient(135deg, #38bdf8, #0ea5e9);
        color: #fff;
        display: grid; place-items: center;
        font-size: 1.5rem;
        box-shadow: 0 12px 24px rgba(14, 165, 233, 0.3);
        flex-shrink: 0;
    }
    .user-header-icon.is-admin {
        background: linear-gradient(135deg, #d63384, #f06595);
        box-shadow: 0 12px 24px rgba(214, 51, 132, 0.3);
    }
    .user-header-title {
        font-weight: 800;
        font-size: 1.45rem;
        letter-spacing: -0.02em;
        color: var(--hhx-text);
    }
    .user-header-stats { display: flex; gap: 8px; flex-wrap: wrap; }
    .user-header-chip {
        background: #fff;
        border: 1px solid var(--hhx-border);
        padding: 5px 12px;
        border-radius: 999px;
        font-size: 0.78rem;
        color: var(--hhx-muted);
        font-weight: 600;
    }
    .user-header-chip strong { color: var(--hhx-text); }

    /* === Search === */
    .user-search-card {
        background: #fff;
        border: 1px solid var(--hhx-border);
        border-radius: 16px;
        padding: 14px;
        margin-bottom: 18px;
        box-shadow: 0 4px 14px rgba(15, 23, 42, 0.04);
    }
    .user-search-form { display: flex; gap: 10px; flex-wrap: wrap; align-items: stretch; }
    .user-search-input {
        position: relative;
        flex-grow: 1;
        min-width: 240px;
    }
    .user-search-input i {
        position: absolute;
        left: 14px; top: 50%; transform: translateY(-50%);
        color: var(--hhx-muted);
    }
    .user-search-input input {
        width: 100%;
        padding: 10px 16px 10px 40px;
        border: 1px solid var(--hhx-border);
        border-radius: 12px;
        font-size: 0.9rem;
        transition: all 0.2s ease;
    }
    .user-search-input input:focus {
        outline: 0;
        border-color: var(--hhx-pink);
        box-shadow: 0 0 0 3px rgba(214, 51, 132, 0.12);
    }

    /* === Avatar === */
    .user-avatar {
        width: 44px; height: 44px;
        border-radius: 50%;
        background: linear-gradient(135deg, #38bdf8, #0ea5e9);
        color: #fff;
        display: grid; place-items: center;
        font-weight: 800;
        font-size: 1rem;
        flex-shrink: 0;
        box-shadow: 0 6px 14px rgba(14, 165, 233, 0.25);
    }
    .user-avatar.is-admin {
        background: linear-gradient(135deg, var(--hhx-pink), var(--hhx-pink-2));
        box-shadow: 0 6px 14px rgba(214, 51, 132, 0.25);
    }
    .user-name { font-weight: 700; color: var(--hhx-text); font-size: 0.95rem; line-height: 1.3; }
    .user-id { font-size: 0.72rem; color: var(--hhx-muted); font-family: 'Courier New', monospace; }

    .user-contact { font-size: 0.85rem; color: var(--hhx-text); }

    /* === Status pills === */
    .user-status-pill {
        display: inline-flex; align-items: center;
        padding: 5px 12px;
        border-radius: 999px;
        font-size: 0.74rem;
        font-weight: 700;
        border: 1px solid currentColor;
    }
    .user-status-pill.is-active { color: #198754; background: rgba(25, 135, 84, 0.08); }
    .user-status-pill.is-locked { color: #dc3545; background: rgba(220, 53, 69, 0.08); }
    .user-status-pill.is-inactive { color: #f59e0b; background: rgba(245, 158, 11, 0.08); }

    /* === Actions === */
    .user-action-btn {
        width: 36px; height: 36px;
        border-radius: 10px;
        background: var(--hhx-bg);
        color: var(--hhx-text);
        border: 1px solid var(--hhx-border);
        display: grid; place-items: center;
        text-decoration: none;
        transition: all 0.2s ease;
        font-size: 0.85rem;
    }
    .user-action-btn:hover {
        background: var(--hhx-pink);
        color: #fff;
        border-color: var(--hhx-pink);
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(214, 51, 132, 0.25);
    }
    .user-action-btn.is-warning:hover { background: #f59e0b; border-color: #f59e0b; box-shadow: 0 8px 16px rgba(245, 158, 11, 0.3); }
    .user-action-btn.is-danger:hover { background: #dc3545; border-color: #dc3545; box-shadow: 0 8px 16px rgba(220, 53, 69, 0.3); }

    /* === Empty === */
    .user-empty {
        padding: 30px 20px;
        color: var(--hhx-muted);
    }
    .user-empty i { color: #cbd5e1; }

    /* === Pagination === */
    .user-pagination {
        padding: 14px 22px;
        border-top: 1px solid var(--hhx-border);
        background: var(--hhx-bg);
    }
    .user-pagination .pagination { margin-bottom: 0; }
    .user-pagination .page-link {
        border: 1px solid var(--hhx-border);
        color: var(--hhx-text);
        margin: 0 2px;
        border-radius: 8px;
    }
    .user-pagination .page-item.active .page-link {
        background: var(--hhx-pink);
        border-color: var(--hhx-pink);
        box-shadow: 0 6px 14px rgba(214, 51, 132, 0.3);
    }

    @media (max-width: 575.98px) {
        .user-header { padding: 18px; }
        .user-header-title { font-size: 1.2rem; }
        .user-search-form { flex-direction: column; }
    }
</style>

<script>
    // Tooltip init
    [...document.querySelectorAll('[data-bs-toggle="tooltip"]')].forEach(el => new bootstrap.Tooltip(el));
</script>
@endsection
