<?php

use App\Models\General_Setting;
use App\Models\Common;
use Illuminate\Support\Facades\Auth;

// Setting
function Setting_Data()
{
    $setting = General_Setting::get();
    $data = [];
    foreach ($setting as $value) {
        $data[$value->key] = $value->value;
    }
    return $data;
}
function App_Name()
{
    $data = Setting_Data();
    $app_name = $data['app_name'];

    if (isset($app_name) && $app_name != "") {
        return $app_name;
    } else {
        return env('APP_NAME');
    }
}
function Tab_Icon()
{
    $settingData = Setting_Data();

    $name = $settingData['app_logo'];
    $folder = "app";
    $model = new Common();

    $icon_parth = $model->getImage($folder, $name);
    return $icon_parth;
}
function Item_Code()
{
    return base64_decode('NTAzMTM0NDQ=');
}
function Delete_Reels_Day()
{
    $settingData = Setting_Data();
    return $settingData['after_day_delete_reels'];
}

// Basic
function Currency_Code()
{
    $data = Setting_Data();
    return $data['currency_code'];
}
function String_Cut($string, $len)
{
    if (strlen($string) > $len) {
        $string = mb_substr(strip_tags($string), 0, $len, 'utf-8') . '...';
        // $string = substr(strip_tags($string),0,$len).'...';
    }
    return $string;
}
function No_Format($num)
{
    if ($num > 1000) {
        $x = round($num);
        $x_number_format = number_format($x);
        $x_array = explode(',', $x_number_format);
        $x_parts = array('K', 'M', 'B', 'T');
        $x_count_parts = count($x_array) - 1;
        $x_display = $x;
        $x_display = $x_array[0] . ((int) $x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '');
        $x_display .= $x_parts[$x_count_parts - 1];
        return $x_display;
    }
    return $num;
}
function Time_To_Milliseconds($str)
{

    $time = explode(":", $str);

    $hour = (int) $time[0] * 60 * 60 * 1000;
    $minute = (int) $time[1] * 60 * 1000;
    $sec = (int) $time[2] * 1000;
    $result = $hour + $minute + $sec;
    return $result;
}

// User
function User_Data()
{
    if (Auth::guard('user')->user()) {
        return Auth::guard('user')->user();
    } else {
        return redirect()->route('user.logout');
    }
}

// Demo Mode
function Check_Admin_Access()
{
    if (env('DEMO_MODE') == 'ON') {
        return 0;
    } else {
        return 1;
    }
}
function Demo_Domain()
{
    $domain = request()->getHost();
    if ($domain == base64_decode('bG9jYWxob3N0') || $domain == base64_decode('ZHR0dWJlLmRpdmluZXRlY2hzLmlu') || $domain == base64_decode('ZHR0dWJlLmRpdmluZXRlY2hzLmNvbQ==')) {
        return 1;
    } else {
        return 0;
    }
}
