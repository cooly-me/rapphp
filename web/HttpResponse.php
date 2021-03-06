<?php
/**
 * 南京灵衍信息科技有限公司
 * User: jinghao@duohuo.net
 * Date: 17/8/31
 * Time: 下午9:34
 */

namespace rap\web;


class HttpResponse{

    // 当前的contentType
    protected $contentType = 'text/html';

    // 字符集
    protected $charset = 'utf-8';

    //状态
    protected $code = 200;

    private $content;

    private $data=[];
    // header参数
    protected $header = [];

    public function send(){
        if (!headers_sent() && !empty($this->header)) {
            // 发送状态码
            http_response_code($this->code);
            // 发送头部信息
            foreach ($this->header as $name => $val) {
                header($name . ':' . $val);
            }
        }
        $this->header['Content-Type'] = $this->contentType . '; charset=' . $this->charset;
        echo $this->content;
        if (function_exists('fastcgi_finish_request')) {
            // 提高页面响应
            fastcgi_finish_request();
        }
    }

    public function setContent($content){
        $this->content=$content;
    }


    /**
     * 获取头部信息
     * @param string $name 头部名称
     * @return mixed
     */
    public function getHeader($name = '')
    {
        return !empty($name) ? $this->header[$name] : $this->header;
    }

    /**
     * 页面输出类型
     * @param string $contentType 输出类型
     * @param string $charset     输出编码
     * @return $this
     */
    public function contentType($contentType, $charset = 'utf-8')
    {

        $this->contentType=$contentType;
        $this->charset=$charset;
        return $this;
    }


    /**
     * 设置响应头
     * @access public
     * @param string|array $name  参数名
     * @param string       $value 参数值
     * @return $this
     */
    public function header($name, $value = null)
    {
        if (is_array($name)) {
            $this->header = array_merge($this->header, $name);
        } else {
            $this->header[$name] = $value;
        }
        return $this;
    }

    /**
     * 发送HTTP状态
     * @param integer $code 状态码
     * @return $this
     */
    public function code($code)
    {
        $this->code = $code;
        return $this;
    }

    public function assign($key,$value=null){
        if(is_array($key)){
          $this->data=array_merge($this->data,$key);
        }else{
            $this->data[$key]=$value;
        }
    }


    public function data($key=""){
        if($key){
            return $this->data[$key];
        }
        return $this->data;
    }


    /**
     * 重定向
     * @param $url
     * @param int $code
     */
    public function redirect($url,$code=200){
        http_response_code($code);
        header("location: $url");
        die;
    }
}