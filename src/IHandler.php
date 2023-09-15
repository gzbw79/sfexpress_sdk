<?php
/**
 * Write by BoWen 2023/9/15 下午1:46
 * Email: gzbw79@163.com
 *
 */

namespace Gzlbw\sfexpress;


interface IHandler
{
    /**
     * 触发请求处理
     * @param array $msgData
     * @param array $options
     * @return mixed
     */
    public function req(array $msgData, array $options = []);
}