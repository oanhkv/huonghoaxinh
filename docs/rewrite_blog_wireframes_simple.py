"""
Replace the 4 Blog admin wireframe pages with SIMPLE / minimal style
matching the existing wireframes (black outline, white fill, centered text,
no gradients, no fills).
"""
import re
import sys, io
sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

DRAWIO = r"C:\Users\Kieu Anh\Desktop\CD1\huonghoaxinh_wireframes.before.drawio"


# ---- simple helpers — match existing wireframe style ----
def box(cid, value, x, y, w, h, *, fontSize=11, bold=False, align="center"):
    """Bordered rectangle with centered text — the universal wireframe component."""
    safe = (value or "").replace("&", "&amp;").replace("<", "&lt;").replace(">", "&gt;").replace('"', "&quot;").replace("\n", "&#xa;")
    style = (
        f"rounded=0;whiteSpace=wrap;html=1;fillColor=none;strokeColor=#000000;"
        f"fontColor=#000000;fontSize={fontSize};fontStyle={'1' if bold else '0'};"
        f"align={align};verticalAlign=middle;"
    )
    return (
        f'<mxCell id="{cid}" value="{safe}" style="{style}" vertex="1" parent="1">'
        f'<mxGeometry x="{x}" y="{y}" width="{w}" height="{h}" as="geometry" /></mxCell>'
    )


def label(cid, value, x, y, w, h, *, fontSize=10, bold=False, align="left", valign="top"):
    """Plain text label without border."""
    safe = (value or "").replace("&", "&amp;").replace("<", "&lt;").replace(">", "&gt;").replace('"', "&quot;").replace("\n", "&#xa;")
    style = (
        f"text;html=1;strokeColor=none;fillColor=none;fontColor=#000000;"
        f"fontSize={fontSize};fontStyle={'1' if bold else '0'};"
        f"align={align};verticalAlign={valign};"
    )
    return (
        f'<mxCell id="{cid}" value="{safe}" style="{style}" vertex="1" parent="1">'
        f'<mxGeometry x="{x}" y="{y}" width="{w}" height="{h}" as="geometry" /></mxCell>'
    )


def title(cid, value, *, page_num):
    return label(f"{cid}_t", value, 0, 10, 1200, 36,
                 fontSize=18, bold=True, align="center", valign="middle")


def frame(cid):
    """Outer page frame."""
    return (
        f'<mxCell id="{cid}_f" value="" '
        f'style="rounded=0;whiteSpace=wrap;html=1;fillColor=none;strokeColor=#000000;" '
        f'vertex="1" parent="1">'
        f'<mxGeometry x="20" y="50" width="1160" height="750" as="geometry" /></mxCell>'
    )


def admin_sidebar(prefix, active_label):
    """Standard admin sidebar (220px wide, items as bordered boxes)."""
    out = []
    items = [
        ("— TỔNG QUAN —", 22, False),
        ("Dashboard", 34, False),
        ("Doanh thu & Thống kê", 34, False),
        ("— BÁN HÀNG —", 22, False),
        ("Đơn hàng", 34, False),
        ("Sản phẩm ▾", 34, False),
        ("Danh mục", 34, False),
        ("Mã giảm giá", 34, False),
        ("Đánh giá", 34, False),
        ("Blog ▾", 34, False),
        ("    ◦ Bài viết", 30, False),
        ("    ◦ Danh mục blog", 30, False),
        ("— QUAN HỆ —", 22, False),
        ("Chat với khách hàng", 34, False),
        ("Tài khoản ▾", 34, False),
        ("— HỆ THỐNG —", 22, False),
        ("Cài đặt website", 34, False),
        ("Hồ sơ", 34, False),
    ]
    # Logo
    out.append(box(f"{prefix}_sb_logo", "LOGO Admin\nHương Hoa Xinh",
                   20, 44, 220, 56))
    # Annotation
    out.append(label(f"{prefix}_sb_note", "SIDEBAR\n(220px, sticky, có submenu Blog)",
                     20, 10, 220, 30, fontSize=9))
    # Menu items
    y = 104
    for i, (lbl, h, _) in enumerate(items):
        text_to_show = lbl
        if lbl.strip().lstrip("◦ ").strip() == active_label:
            text_to_show = lbl + "  [ACTIVE]"
        elif lbl == "Blog ▾" and active_label in ("Bài viết", "Danh mục blog"):
            text_to_show = "Blog ▾  [open]"
        out.append(box(f"{prefix}_sb_i{i}", text_to_show, 20, y, 220, h))
        y += h + 4
    return "\n        ".join(out)


def admin_topbar(prefix, breadcrumb, page_title):
    """Standard topbar with breadcrumb + title + search + icons + admin."""
    out = []
    out.append(label(f"{prefix}_tb_bc", breadcrumb, 260, 40, 400, 24, fontSize=10))
    out.append(label(f"{prefix}_tb_t", page_title, 260, 66, 460, 36,
                     fontSize=16, bold=True, valign="middle"))
    out.append(box(f"{prefix}_tb_s", "[ Tìm kiếm nhanh... ]", 740, 66, 220, 36))
    out.append(box(f"{prefix}_tb_shop", "🛍 Shop", 970, 66, 60, 36))
    out.append(box(f"{prefix}_tb_msg", "✉", 1035, 66, 36, 36))
    out.append(box(f"{prefix}_tb_bell", "🔔", 1075, 66, 36, 36))
    out.append(box(f"{prefix}_tb_admin", "Admin ▾", 1110, 66, 50, 36))
    return "\n        ".join(out)


# =========================================================================
# PAGE 53 — Admin - Blog - Bài viết (list)
# =========================================================================
def page_blog_posts_list(prefix="abpl"):
    out = []
    out.append(title("p53", "53. Admin - Blog - Bài viết (list)", page_num=53))
    out.append(frame("p53"))
    out.append(admin_sidebar(prefix, "◦ Bài viết"))
    out.append(admin_topbar(prefix, "Admin / Blog / Bài viết", "Quản lý bài viết Blog"))

    # Top action row
    out.append(box(f"{prefix}_act", "[🏷 Danh mục blog]      [+ Thêm bài viết]",
                   260, 110, 900, 40))

    # Stats row (4 cards)
    cx = 260
    sw = (900 - 30) / 4  # 4 cards with 10px gap
    stats = [
        ("📰 Tổng bài viết", "7"),
        ("👁 Đang hiển thị", "7"),
        ("👁 Ẩn / Nháp", "0"),
        ("🏷 Danh mục", "12"),
    ]
    for i, (lbl, val) in enumerate(stats):
        x = cx + i * (sw + 10)
        out.append(box(f"{prefix}_s{i}", f"{lbl}\n\n{val}", x, 158, sw, 80, fontSize=11, bold=True))

    # Filter form
    out.append(box(f"{prefix}_fil", "Form lọc:  [🔍 Tìm theo tiêu đề / slug / tóm tắt]  [Danh mục ▾]  [Trạng thái ▾]  [Lọc]",
                   260, 246, 900, 40))

    # Category pills
    out.append(box(f"{prefix}_pills", "[≡ Tất cả]  [Hoa Cưới 1]  [Hoa Sinh Nhật 2]  [Hoa Khai Trương 0]  [Cẩm Nang Cắm Hoa 0]  [Ý Nghĩa Loài Hoa 0]",
                   260, 294, 900, 36))

    # Blog card grid description
    out.append(box(f"{prefix}_desc", "Lưới 2 cột thẻ bài viết — mỗi thẻ:  [ảnh thumb 16:12] | [badge trạng thái Hiển thị/Ẩn] [danh mục] [ngày]  +  Tiêu đề  +  Tóm tắt 2 dòng  +  [👁 Xem] [✎ Sửa] [🗑 Xoá]",
                   260, 340, 900, 60))

    # 4 sample cards (2x2)
    card_w = (900 - 12) / 2
    card_h = 110
    samples = [
        "Bài #1\nHoa khai trương: chọn sao cho hợp phong thuỷ\n[Hoa Khai Trương · 07/05/2026]",
        "Bài #2\nTrang trí phòng khách bằng hoa tươi 4 mùa\n[Trang trí nhà · 09/05/2026]",
        "Bài #3\n4 cách giữ hoa tươi lâu sau khi nhận\n[Cẩm Nang Cắm Hoa · 09/05/2026]",
        "Bài #4\nGợi ý quà tặng 20/10 cho mẹ và vợ\n[Hoa Sinh Nhật · 11/05/2026]",
    ]
    for i, s in enumerate(samples):
        col = i % 2
        row = i // 2
        x = 260 + col * (card_w + 12)
        y = 410 + row * (card_h + 10)
        out.append(box(f"{prefix}_c{i}", s, x, y, card_w, card_h))

    # Pagination
    out.append(box(f"{prefix}_pg", "« Trang 1  2  3  »", 260, 650, 900, 32))

    return "\n        ".join(out)


# =========================================================================
# PAGE 54 — Admin - Blog - Tạo/Sửa bài viết (form + rich editor)
# =========================================================================
def page_blog_post_form(prefix="abpf"):
    out = []
    out.append(title("p54", "54. Admin - Blog - Tạo / Sửa bài viết (Quill Rich Editor)",
                     page_num=54))
    out.append(frame("p54"))
    out.append(admin_sidebar(prefix, "◦ Bài viết"))
    out.append(admin_topbar(prefix, "Admin / Blog / Bài viết / Tạo mới",
                            "Tạo bài viết Blog mới"))

    # Top: back button
    out.append(box(f"{prefix}_back", "[← Quay lại]", 1050, 110, 110, 36))

    # ===== Left column (col-8) — content =====
    lx, ly, lw = 260, 156, 580

    out.append(box(f"{prefix}_lh", "✍ Nội dung chính", lx, ly, lw, 30,
                   fontSize=11, bold=True))
    out.append(box(f"{prefix}_lc", "", lx, ly, lw, 530))

    # Title
    out.append(label(f"{prefix}_t_l", "Tiêu đề *", lx + 12, ly + 42, 200, 16,
                     fontSize=10, bold=True))
    out.append(box(f"{prefix}_t_in", "[ Nhập tiêu đề bài viết... ]",
                   lx + 12, ly + 62, lw - 24, 34))

    # Slug
    out.append(label(f"{prefix}_s_l", "Slug (đường dẫn URL)", lx + 12, ly + 108, 200, 16,
                     fontSize=10, bold=True))
    out.append(box(f"{prefix}_s_in", "/blog/  [ tu-sinh-tu-tieu-de ]  [✨ Auto]",
                   lx + 12, ly + 128, lw - 24, 34))

    # Excerpt
    out.append(label(f"{prefix}_e_l", "Tóm tắt ngắn  (Quill mini toolbar)",
                     lx + 12, ly + 174, 300, 16, fontSize=10, bold=True))
    out.append(box(f"{prefix}_e_tb", "[ B  I  U  •  ⌫ ]",
                   lx + 12, ly + 194, lw - 24, 26))
    out.append(box(f"{prefix}_e_in", "[ 1–2 câu giới thiệu ngắn... ]  (0/500)",
                   lx + 12, ly + 220, lw - 24, 60))

    # Content
    out.append(label(f"{prefix}_c_l", "Nội dung chi tiết *  (Quill rich text editor)",
                     lx + 12, ly + 290, 400, 16, fontSize=10, bold=True))
    out.append(box(f"{prefix}_c_tb1",
                   "Toolbar 1: [Heading ▾] [Font ▾] [Cỡ chữ ▾] [B] [I] [U] [S] [🎨 Màu] [🖍 Highlight]",
                   lx + 12, ly + 310, lw - 24, 26))
    out.append(box(f"{prefix}_c_tb2",
                   "Toolbar 2: [≡ Căn lề] [•  1. Danh sách] [⇥ ⇤ Indent] [❝ Quote] [</> Code] [🔗 Link] [🖼 Ảnh] [⌫ Clear]",
                   lx + 12, ly + 338, lw - 24, 26))
    out.append(box(f"{prefix}_c_in",
                   "[ Vùng soạn thảo nội dung — hỗ trợ ảnh, code, link, blockquote, table... ]",
                   lx + 12, ly + 366, lw - 24, 150))

    # ===== Right column (col-4) — sidebar metadata =====
    rx = 850
    rw = 310

    # Publish card
    out.append(box(f"{prefix}_p_h", "✈ Xuất bản", rx, 156, rw, 28,
                   fontSize=11, bold=True))
    out.append(box(f"{prefix}_p_card", "", rx, 156, rw, 200))
    out.append(box(f"{prefix}_p_tg", "[✓] Hiển thị công khai", rx + 10, 196, rw - 20, 36))
    out.append(label(f"{prefix}_p_dl", "Thời gian xuất bản", rx + 10, 238, 200, 16,
                     fontSize=10, bold=True))
    out.append(box(f"{prefix}_p_dt", "[📅 yyyy-mm-dd hh:mm]", rx + 10, 258, rw - 20, 34))
    out.append(box(f"{prefix}_p_btn", "[+ Tạo bài viết]", rx + 10, 306, rw - 20, 40))

    # Category card
    out.append(box(f"{prefix}_dm_h", "🏷 Danh mục bài viết", rx, 372, rw, 28,
                   fontSize=11, bold=True))
    out.append(box(f"{prefix}_dm_card", "", rx, 372, rw, 90))
    out.append(box(f"{prefix}_dm_sel", "[ ▾ Chọn danh mục (Hoa Cưới, Hoa Sinh Nhật...) ]",
                   rx + 10, 414, rw - 20, 34))

    # Cover card
    out.append(box(f"{prefix}_cv_h", "🖼 Ảnh bìa", rx, 478, rw, 28,
                   fontSize=11, bold=True))
    out.append(box(f"{prefix}_cv_card", "", rx, 478, rw, 200))
    out.append(box(f"{prefix}_cv_pv", "[ Preview ảnh bìa 16:9 ]\n\n(Chưa có ảnh)",
                   rx + 10, 518, rw - 20, 100))
    out.append(box(f"{prefix}_cv_btn", "[☁ Chọn ảnh từ máy]", rx + 10, 628, rw - 20, 40))

    return "\n        ".join(out)


# =========================================================================
# PAGE 55 — Admin - Blog - Danh mục blog (list)
# =========================================================================
def page_blog_categories_list(prefix="abcl"):
    out = []
    out.append(title("p55", "55. Admin - Blog - Danh mục blog (list)", page_num=55))
    out.append(frame("p55"))
    out.append(admin_sidebar(prefix, "◦ Danh mục blog"))
    out.append(admin_topbar(prefix, "Admin / Blog / Danh mục", "Danh mục Blog"))

    # Top action row
    out.append(box(f"{prefix}_act", "[📰 Quay lại Bài viết]      [+ Thêm danh mục]",
                   260, 110, 900, 40))

    # Search
    out.append(box(f"{prefix}_search", "Form tìm:  [🔍 Tìm theo tên / mô tả... ]  [Tìm]",
                   260, 158, 900, 40))

    # Table header
    out.append(box(f"{prefix}_th",
                   "Bảng:  # | Tên danh mục | Mô tả | Số bài viết (badge) | [✎ Sửa] [🗑 Xoá]",
                   260, 206, 900, 36))

    # Sample rows (5)
    rows = [
        "Danh mục #1: Hoa Cưới  —  Mẹo chọn hoa cưới, hoa cô dâu...  —  2 bài",
        "Danh mục #2: Hoa Sinh Nhật  —  Hoa tặng sinh nhật theo giới tính, độ tuổi...  —  3 bài",
        "Danh mục #3: Hoa Khai Trương  —  Hoa kệ khai trương, phong thuỷ...  —  1 bài",
        "Danh mục #4: Cẩm Nang Cắm Hoa  —  Kỹ thuật cắm hoa, giữ hoa tươi lâu...  —  1 bài",
        "Danh mục #5: Ý Nghĩa Loài Hoa  —  Mỗi loài hoa kể một câu chuyện...  —  0 bài",
    ]
    for i, row in enumerate(rows):
        out.append(box(f"{prefix}_r{i}", row, 260, 246 + i * 50, 900, 44))

    # Pagination
    out.append(box(f"{prefix}_pg", "« Trang 1  »", 260, 510, 900, 32))

    # Note
    out.append(label(f"{prefix}_note",
                     "Lưu ý: Không xoá được danh mục đang có bài viết — phải chuyển bài sang danh mục khác trước.",
                     260, 560, 900, 24, fontSize=10))

    return "\n        ".join(out)


# =========================================================================
# PAGE 56 — Admin - Blog - Form danh mục blog
# =========================================================================
def page_blog_category_form(prefix="abcf"):
    out = []
    out.append(title("p56", "56. Admin - Blog - Tạo / Sửa danh mục blog",
                     page_num=56))
    out.append(frame("p56"))
    out.append(admin_sidebar(prefix, "◦ Danh mục blog"))
    out.append(admin_topbar(prefix, "Admin / Blog / Danh mục / Tạo mới",
                            "Tạo danh mục Blog mới"))

    # Back button
    out.append(box(f"{prefix}_back", "[← Quay lại]", 1050, 110, 110, 36))

    # Centered form card
    fw = 600
    fx = 260 + (900 - fw) / 2
    fy = 170

    out.append(box(f"{prefix}_fc", "", fx, fy, fw, 410))

    # Name
    out.append(label(f"{prefix}_n_l", "Tên danh mục *", fx + 20, fy + 24, 200, 16,
                     fontSize=10, bold=True))
    out.append(box(f"{prefix}_n_in", "[ VD: Cẩm nang chăm sóc hoa ]",
                   fx + 20, fy + 44, fw - 40, 38))

    # Slug
    out.append(label(f"{prefix}_s_l", "Slug (đường dẫn)", fx + 20, fy + 100, 200, 16,
                     fontSize=10, bold=True))
    out.append(box(f"{prefix}_s_in", "/blog?category=  [ tu-sinh-tu-ten ]",
                   fx + 20, fy + 120, fw - 40, 38))
    out.append(label(f"{prefix}_s_hint", "Để trống → tự sinh từ tên.",
                     fx + 20, fy + 160, fw - 40, 16, fontSize=9))

    # Description
    out.append(label(f"{prefix}_d_l", "Mô tả", fx + 20, fy + 184, 200, 16,
                     fontSize=10, bold=True))
    out.append(box(f"{prefix}_d_in",
                   "[ Mô tả ngắn về danh mục (tuỳ chọn)...]",
                   fx + 20, fy + 204, fw - 40, 120))

    # Buttons
    out.append(box(f"{prefix}_save", "[+ Tạo danh mục]", fx + 20, fy + 344, 180, 44))
    out.append(box(f"{prefix}_cancel", "[Huỷ]", fx + 210, fy + 344, 100, 44))

    return "\n        ".join(out)


# =========================================================================
# Replace pages 53–56 with simple wireframes
# =========================================================================
PAGES = {
    "53. Admin - Blog - Bài viết": page_blog_posts_list,
    "54. Admin - Blog - Tạo / Sửa bài viết": page_blog_post_form,
    "55. Admin - Blog - Danh mục blog": page_blog_categories_list,
    "56. Admin - Blog - Tạo / Sửa danh mục blog": page_blog_category_form,
}


def main():
    with open(DRAWIO, 'r', encoding='utf-8') as f:
        content = f.read()

    for old_name, builder in PAGES.items():
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

        def callback(m, name=old_name, inner=inner):
            return f'{m.group(1)}{name}{m.group(2)}\n    {inner}\n  {m.group(3)}'

        content, n = pattern.subn(callback, content, count=1)
        print(f"  rewrote [{old_name}] : {n} time(s)")

    with open(DRAWIO, 'w', encoding='utf-8') as f:
        f.write(content)
    print("OK")


if __name__ == "__main__":
    main()
