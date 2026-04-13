@extends('admin.layouts.admin')

@section('title', 'Cài đặt website')

@section('content')
<div class="admin-form-shell">
    <div class="card admin-form-card border-0 shadow-sm">
        <div class="admin-form-hero p-4 p-lg-5">
            <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="admin-form-icon">
                        <i class="fas fa-sliders-h"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-1">Bộ cài đặt website</h3>
                        <p class="admin-form-subtitle">Tuỳ chỉnh thương hiệu, nội dung trang chủ, SEO và thông tin liên hệ cho người dùng.</p>
                    </div>
                </div>
                <span class="badge text-bg-light border px-3 py-2">Cập nhật: {{ now()->format('H:i d/m/Y') }}</span>
            </div>
        </div>

        <div class="card-body p-4 p-lg-5">
            <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="d-grid gap-4">
                @csrf
                @method('PUT')

                <div class="admin-form-panel">
                    <h6 class="admin-section-title">1) Thông tin thương hiệu</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Tên website</label>
                            <input type="text" name="site_name" class="form-control" value="{{ old('site_name', $settings['site_name']) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tagline</label>
                            <input type="text" name="site_tagline" class="form-control" value="{{ old('site_tagline', $settings['site_tagline']) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Logo</label>
                            <input type="file" name="logo" class="form-control" accept="image/*">
                            @if(!empty($settings['logo']))
                                <div class="mt-2"><img src="{{ asset('storage/' . $settings['logo']) }}" alt="Logo" style="height: 40px;"></div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Ảnh banner trang chủ</label>
                            <input type="file" name="hero_image" class="form-control" accept="image/*">
                            @if(!empty($settings['hero_image']))
                                <div class="mt-2"><img src="{{ asset('storage/' . $settings['hero_image']) }}" alt="Banner" class="img-fluid rounded" style="max-height: 120px;"></div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="admin-form-panel">
                    <h6 class="admin-section-title">2) Nội dung trang chủ</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Tiêu đề hero</label>
                            <input type="text" name="hero_title" class="form-control" value="{{ old('hero_title', $settings['hero_title']) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nút CTA</label>
                            <input type="text" name="hero_button_text" class="form-control" value="{{ old('hero_button_text', $settings['hero_button_text']) }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Mô tả hero</label>
                            <input type="text" name="hero_subtitle" class="form-control" value="{{ old('hero_subtitle', $settings['hero_subtitle']) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Thông điệp giao hàng nhanh</label>
                            <input type="text" name="free_shipping_note" class="form-control" value="{{ old('free_shipping_note', $settings['free_shipping_note']) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Số sản phẩm nổi bật</label>
                            <input type="number" name="featured_products_limit" class="form-control" min="4" max="24" value="{{ old('featured_products_limit', $settings['featured_products_limit']) }}">
                        </div>
                    </div>
                </div>

                <div class="admin-form-panel">
                    <h6 class="admin-section-title">3) Liên hệ & mạng xã hội</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Hotline</label>
                            <input type="text" name="hotline" class="form-control" value="{{ old('hotline', $settings['hotline']) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email hỗ trợ</label>
                            <input type="email" name="support_email" class="form-control" value="{{ old('support_email', $settings['support_email']) }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Địa chỉ</label>
                            <input type="text" name="address" class="form-control" value="{{ old('address', $settings['address']) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Facebook URL</label>
                            <input type="url" name="facebook_url" class="form-control" value="{{ old('facebook_url', $settings['facebook_url']) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Instagram URL</label>
                            <input type="url" name="instagram_url" class="form-control" value="{{ old('instagram_url', $settings['instagram_url']) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">YouTube URL</label>
                            <input type="url" name="youtube_url" class="form-control" value="{{ old('youtube_url', $settings['youtube_url']) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Zalo URL</label>
                            <input type="url" name="zalo_url" class="form-control" value="{{ old('zalo_url', $settings['zalo_url']) }}">
                        </div>
                    </div>
                </div>

                <div class="admin-form-panel">
                    <h6 class="admin-section-title">4) SEO & chức năng website</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Meta title</label>
                            <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title', $settings['meta_title']) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Meta keywords</label>
                            <input type="text" name="meta_keywords" class="form-control" value="{{ old('meta_keywords', $settings['meta_keywords']) }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Meta description</label>
                            <textarea name="meta_description" class="form-control" rows="3">{{ old('meta_description', $settings['meta_description']) }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Copyright text</label>
                            <input type="text" name="copyright_text" class="form-control" value="{{ old('copyright_text', $settings['copyright_text']) }}">
                        </div>
                        <div class="col-md-3">
                            <div class="form-check form-switch mt-4 pt-2">
                                <input class="form-check-input" type="checkbox" id="enable_catalog_mode" name="enable_catalog_mode" value="1" {{ old('enable_catalog_mode', $settings['enable_catalog_mode']) == '1' ? 'checked' : '' }}>
                                <label class="form-check-label" for="enable_catalog_mode">Chế độ catalog (ẩn mua nhanh)</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check form-switch mt-4 pt-2">
                                <input class="form-check-input" type="checkbox" id="enable_reviews" name="enable_reviews" value="1" {{ old('enable_reviews', $settings['enable_reviews']) == '1' ? 'checked' : '' }}>
                                <label class="form-check-label" for="enable_reviews">Bật đánh giá sản phẩm</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-save me-2"></i>Lưu cài đặt
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
