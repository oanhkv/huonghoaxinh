"""
Replace 16 admin screenshots inside the .docx ZIP by overwriting the
corresponding word/media/imageN.* files. Original captions/positions stay
intact — only the binary image content is swapped.
"""
import os, sys, io, shutil, zipfile, re
sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

DOC = r'C:\Users\Kieu Anh\Desktop\CD1\Mã đề 18_Nhóm 3_Bài Thi.docx'
SCREENSHOTS = r'C:\Users\Kieu Anh\.claude\projects\C--xampp-htdocs-huonghoaxinh\screenshots'
BACKUP = r'C:\Users\Kieu Anh\Desktop\CD1\Mã đề 18_Nhóm 3_Bài Thi.beforeAdminScreens.docx'

# Map từ tên file screenshot → media path trong docx
# (đã verify đúng vị trí từ document.xml)
MAPPING = {
    "hinh_3_16_admin_login.png":     "word/media/image73.jpg",
    "hinh_3_17_dashboard.png":       "word/media/image74.jpg",
    "hinh_3_18_products_list.png":   "word/media/image75.jpg",
    "hinh_3_19_product_create.png":  "word/media/image76.jpg",
    "hinh_3_20_categories.png":      "word/media/image77.jpg",
    "hinh_3_21_orders_list.png":     "word/media/image78.jpg",
    "hinh_3_22_order_detail.png":    "word/media/image79.jpg",
    "hinh_3_23_users_list.png":      "word/media/image80.jpg",
    "hinh_3_24_vouchers_list.png":   "word/media/image81.jpg",
    "hinh_3_25_voucher_create.png":  "word/media/image82.jpg",
    "hinh_3_26_reviews.png":         "word/media/image83.jpg",
    "hinh_3_27_contact_inbox.png":   "word/media/image84.png",
    "hinh_3_28_contact_chat.png":    "word/media/image85.png",
    "hinh_3_29_revenue.png":         "word/media/image86.jpg",
    "hinh_3_30_settings.png":        "word/media/image87.jpg",
    "hinh_3_31_admin_profile.png":   "word/media/image88.jpg",
}


def main():
    # Backup
    shutil.copy2(DOC, BACKUP)
    print(f"✓ Backup → {BACKUP}")

    # Read all files of original docx
    with zipfile.ZipFile(DOC, 'r') as zin:
        all_names = zin.namelist()
        contents = {name: zin.read(name) for name in all_names}

    # Replace each media file with new screenshot bytes
    replaced = 0
    for screenshot_name, media_path in MAPPING.items():
        screenshot_path = os.path.join(SCREENSHOTS, screenshot_name)
        if not os.path.exists(screenshot_path):
            print(f"✗ MISSING screenshot: {screenshot_path}")
            continue
        if media_path not in contents:
            print(f"✗ MISSING media in docx: {media_path}")
            continue
        with open(screenshot_path, 'rb') as f:
            new_bytes = f.read()
        old_size = len(contents[media_path])
        contents[media_path] = new_bytes
        replaced += 1
        print(f"  {screenshot_name:34s} → {media_path:25s}  "
              f"({old_size:>7d} → {len(new_bytes):>7d} bytes)")

    # Write new docx
    with zipfile.ZipFile(DOC, 'w', zipfile.ZIP_DEFLATED) as zout:
        for name in all_names:
            zout.writestr(name, contents[name])

    print(f"\n✓ Replaced {replaced} admin screenshots in docx")
    print(f"✓ Saved → {DOC}")


if __name__ == "__main__":
    main()
