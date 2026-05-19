@extends('admin.layouts.admin')

@section('title', ($mode === 'create' ? 'Tạo' : 'Sửa') . ' bài viết Blog')

@section('head')
    {{-- Quill 2.0 rich text editor --}}
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
@endsection

@section('content')
<div class="container-fluid hhx-blog-form">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1">
                <i class="fas fa-{{ $mode === 'create' ? 'pen-to-square' : 'pen' }} text-success me-2"></i>
                {{ $mode === 'create' ? 'Tạo bài viết Blog mới' : 'Sửa bài viết' }}
            </h4>
            <div class="text-muted small">
                {{ $mode === 'create'
                    ? 'Đăng cẩm nang, mẹo chọn hoa, ý nghĩa hoa…'
                    : 'Cập nhật nội dung bài viết hiện có.' }}
            </div>
        </div>
        <div class="d-flex gap-2">
            @if($mode === 'edit')
                <a href="{{ route('blog.show', $post->slug) }}" target="_blank" class="btn btn-outline-success">
                    <i class="fas fa-eye me-1"></i> Xem trên web
                </a>
            @endif
            <a href="{{ route('admin.blog-posts.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Quay lại
            </a>
        </div>
    </div>

    <form method="POST"
          id="blogPostForm"
          action="{{ $mode === 'create'
              ? route('admin.blog-posts.store')
              : route('admin.blog-posts.update', $post) }}"
          enctype="multipart/form-data">
        @csrf
        @if($mode === 'edit')@method('PUT')@endif

        {{-- Hidden inputs Quill sẽ ghi vào trước submit --}}
        <input type="hidden" name="excerpt" id="excerptHidden" value="{{ old('excerpt', $post->excerpt) }}">
        <input type="hidden" name="content" id="contentHidden" value="{{ old('content', $post->content) }}">

        <div class="row g-4">
            {{-- ====== Cột trái: nội dung chính ====== --}}
            <div class="col-lg-8">
                <div class="hhx-form-card mb-4">
                    <div class="hhx-form-head">
                        <i class="fas fa-pen-nib text-success me-1"></i> Nội dung chính
                    </div>
                    <div class="hhx-form-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Tiêu đề <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="title" id="postTitle"
                                   value="{{ old('title', $post->title) }}"
                                   class="form-control form-control-lg @error('title') is-invalid @enderror"
                                   required maxlength="255"
                                   placeholder="VD: 5 loài hoa nên tặng vào Ngày của Mẹ">
                            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Slug (đường dẫn URL)</label>
                            <div class="input-group">
                                <span class="input-group-text">/blog/</span>
                                <input type="text" name="slug" id="postSlug"
                                       value="{{ old('slug', $post->slug) }}"
                                       class="form-control @error('slug') is-invalid @enderror"
                                       placeholder="tu-sinh-tu-tieu-de">
                                <button type="button" class="btn btn-outline-success" id="autoSlugBtn" title="Sinh từ tiêu đề">
                                    <i class="fas fa-wand-magic-sparkles"></i>
                                </button>
                                @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-text small">Để trống → tự sinh từ tiêu đề khi lưu.</div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Tóm tắt ngắn</label>
                            <div id="excerptEditor" class="hhx-quill-mini"></div>
                            <div class="form-text small">
                                <span id="excerptCounter">0</span>/500 ký tự — hiển thị ở thẻ blog ngoài trang chủ &amp; danh sách Blog.
                            </div>
                            @error('excerpt')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-0">
                            <label class="form-label fw-semibold d-flex align-items-center justify-content-between">
                                <span>Nội dung chi tiết <span class="text-danger">*</span></span>
                                <span class="badge bg-success-subtle text-success">
                                    <i class="fas fa-paint-brush me-1"></i> Trình soạn thảo nâng cao
                                </span>
                            </label>
                            <div id="contentEditor" class="hhx-quill-main"></div>
                            <div class="form-text small mt-2">
                                <i class="fas fa-info-circle me-1"></i>
                                Hỗ trợ tiêu đề, đậm/nghiêng/gạch chân, danh sách, link, ảnh, trích dẫn, căn lề, đổi font &amp; cỡ chữ.
                                Nội dung sẽ giữ nguyên format khi hiển thị trên trang Blog.
                            </div>
                            @error('content')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- ====== Cột phải ====== --}}
            <div class="col-lg-4">
                {{-- Xuất bản --}}
                <div class="hhx-form-card mb-4">
                    <div class="hhx-form-head">
                        <i class="fas fa-paper-plane text-success me-1"></i> Xuất bản
                    </div>
                    <div class="hhx-form-body">
                        <div class="hhx-publish-box mb-3">
                            <div class="form-check form-switch m-0">
                                <input type="hidden" name="is_active" value="0">
                                <input class="form-check-input" type="checkbox" id="isActive"
                                       name="is_active" value="1"
                                       {{ old('is_active', $post->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="isActive">
                                    Hiển thị công khai
                                </label>
                            </div>
                            <div class="small text-muted mt-1">Bỏ tick để giữ ở dạng nháp.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Thời gian xuất bản</label>
                            <input type="datetime-local" name="published_at"
                                   value="{{ old('published_at', optional($post->published_at)->format('Y-m-d\TH:i')) }}"
                                   class="form-control @error('published_at') is-invalid @enderror">
                            <div class="form-text small">Để trống → dùng thời điểm hiện tại khi tick "Hiển thị".</div>
                            @error('published_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <button type="submit" class="btn btn-success w-100 btn-lg">
                            <i class="fas fa-{{ $mode === 'create' ? 'plus' : 'save' }} me-1"></i>
                            {{ $mode === 'create' ? 'Tạo bài viết' : 'Lưu thay đổi' }}
                        </button>
                    </div>
                </div>

                {{-- Danh mục --}}
                <div class="hhx-form-card mb-4">
                    <div class="hhx-form-head">
                        <i class="fas fa-tags text-success me-1"></i> Danh mục bài viết
                    </div>
                    <div class="hhx-form-body">
                        <select name="blog_category_id" class="form-select @error('blog_category_id') is-invalid @enderror">
                            <option value="">-- Chưa phân loại --</option>
                            @foreach($categories as $c)
                                <option value="{{ $c->id }}"
                                    {{ (int) old('blog_category_id', $post->blog_category_id) === $c->id ? 'selected' : '' }}>
                                    {{ $c->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('blog_category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <a href="{{ route('admin.blog-categories.create') }}" target="_blank" class="small text-success d-inline-block mt-2">
                            <i class="fas fa-plus me-1"></i>Tạo danh mục mới
                        </a>
                    </div>
                </div>

                {{-- Ảnh bìa --}}
                <div class="hhx-form-card">
                    <div class="hhx-form-head">
                        <i class="fas fa-image text-success me-1"></i> Ảnh bìa
                    </div>
                    <div class="hhx-form-body">
                        <div class="hhx-cover-preview mb-3" id="coverPreview">
                            @if($post->image_url)
                                <img src="{{ $post->image_url }}" alt="Ảnh bìa">
                            @else
                                <div class="hhx-cover-placeholder">
                                    <i class="fas fa-cloud-arrow-up"></i>
                                    <div class="small text-muted mt-2">Chưa có ảnh bìa</div>
                                </div>
                            @endif
                        </div>
                        <label class="hhx-upload-btn">
                            <input type="file" name="image" accept="image/*"
                                   class="@error('image') is-invalid @enderror"
                                   id="coverInput">
                            <i class="fas fa-cloud-arrow-up me-1"></i>
                            Chọn ảnh từ máy
                        </label>
                        @error('image')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        <div class="form-text small">Tối đa 4MB. Khuyến nghị tỉ lệ 16:9, ≥ 1280×720px.</div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

{{-- ===== Quill 2.0 ===== --}}
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>

<style>
.hhx-form-card {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(15, 23, 42, .04);
}
.hhx-form-head {
    padding: 14px 18px;
    font-weight: 700;
    background: linear-gradient(135deg, #f0fdf4, #f9fafb);
    border-bottom: 1px solid #e5e7eb;
    font-size: 14px;
}
.hhx-form-body { padding: 20px; }

.hhx-publish-box {
    padding: 14px 16px; border-radius: 10px;
    background: linear-gradient(135deg, #f0fdf4, #ecfdf5);
    border: 1px dashed #86efac;
}

/* Quill — custom look matching admin theme */
.hhx-quill-main .ql-container, .hhx-quill-mini .ql-container {
    border-bottom-left-radius: 10px;
    border-bottom-right-radius: 10px;
    background: #fff;
    font-family: inherit;
    font-size: 14.5px;
}
.hhx-quill-main .ql-toolbar, .hhx-quill-mini .ql-toolbar {
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
    background: #f9fafb;
    border-color: #e5e7eb;
}
.hhx-quill-main .ql-container, .hhx-quill-main .ql-toolbar {
    border-color: #d1d5db;
}
.hhx-quill-main .ql-editor {
    min-height: 380px;
    line-height: 1.75;
}
.hhx-quill-mini .ql-editor {
    min-height: 90px;
}
.hhx-quill-main .ql-editor h1,
.hhx-quill-main .ql-editor h2,
.hhx-quill-main .ql-editor h3 {
    font-weight: 700; margin: 1.1em 0 .5em;
}
.hhx-quill-main .ql-editor blockquote {
    border-left: 4px solid #198754; padding: 6px 16px;
    background: #f0fdf4; color: #374151;
}
.hhx-quill-main .ql-editor img {
    max-width: 100%; height: auto; border-radius: 8px;
    margin: 12px 0;
}

/* Cover image preview */
.hhx-cover-preview {
    border: 2px dashed #d1d5db;
    border-radius: 12px;
    overflow: hidden;
    background: #f9fafb;
    aspect-ratio: 16/9;
    display: flex; align-items: center; justify-content: center;
    transition: border-color .2s ease, background .2s ease;
}
.hhx-cover-preview:hover { border-color: #86efac; background: #f0fdf4; }
.hhx-cover-preview img {
    width: 100%; height: 100%; object-fit: cover;
}
.hhx-cover-placeholder {
    text-align: center; color: #9ca3af;
}
.hhx-cover-placeholder i { font-size: 32px; }

.hhx-upload-btn {
    display: inline-flex; align-items: center;
    padding: 10px 16px;
    background: #ecfdf5;
    color: #065f46;
    border: 1.5px solid #6ee7b7;
    border-radius: 10px;
    font-weight: 600; cursor: pointer;
    transition: all .2s ease;
    width: 100%; justify-content: center;
}
.hhx-upload-btn:hover {
    background: #d1fae5; border-color: #34d399;
}
.hhx-upload-btn input { display: none; }
</style>

<script>
(function () {
    // ====== Auto slug ======
    const titleInput = document.getElementById('postTitle');
    const slugInput  = document.getElementById('postSlug');
    const slugBtn    = document.getElementById('autoSlugBtn');

    function slugify(s) {
        return (s || '')
            .toString()
            .normalize('NFD').replace(/[̀-ͯ]/g, '')
            .replace(/đ/gi, 'd')
            .toLowerCase()
            .trim()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-');
    }

    let slugTouched = (slugInput.value || '').trim().length > 0;
    slugInput.addEventListener('input', () => { slugTouched = true; });
    titleInput.addEventListener('input', () => {
        if (! slugTouched) slugInput.value = slugify(titleInput.value);
    });
    slugBtn.addEventListener('click', () => {
        slugInput.value = slugify(titleInput.value);
        slugTouched = true;
    });

    // ====== Cover image preview ======
    const coverInput   = document.getElementById('coverInput');
    const coverPreview = document.getElementById('coverPreview');
    coverInput.addEventListener('change', function () {
        const file = this.files && this.files[0];
        if (! file) return;
        const reader = new FileReader();
        reader.onload = e => {
            coverPreview.innerHTML = `<img src="${e.target.result}" alt="Ảnh bìa">`;
        };
        reader.readAsDataURL(file);
    });

    // ====== Quill editors ======
    const mainToolbar = [
        [{ 'header': [1, 2, 3, 4, false] }, { 'font': [] }],
        [{ 'size': ['small', false, 'large', 'huge'] }],
        ['bold', 'italic', 'underline', 'strike'],
        [{ 'color': [] }, { 'background': [] }],
        [{ 'align': [] }, { 'list': 'ordered' }, { 'list': 'bullet' }],
        [{ 'indent': '-1' }, { 'indent': '+1' }],
        ['blockquote', 'code-block', 'link', 'image'],
        ['clean'],
    ];

    const contentQuill = new Quill('#contentEditor', {
        theme: 'snow',
        placeholder: 'Bắt đầu viết bài tại đây… (chọn cỡ chữ, font, căn lề, chèn ảnh, trích dẫn…)',
        modules: {
            toolbar: mainToolbar,
            history: { delay: 800, maxStack: 200, userOnly: true },
        }
    });

    const excerptToolbar = [
        ['bold', 'italic', 'underline'],
        [{ 'list': 'bullet' }],
        ['clean'],
    ];
    const excerptQuill = new Quill('#excerptEditor', {
        theme: 'snow',
        placeholder: '1–2 câu giới thiệu ngắn, hiển thị ở thẻ blog ngoài trang chủ…',
        modules: { toolbar: excerptToolbar }
    });

    // Pre-fill content
    const contentHidden  = document.getElementById('contentHidden');
    const excerptHidden  = document.getElementById('excerptHidden');
    if (contentHidden.value) {
        contentQuill.clipboard.dangerouslyPasteHTML(contentHidden.value);
    }
    if (excerptHidden.value) {
        excerptQuill.clipboard.dangerouslyPasteHTML(excerptHidden.value);
    }

    // Counter for excerpt
    const counter = document.getElementById('excerptCounter');
    function updateCounter() {
        const text = excerptQuill.getText().trim();
        counter.textContent = text.length;
        counter.style.color = text.length > 500 ? '#dc2626' : '#6b7280';
    }
    excerptQuill.on('text-change', updateCounter);
    updateCounter();

    // ====== Submit handler ======
    document.getElementById('blogPostForm').addEventListener('submit', function (e) {
        // Pull HTML from Quill into hidden inputs
        // Strip empty editor → empty string
        const contentHtml = contentQuill.root.innerHTML.trim();
        contentHidden.value = (contentQuill.getText().trim().length === 0 && ! /<img|<iframe/.test(contentHtml))
            ? '' : contentHtml;

        const excerptHtml = excerptQuill.root.innerHTML.trim();
        excerptHidden.value = excerptQuill.getText().trim().length === 0 ? '' : excerptHtml;

        // Required check on content
        if (! contentHidden.value) {
            e.preventDefault();
            alert('Vui lòng nhập nội dung bài viết.');
            contentQuill.focus();
            return false;
        }
    });
})();
</script>
@endsection
