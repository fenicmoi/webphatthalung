# 🏛️ เว็บไซต์พอร์ทัลจังหวัดพัทลุง (Web Phatthalung Modern Portal)
> ระบบพอร์ทัลบริการภาครัฐดิจิทัลและศูนย์กลางข้อมูลข่าวสารจังหวัดพัทลุง พัฒนาด้วย **CodeIgniter 4**, **Bootstrap 5**, **Royal Emerald Government Design System**, **ระบบสั่งการด้วยเสียง AI (Speech-to-Text & TTS)**, **Live Text Editor** และผู้ช่วยอัจฉริยะ **"น้องโนรา AI (Nora Assistant)"**

---

## 🚀 คู่มือการซิงค์และเปิดใช้งาน (Setup & Sync Workflow)

### 📌 สำหรับการดึงโค้ดไปทำงานต่อ (Office หรือ เครื่องอื่นที่มีโปรเจกต์เดิม)
เปิด Terminal หรือ PowerShell ในโฟลเดอร์โปรเจกต์:

```bash
# 1. ตรวจสอบและเคลียร์ไฟล์ค้าง (ถ้ามี)
git status

# 2. ดึงโค้ดล่าสุดจาก GitHub
git pull origin main

# 3. อัปเดตฐานข้อมูล (เลือกวิธีใดวิธีหนึ่ง):
# วิธีที่ 3.1 (แนะนำ & รวดเร็วที่สุด): Import ไฟล์ db/webphatthalung.sql ผ่าน phpMyAdmin หรือ CLI
mysql -u root -p webphatthalung < db/webphatthalung.sql

# วิธีที่ 3.2: รันผ่าน Spark Migration & Seeders
php spark migrate
php spark db:seed JsonToDbSeeder
php spark db:seed SearchIndexSeeder
```

---

### 📌 สำหรับการติดตั้งใหม่ตั้งแต่ต้น (Fresh Clone)

```bash
# 1. Clone โปรเจกต์ไปยังโฟลเดอร์ www ของ WampServer (หรือ htdocs)
cd c:\wamp64\www
git clone https://github.com/fenicmoi/webphatthalung.git
cd webphatthalung

# 2. ติดตั้ง Dependencies
composer install

# 3. สร้างไฟล์ .env สำหรับตั้งค่าฐานข้อมูล
cp env .env
```

**ตั้งค่าฐานข้อมูลในไฟล์ `.env`:**
```ini
CI_ENVIRONMENT = development

app.baseURL = 'http://localhost/webphatthalung/public/'

database.default.hostname = localhost
database.default.database = webphatthalung
database.default.username = root
database.default.password = 
database.default.DBDriver = MySQLi
database.default.DBPrefix = 
database.default.port = 3306
```

**นำเข้าฐานข้อมูล:**
- สร้างฐานข้อมูลชื่อ `webphatthalung` (Collation: `utf8mb4_unicode_ci`)
- นำเข้าไฟล์ `db/webphatthalung.sql`

---

## 🌐 ลิงก์เข้าใช้งานระบบ (URLs)

* 🏠 **หน้าหลักประชาชน (Public Portal):**  
  `http://localhost/webphatthalung/public/`
* 🛠️ **ระบบจัดการหลังบ้าน (Admin Panel):**  
  `http://localhost/webphatthalung/public/admin/dashboard`
* ✍️ **ระบบจัดการข้อความเว็บไซต์ (Site Text Manager):**  
  `http://localhost/webphatthalung/public/admin/site-texts`
* 🖼️ **ระบบจัดการแบนเนอร์และภาพสไลด์ (Banner Manager):**  
  `http://localhost/webphatthalung/public/admin/banners`
* 🤖 **ระบบจัดการคลังความรู้ AI น้องโนรา (Nora AI Manager):**  
  `http://localhost/webphatthalung/public/admin/nora-ai`
* 🏛️ **ทำเนียบผู้ว่าราชการจังหวัด (Governor Hall of Fame):**  
  `http://localhost/webphatthalung/public/governors`
* 🗺️ **ระบบแผนที่โครงการพัฒนาจังหวัด (GIS Project Tracker):**  
  `http://localhost/webphatthalung/public/projects/gis`

---

## ✨ ฟีเจอร์เด่นของระบบ (Key Features & Highlights)

1. **🎨 Royal Emerald Government Design (คุมโทนสีเขียวมรกตราชการ):**
   * ออกแบบตามหลักทฤษฎีสี 60-30-10 สวยงาม มีมิติ ลอยตัว และได้มาตรฐานหน่วยงานภาครัฐ
2. **🤖 ผู้ช่วยอัจฉริยะ "น้องโนรา" (Nora AI Assistant):**
   * บอทผู้ช่วยอัจฉริยะประจำจังหวัดพัทลุง ผสานพลัง Google Gemini AI เข้ากับฐานข้อมูลท้องถิ่น
   * ค้นหาข้อมูลสถานที่ท่องเที่ยว บริการภาครัฐ ข้อมูลติดต่อหน่วยงาน พร้อมระบบถาม-ตอบแบบเรียลไทม์
   * รองรับการแปลงเสียงสังเคราะห์ (Text-to-Speech) สำเนียงน่ารักเป็นกันเอง
3. **✏️ ระบบแก้ไขข้อความหน้าเว็บแบบสด (Live On-Page Text Editor):**
   * สำหรับผู้ดูแลระบบ สามารถคลิกไอคอนดินสอแก้ไขหัวข้อ/ข้อความต่างๆ บนหน้าพอร์ทัลได้ทันที
   * บันทึกข้อมูลลงฐานข้อมูลแบบ AJAX โดยไม่ต้องเปิดหน้าต่างใหม่
4. **🖼️ ระบบจัดการแบนเนอร์และสไลด์ประชาสัมพันธ์ (Banner Manager):**
   * จัดการภาพ Hero Banner, ภาพกิจกรรมสำคัญ กำหนดช่วงเวลาแสดงผล ลิงก์ปลายทาง และสถานะเปิด/ปิด
5. **🎙️ แผงค้นหาอัจฉริยะระดับสูง (Futuristic Voice AI Search Dock):**
   * 🎤 **ระบบค้นหาด้วยเสียงพูดภาษาไทย (Speech-to-Text Dictation)** แบบ Real-Time
   * 🔥 **ระบบคำค้นหายอดนิยมจริง (Real-Time Search Trends)** อัปเดตอัตโนมัติ
   * 🔍 ค้นหาครอบคลุมทุกเมนู ทำเนียบผู้ว่าฯ เอกสาร ข่าวสาร และโครงการ GIS
6. **🗓️ ปฏิทินกิจกรรมและตารางงานประจำเดือน (Interactive Monthly Calendar):**
   * ตาราง 7 วันสมมาตร พร้อม 🔊 **ระบบอ่านเสียงกำหนดการประจำเดือนด้วยเสียงภาษาไทย (TTS)**
   * Modal แสดงรายละเอียดกิจกรรมและลิงก์นำทาง Google Maps

---

## 🛠️ คำสั่ง Spark และ Git ที่มีประโยชน์

| คำสั่ง | หน้าที่การทำงาน |
| :--- | :--- |
| `git pull origin main` | ดึงโค้ดและไฟล์ DB ล่าสุดจาก GitHub |
| `git status` | ตรวจสอบสถานะไฟล์ในโปรเจกต์ |
| `php spark migrate` | รันอัปเดตตารางฐานข้อมูลทั้งหมด |
| `php spark migrate:status` | ตรวจสอบสถานะตาราง Migration |
| `php spark db:seed JsonToDbSeeder` | ซิงค์ข้อมูล JSON เข้า Database |
| `php spark db:seed SearchIndexSeeder` | รีเฟรชดัชนีการค้นหา Search Index |

---
*จัดทำขึ้นสำหรับการพัฒนาเว็บไซต์พอร์ทัลดิจิทัลจังหวัดพัทลุง (Web Phatthalung Modern Portal)*
