const searchForm = document.getElementById('search')
const limit = document.getElementById('limit');
limit.addEventListener('change', (e)=>{
    searchForm.submit();
})

// 申請したい日
$('#datePicker').datetimepicker({
	locale: 'ja',
	dayViewHeaderFormat: 'YYYY年M月',
	format: 'YYYY/MM/DD',
});

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

$(document).ready(function() {
    $('#application').tablesorter({
        headers:{
            0: { sorter: false },
            3: { sorter: false },
            5: { sorter: false }
        }
    });

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

});