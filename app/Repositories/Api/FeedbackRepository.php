<?php

namespace App\Repositories\Api;


use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

class FeedbackRepository extends BaseRepository
{
    public function model()
    {
        return 'App\Models\Common\Feedback';
    }

    /**
     * 自动回复
     * @param array $params
     * @return string
     */
    public function reply($params = [])
    {
        $word = $params['word'] ?? '';
        //去除空格
        $oldchar=array(" ","　","\t","\n","\r");
        $newchar=array("","","","","");
        $word = str_replace($oldchar,$newchar,$word);
        //表全名
        $table = DB::getConfig('prefix').$this->model->getTable();

        $reply = DB::select("select * from ".$table." where locate(key_word,'".$word."')>0 limit 1");
        $reply = isset($reply[0]) ? $reply[0] : [];
        //如果匹配不到，则调用默认的自动回复
        if(empty($reply)){
            $reply = $this->model->where('is_default',1)->first();
        }

        $content = isset($reply->content) ? $reply->content : '您好，请联系管理员';

        return $content;
    }

}
