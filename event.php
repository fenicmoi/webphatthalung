<?PHP
$target_protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
$target_domain = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
header("location: {$target_protocol}{$target_domain}/2022/intro");
exit();

// header('Location: intro_king');
// exit();
//require_once 'application/libraries/simple_html_dom.php';
require_once 'simple_html_dom.php';
/**** เธซเธกเธฒเธขเน€เธฅเธ Intro ****/
$defaultNumber  = "1";
$introNumber    = $_REQUEST['number'] ? $_REQUEST['number'] : $defaultNumber;

/**** Domain name ****/
$domainName     = $_SERVER['SERVER_NAME'] ? $_SERVER['SERVER_NAME'] : $_SERVER['HTTP_HOST'];

/**** เธซเธกเธฒเธขเน€เธฅเธ เธญเธเธ•. ****/
$LOCAL_ID       = $domainName;


/**** เธเธทเนเธญ เธญเธเธ•. ****/
$title          = "";
/**** sk, surat, nw, phuket, blar... ****/
$provice        = 'webnok';
/**** index.php , frontpage ****/
$firstPath      = 'webphatthalung';
$secondPath      = 'webphatthalung';
/**** http , https ****/
$protocol       = "https";


/*
     * Search for any Config 
     *  such as 
     *  LOCAL_ID, Title tag
     */ {

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
        $obj                        = $page->find('title');
        if ($obj && $obj[0]->innertext != "") {
            if ($provice != 'webnok') {
                //$title                  = iconv('windows-874', 'utf-8', $obj[0]->innertext);
                $title                  = $obj[0]->innertext;
            } else {
                $title                  = $obj[0]->innertext;
            }
        }

        if ($handle) {
            fwrite($handle, "{$LOCAL_ID},{$title}");
            @fclose($handle);
        }
    }
}
/* Searched Config */





$uploadKey      = "{$provice}{$LOCAL_ID}"; {
    $page       = explode('&', strstr($_SERVER['QUERY_STRING'], 'page'));
    if (count($page) > 1) {
        foreach ($page as $v) {
            $v  = explode('=', $v);
            if ($v[0] == 'page') {
                $page   = $v[1];
                continue;
            }
        }
    } else {
        $page   = explode('=', $page[0]);
        $page   = @$page[1];
    }
}
$page           = $page ? $page : 1;

$url            = "https://api.cityvariety.com/introcenter/frontpage/view/?uploadKey={$uploadKey}&number={$introNumber}&abtname={$domainName}&province={$provice}&page={$page}";

$xml = file_get_html($url);
$pagination     = $xml->find('div.pagination span a');
$len            = count($pagination);
for ($i = 0; $i < $len; $i++) {
    $url        = explode('?', $pagination[$i]->href);
    if (count($url) > 1) {
        $url    = $url[count($url) - 1];
        $url    = "{$protocol}://{$domainName}{$_SERVER['SCRIPT_NAME']}/?" . $url;
    }
    $pagination[$i]->href = $url;
}
$xml            = $xml->save();
$xml            = str_replace('{title}', $title, $xml);
$xml            = str_replace('{domain}', "{$protocol}://$domainName/{$firstPath}", $xml);
$xml            = str_replace('{domain2}', "{$protocol}://$domainName/{$secondPath}", $xml);

echo $xml;

