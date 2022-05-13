// チェックボックス
const checkbox = document.getElementById('authority');
const checked = checkbox.dataset.checked;
if ( checked  == '1' ) {
    checkbox.checked = true;
} else {
    checkbox.checked = false;
    checkbox.value = 0;
}
checkbox.addEventListener('change', (e)=>{
    if(e.target.checked){
        e.target.value = 1;
    }else{
        e.target.value = 0;
    }
})

// 退社日
$('#datePicker').datetimepicker({
    locale: 'ja',
    dayViewHeaderFormat: 'YYYY年M月',
    format: 'YYYY/MM/DD'
});

//退社日の記入を維持
if($('[name=leaving]').val()){
    const leaving = $('[name=leaving]').data();
    $('[name=leaving]').val(leaving.name);
}