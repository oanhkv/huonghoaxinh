"""
Generate draw.io file for UC PHÂN RÃ — QUẢN TRỊ VIÊN
- 2 columns × 10 rows (UC19 đứng riêng ô cuối)
- Trắng đen, khung vuông cân
- Save to user's Downloads
"""
import sys, io, os
sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

OUT = r"C:\Users\Kieu Anh\Downloads\UC_Admin_HuongHoaXinh.drawio"

USECASES = [
    ("uc1",  "Dashboard + Biểu đồ\ndoanh thu 7 ngày"),
    ("uc2",  "Xem đơn gần đây\n+ badge NEW"),
    ("uc3",  "CRUD Sản phẩm\n(size / màu / nguyên liệu)"),
    ("uc4",  "Quản lý danh mục hoa"),
    ("uc5",  "Import / Export\nsản phẩm (Excel)"),
    ("uc6",  "Danh sách + filter\nđơn hàng"),
    ("uc7",  "Chi tiết đơn\n(sender + recipient + thiệp)"),
    ("uc8",  "Cập nhật trạng thái đơn"),
    ("uc9",  "Hoàn tồn kho khi huỷ đơn"),
    ("uc10", "Quản lý khách hàng\n(khoá / mở / xoá)"),
    ("uc11", "Quản lý tài khoản Admin\n(+ tạo admin mới)"),
    ("uc12", "CRUD Voucher\n(% / cố định, giới hạn lượt)"),
    ("uc13", "CRUD Bài viết Blog\n(Quill rich editor)"),
    ("uc14", "CRUD Danh mục Blog"),
    ("uc15", "Quản lý đánh giá (ẩn / xoá)"),
    ("uc16", "Chat 2 chiều trả lời khách\n(realtime, badge)"),
    ("uc17", "Cài đặt website\n(logo / hero / SEO / social)"),
    ("uc18", "Doanh thu +\nXuất báo cáo Excel"),
    ("uc19", "Hồ sơ admin + đổi mật khẩu"),
]

# ============== Layout constants ==============
PAGE_W, PAGE_H = 1400, 900
SYS_X, SYS_Y, SYS_W, SYS_H = 160, 50, 1100, 800

UC_W, UC_H = 380, 55
COL1_X = 200
COL2_X = 700
ROW_Y0 = 95
ROW_STEP = 75   # 55 height + 20 gap

ADMIN_X, ADMIN_Y = 65, 400
CUST_X, CUST_Y = 1310, 555


def cell_vertex(cid, value, x, y, w, h, style):
    safe = (value or "").replace("&", "&amp;").replace("<", "&lt;").replace(">", "&gt;").replace('"', "&quot;").replace("\n", "&#xa;")
    return (
        f'        <mxCell id="{cid}" value="{safe}" style="{style}" vertex="1" parent="1">\n'
        f'          <mxGeometry x="{x}" y="{y}" width="{w}" height="{h}" as="geometry" />\n'
        f'        </mxCell>'
    )


def cell_edge(cid, source, target, style):
    return (
        f'        <mxCell id="{cid}" style="{style}" edge="1" parent="1" source="{source}" target="{target}">\n'
        f'          <mxGeometry relative="1" as="geometry" />\n'
        f'        </mxCell>'
    )


def build():
    parts = []

    # Outer system boundary
    sys_style = (
        "rounded=1;whiteSpace=wrap;html=1;fillColor=none;strokeColor=#000000;"
        "verticalAlign=top;align=center;fontSize=14;fontStyle=1;spacingTop=10;"
        "arcSize=4;"
    )
    parts.append(cell_vertex("sys", "HỆ THỐNG HƯƠNG HOA XINH",
                             SYS_X, SYS_Y, SYS_W, SYS_H, sys_style))

    # Actor: Admin
    actor_style = (
        "shape=umlActor;html=1;fillColor=none;strokeColor=#000000;"
        "verticalLabelPosition=bottom;labelBackgroundColor=none;"
        "fontSize=12;fontStyle=1;"
    )
    parts.append(cell_vertex("admin", "Quản trị viên",
                             ADMIN_X, ADMIN_Y, 30, 60, actor_style))
    parts.append(cell_vertex("customer", "Khách hàng",
                             CUST_X, CUST_Y, 30, 60, actor_style))

    # Use cases — 2 columns
    uc_style = (
        "ellipse;whiteSpace=wrap;html=1;fillColor=none;strokeColor=#000000;"
        "fontSize=11;align=center;verticalAlign=middle;"
    )
    for i, (cid, label) in enumerate(USECASES):
        row = i // 2
        col = i % 2
        # UC19 (i=18) goes to row 9 col 0 — actually i=18 → row=9, col=0 ✓ (col 1 of row 9 is empty)
        x = COL1_X if col == 0 else COL2_X
        y = ROW_Y0 + row * ROW_STEP
        parts.append(cell_vertex(cid, label, x, y, UC_W, UC_H, uc_style))

    # Edges Admin → each UC
    edge_style = (
        "endArrow=open;html=1;rounded=0;strokeColor=#000000;"
        "exitX=1;exitY=0.5;exitDx=0;exitDy=0;"
        "fontSize=10;"
    )
    for i, (cid, _) in enumerate(USECASES):
        col = i % 2
        # Entry point: for col 1 (left), entry on left edge; for col 2 (right), entry on left edge too
        entry_style = edge_style + "entryX=0;entryY=0.5;entryDx=0;entryDy=0;"
        parts.append(cell_edge(f"e_a_{cid}", "admin", cid, entry_style))

    # UC16 ..> Customer (dashed)
    dash_style = (
        "endArrow=open;html=1;rounded=0;strokeColor=#000000;dashed=1;"
        "exitX=1;exitY=0.5;entryX=0;entryY=0.5;"
        "fontSize=10;"
    )
    parts.append(cell_edge("e_c", "uc16", "customer", dash_style))

    body = "\n".join(parts)

    xml = (
        '<mxfile host="app.diagrams.net" agent="huonghoaxinh-generator" version="24.0.0">\n'
        '  <diagram id="uc_admin_decomp" name="UC PHÂN RÃ — QUẢN TRỊ VIÊN">\n'
        f'    <mxGraphModel dx="{PAGE_W}" dy="{PAGE_H}" grid="1" gridSize="10" '
        f'guides="1" tooltips="1" connect="1" arrows="1" fold="1" page="1" '
        f'pageScale="1" pageWidth="{PAGE_W}" pageHeight="{PAGE_H}" math="0" shadow="0">\n'
        '      <root>\n'
        '        <mxCell id="0" />\n'
        '        <mxCell id="1" parent="0" />\n'
        f'{body}\n'
        '      </root>\n'
        '    </mxGraphModel>\n'
        '  </diagram>\n'
        '</mxfile>\n'
    )
    return xml


def main():
    xml = build()
    os.makedirs(os.path.dirname(OUT), exist_ok=True)
    with open(OUT, 'w', encoding='utf-8') as f:
        f.write(xml)
    print(f"OK -> {OUT}")
    print(f"Size: {os.path.getsize(OUT)} bytes")


if __name__ == "__main__":
    main()
