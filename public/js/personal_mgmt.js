// 月選択用のセレクトボックスを選択すると、submitする
function submit_form() {
	var target = document.getElementById("select");
	target.method = "get";
	target.submit();
}

// テーブルの特定のカラムの合計値を計算する関数
function calculate(cell_num, column_id) {
	var tableElem = document.getElementById('input_table');
	var rowElems = tableElem.rows;

	var result = "00:00";
	for (i = 1, len = rowElems.length - 2; i < len; i++) {
		if (rowElems[i].cells[cell_num].innerText == ""){
			continue;
		} else {
			var conprod = rowElems[i].cells[cell_num].innerText;

			prodhrdArr = result.split(":"); 
			conprodArr = conprod.split(":"); 
			var hh1 = parseInt(prodhrdArr[0]) + parseInt(conprodArr[0]); 
			var mm1 = parseInt(prodhrdArr[1]) + parseInt(conprodArr[1]); 

			if (mm1 > 59) { 
				var mm2 = mm1 % 60; 
				var mmx = mm1/60; 
				var mm3 = parseInt(mmx);
				var hh1 = parseInt(hh1) + parseInt(mm3); 
				var mm1 = mm2; 
			}
			hh1 = hh1.toString().padStart(2, '0');
			mm1 = mm1.toString().padStart(2, '0');

			var result = hh1 + ':' + mm1;
		}
	}
	document.getElementById(column_id).innerText = result;
}

// 休憩時間、労働時間、残業時間の合計値を上部のインフォメーションテーブルに表示する
window.addEventListener = calculate(4, 'rest_info');
window.addEventListener = calculate(5, 'worked_info');
window.addEventListener = calculate(6, 'over_info');

// 休憩時間、労働時間、残業時間の合計値をテーブル下部に表示する
window.addEventListener = calculate(4, 'rest');
window.addEventListener = calculate(5, 'worked');
window.addEventListener = calculate(6, 'over');

// 所定時間(既定の労働時間×平日の日数)を計算し、テーブル下部に表示する
window.addEventListener = function () {
	var num = document.querySelectorAll('#weekday').length;
	var result = fixed_work_time;
	Arr = result.split(":");
	var hh1 = parseInt(Arr[0])*num;
	var mm1 = parseInt(Arr[1])*num;

	var mm2 = mm1 % 60;
	var mmx = mm1/60;
	var mm3 = parseInt(mmx);
	var hh1 = parseInt(hh1) + parseInt(mm3); 
	var mm1 = mm2;

	mm1 = mm1.toString().padStart(2, '0');

	var result = hh1 + ':' + mm1;

	document.getElementById('weekday_sum').innerText = result;
	document.getElementById('weekday_sum_info').innerText = result;
};