const searchForm = document.getElementById('search')
const limit = document.getElementById('limit');
limit.addEventListener('change', (e)=>{
    searchForm.submit();
})

// 申請日
$('#datePicker').datetimepicker({
	locale: 'ja',
	dayViewHeaderFormat: 'YYYY年M月',
	format: 'YYYY/MM/DD',
});

$(document).ready(function() {
    $('#application').tablesorter({
        headers:{
            0: { sorter: false },
            3: { sorter: false },
            5: { sorter: false }
        }
    });

	// 「申請確認フォーム」に値を渡す
	$('#modal-check').on('show.bs.modal', function(event) {
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

		// 「申請編集フォーム」に値を渡す
	$('#modal-edit').on('show.bs.modal', function(event) {
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
		modal.find('.modal-body input#app-id-edit').val(data_id);
		modal.find('.modal-body input#app-name-edit').val(data_name);
		modal.find('.modal-body input#app-dept-edit').val(data_dept);
		modal.find('.modal-body input#app-dept-id-edit').val(data_dept_id);
		$('.modal-body select#app-type-edit').val(data_type);

		modal.find('.modal-body textarea#app-reason-edit').text(data_reason);
		modal.find('.modal-body input#app-date-edit').val(data_date);
		modal.find('.modal-body input#app-start-edit').val(data_start);
		modal.find('.modal-body input#app-end-edit').val(data_end);
	});

		// 「申請再利用フォーム」に値を渡す
	$('#modal-reuse').on('show.bs.modal', function(event) {
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
		modal.find('.modal-body input#app-id-reuse').val(data_id);
		modal.find('.modal-body input#app-name-reuse').val(data_name);
		modal.find('.modal-body input#app-dept-reuse').val(data_dept);
		modal.find('.modal-body input#app-dept-id-reuse').val(data_dept_id);
		$('.modal-body select#app-type-reuse').val(data_type);

		modal.find('.modal-body textarea#app-reason-reuse').text(data_reason);
		modal.find('.modal-body input#app-date-reuse').val(data_date);
		modal.find('.modal-body input#app-start-reuse').val(data_start);
		modal.find('.modal-body input#app-end-reuse').val(data_end);
	});

	// 時間入力必須化(時間外勤務)
    function overTimeRequire(){
        $('[name=start_time]').prop({'disabled': false, 'required': true});
        $('[name=end_time]').prop({'disabled': false, 'required': true});
        $('.startTimePicker').addClass('badge badge-danger ml-1');
        $('.startTimePicker').text('必須');
        $('.endTimePicker').addClass('badge badge-danger ml-1');
        $('.endTimePicker').text('必須');
    }

	// 時間入力必須化(打刻時間修正)
	function editTimeRequire(){
        $('[name=start_time]').prop({'disabled': false, 'required': true});
        $('[name=end_time]').prop({'disabled': false, 'required': false});
        $('.startTimePicker').addClass('badge badge-danger ml-1');
        $('.startTimePicker').text('必須');
		$('.endTimePicker').removeClass('badge badge-danger ml-1');
        $('.endTimePicker').text('');
    }

	// 時間入力無効化(休暇等)
	function timeDisable(){
		$('[name=start_time]').prop({'disabled': true, 'required': false});
		$('[name=end_time]').prop({'disabled': true, 'required': false});
		$('.startTimePicker').removeClass('badge badge-danger ml-1');
		$('.startTimePicker').text('');
		$('.endTimePicker').removeClass('badge badge-danger ml-1');
		$('.endTimePicker').text('');
	}

	    //開始時間、終了時間(初期設定)
		function iniTime(){
			$('#startTimePicker').datetimepicker({
				locale: 'ja',
				format: 'HH:mm',
				maxDate: moment({h:24}),
			});  
			$('#endTimePicker').datetimepicker({
				locale: 'ja',
				format: 'HH:mm',
				maxDate: moment({h:24}),
			});
		}
	
		// HTMLで直接設定したエラーメッセージを削除
		function DelErrMsg(){
			$('.startTimeErrorMsg').text('');
			$('.startTimeError').addClass('d-none');
			$('.endTimeErrorMsg').text('');
			$('.endTimeError').addClass('d-none');
		}	

	const appTypeSelect = document.getElementById('app-type-edit');

	appTypeSelect.addEventListener('change', (e)=>{

		//初期設定(DateTimePickerをTime型に)
        iniTime();

        // HTMLで直接設定したエラーメッセージを削除
        DelErrMsg();

		if (appTypeSelect.value == 4) {
			overTimeRequire();

			const left_time = $('#left_time').data();

			//開始時間、終了時間(終業時間より後のみ選択可)
            $('#startTimePicker').datetimepicker('minDate', moment({h:left_time.name.slice(0, 2), m:left_time.name.slice(3, 5)}));  
            $('#endTimePicker').datetimepicker('minDate', moment({h:left_time.name.slice(0, 2), m:left_time.name.slice(3, 5)}));

            //開始時間、終了時間のバリデーション
            let limitStartTime = left_time.name

            $('[name=start_time]').on('blur', function(e){
                // 始業時間と終業時間のエラーメッセージが空なら申請ボタンを押せないように
                if($('.endTimeErrorMsg').text('') && $('.startTimeErrorMsg').text('') || $('.endTimeErrorMsg').is(':empty') && $('.startTimeErrorMsg').is(':empty')){
                    $('#btn-update').prop("disabled", false); 
                }
                
                //開始時間が終業時間より早かったらエラーメッセージ
                if(e.target.value.replace(':', '') < left_time.name.replace(':', '')){
                    $('.startTimeError').removeClass('d-none');
                    $('.startTimeErrorMsg').text(`${limitStartTime}分以降を選択してください。`);
                    $('#btn-update').prop("disabled", true); 
                }else{
                    $('.startTimeErrorMsg').text('');
                    $('.startTimeError').addClass('d-none');
                }
                
                $('[name=end_time]').on('blur', function(val){
                    const endInputTime = val.target.value.replace(':', '');
                    
                    //終了時間が終業時間より早かったらエラーメッセージ
                    if(endInputTime <= left_time.name.replace(':', '')){
                        $('.endTimeError').removeClass('d-none');
                        $('.endTimeErrorMsg').text(`${limitStartTime}分以降を選択してください。`);
                        $('#btn-update').prop("disabled", true);
                    
                    //終了時間が開始時間より早かったらエラーメッセージ
                    }else if(endInputTime <= e.target.value.replace(':', '')){
                        $('.endTimeError').removeClass('d-none');
                        $('.endTimeErrorMsg').text(`開始時間よりも遅い時間を選択してください。`); 
                        $('#btn-update').prop("disabled", true); 
                    }else{
                        $('.endTimeErrorMsg').text('');
                        $('.endTimeError').addClass('d-none');
                    }
                    //開始時間が終業時間より早かったらエラーメッセージ
                    if(e.target.value.replace(':', '') < left_time.name.replace(':', '')){
                        $('.startTimeError').removeClass('d-none');
                        $('.startTimeErrorMsg').text(`${limitStartTime}分以降を選択してください。`);
                        $('#btn-update').prop("disabled", true);
                    }
                })
            })

		} else if (appTypeSelect.value == 5) {
			editTimeRequire();
		} else {
			timeDisable();
		}
	})

});