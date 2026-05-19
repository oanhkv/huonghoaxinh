"""
Append 4 new wireframe pages for the newly-added Blog admin module:
  53. Admin - Blog - Bài viết (list)
  54. Admin - Blog - Tạo/Sửa bài viết (form + Quill editor)
  55. Admin - Blog - Danh mục blog (list)
  56. Admin - Blog - Tạo/Sửa danh mục blog (form)
"""
import sys, io
sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

DRAWIO = r"C:\Users\Kieu Anh\Desktop\CD1\huonghoaxinh_wireframes.before.drawio"


# ---- helpers ----
def cell(cid, value, x, y, w, h, *,
         style="rounded=0;whiteSpace=wrap;html=1;fillColor=#FFFFFF;strokeColor=#9E9E9E;",
         vertex=1, parent="1"):
    safe = (value or "").replace("&", "&amp;").replace("<", "&lt;").replace(">", "&gt;").replace('"', "&quot;")
    return (
        f'<mxCell id="{cid}" value="{safe}" style="{style}" vertex="{vertex}" parent="{parent}">'
        f'<mxGeometry x="{x}" y="{y}" width="{w}" height="{h}" as="geometry" /></mxCell>'
    )


def text(cid, value, x, y, w, h, *,
         size=12, bold=False, align="left", color="#000000", italic=False):
    style = (
        f"text;html=1;strokeColor=none;fillColor=none;fontColor={color};"
        f"fontSize={size};fontStyle={(1 if bold else 0) + (2 if italic else 0)};"
        f"align={align};verticalAlign=middle;"
    )
    return cell(cid, value, x, y, w, h, style=style)


def filled(cid, value, x, y, w, h, *,
           fill="#F3F4F6", stroke="#9E9E9E", round_=False, fontSize=12, fontColor="#000000",
           bold=False):
    style = (
        f"rounded={'1' if round_ else '0'};whiteSpace=wrap;html=1;"
        f"fillColor={fill};strokeColor={stroke};fontSize={fontSize};fontColor={fontColor};"
        f"fontStyle={'1' if bold else '0'};"
    )
    return cell(cid, value, x, y, w, h, style=style)


def button(cid, value, x, y, w, h, *,
           fill="#198754", color="#FFFFFF", bold=True):
    style = (
        f"rounded=1;whiteSpace=wrap;html=1;fillColor={fill};strokeColor={fill};"
        f"fontColor={color};fontSize=12;fontStyle={'1' if bold else '0'};"
    )
    return cell(cid, value, x, y, w, h, style=style)


def section_card(cid_prefix, title, x, y, w, h):
    out = []
    out.append(cell(f"{cid_prefix}_card", "", x, y, w, h,
                    style="rounded=1;whiteSpace=wrap;html=1;fillColor=#FFFFFF;strokeColor=#D1D5DB;arcSize=8;"))
    out.append(cell(f"{cid_prefix}_head", title, x, y, w, 36,
                    style="rounded=0;whiteSpace=wrap;html=1;fillColor=#F0FDF4;strokeColor=#D1D5DB;fontSize=12;fontStyle=1;align=left;spacingLeft=14;"))
    return "\n".join(out)


def admin_sidebar(prefix, active_item):
    """Render the admin sidebar with given menu item active. active_item ∈ {'blog-posts', 'blog-cats'}"""
    out = []
    # Sidebar dark bg
    items = [
        ("Dashboard", "📊"),
        ("Sản phẩm", "📦"),
        ("Danh mục", "🏷"),
        ("Đơn hàng", "🛒"),
        ("Khách hàng", "👥"),
        ("Voucher", "🎟"),
        ("Đánh giá", "⭐"),
        ("Blog ▾", "📰"),
        ("   ◦ Bài viết", "•"),
        ("   ◦ Danh mục blog", "•"),
        ("Chat với KH", "💬"),
        ("Doanh thu", "📈"),
        ("Cài đặt", "⚙"),
    ]
    # Build sidebar content as lines
    lines = ["Hương Hoa Xinh", "ADMIN PANEL", ""]
    for label, _ in items:
        lines.append(label)
    sb_text = "\n".join(lines)
    out.append(cell(f"{prefix}_sb", "", 0, 0, 220, 820,
                    style="rounded=0;whiteSpace=wrap;html=1;fillColor=#0F172A;strokeColor=#0F172A;"))
    # Brand
    out.append(text(f"{prefix}_sb_brand", "🌸 Hương Hoa Xinh", 14, 16, 200, 30,
                    size=14, bold=True, color="#FFFFFF"))
    out.append(text(f"{prefix}_sb_brand2", "ADMIN PANEL", 14, 48, 200, 18,
                    size=9, bold=True, color="#9CA3AF"))

    y0 = 84
    for i, (label, icon) in enumerate(items):
        is_active = False
        if active_item == "blog-posts" and label.strip() == "◦ Bài viết":
            is_active = True
        elif active_item == "blog-cats" and label.strip() == "◦ Danh mục blog":
            is_active = True
        elif active_item == "blog-parent" and label == "Blog ▾":
            is_active = True

        fill_color = "#1F2937" if is_active else "transparent"
        text_color = "#FFFFFF" if is_active else "#CBD5E1"
        font_bold = is_active or (label == "Blog ▾")

        # Sub-items get indentation
        is_sub = label.strip().startswith("◦")
        font_color = "#10B981" if is_sub and is_active else text_color

        bg_style = (
            "rounded=0;whiteSpace=wrap;html=1;"
            f"fillColor={fill_color};strokeColor=none;"
            "fontColor=" + font_color + ";"
            "fontSize=12;fontStyle=" + ("1" if font_bold else "0") + ";"
            "align=left;spacingLeft=" + ("32" if is_sub else "18") + ";"
        )
        out.append(cell(f"{prefix}_sb_it{i}", label, 0, y0 + i * 36, 220, 32, style=bg_style))

    # Bottom divider note
    out.append(cell(f"{prefix}_sb_line", "", 219, 0, 1, 820,
                    style="rounded=0;whiteSpace=wrap;html=1;fillColor=#D63384;strokeColor=#D63384;"))
    return "\n".join(out)


def admin_topbar(prefix, page_title):
    out = []
    # Topbar bg
    out.append(filled(f"{prefix}_tb_bg", "", 220, 0, 980, 64, fill="#FFFFFF", stroke="#E5E7EB"))
    # Hamburger + page title (small breadcrumb above)
    out.append(text(f"{prefix}_tb_bc", "TRANG QUẢN TRỊ", 244, 8, 200, 18, size=9, bold=True, color="#9CA3AF"))
    out.append(text(f"{prefix}_tb_title", page_title, 244, 28, 500, 26, size=16, bold=True, color="#0F172A"))
    # Search
    out.append(filled(f"{prefix}_tb_search", "🔍  Tìm sản phẩm, đơn hàng...", 760, 16, 240, 32,
                      fill="#F9FAFB", stroke="#E5E7EB", round_=True, fontSize=11, fontColor="#9CA3AF"))
    # Notif icons
    out.append(filled(f"{prefix}_tb_n1", "🔔", 1010, 16, 36, 32, fill="#F9FAFB", stroke="#E5E7EB", round_=True))
    out.append(filled(f"{prefix}_tb_n2", "💬", 1056, 16, 36, 32, fill="#F9FAFB", stroke="#E5E7EB", round_=True))
    # Avatar
    out.append(filled(f"{prefix}_tb_av", "Q", 1102, 16, 32, 32,
                      fill="#D63384", stroke="#D63384", round_=True,
                      fontSize=14, fontColor="#FFFFFF"))
    out.append(text(f"{prefix}_tb_name", "Quản Trị", 1140, 18, 60, 14, size=10, bold=True))
    out.append(text(f"{prefix}_tb_role", "Admin", 1140, 32, 60, 14, size=9, color="#9CA3AF"))
    return "\n".join(out)


# =========================================================================
# PAGE 53: Admin - Blog - Bài viết (list)
# =========================================================================
def page_blog_posts_list(prefix="abpl"):
    out = []
    out.append(admin_sidebar(prefix, "blog-posts"))
    out.append(admin_topbar(prefix, "Quản lý Blog"))

    cx = 240  # content left
    cy = 80   # content top
    cw = 940  # content width

    # Page header
    out.append(text(f"{prefix}_h1", "📰  Quản lý bài viết Blog", cx, cy, 600, 28,
                    size=16, bold=True))
    out.append(text(f"{prefix}_h1s", "Đăng / sửa / xoá bài viết về hoa, cẩm nang chăm sóc, ý nghĩa hoa…",
                    cx, cy + 28, 700, 18, size=11, color="#6B7280"))
    # Action buttons (top right)
    out.append(button(f"{prefix}_btn_dm", "🏷 Danh mục blog", cx + cw - 320, cy + 8, 150, 36,
                      fill="#FFFFFF", color="#198754", bold=True))
    out.append(cell(f"{prefix}_btn_dm_b", "", cx + cw - 320, cy + 8, 150, 36,
                    style="rounded=1;whiteSpace=wrap;html=1;fillColor=#FFFFFF;strokeColor=#198754;fontColor=#198754;fontSize=12;fontStyle=1;"))
    out[-2] = cell(f"{prefix}_btn_dm", "🏷  Danh mục blog", cx + cw - 320, cy + 8, 150, 36,
                   style="rounded=1;whiteSpace=wrap;html=1;fillColor=#FFFFFF;strokeColor=#198754;fontColor=#198754;fontSize=12;fontStyle=1;")
    out.append(button(f"{prefix}_btn_add", "+ Thêm bài viết", cx + cw - 160, cy + 8, 160, 36))

    # ===== Stats cards =====
    y_stat = cy + 70
    stats = [
        ("📰", "Tổng bài viết", "7",  "#198754", "#20A464"),
        ("👁", "Đang hiển thị", "7",  "#3B82F6", "#06B6D4"),
        ("👁", "Ẩn / Nháp",    "0",  "#9CA3AF", "#6B7280"),
        ("🏷", "Danh mục",     "12", "#D63384", "#F06595"),
    ]
    card_w = (cw - 36) / 4  # 4 cards with 12px gap
    for i, (icon, lbl, val, c1, c2) in enumerate(stats):
        x = cx + i * (card_w + 12)
        # card bg
        out.append(cell(f"{prefix}_st{i}", "", x, y_stat, card_w, 88,
                        style="rounded=1;whiteSpace=wrap;html=1;fillColor=#FFFFFF;strokeColor=#F1F2F6;arcSize=8;"))
        # icon box
        out.append(cell(f"{prefix}_st{i}_ic", icon, x + 16, y_stat + 19, 50, 50,
                        style=f"rounded=1;whiteSpace=wrap;html=1;fillColor={c1};strokeColor={c1};fontColor=#FFFFFF;fontSize=20;fontStyle=1;align=center;"))
        # label
        out.append(text(f"{prefix}_st{i}_l", lbl, x + 80, y_stat + 16, 200, 18,
                        size=10, bold=True, color="#6B7280"))
        # value
        out.append(text(f"{prefix}_st{i}_v", val, x + 80, y_stat + 36, 200, 36,
                        size=22, bold=True, color="#111827"))

    # ===== Filter card =====
    y_fil = y_stat + 108
    out.append(cell(f"{prefix}_fil", "", cx, y_fil, cw, 100,
                    style="rounded=1;whiteSpace=wrap;html=1;fillColor=#FFFFFF;strokeColor=#F1F2F6;arcSize=8;"))
    out.append(filled(f"{prefix}_fil_s", "🔍  Tìm theo tiêu đề, slug, tóm tắt...",
                      cx + 16, y_fil + 16, 360, 36,
                      fill="#FFFFFF", stroke="#D1D5DB", round_=True, fontColor="#9CA3AF", fontSize=11))
    out.append(filled(f"{prefix}_fil_c", "▾  Tất cả danh mục",
                      cx + 388, y_fil + 16, 200, 36,
                      fill="#FFFFFF", stroke="#D1D5DB", round_=True, fontColor="#374151", fontSize=11))
    out.append(filled(f"{prefix}_fil_st", "▾  Tất cả trạng thái",
                      cx + 600, y_fil + 16, 180, 36,
                      fill="#FFFFFF", stroke="#D1D5DB", round_=True, fontColor="#374151", fontSize=11))
    out.append(button(f"{prefix}_fil_btn", "🧹  Lọc", cx + 790, y_fil + 16, 130, 36))

    # Category pills below filter
    pills = [
        ("≡ Tất cả", True),
        ("Hoa Cưới 1", False),
        ("Hoa Sinh Nhật 2", False),
        ("Hoa Khai Trương 0", False),
        ("Cẩm Nang Cắm Hoa 0", False),
        ("Ý Nghĩa Loài Hoa 0", False),
    ]
    px = cx + 16
    py = y_fil + 60
    for i, (label, active) in enumerate(pills):
        w = 90 + len(label) * 4
        if active:
            out.append(filled(f"{prefix}_p{i}", label, px, py, w, 28,
                              fill="#198754", stroke="#198754", round_=True,
                              fontSize=10, fontColor="#FFFFFF"))
        else:
            out.append(filled(f"{prefix}_p{i}", label, px, py, w, 28,
                              fill="#F3F4F6", stroke="#F3F4F6", round_=True,
                              fontSize=10, fontColor="#4B5563"))
        px += w + 6

    # ===== Blog rows grid (2x2) =====
    y_grid = y_fil + 116
    posts = [
        ("Hoa khai trương: chọn sao cho hợp phong thuỷ", "Hoa Khai Trương",
         "Hướng dẫn chọn kệ hoa khai trương theo mệnh và phong thuỷ...", "07/05/2026", True),
        ("Trang trí phòng khách bằng hoa tươi 4 mùa", "Trang trí nhà",
         "Bí quyết chọn hoa theo mùa để phòng khách luôn tươi mới...", "09/05/2026", True),
        ("4 cách giữ hoa tươi lâu sau khi nhận", "Cẩm Nang Cắm Hoa",
         "Mẹo chăm sóc hoa đơn giản giúp mỗi bó luôn tươi tắn...", "09/05/2026", True),
        ("Gợi ý quà tặng 20/10 cho mẹ và vợ", "Hoa Sinh Nhật",
         "Những gợi ý hoa và quà tặng 20/10 ý nghĩa, giúp bạn ghi điểm...", "11/05/2026", True),
    ]
    card_w = (cw - 12) / 2
    card_h = 130
    for i, (title, cat, ex, dt, active) in enumerate(posts):
        col = i % 2
        row = i // 2
        x = cx + col * (card_w + 12)
        y = y_grid + row * (card_h + 12)
        out.append(cell(f"{prefix}_b{i}", "", x, y, card_w, card_h,
                        style="rounded=1;whiteSpace=wrap;html=1;fillColor=#FFFFFF;strokeColor=#F1F2F6;arcSize=8;"))
        # Thumb on left
        out.append(filled(f"{prefix}_b{i}_th", "[ ảnh ]", x + 12, y + 12, 130, 106,
                          fill="#E5E7EB", stroke="#E5E7EB", round_=True, fontColor="#9CA3AF"))
        # Status badge top-left of thumb
        out.append(filled(f"{prefix}_b{i}_st", "Hiển thị", x + 16, y + 16, 60, 18,
                          fill="#198754", stroke="#198754", round_=True, fontColor="#FFFFFF", fontSize=9))
        # Meta line: category + date
        out.append(filled(f"{prefix}_b{i}_cat", cat, x + 154, y + 14, 140, 18,
                          fill="#ECFDF5", stroke="#ECFDF5", round_=True, fontColor="#065F46", fontSize=9))
        out.append(text(f"{prefix}_b{i}_dt", "📅 " + dt, x + 304, y + 14, 100, 18,
                        size=9, color="#9CA3AF"))
        # Title
        out.append(text(f"{prefix}_b{i}_t", title, x + 154, y + 38, card_w - 170, 22,
                        size=12, bold=True, color="#111827"))
        # Excerpt
        out.append(text(f"{prefix}_b{i}_ex", ex, x + 154, y + 62, card_w - 170, 30,
                        size=10, color="#6B7280"))
        # Actions
        out.append(filled(f"{prefix}_b{i}_a1", "👁 Xem", x + 154, y + 98, 60, 22,
                          fill="#FFFFFF", stroke="#6EE7B7", round_=True, fontColor="#198754", fontSize=10))
        out.append(filled(f"{prefix}_b{i}_a2", "✎ Sửa", x + 220, y + 98, 60, 22,
                          fill="#FFFFFF", stroke="#FCD34D", round_=True, fontColor="#D97706", fontSize=10))
        out.append(filled(f"{prefix}_b{i}_a3", "🗑 Xoá", x + 286, y + 98, 60, 22,
                          fill="#FFFFFF", stroke="#FCA5A5", round_=True, fontColor="#DC2626", fontSize=10))

    # Pagination
    out.append(text(f"{prefix}_pg", "« 1 2 3 »", cx + cw - 90, y_grid + 280, 80, 24,
                    size=11, color="#6B7280"))

    # Page label at top
    out.append(text(f"{prefix}_pl", "53. Admin - Blog - Bài viết (list)", 0, 800, 1200, 18,
                    size=11, color="#9CA3AF", align="center"))
    return "\n        ".join(out)


# =========================================================================
# PAGE 54: Admin - Blog - Form bài viết (create/edit)
# =========================================================================
def page_blog_post_form(prefix="abpf"):
    out = []
    out.append(admin_sidebar(prefix, "blog-posts"))
    out.append(admin_topbar(prefix, "Tạo bài viết Blog mới"))

    cx, cy, cw = 240, 80, 940

    # Header
    out.append(text(f"{prefix}_h1", "✎  Tạo bài viết Blog mới", cx, cy, 600, 28,
                    size=16, bold=True))
    out.append(text(f"{prefix}_h1s", "Đăng cẩm nang, mẹo chọn hoa, ý nghĩa hoa…",
                    cx, cy + 28, 700, 18, size=11, color="#6B7280"))
    out.append(filled(f"{prefix}_back", "← Quay lại", cx + cw - 120, cy + 8, 120, 36,
                      fill="#FFFFFF", stroke="#9CA3AF", round_=True, fontColor="#374151", fontSize=11, bold=True))

    # ===== Two-column form =====
    y_form = cy + 70
    # Left column (form content)
    lx = cx
    lw = 600
    # Card
    out.append(cell(f"{prefix}_lc", "", lx, y_form, lw, 660,
                    style="rounded=1;whiteSpace=wrap;html=1;fillColor=#FFFFFF;strokeColor=#E5E7EB;arcSize=8;"))
    out.append(cell(f"{prefix}_lc_h", "✍ Nội dung chính", lx, y_form, lw, 36,
                    style="rounded=0;whiteSpace=wrap;html=1;fillColor=#F0FDF4;strokeColor=#E5E7EB;fontSize=12;fontStyle=1;align=left;spacingLeft=14;"))

    # Title
    out.append(text(f"{prefix}_t_l", "Tiêu đề *", lx + 16, y_form + 50, 200, 18,
                    size=11, bold=True))
    out.append(filled(f"{prefix}_t_in", "VD: 5 loài hoa nên tặng vào Ngày của Mẹ",
                      lx + 16, y_form + 72, lw - 32, 38,
                      fill="#FFFFFF", stroke="#D1D5DB", round_=True, fontColor="#9CA3AF", fontSize=12))

    # Slug
    out.append(text(f"{prefix}_sl_l", "Slug (đường dẫn URL)", lx + 16, y_form + 124, 200, 18,
                    size=11, bold=True))
    out.append(filled(f"{prefix}_sl_pf", "/blog/", lx + 16, y_form + 146, 60, 36,
                      fill="#F3F4F6", stroke="#D1D5DB", fontColor="#6B7280", fontSize=11))
    out.append(filled(f"{prefix}_sl_in", "tu-sinh-tu-tieu-de", lx + 76, y_form + 146, lw - 132, 36,
                      fill="#FFFFFF", stroke="#D1D5DB", fontColor="#9CA3AF", fontSize=11))
    out.append(filled(f"{prefix}_sl_btn", "✨", lx + lw - 56, y_form + 146, 40, 36,
                      fill="#ECFDF5", stroke="#6EE7B7", fontColor="#198754", fontSize=12))

    # Excerpt
    out.append(text(f"{prefix}_ex_l", "Tóm tắt ngắn (Quill mini)", lx + 16, y_form + 198, 250, 18,
                    size=11, bold=True))
    # Mini toolbar
    out.append(filled(f"{prefix}_ex_tb", "B  I  U  •  ⌫",
                      lx + 16, y_form + 220, lw - 32, 28,
                      fill="#F9FAFB", stroke="#D1D5DB", fontColor="#374151", fontSize=10))
    out.append(filled(f"{prefix}_ex_box", "1–2 câu giới thiệu ngắn...",
                      lx + 16, y_form + 248, lw - 32, 64,
                      fill="#FFFFFF", stroke="#D1D5DB", fontColor="#9CA3AF", fontSize=11))
    out.append(text(f"{prefix}_ex_cnt", "0/500 ký tự", lx + 16, y_form + 316, 200, 16,
                    size=9, color="#9CA3AF"))

    # Content with rich toolbar
    out.append(text(f"{prefix}_ct_l", "Nội dung chi tiết * (Quill rich text editor)",
                    lx + 16, y_form + 340, 300, 18, size=11, bold=True))
    out.append(filled(f"{prefix}_ct_btag", "Trình soạn thảo nâng cao",
                      lx + lw - 200, y_form + 340, 184, 20,
                      fill="#ECFDF5", stroke="#ECFDF5", round_=True, fontColor="#065F46", fontSize=9))
    # Toolbar row 1
    out.append(filled(f"{prefix}_ct_tb1", "▾ Heading  |  ▾ Font  |  ▾ Cỡ chữ  |  B  I  U  S  |  🎨 ▾  🖍 ▾",
                      lx + 16, y_form + 364, lw - 32, 30,
                      fill="#F9FAFB", stroke="#D1D5DB", fontColor="#374151", fontSize=10))
    # Toolbar row 2
    out.append(filled(f"{prefix}_ct_tb2", "≡ ≣ ≡ ≡  |  •  1.  |  ⇥ ⇤  |  ❝  </>  🔗  🖼  |  ⌫",
                      lx + 16, y_form + 394, lw - 32, 30,
                      fill="#F9FAFB", stroke="#D1D5DB", fontColor="#374151", fontSize=10))
    # Editor area
    out.append(filled(f"{prefix}_ct_box",
                      "Bắt đầu viết bài tại đây…\n\n(chọn cỡ chữ, font, căn lề, chèn ảnh, trích dẫn…)",
                      lx + 16, y_form + 424, lw - 32, 210,
                      fill="#FFFFFF", stroke="#D1D5DB", fontColor="#9CA3AF", fontSize=11))

    # Right column
    rx = cx + lw + 16
    rw = cw - lw - 16

    # Publish card
    out.append(cell(f"{prefix}_rc1", "", rx, y_form, rw, 220,
                    style="rounded=1;whiteSpace=wrap;html=1;fillColor=#FFFFFF;strokeColor=#E5E7EB;arcSize=8;"))
    out.append(cell(f"{prefix}_rc1_h", "✈ Xuất bản", rx, y_form, rw, 36,
                    style="rounded=0;whiteSpace=wrap;html=1;fillColor=#F0FDF4;strokeColor=#E5E7EB;fontSize=12;fontStyle=1;align=left;spacingLeft=14;"))
    # Toggle
    out.append(filled(f"{prefix}_rc1_tg", "🟢 ✓ Hiển thị công khai",
                      rx + 16, y_form + 52, rw - 32, 50,
                      fill="#ECFDF5", stroke="#86EFAC", round_=True, fontColor="#065F46", fontSize=12, bold=True))
    # Datetime
    out.append(text(f"{prefix}_rc1_dl", "Thời gian xuất bản", rx + 16, y_form + 114, 200, 18,
                    size=11, bold=True))
    out.append(filled(f"{prefix}_rc1_dt", "📅 2026-05-19  10:00",
                      rx + 16, y_form + 134, rw - 32, 34,
                      fill="#FFFFFF", stroke="#D1D5DB", fontColor="#374151", fontSize=11))
    # Submit button
    out.append(button(f"{prefix}_rc1_sub", "+ Tạo bài viết", rx + 16, y_form + 178, rw - 32, 36))

    # Category card
    y_cat = y_form + 236
    out.append(cell(f"{prefix}_rc2", "", rx, y_cat, rw, 110,
                    style="rounded=1;whiteSpace=wrap;html=1;fillColor=#FFFFFF;strokeColor=#E5E7EB;arcSize=8;"))
    out.append(cell(f"{prefix}_rc2_h", "🏷 Danh mục bài viết", rx, y_cat, rw, 36,
                    style="rounded=0;whiteSpace=wrap;html=1;fillColor=#F0FDF4;strokeColor=#E5E7EB;fontSize=12;fontStyle=1;align=left;spacingLeft=14;"))
    out.append(filled(f"{prefix}_rc2_sel", "▾  Hoa Cưới",
                      rx + 16, y_cat + 52, rw - 32, 36,
                      fill="#FFFFFF", stroke="#D1D5DB", fontColor="#111827", fontSize=11))

    # Cover image card
    y_cv = y_cat + 122
    out.append(cell(f"{prefix}_rc3", "", rx, y_cv, rw, 290,
                    style="rounded=1;whiteSpace=wrap;html=1;fillColor=#FFFFFF;strokeColor=#E5E7EB;arcSize=8;"))
    out.append(cell(f"{prefix}_rc3_h", "🖼 Ảnh bìa", rx, y_cv, rw, 36,
                    style="rounded=0;whiteSpace=wrap;html=1;fillColor=#F0FDF4;strokeColor=#E5E7EB;fontSize=12;fontStyle=1;align=left;spacingLeft=14;"))
    # Image preview area (16:9)
    out.append(filled(f"{prefix}_rc3_pv", "☁\n\nChưa có ảnh bìa",
                      rx + 16, y_cv + 52, rw - 32, 150,
                      fill="#F9FAFB", stroke="#D1D5DB", fontColor="#9CA3AF", fontSize=11))
    # Upload btn
    out.append(filled(f"{prefix}_rc3_up", "☁ Chọn ảnh từ máy",
                      rx + 16, y_cv + 214, rw - 32, 42,
                      fill="#ECFDF5", stroke="#6EE7B7", round_=True, fontColor="#065F46", fontSize=12, bold=True))
    out.append(text(f"{prefix}_rc3_hint", "Tối đa 4MB. Khuyến nghị tỉ lệ 16:9, ≥ 1280×720px.",
                    rx + 16, y_cv + 260, rw - 32, 20, size=9, color="#9CA3AF"))

    # Page label
    out.append(text(f"{prefix}_pl", "54. Admin - Blog - Tạo / Sửa bài viết (form + Quill editor)",
                    0, 800, 1200, 18, size=11, color="#9CA3AF", align="center"))

    return "\n        ".join(out)


# =========================================================================
# PAGE 55: Admin - Blog - Danh mục blog (list)
# =========================================================================
def page_blog_categories_list(prefix="abcl"):
    out = []
    out.append(admin_sidebar(prefix, "blog-cats"))
    out.append(admin_topbar(prefix, "Danh mục Blog"))

    cx, cy, cw = 240, 80, 940

    # Header
    out.append(text(f"{prefix}_h1", "🏷  Danh mục Blog", cx, cy, 600, 28, size=16, bold=True))
    out.append(text(f"{prefix}_h1s", "Phân loại bài viết: Cẩm nang, Ý nghĩa hoa, Cách chăm sóc, Dịp lễ…",
                    cx, cy + 28, 700, 18, size=11, color="#6B7280"))
    # Action buttons
    out.append(filled(f"{prefix}_back", "📰 Quay lại Bài viết",
                      cx + cw - 360, cy + 8, 180, 36,
                      fill="#FFFFFF", stroke="#198754", round_=True, fontColor="#198754", fontSize=11, bold=True))
    out.append(button(f"{prefix}_add", "+ Thêm danh mục", cx + cw - 170, cy + 8, 170, 36))

    # Search card
    y_s = cy + 70
    out.append(cell(f"{prefix}_sc", "", cx, y_s, cw, 70,
                    style="rounded=1;whiteSpace=wrap;html=1;fillColor=#FFFFFF;strokeColor=#F1F2F6;arcSize=8;"))
    out.append(filled(f"{prefix}_sc_in", "🔍  Tìm theo tên / mô tả...",
                      cx + 16, y_s + 16, 680, 36,
                      fill="#FFFFFF", stroke="#D1D5DB", round_=True, fontColor="#9CA3AF", fontSize=11))
    out.append(button(f"{prefix}_sc_btn", "🔍 Tìm", cx + 706, y_s + 16, 200, 36))

    # Table
    y_t = y_s + 88
    th = 50
    table_h = 50 + th * 5
    out.append(cell(f"{prefix}_t", "", cx, y_t, cw, table_h,
                    style="rounded=1;whiteSpace=wrap;html=1;fillColor=#FFFFFF;strokeColor=#F1F2F6;arcSize=8;"))
    # Header row
    out.append(filled(f"{prefix}_t_h", "", cx, y_t, cw, 44, fill="#F3F4F6", stroke="#F3F4F6"))
    col_widths = [70, 220, 380, 130, 140]  # = 940
    headers = ["#", "Tên danh mục", "Mô tả", "Số bài viết", "Hành động"]
    cur_x = cx
    for i, (label, w) in enumerate(zip(headers, col_widths)):
        out.append(text(f"{prefix}_t_h{i}", label, cur_x + 14, y_t + 14, w - 14, 18,
                        size=11, bold=True, color="#111827"))
        cur_x += w

    # Data rows
    data = [
        ("1", "Hoa Cưới",          "Mẹo chọn hoa cưới, hoa cô dâu, hoa cài áo, hoa bàn tiệc.", "2 bài"),
        ("2", "Hoa Sinh Nhật",     "Hoa tặng sinh nhật theo giới tính, độ tuổi, ý nghĩa từng màu.", "3 bài"),
        ("3", "Hoa Khai Trương",   "Hoa kệ khai trương, ý nghĩa và phong thuỷ cho từng ngành nghề.", "1 bài"),
        ("4", "Cẩm Nang Cắm Hoa",  "Kỹ thuật cắm hoa, giữ hoa tươi lâu, phối màu, dụng cụ.", "1 bài"),
        ("5", "Ý Nghĩa Loài Hoa",  "Mỗi loài hoa kể một câu chuyện — ngôn ngữ và biểu tượng.", "0 bài"),
    ]
    for ri, row in enumerate(data):
        ry = y_t + 44 + ri * th
        # row border
        out.append(filled(f"{prefix}_t_r{ri}_div", "", cx, ry, cw, 1,
                          fill="#F1F2F6", stroke="#F1F2F6"))
        cur_x = cx
        for ci, value in enumerate(row):
            w = col_widths[ci]
            if ci == 0:
                out.append(text(f"{prefix}_t_r{ri}_c{ci}", value, cur_x + 14, ry + 14, w - 14, 22,
                                size=11, color="#9CA3AF"))
            elif ci == 1:
                out.append(text(f"{prefix}_t_r{ri}_c{ci}", value, cur_x + 14, ry + 14, w - 14, 22,
                                size=12, bold=True, color="#111827"))
            elif ci == 2:
                out.append(text(f"{prefix}_t_r{ri}_c{ci}", value, cur_x + 14, ry + 14, w - 14, 22,
                                size=10, color="#6B7280"))
            elif ci == 3:
                out.append(filled(f"{prefix}_t_r{ri}_c{ci}", value, cur_x + 14, ry + 14, 60, 22,
                                  fill="#ECFDF5", stroke="#ECFDF5", round_=True, fontColor="#065F46", fontSize=10, bold=True))
            cur_x += w
        # Actions in last column
        ax = cx + sum(col_widths[:4]) + 14
        out.append(filled(f"{prefix}_t_r{ri}_e", "✎ Sửa", ax, ry + 12, 56, 26,
                          fill="#FFFFFF", stroke="#FCD34D", round_=True, fontColor="#D97706", fontSize=10, bold=True))
        out.append(filled(f"{prefix}_t_r{ri}_d", "🗑 Xoá", ax + 64, ry + 12, 56, 26,
                          fill="#FFFFFF", stroke="#FCA5A5", round_=True, fontColor="#DC2626", fontSize=10, bold=True))

    # Page label
    out.append(text(f"{prefix}_pl", "55. Admin - Blog - Danh mục blog (list)",
                    0, 800, 1200, 18, size=11, color="#9CA3AF", align="center"))
    return "\n        ".join(out)


# =========================================================================
# PAGE 56: Admin - Blog - Form danh mục blog
# =========================================================================
def page_blog_category_form(prefix="abcf"):
    out = []
    out.append(admin_sidebar(prefix, "blog-cats"))
    out.append(admin_topbar(prefix, "Tạo danh mục Blog"))

    cx, cy, cw = 240, 80, 940

    # Header
    out.append(text(f"{prefix}_h1", "+  Tạo danh mục Blog mới", cx, cy, 600, 28,
                    size=16, bold=True))
    out.append(filled(f"{prefix}_back", "← Quay lại",
                      cx + cw - 120, cy + 8, 120, 36,
                      fill="#FFFFFF", stroke="#9CA3AF", round_=True, fontColor="#374151", fontSize=11, bold=True))

    # Centered form card
    fw = 660
    fx = cx + (cw - fw) / 2
    fy = cy + 80
    out.append(cell(f"{prefix}_fc", "", fx, fy, fw, 440,
                    style="rounded=1;whiteSpace=wrap;html=1;fillColor=#FFFFFF;strokeColor=#E5E7EB;arcSize=8;"))

    # Name
    out.append(text(f"{prefix}_n_l", "Tên danh mục *", fx + 24, fy + 28, 300, 18,
                    size=11, bold=True))
    out.append(filled(f"{prefix}_n_in", "VD: Cẩm nang chăm sóc hoa",
                      fx + 24, fy + 50, fw - 48, 38,
                      fill="#FFFFFF", stroke="#D1D5DB", round_=True, fontColor="#9CA3AF", fontSize=12))

    # Slug
    out.append(text(f"{prefix}_s_l", "Slug (đường dẫn)", fx + 24, fy + 110, 300, 18,
                    size=11, bold=True))
    out.append(filled(f"{prefix}_s_pf", "/blog?category=",
                      fx + 24, fy + 132, 130, 36,
                      fill="#F3F4F6", stroke="#D1D5DB", fontColor="#6B7280", fontSize=11))
    out.append(filled(f"{prefix}_s_in", "tu-sinh-tu-ten",
                      fx + 154, fy + 132, fw - 178, 36,
                      fill="#FFFFFF", stroke="#D1D5DB", fontColor="#9CA3AF", fontSize=11))
    out.append(text(f"{prefix}_s_hint", "Để trống → tự sinh từ tên.",
                    fx + 24, fy + 172, 400, 18, size=9, color="#9CA3AF"))

    # Description
    out.append(text(f"{prefix}_d_l", "Mô tả", fx + 24, fy + 200, 300, 18,
                    size=11, bold=True))
    out.append(filled(f"{prefix}_d_in", "Mô tả ngắn về danh mục (tuỳ chọn)\n\nVí dụ: 'Tổng hợp bài viết về cách chọn hoa cưới, hoa cài áo cô dâu, hoa bàn tiệc...'",
                      fx + 24, fy + 222, fw - 48, 130,
                      fill="#FFFFFF", stroke="#D1D5DB", fontColor="#9CA3AF", fontSize=11))

    # Buttons
    out.append(button(f"{prefix}_save", "+ Tạo danh mục", fx + 24, fy + 370, 200, 44))
    out.append(filled(f"{prefix}_cancel", "Huỷ", fx + 234, fy + 370, 100, 44,
                      fill="#FFFFFF", stroke="#9CA3AF", round_=True, fontColor="#374151", fontSize=12, bold=True))

    # Page label
    out.append(text(f"{prefix}_pl", "56. Admin - Blog - Tạo / Sửa danh mục blog",
                    0, 800, 1200, 18, size=11, color="#9CA3AF", align="center"))
    return "\n        ".join(out)


# =========================================================================
# Build new diagrams + append
# =========================================================================
def build_diagram(page_id, name, body):
    return (
        f'  <diagram id="{page_id}" name="{name}">\n'
        '    <mxGraphModel dx="1200" dy="820" grid="1" gridSize="10" guides="1" '
        'tooltips="1" connect="1" arrows="1" fold="1" page="1" pageScale="1" '
        'pageWidth="1200" pageHeight="820" math="0" shadow="0">\n'
        '      <root>\n'
        '        <mxCell id="0" />\n'
        '        <mxCell id="1" parent="0" />\n'
        f'        {body}\n'
        '      </root>\n'
        '    </mxGraphModel>\n'
        '  </diagram>\n'
    )


def main():
    with open(DRAWIO, 'r', encoding='utf-8') as f:
        content = f.read()

    new_pages = (
        build_diagram("page53", "53. Admin - Blog - Bài viết", page_blog_posts_list())
        + build_diagram("page54", "54. Admin - Blog - Tạo / Sửa bài viết", page_blog_post_form())
        + build_diagram("page55", "55. Admin - Blog - Danh mục blog", page_blog_categories_list())
        + build_diagram("page56", "56. Admin - Blog - Tạo / Sửa danh mục blog", page_blog_category_form())
    )

    # Insert before </mxfile>
    if "</mxfile>" not in content:
        raise SystemExit("Khong tim thay </mxfile>")
    content = content.replace("</mxfile>", new_pages + "</mxfile>")

    # Update pages count attribute on root
    import re
    m = re.search(r'<mxfile([^>]*?)pages="(\d+)"', content)
    if m:
        new_count = int(m.group(2)) + 4
        content = re.sub(r'(<mxfile[^>]*?)pages="\d+"', r'\1pages="' + str(new_count) + '"', content, count=1)

    with open(DRAWIO, 'w', encoding='utf-8') as f:
        f.write(content)

    print("OK")


if __name__ == "__main__":
    main()
