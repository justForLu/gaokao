<?php

namespace App\Enums;

use BenSampo\Enum\Enum;
use ReflectionClass;

/**
 * @method static BaseEnum ENUM()
 */
class BaseEnum extends Enum {

    /**
     * Returns the description of this enum
     *
     * @param $key
     * @return null
     * @throws \ReflectionException
     */
    public static function getEnumDesc($key){
        $reflection = new ReflectionClass(get_called_class());

        if($reflection->hasProperty('desc')){
            $desc  = $reflection->getStaticPropertyValue ('desc');
            return $desc[$key];
        }else{
            return null;
        }

    }

    /**
     * 返回枚举类型数组
     * @return array
     * @throws \ReflectionException
     */
    public static function getEnumArr()
    {
        $enumItems = self::asSelectArray();

        $reflection = new ReflectionClass(get_called_class());
        $reflection->hasProperty('desc');
        $descArr  = $reflection->getStaticPropertyValue ('desc');

        $enumArr = [];
        if($enumItems && $descArr){
            foreach ($enumItems as $key => $item){
                $desc = isset($descArr[strtoupper($item)]) ? $descArr[strtoupper($item)] : '未知';
                $enumArr[] = [
                    'value' => $key,
                    'desc' => $desc
                ];
            }
        }

        return $enumArr;
    }

    /**
     * 获取枚举类描述
     * @param $value
     * @return mixed
     * @throws \ReflectionException
     */
    public static function getDesc($value)
    {
        $enumArr = self::getEnumArr();

        if($enumArr){
            foreach ($enumArr as $key => $item){
                if($item['value'] == $value){
                    return $item['desc'];
                    break;
                }
            }
        }

    }

    /**
     * 获取枚举下拉选框
     * @param $select
     * @param null $default
     * @param null $name
     * @param null $lay_filter
     * @param array $except
     * @throws \ReflectionException
     */
    public static function enumSelect($select,$default=null,$name=null,$lay_filter = null,$except=array())
    {
        if($lay_filter){
            $template = '<select name="'.$name.'" lay-filter="'.$lay_filter.'">';
        }else{
            $template = '<select name="'.$name.'">';
        }

        //设置多选默认项
        if($default){
            $template .= '<option value="">'.$default.'</option>';
        }

        $enumArr = self::getEnumArr();

        if($enumArr){
            foreach($enumArr as $key => $val){
                if(!in_array($val['value'], $except)){
                    if($select == $val['value'] && $select != null){
                        $template .= '<option selected="selected" value="'.$val['value'].'">'.$val['desc'].'</option>';
                    }else{
                        $template .= '<option value="'.$val['value'].'">'.$val['desc'].'</option>';
                    }
                }
            }
        }

        $template .= '</select>';

        echo $template;
    }

    /**
     * 读取枚举数组
     * @return array
     * @throws \ReflectionException
     */
    public static function enumItems()
    {
        $enumArr = array();

        $enumItems = self::getInstances();

        if($enumItems) {
            foreach ($enumItems as $key => $val) {
                $enumArr[] = array(
                    'value' => $val->getRandomValue(),
                    'desc' => self::getEnumDesc($key)
                );
            }
        }

        return $enumArr;
    }

    /**
     * 获取枚举单选框
     * @param $select
     * @param null $name
     * @param array $except
     * @throws \ReflectionException
     */
    public static function enumRadio($select,$name=null,$except=array())
    {
        $template = '';

        $enumArr = self::getEnumArr();

        if($enumArr){
            foreach($enumArr as $key=>$val){
                if(!in_array($val['value'], $except)){
                    if($select == $val['value'] && $select !== null){
                        $template .= '<input name="'.$name.'" type="radio" value="'.$val['value'].'" checked title="'.$val['desc'].'">';
                    }else{
                        $template .= '<input name="'.$name.'" type="radio" value="'.$val['value'].'" title="'.$val['desc'].'">';
                    }

                }
            }
        }
        echo $template;
    }
}
