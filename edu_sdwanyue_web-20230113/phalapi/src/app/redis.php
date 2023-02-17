<?php

// +----------------------------------------------------------------------
// | Created by Wanyue
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2019 http://www.sdwanyue.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: https://gitee.com/WanYueKeJi
// +----------------------------------------------------------------------
// | Date: 2020/09/08 18:22
// +----------------------------------------------------------------------
namespace App;

/* redis */
/* redis链接 */
function connectionRedis(){
    if(!isset($GLOBALS['redisdb']) || !$GLOBALS['redisdb']){
        $REDIS_HOST= \PhalApi\DI()->config->get('app.REDIS_HOST');
        $REDIS_AUTH= \PhalApi\DI()->config->get('app.REDIS_AUTH');
        $REDIS_PORT= \PhalApi\DI()->config->get('app.REDIS_PORT');
        $REDIS_SELECT= \PhalApi\DI()->config->get('app.REDIS_SELECT');
        
        $redis = new \Redis();
        $redis -> connect($REDIS_HOST,$REDIS_PORT);
        $redis -> auth($REDIS_AUTH);
        $redis -> select($REDIS_SELECT);
        
        $GLOBALS['redisdb']=$redis;
    }
}

/**
 * redis 字符串（String） 类型
 * 将key和value对应。如果key已经存在了，它会被覆盖，而不管它是什么类型。
 * @param $key
 * @param $info
 * @param $exp 过期时间
 */
function setcaches($key,$info,$time=0){
    $GLOBALS['redisdb']->set($key,json_encode($info));
    if($time > 0){
        $GLOBALS['redisdb']->expire($key, $time); 
    }
    return 1;
}
	
/**
 * redis 字符串（String） 类型
 * 返回key的value。如果key不存在，返回特殊值nil。如果key的value不是string，就返回错误，因为GET只处理string类型的values。
 * @param $key
 */
function getcaches($key){
    $isexist=$GLOBALS['redisdb']->Get($key);
    return json_decode($isexist,true);
}

/**
 * 删除一个或多个key
 * @param $keys  数组/ 数组以逗号拼接的string
 */
function delcache($key){
    $isexist=$GLOBALS['redisdb']->del($key);
    return 1;
}

/**
 * redis 哈希表(hash)类型
 * 返回哈希表 $key 中，所有的域和值。
 * @param $key
 *
 */
function hGetAll($key){
    return $GLOBALS['redisdb']->hGetAll($key);
}

/**
 * 添加一个VALUE到HASH中。如果VALUE已经存在于HASH中，则返回FALSE。
 * @param string $key
 * @param string $hashKey
 * @param string $value
 */
function hSet( $key, $hashKey, $value ) {
    return $GLOBALS['redisdb']->hSet($key, $hashKey, $value);
}

/**
 * redis 哈希表(hash)类型 
 * 批量填充HASH表。不是字符串类型的VALUE，自动转换成字符串类型。使用标准的值。NULL值将被储存为一个空的字符串。
 * 可以批量添加更新 value,key 不存在将创建，存在则更新值
 * @param  $key
 * @param  $fieldArr  要设置的键对值
 * @return
 * 当key不是哈希表(hash)类型时，返回一个错误。
 */
function hMSet($key,$fieldArr){
    
    return $GLOBALS['redisdb']->hmset($key,$fieldArr);
}

/**
 * 取得HASH中的VALUE，如何HASH不存在，或者KEY不存在返回FLASE。
 * @param   string  $key
 * @param   string  $hashKey
 * @return  string  The value, if the command executed successfully BOOL FALSE in case of failure
 */
function hGet($key, $hashKey) {
    return $GLOBALS['redisdb']->hGet($key,$hashKey);
}

/**
 * 批量取得HASH中的VALUE，如何hashKey不存在，或者KEY不存在返回FLASE。
 * @param string $key
 * @param array $hashKey 
 */
function hMGet( $key, $hashKeys ) {
    return $GLOBALS['redisdb']->hMGet($key,$hashKeys);
}

/**
 * 根据HASH表的KEY，为KEY对应的VALUE自增参数VALUE。浮点型 
 * 推荐使用 hIncrByFloat  不推荐使用 hIncrBy(整型)
 * 先用 hIncrByFloat 再使用  hIncrBy  自增无效
 * @param string $key
 * @param string $hashKey
 * @param value  自增值  整型/小数
 */
function hIncrByFloat( $key, $hashKey, $value){
    return $GLOBALS['redisdb']->hIncrByFloat( $key, $hashKey, $value);
}

/**
 *  删除哈希表key中的一个指定域，不存在的域将被忽略。
 * @param string $key
 * @param string $hashKey
 */
function hDel($key,$hashKey){
    return $GLOBALS['redisdb']->hDel( $key, $hashKey);
}



/**
 * 添加一个字符串值到LIST容器的顶部（左侧），如果KEY不存在，曾创建一个LIST容器，如果KEY存在并且不是一个LIST容器，那么返回FLASE。
 * @param string $key
 * @param string $val
 */
function lPush($key,$val){
    return	$GLOBALS['redisdb']->lPush($key,$val);
}

/**
 * 添加一个字符串值到LIST容器的底部（右侧），如果KEY不存在，曾创建一个LIST容器，如果KEY存在并且不是一个LIST容器，那么返回FLASE。
 * 
 * @param string $key
 * @param string $val
 */
function rPush($key,$val){
    return	$GLOBALS['redisdb']->rPush($key,$val);
}
/**
 * 返回LIST顶部（左侧）的VALUE，并且从LIST中把该VALUE弹出。
 * @param string $key
 */
function lPop($key){
    return $GLOBALS['redisdb']->lPop($key);
}
/**
 * 返回LIST底部（右侧）的VALUE，并且从LIST中把该VALUE弹出。
 * @param string $key
 */
function rPop($key){
    return $GLOBALS['redisdb']->rPop($key);
}

/**
 * 移除指定数量的value  0移除全部 正整数 从左侧开始移除，负数从右侧移除
 * @param string $key
 */
function lRem($key,$value,$count=0){
    return $GLOBALS['redisdb']->lRem($key,$value,$count);
}

/**
 * 获取索引范围内的数据
 * @param string $key
 */
function lRange($key,$start,$end){
    return $GLOBALS['redisdb']->lRange($key,$start,$end);
}


/*
 * 构建一个集合(有序集合)  可排序
 * @param  string $key 集合名称
 * @param  string $value1  值
 * @param  double $score1  值
 * return 被成功添加的新成员的数量，不包括那些被更新的、已经存在的成员。
 */
function zAdd($key,$score1,$value1){
    return $GLOBALS['redisdb']->zAdd($key,$score1,$value1);
}

/**
 * 返回key对应的有序集合中member的score值。如果member在有序集合中不存在，那么将会返回nil。
 * @param   string  $key
 * @param   string  $member
 * @return  float
 */
function zScore( $key, $member ) {
    return $GLOBALS['redisdb']->zScore( $key, $member );
}

/**
 * 返回key对应的有序集合中介于min和max间的元素的个数。
 * @param   string  $key
 * @param   string  $start
 * @param   string  $end
 * @return  float
 */
function zCount( $key, $start, $end ) {
    return $GLOBALS['redisdb']->zCount( $key, $start, $end );
}

/**
 * 返回存储在key对应的有序集合中的元素的个数。
 * @param   string  $key
 * @return  int     the set's cardinality

 */
function zCard($key){
    return $GLOBALS['redisdb']->zCard($key);
}

/**
 * 将key对应的有序集合中member元素的scroe加上 value     value可以是负值
 * @param   string  $key
 * @param   float   $value (double) value that will be added to the member's score
 * @param   string  $member
 * @return  float   the new value
 * @example
 * <pre>
 * $redis->delete('key');
 * $redis->zIncrBy('key', 2.5, 'member1');  // key or member1 didn't exist, so member1's score is to 0
 *                                          // before the increment and now has the value 2.5
 * $redis->zIncrBy('key', 1, 'member1');    // 3.5
 * </pre>
 * member 成员的新 score 值，以字符串形式表示。
 */
function zIncrBy( $key, $value, $member ) {
    
    return $GLOBALS['redisdb']->zIncrBy( $key, $value, $member );
}

/**
 * 取得特定范围内的排序元素,0代表第一个元素,1代表第二个以此类推。-1代表最后一个,-2代表倒数第二个...
 * @param   string  $key
 * @param   int     $start
 * @param   int     $end
 * @param   bool    $withscores 
 * @return  array   Array containing the values in specified range.
 * @example
 * <pre>
 * $redis->zAdd('key1', 0, 'val0');
 * $redis->zAdd('key1', 2, 'val2');
 * $redis->zAdd('key1', 10, 'val10');
 * $redis->zRange('key1', 0, -1); // array('val0', 'val2', 'val10')
 * // with scores
 * $redis->zRange('key1', 0, -1, true); // array('val0' => 0, 'val2' => 2, 'val10' => 10)
 * </pre>
 * 指定区间内，带有 score 值(可选)的有序集成员的列表。
 * zRange 根据 score 正序   zRevRange 倒序
 */
function zRange( $key, $start, $end, $withscores = null ) {
    
    return $GLOBALS['redisdb']->zRange( $key, $start, $end, $withscores) ;
}
function zRevRange( $key, $start, $end, $withscores = null ) {

    return $GLOBALS['redisdb']->zRevRange( $key, $start, $end, $withscores) ;
}

/**
 * 从有序集合中删除指定的成员。
 * @param   string  $key
 * @param   string  $member1
 * @return  int     Number of deleted values
 */
function zRem( $key, $member1 ) {
    
    return $GLOBALS['redisdb']->zRem( $key, $member1 );
}