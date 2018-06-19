<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/1/23
 * Time: 11:43
 */

namespace jeemoo\rbac\common;

use Yii;

class HtmlUtils
{
    public static function a($label, $route, $params = [], $props = [], $data = [])
    {
        if (empty($route)) {
            return null;
        }

        if (!Yii::$app->user->can($route)) {
            return null;
        }

        $dataStr = '';
        foreach ($data as $prop => $value) {
            if (is_object($value)) {
                $value = json_encode($value);
            }
            $dataStr .= "data-{$prop}='{$value}' ";
        }

        $propStr = '';
        foreach ($props as $key => $val) {
            $propStr .= "{$key}='{$val}' ";
        }

        $paramStr = '';
        foreach ($params as $key => $val) {
            $paramStr .= "{$key}={$val}&";
        }
        if (!empty($paramStr)) {
            $paramStr = '?' . rtrim($paramStr, '&');
        }

        return "<a href='/{$route}{$paramStr}' {$propStr} {$dataStr}>{$label}</a>\n";
    }
}