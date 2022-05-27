const searchForm = document.getElementById('search')
const limit = document.getElementById('limit');
limit.addEventListener('change', (e)=>{
    searchForm.submit();
})

$(document).ready(function() {
    $('#application').tablesorter({
        headers:{
            0: { sorter: false },
            3: { sorter: false },
            4: { sorter: false }
        }
    });
});

$(document).ready(function() {
	$('#modal-application').on('show.bs.modal', function(event) {
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