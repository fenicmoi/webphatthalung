<?PHP
/*header( "location: https://www.phatthalung.go.th/2026/frontpage" );
exit();*/
// header('Location: intro14');
// exit();
//require_once 'application/libraries/simple_html_dom.php';
require_once 'simple_html_dom.php';
/**** หมายเลข Intro ****/
$defaultNumber  = "1";
$introNumber    = isset($_REQUEST['number']) && $_REQUEST['number'] ? $_REQUEST['number'] : $defaultNumber;
/**** Domain name ****/
$domainName     = !empty($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : (!empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost');
/**** หมายเลข อบต. ****/
$LOCAL_ID       = $domainName;
/**** ชื่อ อบต. ****/
$title          = "";
/**** sk, surat, nw, phuket, blar... ****/
$provice        = 'webnok';
/**** index.php , frontpage ****/
$firstPath      = '2026/frontpage';
$secondPath     = '2026/frontpage';
/**** http , https ****/
$protocol       = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";

if (file_exists('files/event.conf') && file_get_contents('files/event.conf') != "") {
    $data       = file_get_contents('files/event.conf');
    $data       = explode(',', $data);
    $LOCAL_ID   = $data[0];
    $title      = $data[1];
} else {
    $handle     = @fopen('files/event.conf', 'w');
    $path_file_application          = '';
    if (file_exists('application.php')) {
        $path_file_application      = 'application.php';
    } else if (file_exists('lib/application.php')) {
        $path_file_application      = 'lib/application.php';
    } else if (file_exists('cvlibs/application.php')) {
        $path_file_application      = 'cvlibs/application.php';
    } else if (file_exists('cvlib/application.php')) {
        $path_file_application      = 'cvlib/application.php';
    }
    if ($path_file_application != '') {
        require_once $path_file_application;
    }
    $page                       = file_get_html("{$protocol}://{$domainName}/{$firstPath}");
    if ($page) {
        $obj                    = $page->find('title');
        if ($obj && $obj[0]->innertext != "") {
            if ($provice != 'webnok') {
                $title          = $obj[0]->innertext;
            } else {
                $title          = $obj[0]->innertext;
            }
        }
    }
    if ($handle) {
        fwrite($handle, "{$LOCAL_ID},{$title}");
        @fclose($handle);
    }
}
/* Searched Config */
$uploadKey      = "{$provice}{$LOCAL_ID}";
$page           = explode('&', strstr($_SERVER['QUERY_STRING'] ?? '', 'page'));
if (count($page) > 1) {
    foreach ($page as $v) {
        $v      = explode('=', $v);
        if ($v[0] == 'page') {
            $page = $v[1];
            continue;
        }
    }
} else {
    $page       = explode('=', $page[0]);
    $page       = @$page[1];
}
$page           = $page ? $page : 1;
$url            = "https://api.cityvariety.com/introcenter/frontpage/view/?uploadKey={$uploadKey}&number={$introNumber}&abtname={$domainName}&province={$provice}&page={$page}";
$xml            = file_get_html($url);
if ($xml) {
    $pagination = $xml->find('div.pagination span a');
    $len        = count($pagination);
    for ($i = 0; $i < $len; $i++) {
        $url    = explode('?', $pagination[$i]->href);
        if (count($url) > 1) {
            $url = $url[count($url) - 1];
            $url = "{$protocol}://{$domainName}{$_SERVER['SCRIPT_NAME']}/?" . $url;
        }
        $pagination[$i]->href = $url;
    }
    $xml        = $xml->save();
    // $xml     = str_replace('{title}', $title, $xml); //ไม่เอาชื่อหน่วยงานหน้าintro
    $xml        = str_replace('{title}', '', $xml);
    $xml        = str_replace('{domain}', "{$protocol}://$domainName/{$firstPath}", $xml);
    $xml        = str_replace('{domain2}', "{$protocol}://$domainName/{$secondPath}", $xml);
    echo $xml;
}
?>
<script>
    var btnIntro1 = document.querySelector('.btn-intro1');
    if (btnIntro1) {
        btnIntro1.innerHTML = `
  ด้วยเกล้าด้วยกระหม่อม ขอเดชะ<br/>
  ข้าพระพุทธเจ้า ผู้ว่าราชการจังหวัดพัทลุง<br/>
  พร้อมด้วยข้าราชการทุกหมู่เหล่า และพสกนิกรชาวจังหวัดพัทลุง
`;
    }
</script>
