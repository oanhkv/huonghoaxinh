"""
Replace 4 wireframe pages in huonghoaxinh_wireframes.drawio after the
contact-form → chat migration and checkout sender/recipient refactor.

Pages affected:
  - Page "05. Thanh toán (Checkout)" — sender + recipient sections
  - Page "17. Liên hệ (Contact)"     — replaced by chat hộp thoại
  - Page "41. Admin - Tin nhắn liên hệ"  — chat inbox style
  - Page "42. Admin - Chi tiết tin nhắn" — chat thread + reply box
"""
import re
import sys, io
sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

DRAWIO = r"C:\Users\Kieu Anh\Desktop\CD1\huonghoaxinh_wireframes.drawio"


# ---- helper to build cells -----------------------------------------------
def cell(cid: str, value: str, x: int, y: int, w: int, h: int, *,
         style: str = "rounded=0;whiteSpace=wrap;html=1;fillColor=#FFFFFF;strokeColor=#9E9E9E;",
         vertex: int = 1, parent: str = "1") -> str:
    # Escape XML entities in value
    safe = (value or "").replace("&", "&amp;").replace("<", "&lt;").replace(">", "&gt;").replace('"', "&quot;")
    return (
        f'<mxCell id="{cid}" value="{safe}" style="{style}" vertex="{vertex}" parent="{parent}">'
        f'<mxGeometry x="{x}" y="{y}" width="{w}" height="{h}" as="geometry" /></mxCell>'
    )


def text(cid: str, value: str, x: int, y: int, w: int, h: int, *,
         size: int = 12, bold: bool = False, align: str = "left", color: str = "#000000") -> str:
    style = (
        f"text;html=1;strokeColor=none;fillColor=none;fontColor={color};"
        f"fontSize={size};fontStyle={'1' if bold else '0'};"
        f"align={align};verticalAlign=middle;"
    )
    return cell(cid, value, x, y, w, h, style=style)


def filled(cid: str, value: str, x: int, y: int, w: int, h: int, *,
           fill: str = "#F3F4F6", stroke: str = "#9E9E9E", round_: bool = False) -> str:
    style = (
        f"rounded={'1' if round_ else '0'};whiteSpace=wrap;html=1;"
        f"fillColor={fill};strokeColor={stroke};fontSize=12;"
    )
    return cell(cid, value, x, y, w, h, style=style)


def button(cid: str, value: str, x: int, y: int, w: int, h: int, *,
           fill: str = "#198754", color: str = "#FFFFFF") -> str:
    style = (
        f"rounded=1;whiteSpace=wrap;html=1;fillColor={fill};strokeColor={fill};"
        f"fontColor={color};fontSize=12;fontStyle=1;"
    )
    return cell(cid, value, x, y, w, h, style=style)


def section_card(cid_prefix: str, title: str, x: int, y: int, w: int, h: int) -> str:
    """Outer rounded card with title bar."""
    cells = []
    cells.append(cell(f"{cid_prefix}_card", "", x, y, w, h,
                      style="rounded=1;whiteSpace=wrap;html=1;fillColor=#FFFFFF;strokeColor=#D1D5DB;arcSize=8;"))
    cells.append(cell(f"{cid_prefix}_head", title, x, y, w, 36,
                      style="rounded=0;whiteSpace=wrap;html=1;fillColor=#F0FDF4;strokeColor=#D1D5DB;fontSize=12;fontStyle=1;align=left;spacingLeft=14;"))
    return "\n".join(cells)


# =========================================================================
# PAGE 05. Thanh toán (Checkout) — sender + recipient sections
# =========================================================================
def page_checkout(prefix: str = "co") -> str:
    out = []
    # Title
    out.append(text(f"{prefix}_title", "05. Thanh toán (Checkout)", 0, 10, 1200, 36,
                    size=18, bold=True, align="center"))
    # Frame
    out.append(cell(f"{prefix}_frame", "", 20, 50, 1160, 750,
                    style="rounded=0;whiteSpace=wrap;html=1;fillColor=none;strokeColor=#000000;"))
    # Header
    out.append(filled(f"{prefix}_header", "Hương Hoa Xinh   |   Logo   Search   Cart   User", 40, 60, 1120, 36,
                      fill="#F8F8FA"))
    # Breadcrumb
    out.append(text(f"{prefix}_bc", "Trang chủ  >  Giỏ hàng  >  Thanh toán", 40, 110, 600, 22, size=11, color="#6B7280"))
    # Page heading
    out.append(text(f"{prefix}_h1", "💳  Thanh toán đơn hàng", 40, 140, 600, 28, size=16, bold=True))

    # ============ Left column ============
    x_left = 40
    w_left = 700
    y = 188

    # Section 1: Sender
    out.append(section_card(f"{prefix}_s1", "①   Thông tin người gửi", x_left, y, w_left, 200))
    out.append(text(f"{prefix}_s1_lbl1", "Họ tên người gửi *", x_left+20, y+50, 280, 20, size=11, bold=True))
    out.append(filled(f"{prefix}_s1_in1", "Nguyễn Văn A", x_left+20, y+72, 320, 32, fill="#FFFFFF"))
    out.append(text(f"{prefix}_s1_lbl2", "SĐT người gửi *", x_left+360, y+50, 280, 20, size=11, bold=True))
    out.append(filled(f"{prefix}_s1_in2", "0859 773 086", x_left+360, y+72, 320, 32, fill="#FFFFFF"))
    out.append(filled(f"{prefix}_s1_chk", "☑  Tôi cũng là người nhận hoa  (bỏ tick nếu gửi tặng người khác)",
                      x_left+20, y+120, 660, 60, fill="#ECFDF5", stroke="#10B981", round_=True))

    # Section 2: Recipient
    y2 = y + 220
    out.append(section_card(f"{prefix}_s2", "②   Thông tin người nhận   🎁", x_left, y2, w_left, 360))
    out.append(text(f"{prefix}_s2_lbl1", "Họ tên người nhận *", x_left+20, y2+50, 280, 20, size=11, bold=True))
    out.append(filled(f"{prefix}_s2_in1", "Nguyễn Thị B", x_left+20, y2+72, 320, 32, fill="#FFFFFF"))
    out.append(text(f"{prefix}_s2_lbl2", "SĐT người nhận *", x_left+360, y2+50, 280, 20, size=11, bold=True))
    out.append(filled(f"{prefix}_s2_in2", "0888 999 000", x_left+360, y2+72, 320, 32, fill="#FFFFFF"))

    out.append(text(f"{prefix}_s2_lbl3", "Địa chỉ nhận hàng *", x_left+20, y2+115, 280, 20, size=11, bold=True))
    out.append(filled(f"{prefix}_s2_in3", "Số nhà, ngõ, phường/xã, Hà Nội", x_left+20, y2+137, 660, 32, fill="#FFFFFF"))

    out.append(text(f"{prefix}_s2_lbl4", "Ngày giao", x_left+20, y2+180, 200, 20, size=11, bold=True))
    out.append(filled(f"{prefix}_s2_in4", "📅  20/05/2026", x_left+20, y2+202, 260, 32, fill="#FFFFFF"))
    out.append(text(f"{prefix}_s2_lbl5", "Khung giờ giao", x_left+300, y2+180, 200, 20, size=11, bold=True))
    out.append(filled(f"{prefix}_s2_in5", "▾  10:00 - 12:00", x_left+300, y2+202, 380, 32, fill="#FFFFFF"))

    out.append(text(f"{prefix}_s2_lbl6", "✉ Lời nhắn gửi người nhận (in trên thiệp)", x_left+20, y2+245, 400, 20, size=11, bold=True))
    out.append(filled(f"{prefix}_s2_in6", "Chúc mừng sinh nhật! Yêu thương từ A.", x_left+20, y2+267, 660, 48, fill="#FFFBEB", stroke="#FBBF24"))

    out.append(text(f"{prefix}_s2_lbl7", "🏪 Ghi chú dành cho shop", x_left+20, y2+322, 400, 20, size=11, bold=True))
    out.append(filled(f"{prefix}_s2_note", "VD: Gọi shipper trước 15 phút...", x_left+20, y2+344, 660, 0, fill="#FFFFFF"))

    # Payment method
    y3 = y2 + 380
    out.append(section_card(f"{prefix}_pm", "💳  Phương thức thanh toán", x_left, y3, w_left, 130))
    out.append(filled(f"{prefix}_pm1", "● COD - Thanh toán khi nhận hàng", x_left+20, y3+50, 320, 60, fill="#ECFDF5", stroke="#10B981", round_=True))
    out.append(filled(f"{prefix}_pm2", "○ Chuyển khoản / VietQR / MoMo", x_left+360, y3+50, 320, 60, fill="#FFFFFF", round_=True))

    # ============ Right column ============
    x_r = 760
    w_r = 400

    # Voucher
    out.append(section_card(f"{prefix}_v", "🎟  Mã giảm giá", x_r, 188, w_r, 110))
    out.append(filled(f"{prefix}_v_in", "Nhập mã...", x_r+20, 188+50, 250, 36, fill="#FFFFFF"))
    out.append(button(f"{prefix}_v_btn", "Áp dụng", x_r+280, 188+50, 100, 36))

    # Summary
    out.append(section_card(f"{prefix}_sum", "🧾  Tóm tắt đơn hàng", x_r, 318, w_r, 320))
    out.append(filled(f"{prefix}_sum_p1", "🌸  Hoa hồng đỏ × 2     1.000.000₫", x_r+20, 318+50, 360, 32, fill="#F9FAFB"))
    out.append(filled(f"{prefix}_sum_p2", "🌷  Hoa tulip × 1         500.000₫", x_r+20, 318+90, 360, 32, fill="#F9FAFB"))
    out.append(text(f"{prefix}_sum_st",  "Tạm tính:                1.500.000₫", x_r+20, 318+140, 360, 22, size=12))
    out.append(text(f"{prefix}_sum_sh",  "Phí vận chuyển:               30.000₫", x_r+20, 318+165, 360, 22, size=12))
    out.append(text(f"{prefix}_sum_dc",  "Giảm giá:                    -100.000₫", x_r+20, 318+190, 360, 22, size=12, color="#DC2626"))
    out.append(filled(f"{prefix}_sum_div", "", x_r+20, 318+215, 360, 1, fill="#D1D5DB", stroke="#D1D5DB"))
    out.append(text(f"{prefix}_sum_tot", "TỔNG CỘNG:           1.430.000₫", x_r+20, 318+225, 360, 28, size=14, bold=True, color="#D63384"))
    out.append(button(f"{prefix}_sum_btn", "🔒  Tiếp tục thanh toán", x_r+20, 318+265, 360, 42))

    return "\n        ".join(out)


# =========================================================================
# PAGE 17. Chat - Hộp thoại với cửa hàng (frontend)
# =========================================================================
def page_chat(prefix: str = "ch") -> str:
    out = []
    out.append(text(f"{prefix}_title", "17. Chat - Hộp thoại trực tiếp với cửa hàng", 0, 10, 1200, 36,
                    size=18, bold=True, align="center"))
    out.append(cell(f"{prefix}_frame", "", 20, 50, 1160, 750,
                    style="rounded=0;whiteSpace=wrap;html=1;fillColor=none;strokeColor=#000000;"))
    # Header
    out.append(filled(f"{prefix}_header", "Hương Hoa Xinh   |   Logo   Search   Cart   User dropdown ▾", 40, 60, 1120, 36,
                      fill="#F8F8FA"))
    out.append(text(f"{prefix}_bc", "Trang chủ  >  Tài khoản  >  Chat với cửa hàng", 40, 110, 700, 22, size=11, color="#6B7280"))
    out.append(text(f"{prefix}_h1", "💬 Chat với Hương Hoa Xinh", 40, 140, 700, 28, size=18, bold=True))
    out.append(text(f"{prefix}_sub", "Hộp thoại trực tiếp – tất cả tin nhắn lưu lại để xem bất kỳ lúc nào.",
                    40, 170, 700, 20, size=11, color="#6B7280"))

    # Chat container
    x0, y0, w, h = 240, 210, 720, 500
    out.append(cell(f"{prefix}_box", "", x0, y0, w, h,
                    style="rounded=1;whiteSpace=wrap;html=1;fillColor=#FAFAFA;strokeColor=#D1D5DB;arcSize=8;"))

    # Bubble: customer (right)
    out.append(filled(f"{prefix}_b1", "Mình đặt hoa lúc 14h được không shop?", x0+200, y0+20, 480, 50,
                      fill="#D1E7DD", stroke="#10B981", round_=True))
    out.append(text(f"{prefix}_b1m", "👤 Bạn  ·  14:32  19/05", x0+200, y0+72, 480, 18, size=10, align="right", color="#6B7280"))

    # Bubble: shop (left)
    out.append(filled(f"{prefix}_b2", "Dạ được anh ạ! Mình ghi chú thời gian 14h00 - 16h00 nhé. Anh muốn kèm thiệp không?",
                      x0+40, y0+105, 500, 60, fill="#FFFFFF", round_=True))
    out.append(text(f"{prefix}_b2m", "🏪 Shop  ·  14:33  19/05", x0+40, y0+167, 500, 18, size=10, color="#6B7280"))

    # Bubble: customer
    out.append(filled(f"{prefix}_b3", "Có ạ, ghi giúp mình 'Chúc mừng sinh nhật! - A'", x0+200, y0+200, 480, 50,
                      fill="#D1E7DD", stroke="#10B981", round_=True))
    out.append(text(f"{prefix}_b3m", "👤 Bạn  ·  14:34  19/05", x0+200, y0+252, 480, 18, size=10, align="right", color="#6B7280"))

    # Bubble: shop
    out.append(filled(f"{prefix}_b4", "Đã ghi nhận! Mình đang chuẩn bị, 15h sẽ giao đến ạ. Cảm ơn anh đã ủng hộ Hương Hoa Xinh 🌸",
                      x0+40, y0+285, 540, 60, fill="#FFFFFF", round_=True))
    out.append(text(f"{prefix}_b4m", "🏪 Shop  ·  14:35  19/05", x0+40, y0+347, 540, 18, size=10, color="#6B7280"))

    # Input area at bottom
    out.append(filled(f"{prefix}_in_bg", "", x0, y0+h-80, w, 80,
                      fill="#FFFFFF", stroke="#E5E7EB"))
    out.append(filled(f"{prefix}_in_box", "Nhập tin nhắn... (Enter để gửi, Shift + Enter xuống dòng)",
                      x0+20, y0+h-60, w-160, 50, fill="#F9FAFB"))
    out.append(button(f"{prefix}_in_btn", "✈ Gửi", x0+w-120, y0+h-60, 100, 50))

    # Side note: FAB
    out.append(filled(f"{prefix}_fab", "💬", 1100, 720, 60, 60,
                      fill="#D63384", stroke="#D63384", round_=True))
    out.append(text(f"{prefix}_fab_note", "Nút chat nổi\n(hiện ở mọi trang)\nbadge tin mới khi có",
                    1010, 760, 200, 50, size=9, color="#6B7280", align="center"))

    return "\n        ".join(out)


# =========================================================================
# PAGE 41. Admin - Chat với khách hàng (inbox)
# =========================================================================
def page_admin_inbox(prefix: str = "ai") -> str:
    out = []
    out.append(text(f"{prefix}_title", "41. Admin - Chat với khách hàng (chat inbox)", 0, 10, 1200, 36,
                    size=18, bold=True, align="center"))
    out.append(cell(f"{prefix}_frame", "", 20, 50, 1160, 750,
                    style="rounded=0;whiteSpace=wrap;html=1;fillColor=none;strokeColor=#000000;"))

    # Sidebar
    out.append(filled(f"{prefix}_sb", "Sidebar Admin\n\nDashboard\nSản phẩm\nDanh mục\nĐơn hàng\nKhách hàng\nVoucher\nĐánh giá\n\n● Chat với KH (3)\n\nDoanh thu\nCài đặt",
                      40, 60, 200, 740, fill="#1F2937", stroke="#1F2937"))
    # The above shows as white text on dark — drawio will respect fontColor, but our style is default black.
    # Make it lighter — use a custom style.
    out[-1] = cell(f"{prefix}_sb",
                   "Sidebar Admin\\n\\nDashboard\\nSản phẩm\\nDanh mục\\nĐơn hàng\\nKhách hàng\\nVoucher\\nĐánh giá\\n\\n● Chat với KH (3)\\n\\nDoanh thu\\nCài đặt",
                   40, 60, 200, 740,
                   style="rounded=0;whiteSpace=wrap;html=1;fillColor=#1F2937;strokeColor=#1F2937;fontColor=#FFFFFF;fontSize=11;align=left;spacingLeft=14;verticalAlign=top;spacingTop=14;")

    # Top bar
    out.append(filled(f"{prefix}_top", "Search bar  |  🔔  💬  Admin Profile ▾", 260, 60, 900, 50, fill="#FFFFFF"))

    # Card
    x, y, w, h = 260, 130, 900, 670
    out.append(cell(f"{prefix}_card", "", x, y, w, h,
                    style="rounded=1;whiteSpace=wrap;html=1;fillColor=#FFFFFF;strokeColor=#E5E7EB;arcSize=8;"))

    # Card head: title + search
    out.append(text(f"{prefix}_card_h", "💬  Hộp thoại với khách hàng", x+20, y+20, 400, 28, size=14, bold=True))
    out.append(text(f"{prefix}_card_sub", "Toàn bộ hội thoại trực tiếp giữa shop và khách. Click để mở chat.",
                    x+20, y+48, 600, 18, size=10, color="#6B7280"))
    out.append(filled(f"{prefix}_search", "🔍  Tìm theo tên / email / nội dung...", x+w-280, y+20, 260, 36, fill="#F7F8FA", round_=True))

    # Status pills
    out.append(filled(f"{prefix}_t1", "📥 Tất cả (12)", x+20, y+80, 130, 32, fill="#198754", stroke="#198754", round_=True))
    out.append(filled(f"{prefix}_t2", "🔔 Khách phản hồi mới (3)", x+160, y+80, 220, 32, fill="#FEE2E2", stroke="#FCA5A5", round_=True))
    out.append(filled(f"{prefix}_t3", "✉ Tin mới (2)", x+390, y+80, 130, 32, fill="#FEF3C7", stroke="#FCD34D", round_=True))
    out.append(filled(f"{prefix}_t4", "✅ Đã trả lời (7)", x+530, y+80, 150, 32, fill="#D1FAE5", stroke="#6EE7B7", round_=True))

    # Inbox rows
    row_y = y + 130
    for i, row in enumerate([
        ("MA", "Minh Anh",       "📍 Khách vừa nhắn", "Bạn: Có hoa hồng tươi không shop?", "minhanh@gmail.com  ·  3 tin nhắn",   "5 phút trước", True, "#D63384"),
        ("HL", "Hoàng Long",     "✅ Đã trả lời",       "Shop: Đơn của bạn đã chuẩn bị xong nhé!", "hoanglong@example.com  ·  6 tin nhắn", "2 giờ trước", False, "#198754"),
        ("PQ", "Phương Quỳnh",   "📨 Mới",              "Bạn: Cho mình tư vấn hoa cưới với ạ", "phuongquynh@example.com  ·  1 tin nhắn", "Hôm qua",    True,  "#F59E0B"),
        ("BT", "Bích Thuỷ",      "✅ Đã trả lời",       "Shop: Cảm ơn bạn đã ủng hộ shop!",       "bichthuy@example.com  ·  4 tin nhắn",  "3 ngày trước", False, "#5E60CE"),
    ]):
        ini, name, tag, preview, meta, time, unread, color = row
        bg = "#FFF5F9" if unread else "#FFFFFF"
        # Item bg
        out.append(filled(f"{prefix}_it{i}", "", x+20, row_y, w-40, 80,
                          fill=bg, stroke="#F1F2F6"))
        # Avatar
        out.append(filled(f"{prefix}_av{i}", ini, x+34, row_y+14, 52, 52,
                          fill=color, stroke=color, round_=True))
        # Make avatar text white
        # Replace style for avatar
        out[-1] = cell(f"{prefix}_av{i}", ini, x+34, row_y+14, 52, 52,
                       style=f"ellipse;whiteSpace=wrap;html=1;fillColor={color};strokeColor={color};fontColor=#FFFFFF;fontSize=14;fontStyle=1;align=center;")
        # Name + tag
        out.append(text(f"{prefix}_nm{i}", name, x+100, row_y+8, 250, 22, size=12, bold=True))
        tag_color = "#FEE2E2" if "vừa" in tag else ("#D1FAE5" if "Đã" in tag else "#FEF3C7")
        tag_fc = "#B91C1C" if "vừa" in tag else ("#065F46" if "Đã" in tag else "#92400E")
        out.append(cell(f"{prefix}_tg{i}", tag, x+330, row_y+12, 150, 22,
                        style=f"rounded=1;whiteSpace=wrap;html=1;fillColor={tag_color};strokeColor={tag_color};fontColor={tag_fc};fontSize=10;fontStyle=1;align=center;"))
        # Time on right
        out.append(text(f"{prefix}_tm{i}", time, x+w-160, row_y+12, 140, 20, size=10, align="right", color="#9CA3AF"))
        # Preview
        out.append(text(f"{prefix}_pv{i}", preview, x+100, row_y+34, 700, 18, size=11, color="#374151"))
        # Meta line
        out.append(text(f"{prefix}_mt{i}", meta, x+100, row_y+54, 700, 16, size=10, color="#9CA3AF"))
        row_y += 90

    # Pagination
    out.append(text(f"{prefix}_pg", "« 1 2 3 »", x+w-100, y+h-30, 80, 24, size=11, color="#6B7280"))

    return "\n        ".join(out)


# =========================================================================
# PAGE 42. Admin - Chi tiết hộp thoại chat (thread + reply)
# =========================================================================
def page_admin_chat(prefix: str = "ac") -> str:
    out = []
    out.append(text(f"{prefix}_title", "42. Admin - Chi tiết hộp thoại chat", 0, 10, 1200, 36,
                    size=18, bold=True, align="center"))
    out.append(cell(f"{prefix}_frame", "", 20, 50, 1160, 750,
                    style="rounded=0;whiteSpace=wrap;html=1;fillColor=none;strokeColor=#000000;"))

    # Sidebar
    out.append(cell(f"{prefix}_sb",
                    "Sidebar Admin\\n\\nDashboard\\nSản phẩm\\n...\\n● Chat với KH",
                    40, 60, 200, 740,
                    style="rounded=0;whiteSpace=wrap;html=1;fillColor=#1F2937;strokeColor=#1F2937;fontColor=#FFFFFF;fontSize=11;align=left;spacingLeft=14;verticalAlign=top;spacingTop=14;"))
    # Top bar
    out.append(filled(f"{prefix}_top", "← Quay lại  |  Search  |  🔔  💬  Admin", 260, 60, 900, 50, fill="#FFFFFF"))

    x, y, w, h = 260, 130, 900, 670
    out.append(cell(f"{prefix}_card", "", x, y, w, h,
                    style="rounded=1;whiteSpace=wrap;html=1;fillColor=#FFFFFF;strokeColor=#E5E7EB;arcSize=8;"))

    # Card head
    out.append(text(f"{prefix}_h1", "💬  Chat với Minh Anh", x+20, y+18, 400, 24, size=14, bold=True))
    out.append(text(f"{prefix}_h2", "minhanh@gmail.com  ·  0888 123 456  ·  bắt đầu 18/05/2026  ·  6 tin nhắn",
                    x+20, y+44, 600, 18, size=10, color="#6B7280"))
    out.append(button(f"{prefix}_h_back", "← Quay lại", x+w-220, y+20, 100, 32, fill="#6B7280"))
    out.append(button(f"{prefix}_h_del", "🗑 Xoá", x+w-110, y+20, 80, 32, fill="#DC2626"))

    # Chat area
    cx, cy, cw, ch = x+20, y+90, w-40, h-180
    out.append(cell(f"{prefix}_chat_bg", "", cx, cy, cw, ch,
                    style="rounded=1;whiteSpace=wrap;html=1;fillColor=#FAFAFA;strokeColor=#E5E7EB;arcSize=8;"))

    # Customer bubble (left)
    out.append(filled(f"{prefix}_b1", "Có hoa hồng tươi không shop?", cx+20, cy+20, 360, 40,
                      fill="#FFFFFF", round_=True))
    out.append(text(f"{prefix}_b1m", "👤 Minh Anh  ·  14:30  19/05", cx+20, cy+62, 360, 16, size=9, color="#6B7280"))

    # Shop bubble (right)
    out.append(filled(f"{prefix}_b2", "Dạ shop có hoa hồng đỏ - hồng pastel - hồng kem ạ! Anh muốn loại nào?",
                      cx+cw-440, cy+95, 420, 50, fill="#D1E7DD", round_=True))
    out.append(text(f"{prefix}_b2m", "🏪 Shop · admin@huonghoaxinh · 14:31  19/05", cx+cw-440, cy+147, 420, 16, size=9, align="right", color="#6B7280"))

    # Customer bubble (left)
    out.append(filled(f"{prefix}_b3", "Cho mình bó hoa hồng đỏ 20 bông nhé, giao 15h chiều nay",
                      cx+20, cy+180, 400, 40, fill="#FFFFFF", round_=True))
    out.append(text(f"{prefix}_b3m", "👤 Minh Anh  ·  14:32  19/05", cx+20, cy+222, 400, 16, size=9, color="#6B7280"))

    # Shop bubble (right)
    out.append(filled(f"{prefix}_b4", "Dạ ok, mời anh vào trang Shop chọn 'Hoa hồng đỏ - bó 20 bông' nhé!",
                      cx+cw-440, cy+255, 420, 50, fill="#D1E7DD", round_=True))
    out.append(text(f"{prefix}_b4m", "🏪 Shop · admin · 14:33  19/05", cx+cw-440, cy+307, 420, 16, size=9, align="right", color="#6B7280"))

    # Reply input area
    ry = y + h - 80
    out.append(filled(f"{prefix}_in_bg", "", x+20, ry, w-40, 60,
                      fill="#FFFFFF", stroke="#E5E7EB"))
    out.append(filled(f"{prefix}_in_box", "Trả lời khách... (Enter để gửi, Shift+Enter xuống dòng)",
                      x+30, ry+10, w-160, 40, fill="#F9FAFB"))
    out.append(button(f"{prefix}_in_btn", "✈ Gửi", x+w-130, ry+10, 100, 40))

    return "\n        ".join(out)


# =========================================================================
# Replace pages in drawio
# =========================================================================
PAGE_BUILDERS = {
    "05. Thanh toán (Checkout)": page_checkout,
    "17. Liên hệ (Contact)": page_chat,
    "41. Admin - Tin nhắn liên hệ": page_admin_inbox,
    "42. Admin - Chi tiết tin nhắn": page_admin_chat,
}

PAGE_RENAMES = {
    "17. Liên hệ (Contact)":           "17. Chat - Hộp thoại với cửa hàng",
    "41. Admin - Tin nhắn liên hệ":    "41. Admin - Chat với khách hàng",
    "42. Admin - Chi tiết tin nhắn":   "42. Admin - Chi tiết hộp thoại chat",
}


def replace_pages(content: str) -> str:
    for old_name, builder in PAGE_BUILDERS.items():
        new_name = PAGE_RENAMES.get(old_name, old_name)

        pattern = re.compile(
            r'(<diagram[^>]*name=")'
            + re.escape(old_name)
            + r'("[^>]*>)\s*<mxGraphModel[^>]*>.*?</mxGraphModel>\s*(</diagram>)',
            re.DOTALL,
        )

        new_body = builder()
        inner = (
            '<mxGraphModel dx="1200" dy="820" grid="1" gridSize="10" guides="1" '
            'tooltips="1" connect="1" arrows="1" fold="1" page="1" pageScale="1" '
            'pageWidth="1200" pageHeight="820" math="0" shadow="0">\n'
            '      <root>\n'
            '        <mxCell id="0" />\n'
            '        <mxCell id="1" parent="0" />\n'
            f'        {new_body}\n'
            '      </root>\n'
            '    </mxGraphModel>'
        )

        # Use a callable replacement to avoid backslash-group parsing issues
        def make_repl(g1, g2, g3, new_name=new_name, inner=inner):
            return f'{g1}{new_name}{g2}\n    {inner}\n  {g3}'

        def callback(m):
            return make_repl(m.group(1), m.group(2), m.group(3))

        content, n = pattern.subn(callback, content, count=1)
        print(f"  replaced [{old_name}] -> [{new_name}] : {n} time(s)")
    return content


def main():
    with open(DRAWIO, 'r', encoding='utf-8') as f:
        content = f.read()

    content = replace_pages(content)

    with open(DRAWIO, 'w', encoding='utf-8') as f:
        f.write(content)
    print("OK")


if __name__ == "__main__":
    main()
