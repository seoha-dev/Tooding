<?php

namespace App\Models;

use CodeIgniter\Model;

class TodoModel extends Model
{
    // TABLE
    protected $table = 'td_main';

    // PK
    protected $primaryKey = 'tdm_no';

    // 데이터베이스에 삽입할 수 있는 필드
    protected $allowedFields = [
        'tdm_user',
        'tdm_title',
        'tdm_moddate',
        'tdm_detail',
        'tdm_regdate',
        'tdm_urgent',
        'tdm_stat'
    ];

    // 유효성 검사 규칙
    protected $validationRules = [
        'tdm_user' => 'required|max_length[50]',
        'tdm_title' => 'required|max_length[50]',
        'tdm_detail' => 'permit_empty|max_length[1000]',
        'tdm_regdate' => 'required|valid_date',
        'tdm_urgent' => 'in_list[0,1]',
        'tdm_stat' => 'required|integer'
    ];

    // 유효성 검사 실패 시 사용되는 메시지
    protected $validationMessages = [
        'tdm_user' => [
            'required' => 'The user field is required.',
            'max_length' => 'The user field cannot exceed 50 characters.',
        ],
        'tdm_title' => [
            'required' => 'The title field is required.',
            'max_length' => 'The title field cannot exceed 50 characters.',
        ],
        'tdm_regdate' => [
            'required' => 'The registration date field is required.',
            'valid_date' => 'The registration date must be a valid date.',
        ],
        'tdm_urgent' => [
            'in_list' => 'The urgent field must be either 0 or 1.',
        ],
        'tdm_stat' => [
            'required' => 'The status field is required.',
            'integer' => 'The status field must be an integer.',
        ],
    ];

    // 타임스탬프 자동 처리 비활성화 (필요 시 활성화)
    protected $useTimestamps = false;

    /**
     * Insert a new todo item into the database.
     *
     * @param string $tdm_user
     * @param string $tdm_title
     * @param string|null $tdm_detail
     * @param string $tdm_regdate
     * @param int $tdm_urgent
     * @param int $tdm_stat
     * @return bool
     */
    public function insert_todo($tdm_title, $tdm_detail, $tdm_urgent, $tdm_user, $tdm_regdate, $tdm_stat)
    {
        $data = [
            'tdm_user' => $tdm_user,
            'tdm_title' => $tdm_title,
            'tdm_detail' => $tdm_detail,
            'tdm_regdate' => $tdm_regdate,
            'tdm_urgent' => $tdm_urgent,
            'tdm_stat' => $tdm_stat
        ];

        return $this->insert($data);
    }

    public function hide_todo($tdm_no, $new_stat, $new_moddate)
    {

        return $this->update($tdm_no, [
            'tdm_stat' => $new_stat,
            'tdm_moddate' => $new_moddate
        ]);
    }

    public function update_todo($tdm_no, $new_title, $new_detail, $new_urgent, $new_moddate)
    {

        return $this->update($tdm_no, [
            'tdm_title' => $new_title,
            'tdm_detail' => $new_detail,
            'tdm_urgent' => $new_urgent,
            'tdm_moddate' => $new_moddate
        ]);

    }


    // 할 일 목록을 조회하는 메서드 (urgent 순, 수정일자 순, 날짜 내림차 순)
    public function getTodos()
    {
        $query = $this->db->table($this->table)
            ->where('tdm_stat <', 2)
            ->orderBy('tdm_stat', 'ASC')
            ->orderBy('tdm_urgent', 'DESC')  // tdm_urgent를 내림차순으로 정렬
            ->orderBy('tdm_moddate', 'ASC')  // tdm_moddate를 오름차순으로 정렬
            ->orderBy('tdm_regdate', 'ASC')  // tdm_regdate를 오름차순으로 정렬
            ->get();  // 쿼리 실행

        // 결과를 배열로 반환
        return $query->getResultArray();
    }


    // 특정 할 일을 조회하는 메서드
    public function getTodoById($tdm_no)
    {
        return $this->find($tdm_no);
    }

    // 조건에 맞는 할 일을 조회하는 메서드
    public function getTodosByUser($tdm_user)
    {
        return $this->where('tdm_user', $tdm_user)->findAll();
    }


}
