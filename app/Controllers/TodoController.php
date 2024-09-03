<?php

namespace App\Controllers;

use App\Models\TodoModel;
use CodeIgniter\Controller;

class TodoController extends Controller
{
    protected $todoModel;

    public function __construct()
    {
        // 모델 로드
        $this->todoModel = new TodoModel();
    }

    public function index()
    {

        // 전체 Todo 조회
        $data['todos'] = $this->todoModel->getTodos();
//        if (empty($data['todos'])) {
//            log_message('debug', 'No todos found in the database.');
//        }

        // 데이터를 뷰에 전달하고 header, footer 포함하여 뷰 로드
        return view('templates/header')
            . view('pages/todo', $data)
            . view('templates/footer');
    }

    public function getTodos()
    {
        $todos = $this->todoModel->getTodos();
        //JSON으로 값만 반환
        return $this->response->setJSON($todos);
    }


    public function view($tdm_no)
    {
        // 특정 할 일 조회
        $todo = $this->todoModel->getTodoById($tdm_no);

        // 뷰로 데이터 전달
        return view('todo_detail', ['todo' => $todo]);
    }

    public function userTodos($user)
    {
        // 특정 유저의 할 일 목록 조회
        $todos = $this->todoModel->getTodosByUser($user);

        // 데이터 출력 (뷰에 전달하거나 JSON으로 반환)
        return $this->response->setJSON($todos);
    }


    public function add_todo()
    {
        // AJAX 요청인지 확인
        if ($this->request->isAJAX()) {

            // 폼 데이터 받아오기
            $tdm_title = $this->request->getPost('tdm_title');
            $tdm_detail = $this->request->getPost('tdm_detail');
            $tdm_urgent = $this->request->getPost('tdm_urgent');
            $tdm_user = "guest";
            $tdm_regdate = date('Y-m-d H:i:s');
            $tdm_stat = "0";


            // 유효성 검사
            if (empty($tdm_title)) {
                echo "할 일은 꼭 적어주어야 합니다.";
                return;
            }

            // 데이터베이스에 저장
            $result = $this->todoModel->insert_todo($tdm_title, $tdm_detail, $tdm_urgent, $tdm_user, $tdm_regdate, $tdm_stat);

            // response 반환 ( 1 == success )
            echo $result ? "1" : "ERROR:". $result['error'];

        } else {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Page not found');
        }
    }


    // todo 삭제, 실제 레코드를 DELETE 하지 않고 stat을 바꾸어 hide
    public function hide_todo()
    {
        // AJAX 요청인지 확인
        if ($this->request->isAJAX()) {

            // 폼 데이터 받아오기
            $tdm_no = $this->request->getPost('tdm_no');
            $new_moddate = date('Y-m-d H:i:s');
            $new_stat = "2";

            // 유효성 검사
            if (empty($tdm_no)) {
                echo "삭제할 투두 NO 지정해주세요.";
                return;
            }

            // 데이터베이스에 저장
            $result = $this->todoModel->hide_todo($tdm_no, $new_stat, $new_moddate);

            // response 반환 ( 1 == success )
            echo $result ? "1" : "ERROR:". $result['error'];

        } else {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Page not found');
        }
    }

    // todo 완료, 복구
    public function complete_todo()
    {
        if ($this->request->isAJAX()) {

            // 폼 데이터 받아오기
            $tdm_no = $this->request->getPost('tdm_no');
            $new_stat = $this->request->getPost('new_stat');
            $new_moddate = date('Y-m-d H:i:s');

            // 유효성 검사
            if (empty($tdm_no)) {
                echo "완료할 투두 NO 지정해주세요.";
                return;
            }

            // 데이터베이스에 저장
            $result = $this->todoModel->hide_todo($tdm_no, $new_stat, $new_moddate);

            // response 반환 ( 1 == success )
            echo $result ? "1" : "ERROR:". $result['error'];

        } else {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Page not found');
        }
    }

    // todo 수정
    public function update_todo()
    {
        if ($this->request->isAJAX()) {

            // 폼 데이터 받아오기
            $tdm_no = $this->request->getPost('tdm_no');
            $new_title = $this->request->getPost('new_title');
            $new_detail = $this->request->getPost('new_detail');
            $new_urgent = $this->request->getPost('new_urgent');
            $new_moddate = date('Y-m-d H:i:s');

            // 유효성 검사
            if (empty($tdm_no)) {
                echo "수정할 투두 NO 지정해주세요.";
                return;
            }

            // 데이터베이스에 저장
            $result = $this->todoModel->update_todo($tdm_no, $new_title, $new_detail, $new_urgent, $new_moddate);

            // response 반환 ( 1 == success )
            echo $result ? "1" : "ERROR:". $result['error'];
            
        } else {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Page not found');
        }
    }
}

