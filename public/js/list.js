const searchForm = document.getElementById('search')
const department = document.getElementById('department');
const limit = document.getElementById('limit');
const keyword = document.getElementById('search-input');

//検索ボタンの✕ボタンをクリックすると、検索クリア
if(keyword){
	keyword.addEventListener('search', ()=>{
	
		let url = new URL(window.location.href);
	
		url.searchParams.delete('keyword');
		location.href = url;
	});
}

//部署名変更時にget送信
department.addEventListener('change', ()=>{
    searchForm.submit();
})

//表示件数変更時にget送信
limit.addEventListener('change', ()=>{
    searchForm.submit();
})

//テーブルの並び替え設定
// $(document).ready(function() {
//     $('#employees').tablesorter({
//         headers:{
//             0: { sorter: false },
//             3: { sorter: false },
//             4: { sorter: false }
//         }
//     });
// });

// $(document).ready(function() {
//     $('#application').tablesorter({
//         headers:{
//             0: { sorter: false },
//             2: { sorter: false },
//             4: { sorter: false }
//         }
//     });
// });


$(document).ready(function() {
	$('#modal-approval').on('show.bs.modal', function(event){
		var data = $(event.relatedTarget);
		var data_id = data.data('id');
		var data_name = data.data('name');
		var data_dept = data.data('dept');
		var data_dept_id = data.data('dept_id');
		var data_type = data.data('type');
		var data_reason = data.data('reason');
		var data_date = data.data('date');
		var data_start = data.data('start');
		var data_end = data.data('end');

		var modal = $(this);
		modal.find('.modal-body input#app-id').val(data_id);
		modal.find('.modal-body input#app-name').val(data_name);
		modal.find('.modal-body input#app-dept').val(data_dept);
		modal.find('.modal-body input#app-dept-id').val(data_dept_id);
		modal.find('.modal-body input#app-type').val(data_type);
		modal.find('.modal-body textarea#app-reason').text(data_reason);
		modal.find('.modal-body input#app-date').val(data_date);
		modal.find('.modal-body input#app-start').val(data_start);
		modal.find('.modal-body input#app-end').val(data_end);
	});
});

// コメントが６０文字を超えた場合にエラーメッセージを表示する
function checkComment() {
	const comment = document.getElementById('comment').value;

	// コメントが６０文字を超えた場合
	if(comment.length > 60)
	{
		// error message
		$('.commentError').removeClass('d-none');
		$('.commentErrorMsg').text('コメントは６０文字以内で入力してください。');

	} else {
		$('#approval').submit();
	}
}

$('#btn-approve').click(function() {
	$('#value-approve').prop('disabled', false);
	checkComment();
});
$('#btn-reject').click(function() {
	$('#value-reject').prop('disabled', false);
	checkComment();
});
$('#btn-stop').click(function() {
	$('#value-stop').prop('disabled', false);
	checkComment();
});