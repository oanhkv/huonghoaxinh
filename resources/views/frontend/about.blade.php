@extends('frontend.layouts.app')

@section('title', 'Về Chúng Tôi - Hương Hoa Xinh')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">

            <div class="text-center mb-5">
                <h1 class="display-5 fw-bold text-success">Về Hương Hoa Xinh</h1>
                <p class="lead text-muted">Hoa tươi uy tín - Giao nhanh - Tận tâm từ 2018</p>
            </div>

            <div class="row align-items-center mb-5">
                <div class="col-lg-6">
                    <img src="https://source.unsplash.com/random/800x600/?flower,shop" 
                         class="img-fluid rounded shadow" alt="Hương Hoa Xinh">
                </div>
                <div class="col-lg-6">
                    <h2 class="fw-bold mb-4">Tại sao nên chọn Hương Hoa Xinh?</h2>
                    
                    <div class="mb-4">
                        <h5>🌸 Hoa tươi được chọn lọc kỹ lưỡng</h5>
                        <p>Chúng tôi nhập hoa trực tiếp từ các vườn hoa uy tín tại Đà Lạt, Lâm Đồng và nhập khẩu từ Hà Lan, Ecuador. Mỗi bó hoa đều được kiểm tra chất lượng trước khi đến tay khách hàng.</p>
                    </div>

                    <div class="mb-4">
                        <h5>🚚 Giao hàng nhanh chóng & tận nơi</h5>
                        <p>Giao hoa trong vòng 2 giờ nội thành TP.HCM. Đội ngũ shipper chuyên nghiệp, hoa được bảo quản lạnh trong suốt quá trình vận chuyển.</p>
                    </div>

                    <div class="mb-4">
                        <h5>🎨 Thiết kế theo yêu cầu</h5>
                        <p>Đội ngũ hoa sĩ giàu kinh nghiệm sẵn sàng tư vấn và thiết kế hoa theo chủ đề, sự kiện, màu sắc yêu thích của bạn.</p>
                    </div>

                    <div class="mb-4">
                        <h5>💯 Cam kết chất lượng & dịch vụ</h5>
                        <p>Hoàn tiền 100% nếu hoa không tươi hoặc không đúng như cam kết. Hỗ trợ đổi trả trong vòng 24h.</p>
                    </div>
                </div>
            </div>

            <div class="bg-light p-5 rounded-3 mb-5">
                <h4 class="text-center fw-bold mb-4">Giá trị cốt lõi của chúng tôi</h4>
                <div class="row text-center">
                    <div class="col-md-4 mb-4">
                        <i class="fas fa-leaf fa-3x text-success mb-3"></i>
                        <h5>Tươi mới từng ngày</h5>
                        <p>Hoa được nhập mới mỗi sáng</p>
                    </div>
                    <div class="col-md-4 mb-4">
                        <i class="fas fa-heart fa-3x text-danger mb-3"></i>
                        <h5>Tận tâm với từng bó hoa</h5>
                        <p>Thiết kế thủ công, đóng gói cẩn thận</p>
                    </div>
                    <div class="col-md-4 mb-4">
                        <i class="fas fa-shield-alt fa-3x text-primary mb-3"></i>
                        <h5>Uy tín & Minh bạch</h5>
                        <p>Giá cả rõ ràng, không ẩn phí</p>
                    </div>
                </div>
            </div>

            <div class="text-center">
                <h4 class="fw-bold">Cảm ơn bạn đã tin tưởng và đồng hành cùng Hương Hoa Xinh</h4>
                <p class="lead">Chúng tôi không chỉ bán hoa, chúng tôi mang đến niềm vui và yêu thương qua từng bó hoa.</p>
                <a href="{{ route('shop') }}" class="btn btn-success btn-lg px-5 mt-3">
                    Mua hoa ngay hôm nay
                </a>
            </div>

        </div>
    </div>
</div>
@endsection