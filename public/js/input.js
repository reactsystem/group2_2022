// 月選択用のセレクトボックスを選択すると、submitする
function submit_form() {
	var target = document.getElementById("select");
	target.method = "post";
	target.submit();
}

// テーブルの各列の合計値を計算
window.onload = function () {
	var tableElem = document.getElementById('input_table');
	var rowElems = tableElem.rows;

	// 「休憩時間」の合計値を計算する
	var rest = "00:00";
	for (i = 1, len = rowElems.length - 2; i < len; i++) {
		if (rowElems[i].cells[4].innerText == ""){
			continue;
		} else {
			var conprod = rowElems[i].cells[4].innerText;

			prodhrdArr = rest.split(":"); 
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
			var rest = hh1 + ':' + mm1;
		}
	}

	// 「労働時間」の合計値を計算する
	var worked = "00:00";
	for (i = 1, len = rowElems.length - 2; i < len; i++) {
		if (rowElems[i].cells[5].innerText == ""){
			continue;
		} else {
			var conprod = rowElems[i].cells[5].innerText;

			prodhrdArr = worked.split(":"); 
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
			var worked = hh1 + ':' + mm1;
		}
	}

	// 「時間外」の合計値を計算する
	var over = "00:00";
	for (i = 1, len = rowElems.length - 2; i < len; i++) {
		if (rowElems[i].cells[6].innerText == ""){
			continue;
		} else {
			var conprod = rowElems[i].cells[6].innerText;

			prodhrdArr = over.split(":"); 
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
			var over = hh1 + ':' + mm1;
		}
	}
	document.getElementById('rest').innerText = rest;
	document.getElementById('worked').innerText = worked;
	document.getElementById('over').innerText = over;
}
