<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        // 데이터 배열을 생성하여 뷰에 전달
        $data = [
            'title' => 'Tooding :: Main', // 페이지 제목 설정
        ];

        // 'templates/header' 뷰와 'home' 뷰, 'templates/footer' 뷰를 렌더링
        return view('templates/header', $data)
            . view('pages/index', $data) // 'index' 뷰 파일을 사용합니다
            . view('templates/footer');
    }
}
