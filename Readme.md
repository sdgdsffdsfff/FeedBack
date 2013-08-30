背景：

目前逸香各个项目用户反馈信息比较分散，基本上每个应用都单独存储，这样不利于统一管理查看，鉴于此Feedback项目产生。

满足需求：

主要需满足各平台对用户反馈及举报信息的统一管理功能；
反馈信息HTTP接口的形式接收各项目发送过来的反馈信息，存储在数据库中；
实现后台管理功能，按需导出反馈信息；

主要实现：

1.发送反馈信息接口 URL/index.php?act=sendFeedBack
提交方式：POST
提交参数：type={$type}&appid={$appid}&content={$content}&mid={$mid}&uuid={$uuid}&contact={$contact}&token={$token}&rand={$rand}
参数说明：type:    反馈类型,1 - 建议, 2 - 投诉
          appid:   应用ID, 由后台分配
          content: 内容
          mid:     对应的通行证用户ID, 没有可为空
          uuid:    设备号
          contact: 联系方式
          token:   验证字串(下面会介绍token的生成方法)
          rand:    生成验证字串所需的8位随机字符         
返回格式：JSON
返回信息：{"status":1, "message":"succeed"}

2.查看反馈信息接口 URL/index.php?act=getFeedBack
提交信息：POST
提交参数：page={$page}&limit={$limit}&type={$type}&appid={$app}&token={$token}&rand={$rand}
参数说明：type:    反馈类型,1 - 建议, 2 - 投诉
          appid:   应用ID
          page:    当前请求的页码数
          limit:   每页请求的数据条数
          token:   验证字串(下面会介绍token的生成方法)
          rand:    生成验证字串所需的8位随机字符
返回格式：JSON
返回信息：
    {
        "status": 1,
        "message": "succeed",
        "result": { ...}
    }

token生成规则：
由于接口不是对外开放的，需对接口进行权限限制：
    1.首先服务器为每个客户端分配appid和secret；
    2.客户端生成8位随机码rand；
    3.客户端将appid、token和rand转换成字符或字符串类型，然后再按字典排序，然后进行sha1加密，获取40位消息摘要token；
    4.将token和rand附加在提交参数中一起提交，服务器会验证token的有效性，
    例：
        $appid = '1';
        $secret = 'kdiJEmwe984jFijekdf9EE354';
        $rand = {8位随机字符串};
        $arr = array('appid' => $appid, 'secret' => $secret, 'rand' => $rand);
        asort($arr);
        $token = sha1(implode('', $arr));

更新日期:201.08.30
