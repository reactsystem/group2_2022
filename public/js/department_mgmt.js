$(document).ready(function () {
	// DatePicker
	$('#date-picker').datetimepicker(
	{
		locale: 'ja',
		dayViewHeaderFormat: 'YYYY年M月',
		format: 'YYYY/MM/DD'
	});

	// 日付変更時にフォームを送信する
	$('#date-picker').on('change.datetimepicker', function()
	{
		$('#form-cond').submit();
	});

	// modalのformが送信されたらmodalを非表示にする
	$('#form-export').submit(function()
	{
		$('.modal-backdrop').remove();
		$('#modal-export').hide();
	});
});