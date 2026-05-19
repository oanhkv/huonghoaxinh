"""
Playwright script: chụp 20 ảnh giao diện admin của Hương Hoa Xinh.
- Login admin → screenshot từng trang
- Save vào C:\\Users\\Kieu Anh\\.claude\\projects\\C--xampp-htdocs-huonghoaxinh\\screenshots
"""
import asyncio, os, sys, io
sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')
from playwright.async_api import async_playwright

BASE = "http://127.0.0.1:8000"
OUT_DIR = r"C:\Users\Kieu Anh\.claude\projects\C--xampp-htdocs-huonghoaxinh\screenshots"
os.makedirs(OUT_DIR, exist_ok=True)

# (filename, url, optional wait selector, full_page)
PAGES = [
    # ===== 16 trang admin có sẵn (replace existing) =====
    ("hinh_3_16_admin_login.png",         "/admin/login",         "form", False),  # logged-out
    ("hinh_3_17_dashboard.png",           "/admin/dashboard",     None, True),
    ("hinh_3_18_products_list.png",       "/admin/products",      "table, .table-responsive", True),
    ("hinh_3_19_product_create.png",      "/admin/products/create", "form", True),
    ("hinh_3_20_categories.png",          "/admin/categories",    "table, .table-responsive", True),
    ("hinh_3_21_orders_list.png",         "/admin/orders",        "table, .table-responsive", True),
    ("hinh_3_22_order_detail.png",        "/admin/orders/1",      None, True),
    ("hinh_3_23_users_list.png",          "/admin/users",         "table, .table-responsive", True),
    ("hinh_3_24_vouchers_list.png",       "/admin/vouchers",      "table, .table-responsive", True),
    ("hinh_3_25_voucher_create.png",      "/admin/vouchers/create", "form", True),
    ("hinh_3_26_reviews.png",             "/admin/reviews",       "table, .table-responsive", True),
    ("hinh_3_27_contact_inbox.png",       "/admin/contact-messages", None, True),
    ("hinh_3_28_contact_chat.png",        "/admin/contact-messages/1", None, True),
    ("hinh_3_29_revenue.png",             "/admin/revenue",       None, True),
    ("hinh_3_30_settings.png",            "/admin/settings",      "form", True),
    ("hinh_3_31_admin_profile.png",       "/admin/profile",       "form", True),

    # ===== 4 trang Blog admin mới (Hình 3.32-3.35) =====
    ("hinh_3_32_blog_posts_list.png",     "/admin/blog-posts",    None, True),
    ("hinh_3_33_blog_post_form.png",      "/admin/blog-posts/create", "form", True),
    ("hinh_3_34_blog_categories.png",     "/admin/blog-categories", None, True),
    ("hinh_3_35_blog_category_form.png",  "/admin/blog-categories/create", "form", True),
]


async def main():
    async with async_playwright() as pw:
        browser = await pw.chromium.launch(headless=True)
        ctx = await browser.new_context(
            viewport={"width": 1440, "height": 900},
            device_scale_factor=1.5,
        )
        page = await ctx.new_page()

        # ===== 1. Screenshot trang Login TRƯỚC khi đăng nhập =====
        print(f"[1/{len(PAGES)}] /admin/login (logged-out)")
        await page.goto(BASE + "/admin/login", wait_until="domcontentloaded")
        try:
            await page.wait_for_selector("form", timeout=5000)
        except Exception:
            pass
        await page.screenshot(path=os.path.join(OUT_DIR, "hinh_3_16_admin_login.png"),
                              full_page=False)

        # ===== 2. Login admin =====
        await page.fill('input[name="email"]', 'admin@huonghoaxinh.com')
        await page.fill('input[name="password"]', '12345678')
        await page.click('button[type="submit"]')
        try:
            await page.wait_for_url("**/admin/dashboard", timeout=10000)
        except Exception:
            print("  WARN: login didn't redirect to dashboard")

        # ===== 3. Screenshot các trang sau khi đăng nhập =====
        for i, (fname, url, wait_sel, full) in enumerate(PAGES[1:], start=2):
            print(f"[{i}/{len(PAGES)}] {url}")
            try:
                await page.goto(BASE + url, wait_until="networkidle", timeout=15000)
            except Exception as e:
                print(f"  WARN: navigate timeout — continuing: {e}")
            if wait_sel:
                try:
                    await page.wait_for_selector(wait_sel, timeout=4000)
                except Exception:
                    pass
            # Wait briefly for any JS
            await page.wait_for_timeout(800)
            try:
                await page.screenshot(path=os.path.join(OUT_DIR, fname), full_page=full)
                print(f"  saved {fname}")
            except Exception as e:
                print(f"  ERROR saving {fname}: {e}")

        await browser.close()
    print("\nALL DONE.")


if __name__ == "__main__":
    asyncio.run(main())
