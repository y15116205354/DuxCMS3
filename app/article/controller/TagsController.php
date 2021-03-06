<?php

/**
 * Tag标签
 */
namespace app\article\controller;

class TagsController extends \app\base\controller\SiteController {

    protected $_middle = 'article/List';

    protected $urlParams = [];

    public function init() {
        $limit = request('get', 'limit');
        $tag = request('get', 'name');
        if(empty($tag)) {
            $this->error('标签不存在!');
        }
        $this->urlParams = [
            'tag' => $tag,
            'limit' => $limit,
        ];
    }

    public function index() {
        target($this->_middle, 'middle')->setParams($this->urlParams)->meta(urldecode($this->urlParams['tag']) .'相关文章', urldecode($this->urlParams['tag']), url('index', ['name' => $this->urlParams['tag']]))->data()->export(function ($data) {
            $this->assign($data);
            $this->assign('urlParams', $this->urlParams);
            $this->assign('page', $this->htmlPage($data['pageData']['raw'], $this->urlParams));
            $this->siteDisplay();
        }, function ($message, $code, $url) {
            $this->errorCallback($message, $code, $url);
        });
    }

    public function ajax() {
        target($this->_middle, 'middle')->setParams($this->urlParams)->data()->export(function ($data) {
            if(!empty($data['pageList'])) {
                $this->success([
                    'data' => $data['pageList'],
                    'page' => $data['pageData']['page'],
                ]);
            }else {
                $this->error('暂无数据');
            }

        }, function ($message, $code, $url) {
            $this->errorCallback($message, $code, $url);
        });
    }

}