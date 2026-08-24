# 🏛️ เว็บไซต์พอร์ทัลจังหวัดพัทลุง (Web Phatthalung Modern Portal)
> ระบบพอร์ทัลบริการภาครัฐดิจิทัลและศูนย์กลางข้อมูลข่าวสารจังหวัดพัทลุง พัฒนาด้วย **CodeIgniter 4**, **Bootstrap 5**, **Royal Emerald Government Design System**, และ **ระบบสั่งการด้วยเสียง AI (Speech-to-Text & TTS)**

---

## 🚀 คู่มือการติดตั้งและเปิดใช้งานที่บ้าน (Quick Setup at Home)

### 📌 กรณีที่ 1: มีโฟลเดอร์โปรเจกต์เดิมอยู่ที่บ้านแล้ว
เปิด Terminal หรือ PowerShell ในโฟลเดอร์โปรเจกต์ แล้วรันคำสั่ง:

```bash
# 1. ดึงโค้ดล่าสุดจาก GitHub
git pull origin main

# 2. สั่งรัน Database Migration เพื่ออัปเดตตารางฐานข้อมูลอัตโนมัติ
php spark migrate

# 3. (ทางเลือก) เติมข้อมูลตั้งต้นเข้าระบบ
php spark db:seed JsonToDbSeeder
php spark db:seed SearchIndexSeeder
```

---

### 📌 กรณีที่ 2: เริ่มต้นติดตั้งใหม่บนเครื่องที่บ้าน (Clone ใหม่)

```bash
# 1. Clone โปรเจกต์ไปยังโฟลเดอร์ www ของ WampServer (หรือ htdocs)
cd c:\wamp64\www
git clone https://github.com/fenicmoi/webphatthalung.git
cd webphatthalung

# 2. ติดตั้ง Dependencies (หากจำเป็น)
composer install

# 3. สร้างไฟล์ .env สำหรับตั้งค่าฐานข้อมูล
cp env .env
```

**ตั้งค่าฐานข้อมูลในไฟล์ `.env`:**
```ini
CI_ENVIRONMENT = development

database.default.hostname = localhost
database.default.database = webphatthalung
database.default.username = root
database.default.password = 
database.default.DBDriver = MySQLi
database.default.DBPrefix = 
database.default.port = 3306
```

**สั่งสร้างฐานข้อมูลและตารางอัตโนมัติ:**
```bash
# สั่งสร้างตารางทั้งหมด 16 ตารางอัตโนมัติ
php spark migrate

# นำเข้าข้อมูลตั้งต้น (Seeder)
php spark db:seed JsonToDbSeeder
php spark db:seed SearchIndexSeeder
```

---

## 🌐 ลิงก์เข้าใช้งานระบบ (URLs)

* 🏠 **หน้าหลักประชาชน (Public Portal):**  
  `http://localhost/webphatthalung/public/`
* 🛠️ **ระบบจัดการหลังบ้าน (Admin Panel):**  
  `http://localhost/webphatthalung/public/admin/dashboard`
* 🏛️ **ทำเนียบผู้ว่าราชการจังหวัด (Governor Hall of Fame):**  
  `http://localhost/webphatthalung/public/governors`
* 🗺️ **ระบบแผนที่โครงการพัฒนาจังหวัด (GIS Project Tracker):**  
  `http://localhost/webphatthalung/public/projects/gis`

---

## ✨ ฟีเจอร์เด่นของระบบ (Key Highlights)

1. **🎨 Royal Emerald Government Design (คุมโทนสีเขียวมรกตราชการ):**
   * ออกแบบตามหลักทฤษฎีสี 60-30-10 สวยงาม มีมิติ ลอยตัว และเป็นทางการ
2. **🗓️ ปฏิทินกิจกรรมและตารางงานประจำเดือน (Interactive Monthly Calendar):**
   * ตาราง 7 วันแบบสมมาตร เท่ากันทุกช่อง พร้อมระบบตัดคำอัจฉริยะ (`...`)
   * 🔊 **ระบบอ่านเสียงกำหนดการประจำเดือนด้วยเสียงภาษาไทย (Thai TTS Voice Engine)**
   * 팝업 Modal สีเขียวมรกต พร้อมปุ่มฟังเสียงบรรยายและลิงก์นำทาง Google Maps
3. **🎙️ แผงค้นหาอัจฉริยะระดับสูง (Futuristic Voice AI Search Dock):**
   * ดีไซน์ Command Deck ลอยตัว โดดเด่น ชัดเจน ไม่กลืนกับเนื้อหา
   * 🎤 **ระบบค้นหาด้วยเสียงพูดภาษาไทย (Speech-to-Text Dictation)** แบบ Real-Time
   * 🔥 **ระบบคำค้นหายอดนิยมจริง (Real-Time Search Trends)** อัปเดตอัตโนมัติ
   * 🔍 ค้นหาครอบคลุมทุกเมนู ทำเนียบผู้ว่าฯ เอกสาร ข่าวสาร และโครงการ GIS

---

## 🛠️ คำสั่ง Spark ที่มีประโยชน์ (Useful CLI Commands)

| คำสั่ง | หน้าที่การทำงาน |
| :--- | :--- |
| `php spark migrate` | รันอัปเดตตารางฐานข้อมูลทั้งหมด |
| `php spark migrate:status` | ตรวจสอบสถานะตาราง Migration |
| `php spark migrate:rollback` | ย้อนกลับ Migration ล่าสุด |
| `php spark db:seed JsonToDbSeeder` | ซิงค์ข้อมูล JSON เข้า Database |
| `php spark db:seed SearchIndexSeeder` | รีเฟรชดัชนีการค้นหา Search Index |

---
*จัดทำขึ้นสำหรับการพัฒนาเว็บไซต์จังหวัดพัทลุง (Web Phatthalung Modern Portal)*
