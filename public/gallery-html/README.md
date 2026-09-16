# คลังภาพกิจกรรมจังหวัดพัทลุง (Phatthalung Visual Archive)

Modern Government Website - Premium Front-end Template ออกแบบตามมาตรฐานเว็บไซต์ภาครัฐไทย โดยใช้ **Bootstrap 5**, **Google Font (Noto Sans Thai)**, **Font Awesome 6**, และ **Vanilla JavaScript ES6+**

---

## 📁 โครงสร้างไฟล์ (File Structure)

```text
gallery-html/
├── index.html       # โครงสร้างหน้าเว็บ Semantic HTML5 พร้อม Bootstrap 5
├── css/
│   └── style.css    # สไตล์ชีทระบบสี สีเขียวพระราชทานพัทลุง โหมดมืด และการจัดวาง
├── js/
│   └── main.js      # JavaScript ES6+ (Data Array, ค้นหาแบบเรียลไทม์, ฟิลเตอร์, ปรับฟอนต์, Modal)
└── README.md        # คู่มือการใช้งาน
```

---

## 🎨 ระบบสี (Color System)
- **Primary:** `#006B54`
- **Dark Green:** `#004D40`
- **Deep Green:** `#003B30`
- **Accent:** `#00A878`
- **Light Green:** `#EAF7F2`
- **Background:** `#F7F9F8`
- **White:** `#FFFFFF`
- **Text:** `#17332D`
- **Muted:** `#71807B`

---

## 🚀 ฟังก์ชันการทำงานหลัก (Features)
1. **Top Information Bar**: สายด่วน 1567, ปรับขนาดตัวอักษร 3 ระดับ (A-, A, A+), สลับภาษา และสวิตช์ Dark Mode ที่บันทึกลง LocalStorage
2. **Navbar**: ตราสัญลักษณ์จังหวัดพัทลุง, เมนูดรอปดาวน์ Bootstrap 5, ปุ่ม Login ทันสมัย, มีเงา Shadow เมื่อเลื่อนหน้าจอ
3. **Hero Banner**: ภาพทิวทัศน์พัทลุงพร้อม Gradient Overlay สีเขียวเข้ม, Breadcrumb, และ Typography ชัดเจน
4. **Category Filter & Real-time Search**: ปุ่ม Pill Filter หมวดหมู่ พร้อมกล่องค้นหาแบบเรียลไทม์ (ค้นหาจาก ชื่อ, หมวดหมู่, คำอธิบาย, และสถานที่)
5. **Gallery Cards & Badges**: อัตราส่วนภาพ 16:9, เอฟเฟกต์ Hover Zoom, Badge หมวดหมู่, วันที่, และจำนวนภาพ
6. **Bootstrap 5 Modal**: แสดงภาพขนาดใหญ่และรายละเอียดกิจกรรมอย่างครบถ้วนเมื่อกด "ดูรายละเอียด →"
7. **Floating Action Buttons**: ปุ่ม "ติดต่อเจ้าหน้าที่" ด้านซ้ายล่าง และปุ่ม "ถามน้องโสธร AI" ด้านขวาล่าง พร้อมไฟกระพริบ Pulse Dot
8. **Responsive**: รองรับมือถือ (1 คอลัมน์), แท็บเล็ต (2 คอลัมน์), และคอมพิวเตอร์เดสก์ท็อป (3 คอลัมน์) ไม่มี Overflow
