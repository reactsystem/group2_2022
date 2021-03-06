$(document).ready(function ()
{
	/* add class -----------------------------------------------*/
	$('th').addClass('align-middle');
	$('td').addClass('align-middle');

	$('.card').addClass('mb-3');
	/*==========================================================*/

	/* ScrollSpy -----------------------------------------------*/
	// bodyにScrollSpyの属性を追加
	$('body').attr(
	{
		'data-bs-spy': 'scroll',
		'data-bs-target': '#scroll-menu',
		'data-bs-offset': '100',
	});

	// ScrollSpyの実行
	$('body').scrollspy({target:'#scroll-menu'});
	/*==========================================================*/

	/* TimePicker ----------------------------------------------*/
	// 始業時間
	$('#start-time-picker').datetimepicker(
	{
		locale: 'ja',
		format: 'HH:mm',
		maxDate: moment({h:24}),
	});

	// 終業時間
	$('#left-time-picker').datetimepicker(
	{
		locale: 'ja',
		format: 'HH:mm',
		maxDate: moment({h:24}),
	});

	// 休憩時間
	$('#rest-time-picker').datetimepicker(
	{
		locale: 'ja',
		format: 'HH:mm',
		maxDate: moment({h:24}),
	});
	/*==========================================================*/

	/* モーダル自動入力 ----------------------------------------*/
	// 就業時間編集フォーム
	$('#modal-edit-fixed').on('show.bs.modal', function(event)
	{
		var data = $(event.relatedTarget);
		var data_id = data.data('id');
		var data_start = data.data('start');
		var data_left = data.data('left');
		var data_rest = data.data('rest');

		var modal = $(this);
		modal.find('.modal-body input#fixed-id').val(data_id);
		modal.find('.modal-body input#fixed-start').val(data_start);
		modal.find('.modal-body input#fixed-left').val(data_left);
		modal.find('.modal-body input#fixed-rest').val(data_rest);
	});

	// 申請項目編集フォーム
	$('#modal-edit-app').on('show.bs.modal', function(event)
	{
		var data = $(event.relatedTarget);
		var data_id = data.data('id');
		var data_name = data.data('name');
		var data_work_type = data.data('work-type');

		var modal = $(this);
		modal.find('.modal-body input#edit-app-id').val(data_id);
		modal.find('.modal-body input#edit-app-name').val(data_name);
		$('.modal-body select#select-work-type').val(data_work_type);
	});

	// 追加フォーム
	$('#modal-add').on('show.bs.modal', function(event)
	{
		var data = $(event.relatedTarget);
		var data_title = data.data('title');
		var data_label = data.data('label');
		var data_table = data.data('table');

		var modal = $(this);
		// Title
		modal.find('.modal-title').text(data_title + '追加');
		// Name
		modal.find('.modal-body label#add-label').text(data_label);
		modal.find('.modal-body input#add-table').val(data_table);

		// TODO : autofocusしたい
		$('.modal-body input#add-name').focus();
	});

	// 編集フォーム
	$('#modal-edit').on('show.bs.modal', function(event)
	{
		var data = $(event.relatedTarget);
		var data_title = data.data('title');
		var data_label = data.data('label');
		var data_table = data.data('table');
		var data_id = data.data('id');
		var data_name = data.data('name');

		var modal = $(this);
		// Title
		modal.find('.modal-title').text(data_title + '編集');
		// Name
		modal.find('.modal-body label#edit-label').text(data_label);
		modal.find('.modal-body input#edit-table').val(data_table);
		modal.find('.modal-body input#edit-id').val(data_id);
		modal.find('.modal-body input#edit-name').val(data_name);
	});

	// 削除フォーム
	$('#modal-del').on('show.bs.modal', function(event)
	{
		var data = $(event.relatedTarget);
		var data_title = data.data('title');
		var data_label = data.data('label');
		var data_table = data.data('table');
		var data_id = data.data('id');
		var data_name = data.data('name');

		var modal = $(this);
		// Title
		modal.find('.modal-title').text(data_title + '削除');
		// Message
		modal.find('.modal-body label#del-label').text(data_label + '『' + data_name + '』を削除しますか？');
		modal.find('.modal-body input#del-table').val(data_table);
		modal.find('.modal-body input#del-id').val(data_id);
	});
	/*==========================================================*/


	/* 就業時間の入力を保持 ------------------------------------*/
	// 始業時間
	if($('.modal-body input#fixed-start').val()){
		const start = $('.modal-body input#fixed-start').data();
		$('.modal-body input#fixed-start').val(start);
	}

	// 終業時間
	if($('.modal-body input#fixed-start').val()){
		const start = $('.modal-body input#fixed-start').data();
		$('.modal-body input#fixed-start').val(start);
	}

	// 休憩時間
	if($('.modal-body input#fixed-start').val()){
		const start = $('.modal-body input#fixed-start').data();
		$('.modal-body input#fixed-start').val(start);
	}
	/*==========================================================*/

	/* 文字数制限のバリデーション ------------------------------*/
	$('.modal input[name="name"]').change(function()
	{
        $('.nameError').addClass('d-none');
	});

	$('.modal form').submit(function()
	{
		if ($('.modal input#add-name').val().length > 20 ||
			$('.modal input#edit-name').val().length > 20 ||
			$('.modal input#add-app-name').val().length > 20 ||
			$('.modal input#edit-app-name').val().length > 20)
		{
			$('.nameError').removeClass('d-none');
			$('.nameErrorMsg').text('20文字以下で入力してください。');
			return false;
		}
	});
	/*==========================================================*/
});