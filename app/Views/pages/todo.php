<?
// var_dump($todos);
// echo getenv('CI_ENVIRONMENT');
//exit;

?>
<section class="section-todo-top">
    <div class="section-inner text-center h-100 align-items-center m-auto p-3" id="todo_list">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <input type="text" class="form-control" placeholder="할 일 이름" id="tdm_title">
                </h5>
                <p class="card-text">
                    <textarea class="form-control" placeholder="추가 메모(선택)" id="tdm_detail"></textarea>
                </p>
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" role="switch" id="tdm_urgent">
                    <label class="form-check-label d-flex" for="tdm_urgent">중요표시</label>
                </div>
                <div class="btn-group w-100" role="group" aria-label="Basic outlined example">
                    <button type="button" class="btn btn-outline-primary" id="createTodo">저장</button>
                </div>
            </div>
        </div>

        <? if (!empty($todos) && is_array($todos)) {
            foreach ($todos as $todo) {
                $tdm_no = esc($todo['tdm_no']);
                $tdm_title = esc($todo['tdm_title']);
                $tdm_detail = esc($todo['tdm_detail']);
                $tdm_urgent = esc($todo['tdm_urgent']);
                $tdm_stat = esc($todo['tdm_stat']);

                $tdm_regdate = new DateTime($todo['tdm_regdate']);
                $tdm_moddate = isset($todo['tdm_moddate']) ? new \DateTime($todo['tdm_moddate']) : null;
                $tdm_regdate = $tdm_regdate->format('Y.m.d');
                $tdm_moddate = $tdm_moddate ? $tdm_moddate->format('Y.m.d') : '';
                ?>

                <div class="card <?= ($tdm_stat == 1) ? "card-done" : ""; ?>">
                    <div class="card-body">
                        <div class="card-head">
                            <i class="bi bi-bookmark-fill text-danger
                                <?= ($tdm_urgent > 0) ? "" : "invisible"; ?>"></i>
                            <i class="bi bi-x-lg card-cancel"></i>
                            <input type="hidden" class="tdm-no" value="<?= $tdm_no ?>">
                            <input type="hidden" class="tdm-stat" value="<?= $tdm_stat ?>">
                            <input type="hidden" class="tdm-title" value="<?= $tdm_title ?>">
                            <input type="hidden" class="tdm-detail" value="<?= $tdm_detail ?>">
                            <input type="hidden" class="tdm-urgent" value="<?= $tdm_urgent ?>">
                        </div>
                        <h5 class="card-title"><?= $tdm_title ?></h5>
                        <span class="card-subtitle text-secondary">
                            <?= $tdm_moddate ? "(수정일: $tdm_moddate)" : "(등록일: $tdm_regdate)"; ?>
                        </span>
                        <p class="card-text"><?= $tdm_detail ?></p>
                        <div class="btn-group w-100" role="group">
                            <button type="button" class="btn btn-outline-primary todo-update">수정</button>
                            <button type="button" class="btn btn-outline-primary todo-complete">
                                <?= ($tdm_stat == 0) ? "완료" : "복구"; ?>
                            </button>
                        </div>
                    </div>
                </div>
            <? }
        } else {
            echo "새로운 투두를 등록해보세요.";
        } ?>

    </div>


</section>


<style>

    .section-todo-top {
        width: 100%;
        min-height: 40em;
    }

    .section-todo-top .section-inner {
        width: 100%;
        max-width: 1240px;
        display: grid;
        grid-template-columns: repeat(4, 1fr);
    }

    .section-inner .card {
        margin: 1.5rem;
        width: 15rem;
        height: 15rem;
    }

    .card-text textarea {
        resize: none;
    }

    .card .card-head {
        display: flex;
        justify-content: space-between;
    }

    .card .card-cancel {
        color: #404040;
    }

    .card-done {
        background-color: #ededee;
        text-decoration: line-through;
    }

    .card-cancel {
        cursor: pointer;
    }

    .card-subtitle {
        font-size: .8rem;
    }

    .card-text {
        margin-top: 1rem;
        height: 4rem;
    }


</style>
<script>

    // 동적 요소기 띠문에 요소에 이벤트 달지 않음
    $(document).on('click', '.card-cancel', function () {
        let tdm_no = $(this).siblings('.tdm-no').val();
        // console.log(tdm_no);

        $.ajax({
            url: "todo/hide",
            method: "POST",
            data: {
                tdm_no
            },
            success: (response) => {
                response == 1
                    ? loadTodos()
                    : alert("에러가 발생했습니다. 다시 시도해주세요.\n" + response);
            },
            error: (request, status, error, response) => {
                console.log("ajax error!");
                console.log(
                    `status: ${request.status},
                message: ${request.responseText},
                error: ${error},
                response: ${response}`
                );
            },
        });
    });

    $(document).on('click', '#createTodo', function () {
        let tdm_title = $('#tdm_title').val();
        let tdm_detail = $('#tdm_detail').val();
        let tdm_urgent = $('#tdm_urgent').is(':checked') ? 1 : 0;

        //console.log(tdm_title, tdm_detail, tdm_urgent);

        $.ajax({
            url: "todo/add",
            method: "POST",
            data: {
                tdm_title,
                tdm_detail,
                tdm_urgent
            },
            success: (response) => {
                response == 1
                    ? loadTodos()
                    : alert("에러가 발생했습니다. 다시 시도해주세요.\n" + response);
            },
            error: (request, status, error, response) => {
                console.log("ajax error!");
                console.log(
                    `status: ${request.status},
                message: ${request.responseText},
                error: ${error},
                response: ${response}`
                );
            },
        });
    });


    function loadTodos() {
        $.ajax({
            url: '/todo/reload',
            method: 'GET',
            dataType: 'json', // 응답 데이터 형식
            success: (data) => {
                // 요청이 성공
                let html = '';
                html += `<div class="card">
                    <div class="card-body">
                    <h5 class="card-title">
                    <input type="text" class="form-control" placeholder="할 일 이름" id="tdm_title">
                    </h5>
                <p class="card-text">
                    <textarea class="form-control" placeholder="추가 메모(선택)" id="tdm_detail"></textarea>
                </p>
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" role="switch" id="tdm_urgent">
                        <label class="form-check-label d-flex" for="tdm_urgent">중요표시</label>
                </div>
                <div class="btn-group w-100" role="group" aria-label="Basic outlined example">
                    <button type="button" class="btn btn-outline-primary" id="createTodo">저장</button>
                </div>
            </div>
            </div>`;

                $.each(data, function (index, todo) {
                    const cardClass = todo.tdm_stat >= 1 ? 'card-done' : '';
                    const urgentClass = todo.tdm_urgent > 0 ? '' : 'invisible';
                    const moddateText = todo.tdm_moddate ? `(수정일: ${formatDate(todo.tdm_moddate)})` : `(등록일: ${formatDate(todo.tdm_regdate)})`;
                    const completeText = todo.tdm_stat >= 1 ? '복구' : '완료';

                    html += `
                    <div class="card ${cardClass}">
                        <div class="card-body">
                            <div class="card-head">
                                <i class="bi bi-bookmark-fill text-danger ${urgentClass}"></i>
                                <i class="bi bi-x-lg card-cancel"></i>
                                <input type="hidden" class="tdm-no" value="${todo.tdm_no}">
                                <input type="hidden" class="tdm-stat" value="${todo.tdm_stat}">
                                <input type="hidden" class="tdm-title" value="${todo.tdm_title}">
                                <input type="hidden" class="tdm-detail" value="${todo.tdm_detail}">
                                <input type="hidden" class="tdm-urgent" value="${todo.tdm_urgent}">
                            </div>
                            <h5 class="card-title">${todo.tdm_title}</h5>
                            <span class="card-subtitle text-secondary">
                                ${moddateText}
                            </span>
                            <p class="card-text">${todo.tdm_detail}</p>
                            <div class="btn-group w-100" role="group">
                                <button type="button" class="btn btn-outline-primary todo-update">수정</button>
                                <button type="button" class="btn btn-outline-primary todo-complete">${completeText}</button>
                            </div>
                        </div>
                    </div>
                `;

                });
                $('#todo_list').html(html); // TODO 목록을 업데이트합니다.


            },
            error: (request, status, error, response) => {
                console.log("ajax error!");
                console.log(
                    `status: ${request.status},
                message: ${request.responseText},
                error: ${error},
                response: ${response}`
                );
            },
        });
    }

    function formatDate(dateString) {
        if (!dateString) return '';

        const date = new Date(dateString);

        // 날짜 포맷을 YYYY.MM.DD로 설정
        return date.toLocaleDateString('ko-KR').replace(/\./g, '.');
    }

    $(document).on('click', '.todo-complete', function () {

        let tdm_no = $(this).closest('.card').find('.tdm-no').val();
        let tdm_stat = $(this).closest('.card').find('.tdm-stat').val();
        let new_stat = (tdm_stat >= 1) ? 0 : 1;

        //console.log(tdm_stat, new_stat);

        $.ajax({
            url: "todo/complete",
            method: "POST",
            data: {
                tdm_no,
                new_stat
            },
            success: (response) => {
                response == 1
                    ? loadTodos()
                    : alert("에러가 발생했습니다. 다시 시도해주세요.\n" + response);
            },
            error: (request, status, error, response) => {
                console.log("ajax error!");
                console.log(
                    `status: ${request.status},
                message: ${request.responseText},
                error: ${error},
                response: ${response}`
                );
            },
        });

    });


    $(document).on('click', '.todo-update', function () {

        let tdm_no = $(this).closest('.card').find('.tdm-no').val();
        let tdm_stat = $(this).closest('.card').find('.tdm-stat').val();
        let tdm_title = $(this).closest('.card').find('.tdm-title').val();
        let tdm_detail = $(this).closest('.card').find('.tdm-detail').val();
        let tdm_urgent = $(this).closest('.card').find('.tdm-urgent').val();

        const urgent_check = (tdm_urgent >= 1) ? "CHECKED" : "";

        const editForm = `<div class="card-body">
                <h5 class="card-title">
                    <input id="new_title" type="text" class="form-control" placeholder="할 일 이름" value="${tdm_title}">
                </h5>
                <p class="card-text">
                    <textarea id="new_detail" class="form-control" placeholder="추가 메모(선택)">${tdm_detail}</textarea>
                </p>
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" role="switch" id="new_urgent" ${urgent_check}>
                    <label class="form-check-label d-flex" for="new_urgent">중요표시</label>
                </div>
            </div>
`;

        //console.log(tdm_stat, new_stat);
        bootbox.dialog({
            title: '선택한 Todo를 수정합니다.',
            centerVertical: true,
            message: editForm,
            buttons: {
                update: {
                    label: '수정',
                    ClassName: 'btn btn-primary w-100',
                    callback: function () {

                        let new_title = $('#new_title').val();
                        let new_detail = $('#new_detail').val();
                        let new_urgent = $('#new_urgent').is(':checked') ? 1 : 0;
                        //console.log(new_title, new_detail, new_urgent);
                        $.ajax({
                            url: "todo/update",
                            method: "POST",
                            data: {
                                tdm_no,
                                new_title,
                                new_detail,
                                new_urgent
                            },
                            success: (response) => {
                                response == 1
                                    ? loadTodos()
                                    : alert("에러가 발생했습니다. 다시 시도해주세요.\n" + response);
                            },
                            error: (request, status, error, response) => {
                                console.log("ajax error!");
                                console.log(
                                    `status: ${request.status},
                message: ${request.responseText},
                error: ${error},
                response: ${response}`
                                );
                            },
                        });


                    }
                },
            }
        });

    });


</script>