<?php
echo "<h3>ตรวจสอบระบบสำหรับ CodeIgniter 4</h3>";
echo "PHP Version: " . PHP_VERSION . " (ต้องการ 8.1 ขึ้นไป) -> ";
echo (version_compare(PHP_VERSION, '8.1.0', '>=')) ? "<b><span style='color:green'>ผ่าน</span></b><br>" : "<b><span style='color:red'>ไม่ผ่าน (เวอร์ชันต่ำไป)</span></b><br>";

$extensions = ['intl', 'mbstring', 'json', 'xml', 'curl', 'mysqli', 'pdo_mysql'];
echo "<ul>";
foreach ($extensions as $ext) {
    echo "<li>Extension <b>$ext</b>: " . (extension_loaded($ext) ? "<span style='color:green'>ติดตั้งแล้ว</span>" : "<span style='color:red'>ยังไม่เปิดใช้งาน</span>") . "</li>";
}
echo "</ul>";

echo "<h4>ข้อมูลโดยละเอียด (phpinfo):</h4>";
phpinfo();
?>
