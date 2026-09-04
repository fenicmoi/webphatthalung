# คู่มือการนำโปรเจกต์ Web Phatthalung ไปรันต่อที่บ้าน (Migration & Setup Guide)

คู่มือนี้จัดทำขึ้นเพื่อให้คุณสามารถนำโปรเจกต์ **Web Phatthalung** ไปเปิดและพัฒนาต่อที่บ้านได้อย่างราบรื่น 100%

---

## 🚀 สรุปขั้นตอนด่วน (Quick Start)

เมื่อนำโค้ดไปลงที่เครื่องคอมพิวเตอร์ที่บ้าน (ผ่าน Git หรือ Copy Folder):

1. **คัดลอกไฟล์ `.env.example` ไปเป็น `.env`**
   ```bash
   cp .env.example .env
   ```
   *(หรือเปิดโปรเจกต์แล้วกดดับเบิลคลิกไฟล์ `scripts/setup-db.bat` ระบบจะสร้างไฟล์ `.env` ให้อัตโนมัติ)*

2. **สร้างฐานข้อมูลใน MySQL / phpMyAdmin**
   - ชื่อฐานข้อมูล: `phatthalun_2026db`
   - หรือใช้คำสั่ง SQL: `CREATE DATABASE phatthalun_2026db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;`

3. **ติดตั้งตารางและข้อมูลตั้งต้น (เลือกวิธีใดวิธีหนึ่ง)**:
   - **วิธีที่ 1 (แนะนำ - 1-Click Batch Script)**: 
     ดับเบิลคลิกไฟล์ `scripts\setup-db.bat` (จะรัน Migration ทั้ง 16 ชุด + Master Seeder ครบทุกข้อมูล)
   - **วิธีที่ 2 (ผ่าน Terminal / CLI)**:
     ```bash
     php spark migrate
     php spark db:seed MasterSeeder
     ```
   - **วิธีที่ 3 (Import SQL ตรงเข้า phpMyAdmin หรือ Database GUI)**:
     Import ไฟล์ `db/webphatthalung.sql`
     หรือดับเบิลคลิก `scripts\import-db.bat`

4. **รันเซิร์ฟเวอร์**:
   - ดับเบิลคลิก `scripts\start-server.bat`
   - หรือรันคำสั่ง: `php spark serve`
   - เข้าใช้งานที่: [http://localhost:8080](http://localhost:8080)

---

## 🛠️ เครื่องมือและสคริปต์ในโฟลเดอร์ `scripts/`

| สคริปต์ | หน้าที่การทำงาน |
| :--- | :--- |
| **`scripts/setup-db.bat`** | รัน `migrate` สร้างตารางทั้งหมด และรัน `MasterSeeder` เพื่อนำเข้าข้อมูลตั้งต้นอัตโนมัติ |
| **`scripts/export-db.bat`** | ส่งออกข้อมูลฐานข้อมูลล่าสุดเป็น `db/webphatthalung.sql` และอัปเดตไฟล์ชุดข้อมูล Seed ใน `writable/` ก่อนย้ายเครื่อง |
| **`scripts/import-db.bat`** | นำเข้าไฟล์ `db/webphatthalung.sql` เข้าฐานข้อมูลทันที |
| **`scripts/start-server.bat`** | เริ่มต้นรัน CodeIgniter Development Server ที่พอร์ต `8080` |

---

## 📋 รายละเอียด Migration & Seeders

### 1. โครงสร้างตาราง (Migrations):
- `CreateUsersTable` (ตารางผู้ใช้งานระบบ)
- `CreateNewsTable` (ข่าวประชาสัมพันธ์)
- `CreateServicesTable` (บริการประชาชน)
- `CreateSettingsTable` (การตั้งค่าระบบ)
- `CreateSiteBannersTable` (แบนเนอร์หน้าแรกและไฮไลท์)
- `CreateExecutivesTable` (ทำเนียบคณะผู้บริหาร)
- `CreateGalleryAlbumsTable` / `CreateGalleryPhotosTable` (อัลบั้มภาพกิจกรรม)
- `CreateItaDocumentsTable` (เอกสารเปิดเผยข้อมูลสาธารณะ ITA / OIT)
- `CreateProcurementsTable` (ประกาศจัดซื้อจัดจ้าง)
- `CreateNoraKnowledgeTable` (ฐานความรู้ AI น้องโนรา)
- `SearchIndex` (ดัชนีการค้นหาแบบรวมศูนย์)
- `CreatePagesTable` (หน้าเนื้อหา CMS)
- `AddParentIdToPages` / `AddHeaderImageToPages` (ฟิลด์เมนูย่อยและรูป Header)
- `CreateProvincialProjectsTable` (ระบบติดตามโครงการยุทธศาสตร์จังหวัด 16 โครงการ + การตั้งค่าเชื่อมโยง e-MENSCR)

### 2. ข้อมูลตั้งต้น (Seeders):
- **`MasterSeeder`**: ตัวรันหลัก เรียกทำงาน Seeder ทั้งหมดแบบอัตโนมัติ
  - `JsonToDbSeeder`: นำเข้าข้อมูลแบนเนอร์, คณะผู้บริหาร, ITA, จัดซื้อจัดจ้าง, โนรา AI, แกลเลอรี
  - `PagesSeeder`: นำเข้าหน้าข้อมูลทั่วไป, ประวัติศาสตร์, สัญลักษณ์ประจำจังหวัด, แผนที่ GIS, ยุทธศาสตร์
  - `ProvincialProjectsSeeder`: นำเข้าชุดโครงการยุทธศาสตร์จังหวัดทั้ง 16 โครงการ และการตั้งค่า API e-MENSCR
  - `SearchIndexSeeder`: สร้างดัชนีค้นหาสำหรับทุกหมวดหมู่

---

## 🔄 คำสั่งสำหรับ Synchronize ข้อมูลเมื่อทำงานสลับระหว่างที่ทำงานและที่บ้าน

### ก่อนกลับบ้าน / ก่อนย้ายเครื่อง (เครื่องต้นทาง):
1. ดับเบิลคลิก `scripts\export-db.bat` หรือรัน:
   ```bash
   php scripts/export_db.php
   ```
2. ทำการ Git Commit & Push หรือคัดลอกโฟลเดอร์โปรเจกต์ (รวม `db/webphatthalung.sql` และโฟลเดอร์ `writable/uploads`)

### เมื่อถึงบ้าน (เครื่องปลายทาง):
1. ดึงโค้ดล่าสุด (`git pull`)
2. ดับเบิลคลิก `scripts\setup-db.bat` หรือ `scripts\import-db.bat`
3. ดับเบิลคลิก `scripts\start-server.bat` แล้วพัฒนาต่อได้ทันที!
