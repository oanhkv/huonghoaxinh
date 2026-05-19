"""
Generate ERD draw.io file using Chen notation:
  - Entity = rectangle (pink)
  - Weak entity = double-border rectangle (orange)
  - Relationship = diamond/rhombus (yellow)
  - Attribute = ellipse (blue), PK underlined
  - Edges = thin black lines with cardinality labels (1, N, M)

Output: 3 pages in single .drawio file
  1. Tổng quan ERD (16 entities + quan hệ, no attrs)
  2. ERD Sản phẩm & Đơn hàng (chi tiết + attrs)
  3. ERD Blog & Chat (chi tiết + attrs)
"""
import sys, io, os
sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

OUT = r"C:\Users\Kieu Anh\Downloads\ERD_HuongHoaXinh.drawio"

# ============== STYLES ==============
S_ENT = (
    "rounded=0;whiteSpace=wrap;html=1;fillColor=#FCE4EC;strokeColor=#C2185B;"
    "fontColor=#880E4F;fontSize=12;fontStyle=1;align=center;verticalAlign=middle;"
)
S_WEAK = (
    "rounded=0;whiteSpace=wrap;html=1;fillColor=#FFE0B2;strokeColor=#E65100;"
    "fontColor=#BF360C;fontSize=11;fontStyle=1;align=center;verticalAlign=middle;"
    "strokeWidth=2;"
)
S_REL = (
    "rhombus;whiteSpace=wrap;html=1;fillColor=#FFF9C4;strokeColor=#F57C00;"
    "fontColor=#7C2D12;fontSize=10;align=center;verticalAlign=middle;"
)
S_ATTR = (
    "ellipse;whiteSpace=wrap;html=1;fillColor=#E3F2FD;strokeColor=#1976D2;"
    "fontColor=#0D47A1;fontSize=10;align=center;verticalAlign=middle;"
)
S_ATTR_PK = (
    "ellipse;whiteSpace=wrap;html=1;fillColor=#E3F2FD;strokeColor=#1976D2;"
    "fontColor=#0D47A1;fontSize=10;fontStyle=4;align=center;verticalAlign=middle;"
)
S_EDGE = (
    "endArrow=none;html=1;rounded=0;strokeColor=#555555;strokeWidth=1.2;"
    "fontSize=11;fontStyle=1;fontColor=#7C2D12;"
)
S_EDGE_TITLE = (
    "text;html=1;strokeColor=none;fillColor=none;fontColor=#0F172A;"
    "fontSize=16;fontStyle=1;align=center;verticalAlign=middle;"
)
S_TITLE_SUB = (
    "text;html=1;strokeColor=none;fillColor=none;fontColor=#475569;"
    "fontSize=11;fontStyle=2;align=center;verticalAlign=middle;"
)


# ============== HELPERS ==============
def cell_vertex(cid, value, x, y, w, h, style):
    safe = (value or "").replace("&", "&amp;").replace("<", "&lt;").replace(">", "&gt;").replace('"', "&quot;").replace("\n", "&#xa;")
    return (
        f'        <mxCell id="{cid}" value="{safe}" style="{style}" vertex="1" parent="1">\n'
        f'          <mxGeometry x="{x}" y="{y}" width="{w}" height="{h}" as="geometry" />\n'
        f'        </mxCell>'
    )


def cell_edge(cid, source, target, label="", style=None):
    style = style or S_EDGE
    safe = (label or "").replace("&", "&amp;").replace("<", "&lt;").replace(">", "&gt;").replace('"', "&quot;")
    return (
        f'        <mxCell id="{cid}" value="{safe}" style="{style}" edge="1" parent="1" source="{source}" target="{target}">\n'
        f'          <mxGeometry relative="1" as="geometry" />\n'
        f'        </mxCell>'
    )


# ============================================================
# PAGE 1: TỔNG QUAN — 16 entities + quan hệ chính (no attrs)
# ============================================================
def page_overview():
    out = []
    # Title
    out.append(cell_vertex("ov_title", "SƠ ĐỒ ERD TỔNG QUAN — HỆ THỐNG HƯƠNG HOA XINH",
                           400, 20, 1000, 30, S_EDGE_TITLE))
    out.append(cell_vertex("ov_sub", "16 thực thể · Chen notation: ◻ Entity · ◇ Relationship · cardinality (1, N, M)",
                           400, 50, 1000, 20, S_TITLE_SUB))

    # Entities (positioned in grid)
    EW, EH = 140, 60
    REW, REH = 110, 60   # relationship diamond size
    entities = {
        # core
        "users":     (220, 480),
        "admins":    (220, 100),
        "categories":(750, 100),
        "products":  (750, 380),
        "orders":    (1300, 480),
        "vouchers":  (1700, 480),
        "reviews":   (1050, 720),
        # weak/junction
        "carts":         (550, 580),
        "wishlists":     (550, 660),
        "order_items":   (1050, 480),
        "voucher_user_usages": (1500, 720),
        # blog
        "blog_categories": (220, 880),
        "blog_posts":      (550, 880),
        # contact
        "contact_messages": (1050, 100),
        "contact_replies":  (1300, 280),
        # settings
        "website_settings": (1700, 100),
    }
    weak = {"carts", "wishlists", "order_items", "voucher_user_usages", "contact_replies"}

    labels = {
        "users": "USERS", "admins": "ADMINS",
        "categories": "CATEGORIES", "products": "PRODUCTS",
        "orders": "ORDERS", "vouchers": "VOUCHERS",
        "reviews": "REVIEWS", "carts": "CARTS",
        "wishlists": "WISHLISTS", "order_items": "ORDER_ITEMS",
        "voucher_user_usages": "VOUCHER_USER_USAGES",
        "blog_categories": "BLOG_CATEGORIES", "blog_posts": "BLOG_POSTS",
        "contact_messages": "CONTACT_MESSAGES",
        "contact_replies": "CONTACT_REPLIES",
        "website_settings": "WEBSITE_SETTINGS",
    }

    for name, (x, y) in entities.items():
        style = S_WEAK if name in weak else S_ENT
        w = 160 if "_" in labels[name] else EW
        out.append(cell_vertex(f"ov_{name}", labels[name], x, y, w, EH, style))

    # Relationships (diamond + 2 edges)
    # Format: (id, label, src, tgt, dx, dy from src, card_src, card_tgt)
    rels = [
        # Categories
        ("rel_cat_self", "cha-con", "categories", "categories", 880, 100, "1", "N"),
        ("rel_cat_prod", "thuộc",   "categories", "products",   880, 250, "1", "N"),
        # Products junction relationships
        ("rel_carts",     "trong giỏ",    "users", "products",  450, 480, "1", "N"),
        ("rel_wishlist",  "yêu thích",    "users", "products",  450, 580, "1", "N"),
        ("rel_orderitem", "gồm",          "orders", "products", 1180, 420, "1", "N"),
        ("rel_review_u",  "viết",         "users", "reviews",   650, 720, "1", "N"),
        ("rel_review_p",  "nhận",         "products", "reviews", 880, 720, "1", "N"),
        # Orders
        ("rel_order",     "đặt",          "users", "orders",    750, 500, "1", "N"),
        # Voucher ternary
        ("rel_voucher_u", "dùng",         "vouchers", "voucher_user_usages", 1600, 600, "1", "N"),
        ("rel_vuu_user",  "của",          "voucher_user_usages", "users", 880, 750, "N", "1"),
        # Carts/Wishlists/Order_items connect to junction tables  (just labels on edges)
        # We'll add edges between entities and junction tables directly
        # Admins → contact_replies
        ("rel_adm_rep",   "trả lời",      "admins", "contact_replies", 750, 200, "1", "N"),
        # Users → contact_messages
        ("rel_user_msg",  "có",           "users", "contact_messages", 750, 280, "1", "1"),
        # Contact_messages → contact_replies
        ("rel_msg_rep",   "có",           "contact_messages", "contact_replies", 1180, 200, "1", "N"),
        # Users can also reply
        ("rel_user_rep",  "reply",        "users", "contact_replies", 1180, 360, "1", "N"),
        # Blog
        ("rel_blog",      "phân loại",    "blog_categories", "blog_posts", 380, 880, "1", "N"),
    ]
    for rid, lbl, src, tgt, dx, dy, c_src, c_tgt in rels:
        out.append(cell_vertex(rid, lbl, dx, dy, REW, REH, S_REL))
        out.append(cell_edge(f"e_{rid}_s", src, rid, c_src))
        out.append(cell_edge(f"e_{rid}_t", rid, tgt, c_tgt))

    return "\n".join(out)


# ============================================================
# PAGE 2: ERD CORE — Sản phẩm & Đơn hàng (chi tiết + attrs)
# ============================================================
def page_core():
    out = []
    out.append(cell_vertex("co_title", "ERD CHI TIẾT — Sản phẩm & Đơn hàng",
                           400, 20, 1100, 30, S_EDGE_TITLE))
    out.append(cell_vertex("co_sub",   "Entity + Relationship + Attribute (PK gạch chân)",
                           400, 50, 1100, 20, S_TITLE_SUB))

    # Entities with positions
    entities = {
        "categories": ("CATEGORIES", 750, 120, False),
        "products":   ("PRODUCTS",   750, 400, False),
        "users":      ("USERS",      150, 700, False),
        "orders":     ("ORDERS",     900, 850, False),
        "vouchers":   ("VOUCHERS",   1550, 850, False),
        "reviews":    ("REVIEWS",    1300, 480, False),
        "carts":         ("CARTS",         400, 480, True),
        "wishlists":     ("WISHLISTS",     400, 580, True),
        "order_items":   ("ORDER_ITEMS",   900, 600, True),
        "voucher_user_usages": ("VOUCHER_USER_USAGES", 1250, 850, True),
    }
    EW, EH = 160, 60

    for name, (label, x, y, is_weak) in entities.items():
        style = S_WEAK if is_weak else S_ENT
        out.append(cell_vertex(f"co_{name}", label, x, y, EW, EH, style))

    # ===== Attributes per entity =====
    # Format: (entity_id, [(attr_name, dx, dy, pk?), ...])
    AW, AH = 95, 40
    attrs = {
        "categories": [
            ("id",       -120, -50, True),
            ("name",     +180, -50, False),
            ("parent_id", +180, +40, False),
        ],
        "products": [
            ("id",     -130, -45, True),
            ("name",    -10, -90, False),
            ("price",  +180, -45, False),
            ("stock",  +180, +45, False),
            ("image",   -10, +90, False),
        ],
        "users": [
            ("id",    -120, -50, True),
            ("name",   -10, -90, False),
            ("email", +180, -50, False),
            ("phone", -120, +75, False),
        ],
        "orders": [
            ("id",            -130, -50, True),
            ("order_code",     +180, -50, False),
            ("total_amount",   +180, +40, False),
            ("status",         -10, +90, False),
            ("recipient_name", -130, +75, False),
        ],
        "vouchers": [
            ("id",    -120, -50, True),
            ("code",   +180, -50, False),
            ("value", +180, +40, False),
        ],
        "reviews": [
            ("id",     -120, -50, True),
            ("rating",  +180, -50, False),
            ("comment", +180, +40, False),
        ],
        "carts": [
            ("id",       -120, -45, True),
            ("quantity", +180, -45, False),
        ],
        "wishlists": [
            ("id", -120, -45, True),
        ],
        "order_items": [
            ("id",       -130, -45, True),
            ("quantity", +180, -45, False),
            ("price",    +180, +40, False),
        ],
        "voucher_user_usages": [
            ("id",        -130, -45, True),
            ("used_at",   +180, -45, False),
        ],
    }

    for ent_id, attr_list in attrs.items():
        ex, ey = entities[ent_id][1], entities[ent_id][2]
        for j, (attr_name, dx, dy, pk) in enumerate(attr_list):
            aid = f"co_{ent_id}_a{j}"
            ax, ay = ex + dx, ey + dy
            style = S_ATTR_PK if pk else S_ATTR
            out.append(cell_vertex(aid, attr_name, ax, ay, AW, AH, style))
            out.append(cell_edge(f"co_e_{ent_id}_a{j}", f"co_{ent_id}", aid))

    # ===== Relationships =====
    REW, REH = 105, 55
    rels = [
        # (id, label, src, tgt, x, y, card_src, card_tgt)
        ("co_r_cat_self", "cha-con",  "categories", "categories", 950, 150, "1", "N"),
        ("co_r_cat_prod", "thuộc",    "categories", "products",   780, 250, "1", "N"),
        ("co_r_carts",    "đặt vào",  "users",  "carts",       380, 600, "1", "N"),
        ("co_r_carts2",   "chứa",     "products","carts",       620, 480, "N", "1"),
        ("co_r_wish",     "yêu thích","users",  "wishlists",   380, 670, "1", "N"),
        ("co_r_wish2",    "là",       "products","wishlists",   620, 580, "N", "1"),
        ("co_r_order",    "đặt",      "users",  "orders",      450, 850, "1", "N"),
        ("co_r_orderitem","gồm",      "orders","order_items", 920, 720, "1", "N"),
        ("co_r_oi2",      "chứa",     "products","order_items", 720, 500, "1", "N"),
        ("co_r_review_u", "viết",     "users","reviews",      900, 700, "1", "N"),
        ("co_r_review_p", "nhận",     "products","reviews",   1100, 480, "1", "N"),
        ("co_r_vuu_v",    "dùng",     "vouchers","voucher_user_usages", 1450, 850, "1", "N"),
        ("co_r_vuu_u",    "của",      "voucher_user_usages","users", 700, 880, "N", "1"),
        ("co_r_vuu_o",    "áp vào",   "voucher_user_usages","orders", 1100, 920, "N", "1"),
    ]
    for rid, lbl, src, tgt, x, y, c_src, c_tgt in rels:
        out.append(cell_vertex(rid, lbl, x, y, REW, REH, S_REL))
        out.append(cell_edge(f"co_e_{rid}_s", f"co_{src}", rid, c_src))
        out.append(cell_edge(f"co_e_{rid}_t", rid, f"co_{tgt}", c_tgt))

    return "\n".join(out)


# ============================================================
# PAGE 3: ERD CONTENT — Blog & Chat (chi tiết + attrs)
# ============================================================
def page_content():
    out = []
    out.append(cell_vertex("ct_title", "ERD CHI TIẾT — Blog & Chat & Settings",
                           400, 20, 1100, 30, S_EDGE_TITLE))
    out.append(cell_vertex("ct_sub",   "Hệ thống Chat 2 chiều + Blog CMS + Cài đặt website",
                           400, 50, 1100, 20, S_TITLE_SUB))

    EW, EH = 180, 60
    entities = {
        "users":  ("USERS",  150, 400, False),
        "admins": ("ADMINS", 150, 100, False),
        "contact_messages": ("CONTACT_MESSAGES", 700, 250, False),
        "contact_replies":  ("CONTACT_REPLIES",  1200, 250, True),
        "blog_categories":  ("BLOG_CATEGORIES",  150, 750, False),
        "blog_posts":       ("BLOG_POSTS",       700, 750, False),
        "website_settings": ("WEBSITE_SETTINGS", 1400, 750, False),
    }
    for name, (label, x, y, is_weak) in entities.items():
        style = S_WEAK if is_weak else S_ENT
        out.append(cell_vertex(f"ct_{name}", label, x, y, EW, EH, style))

    # Attributes
    AW, AH = 95, 40
    attrs = {
        "users": [
            ("id",    -120, -50, True),
            ("name",   +200, -50, False),
            ("email",  +200, +40, False),
        ],
        "admins": [
            ("id",    -120, -50, True),
            ("name",   +200, -50, False),
            ("email",  +200, +40, False),
            ("is_active", -120, +75, False),
        ],
        "contact_messages": [
            ("id",       -120, -50, True),
            ("name",      0, -90, False),
            ("subject", +200, -50, False),
            ("status",  +200, +40, False),
        ],
        "contact_replies": [
            ("id",        -120, -50, True),
            ("body",       +200, -50, False),
            ("admin_id",  +200, +40, False),
            ("user_id",   -120, +75, False),
            ("customer_read_at", 0, +90, False),
        ],
        "blog_categories": [
            ("id",   -120, -50, True),
            ("name",  +200, -50, False),
            ("slug",  +200, +40, False),
        ],
        "blog_posts": [
            ("id",       -120, -50, True),
            ("title",     +200, -50, False),
            ("slug",      +200, +40, False),
            ("content",   0,  +90, False),
            ("is_active", -120, +75, False),
            ("published_at", +200, +90, False),
        ],
        "website_settings": [
            ("id",    -120, -50, True),
            ("key",    +200, -50, False),
            ("value",  +200, +40, False),
        ],
    }
    for ent_id, attr_list in attrs.items():
        ex, ey = entities[ent_id][1], entities[ent_id][2]
        for j, (attr_name, dx, dy, pk) in enumerate(attr_list):
            aid = f"ct_{ent_id}_a{j}"
            ax, ay = ex + dx, ey + dy
            style = S_ATTR_PK if pk else S_ATTR
            out.append(cell_vertex(aid, attr_name, ax, ay, AW, AH, style))
            out.append(cell_edge(f"ct_e_{ent_id}_a{j}", f"ct_{ent_id}", aid))

    # Relationships
    REW, REH = 110, 55
    rels = [
        ("ct_r_user_msg",  "có",       "users", "contact_messages", 450, 350, "1", "1"),
        ("ct_r_msg_rep",   "có",       "contact_messages", "contact_replies", 1000, 270, "1", "N"),
        ("ct_r_adm_rep",   "trả lời",  "admins", "contact_replies", 700, 130, "1", "N"),
        ("ct_r_user_rep",  "reply",    "users", "contact_replies", 700, 450, "1", "N"),
        ("ct_r_blog",      "phân loại","blog_categories", "blog_posts", 450, 770, "1", "N"),
    ]
    for rid, lbl, src, tgt, x, y, c_src, c_tgt in rels:
        out.append(cell_vertex(rid, lbl, x, y, REW, REH, S_REL))
        out.append(cell_edge(f"ct_e_{rid}_s", f"ct_{src}", rid, c_src))
        out.append(cell_edge(f"ct_e_{rid}_t", rid, f"ct_{tgt}", c_tgt))

    # Note about settings
    out.append(cell_vertex("ct_note", "Bảng key-value độc lập\n(không có quan hệ FK)",
                           1380, 880, 220, 50,
                           "text;html=1;strokeColor=none;fillColor=none;fontColor=#7C2D12;fontSize=10;fontStyle=2;align=center;"))

    return "\n".join(out)


# ============================================================
# Build full .drawio with 3 pages
# ============================================================
def diagram_xml(diag_id, name, page_w, page_h, body):
    name_safe = name.replace("&", "&amp;").replace("<", "&lt;").replace(">", "&gt;")
    return (
        f'  <diagram id="{diag_id}" name="{name_safe}">\n'
        f'    <mxGraphModel dx="{page_w}" dy="{page_h}" grid="1" gridSize="10" '
        f'guides="1" tooltips="1" connect="1" arrows="1" fold="1" page="1" '
        f'pageScale="1" pageWidth="{page_w}" pageHeight="{page_h}" math="0" shadow="0">\n'
        '      <root>\n'
        '        <mxCell id="0" />\n'
        '        <mxCell id="1" parent="0" />\n'
        f'{body}\n'
        '      </root>\n'
        '    </mxGraphModel>\n'
        '  </diagram>\n'
    )


def main():
    pages = [
        diagram_xml("erd_overview", "1. Tổng quan ERD",        1900, 1050, page_overview()),
        diagram_xml("erd_core",     "2. ERD - Sản phẩm & Đơn", 1850, 1100, page_core()),
        diagram_xml("erd_content",  "3. ERD - Blog & Chat",    1700,  950, page_content()),
    ]
    xml = (
        '<mxfile host="app.diagrams.net" agent="huonghoaxinh-erd" version="24.0.0">\n'
        + "".join(pages)
        + '</mxfile>\n'
    )
    os.makedirs(os.path.dirname(OUT), exist_ok=True)
    with open(OUT, 'w', encoding='utf-8') as f:
        f.write(xml)
    print(f"OK -> {OUT}")
    print(f"Size: {os.path.getsize(OUT)} bytes")


if __name__ == "__main__":
    main()
