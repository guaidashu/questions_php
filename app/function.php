<?php
//通用函数文件
//默认引入代码在bootstrap文件夹下的autoload.php里

//输出测试函数 ，便于查看
//第一个参数为传入的数据，第二个为是否终止(exit)(默认为否fasle)
function debug($arr, $exit = false)
{
    echo "<pre>";
    if (is_array($arr) || is_object($arr)) {
        var_dump($arr);
    } else if ($arr != 0 && empty($arr)) {
        echo "NULL";
    } else {
        echo $arr;
    }
    echo "</pre>";
    echo "<hr />";
    if ($exit) {
        exit;
    }
}

// 将内容转化为json变量并返回 ，参数content为主要内容，id为数据库操作返回id，用时可加
// reply为评论回复id ，用时可加，另外id和reply可酌情使用，看情况
function successReply($data = "", $code = 0, $msg = "", $encode = false)
{
    $arr = array(
        "code" => $code,
        "data" => $data,
        "msg" => $msg
    );

    if ($encode) {
        return json_encode($arr);
    }

    return $arr;
}

function errReply($msg = "", $code = 1, $data = "", $encode = false)
{
    $arr = array(
        "code" => $code,
        "data" => $data,
        "msg" => $msg
    );

    if ($encode) {
        return json_encode($arr);
    }

    return $arr;
}

/**
 * 构造分页
 *
 * @param $data
 * @param $total
 * @return array
 */
function pagination($data, $total)
{
    return array(
        "total" => $total,
        "data" => $data
    );
}

// 邮箱判断函数，检查是否为一个正确的邮箱
function emailCheck($email)
{
    $pattern = "/^\S([a-zA-Z0-9]*)(@)(163|126|sina|sohu|139|gmail|hotmail|21cn|qq)(\.com)$/";
    if (!preg_match($pattern, $email)) {
        return false;
    } else {
        return true;
    }
}

// 电话号码判断函数，检查是否是1开头的十一位电话号码，可酌情修改
function phoneCheck($phone)
{
    $pattern = "/^1([0-9]){10}$/";
    if (!preg_match($pattern, $phone)) {
        return false;
    } else {
        return true;
    }
}

// 判断入学年份 是否为一个四位的纯数字
function yearCheck($year)
{
    $pattern = "/^([0-9]){4}$/";
    if (!preg_match($pattern, $year)) {
        return false;
    } else {
        return true;
    }
}

// 判断是否为纯数字，在文章主键和其他一些传递的时候可以用来过滤，安全性更高
function numCheck($num)
{
    $pattern = "/^([0-9]*)$/";
    if (!preg_match($pattern, $num)) {
        return false;
    } else {
        return true;
    }
}

// 图片转换函数
/*
// 创建一个空图像并在其上加入一些文字
$im = imagecreatetruecolor(120, 20);
$text_color = imagecolorallocate($im, 233, 14, 91);

imagestring($im, 1, 5, 5,  'WebP with PHP', $text_color);

// 保存图像
imagewebp($im, '../superhomework/php.webp');

// 释放内存
imagedestroy($im);
*/
//$src为图片路径和名称  $path为移动到的位置
function imagesChange($src, $path)
{
    $imagesSize = getimagesize($src);
    //获取图片名字，没有后缀
    $imagesName = pathinfo($src, PATHINFO_FILENAME);
    //获取图片宽度
    $width = $imagesSize[0];
    //获取图片高度
    $height = $imagesSize[1];
    //debug($imagesSize);
    //echo $height." ".$width;
    //debug($imagesName);
    $images = imagecreatetruecolor($width, $height);//创建一张图片
    $type = image_type_to_extension($imagesSize[2], false);//获取图片的后缀，第二个参数为false表示不取 点(.)
    $fun = "imagecreatefrom" . $type;
    $oldImages = $fun($src);//创建一张原图的内存临时资源图,格式为jpeg
    imagecopyresampled($images, $oldImages, 0, 0, 0, 0, $width, $height, $width, $height);//将原图拷贝到新的图像上
    imagedestroy($oldImages);//销毁原图
    header("Content-type:image/jpeg");
    //imagejpeg($images);
    ////移动图片到指定位置
    imagejpeg($images, $path);
    //销毁原图释放资源
    imagedestroy($images);
}


/*
        爬虫函数些    参数 $url是要抓取的URL  $cookieFile 是要存的暂时cookie文件夹
 */
function getCookie($url)
{
    // 保存cookie的文件
    // $cookieFile = "tmp.cookie";
    // 设置允许运行的时间，防止死循环
    $timeOut = 5;
    $ip = virtualIp();
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, "baiduspider");
    // 使curl 返回并不输出，而是返回字符串
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('CLIENT-IP:' . $ip, 'X-FORWARDED-FOR:' . $ip));
    // 设置连接时间
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeOut);
    // 保存cookie到指定文件夹
    // curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
    $result = curl_exec($ch);
    preg_match('/Set\-Cookie:([\w\W]*?);/', $result, $str);
    curl_close($ch);
    if (!empty($str[1])) {
        return $str[1];
    } else {
        return "failed";
    }
}

// 保存验证码的图片, $url 为验证码的url地址 ,$cookieFile 是要存的暂时cookie文件夹
function getVerify($url, $cookie, $imgName, $referer = null)
{
    // $imgName = "images/verify.jpg";
    $timeOut = 20;
    $ip = virtualIp();
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    // 带上刚刚的cookie进行访问
    curl_setopt($ch, CURLOPT_COOKIE, $cookie);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    if (!empty($referer)) {
        curl_setopt($ch, CURLOPT_REFERER, $referer);
    }
    curl_setopt($ch, CURLOPT_USERAGENT, "baiduspider");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('CLIENT-IP:' . $ip, 'X-FORWARDED-FOR:' . $ip));
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    // 设置连接时间
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeOut);
    // 执行curl并且返回图片
    $img = curl_exec($ch);
    curl_close($ch);
    $fp = fopen($imgName, "w");
    fwrite($fp, $img);
    fclose($fp);
}

// 模拟登录函数
// 此处的$url为登录处理页面的URL连接，$info为信息数组包括验证码，用foreach来遍历(内置默认为用户名，密码和验证码)
function curlLogin($url, $post, $cookie, $referer = null)
{
    $ch = curl_init();
    $ip = virtualIp();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    if (!empty($referer)) {
        curl_setopt($ch, CURLOPT_REFERER, $referer);
    }
    curl_setopt($ch, CURLOPT_USERAGENT, "baiduspider");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('CLIENT-IP:' . $ip, 'X-FORWARDED-FOR:' . $ip));
    curl_setopt($ch, CURLOPT_COOKIE, $cookie);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

// 模拟登录函数
// 此处的$url为登录处理页面的URL连接，$info为信息数组包括验证码，用foreach来遍历(内置默认为用户名，密码和验证码)
function curlPost($url, $post, $cookie = null, $referer = null)
{
    $ch = curl_init();
    $ip = virtualIp();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    if (!empty($referer)) {
        curl_setopt($ch, CURLOPT_REFERER, $referer);
    }
    curl_setopt($ch, CURLOPT_USERAGENT, "baiduspider");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, is_array($post) ? http_build_query($post) : $post);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('CLIENT-IP:' . $ip, 'X-FORWARDED-FOR:' . $ip, 'Content-Type:application/json'));
    if ($cookie) {
        curl_setopt($ch, CURLOPT_COOKIE, $cookie);
    }
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

function getInfo($url, $cookie = null)
{
    // $url = "http://61.139.105.105:8088/Student/Detail";
    $ip = virtualIp();
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    // 伪造百度useragent
    curl_setopt($ch, CURLOPT_USERAGENT, "baiduspider");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('CLIENT-IP:' . $ip, 'X-FORWARDED-FOR:' . $ip));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_COOKIE, $cookie);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

//需要加域名请求的信息获取
function getInfoRefer($url, $refer = null, $cookie = null)
{
    // 伪造Ip
    $ip = virtualIp();
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, "baiduspider");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('CLIENT-IP:' . $ip, 'X-FORWARDED-FOR:' . $ip));
    curl_setopt($ch, CURLOPT_REFERER, $refer);
    curl_setopt($ch, CURLOPT_COOKIE, $cookie);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

// 伪造ip函数
function virtualIp()
{
    $arr_1 = array("218", "218", "66", "66", "218", "218", "60", "60", "202", "204", "66", "66", "66", "59", "61", "60", "222", "221", "66", "59", "60", "60", "66", "218", "218", "62", "63", "64", "66", "66", "122", "211");
    $randarr = mt_rand(0, count($arr_1));
    if ($randarr > 30) {
        $randarr = $randarr - 1;
    }
    $ip1id = $arr_1[$randarr];
    $ip2id = round(rand(600000, 2550000) / 10000);
    $ip3id = round(rand(600000, 2550000) / 10000);
    $ip4id = round(rand(600000, 2550000) / 10000);
    $ip = $ip1id . "." . $ip2id . "." . $ip3id . "." . $ip4id;
    return $ip;
}


// table转数组函数
function get_td_array($table)
{
    $pattern = '/<tbody>([\w\W]*?)<\/tbody>/';
    preg_match($pattern, $table, $matches);

    if (empty($matches[0])) {
        $table = null;
    } else {
        $table = $matches[0];
    }

    $table = str_replace(array("</tr>", "</td>"), array("{tr}", "{td}"), $table);
//去掉 HTML 标记
    $table = preg_replace("/<\/?[^>]+>/i", '', $table);
    $table = explode('{tr}', $table);
    array_pop($table);
// debug($table);
    foreach ($table as $key => $tr) {
        $td = explode('{td}', $tr);
//去掉空格
        $td = str_replace(array(" ", "　", "\t", "\n", "\r", "&nbsp;"), array("", "", "", "", "", ""), $td);
        array_pop($td);
        $td_array[] = $td;
    }
    if (empty($td_array)) {
        return null;
    } else {
        return $td_array;
    }
}


// 获取IP函数
function getIP()
{
    global $ip;
    if (getenv("HTTP_CLIENT_IP"))
        $ip = getenv("HTTP_CLIENT_IP");
    else if (getenv("HTTP_X_FORWARDED_FOR"))
        $ip = getenv("HTTP_X_FORWARDED_FOR");
    else if (getenv("REMOTE_ADDR"))
        $ip = getenv("REMOTE_ADDR");
    else $ip = "Unknow";
    return $ip;
}
