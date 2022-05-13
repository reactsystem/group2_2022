$(document).ready(function() 
{
    // 申請したい日
    $('#datePicker').datetimepicker({
        locale: 'ja',
        dayViewHeaderFormat: 'YYYY年M月',
        format: 'YYYY/MM/DD',
    });

    const left_time = $('#left_time').data();

    //開始時間、終了時間
    $('#startTimePicker').datetimepicker({
        locale: 'ja',
        format: 'HH:mm',
        minDate: {h:left_time.name.slice(0, 2), m:left_time.name.slice(3, 5)},
        maxDate: moment({h:24}),
    });
    
    $('#endTimePicker').datetimepicker({
        locale: 'ja',
        format: 'HH:mm',
        minDate: {h:left_time.name.slice(0, 2), m:left_time.name.slice(3, 5)},
        maxDate: moment({h:24}),
    });        
    
    // 初期値なし
    if(!$('[name=appliedContent]').val()){
        $('[name=start_time]').val('');
        $('[name=end_time]').val('');
    }

    //時間外勤務と打刻時間修正を選ばれている時のみ開始時間、終了時間を記入可
    function ableSelectTime(){
        let text = $('[name=appliedContent] option:selected').text().trim();
        if(text === '時間外勤務' || text === '打刻時間修正'){
            $('[name=start_time]').prop({'disabled': false, 'required': true});
            $('[name=end_time]').prop({'disabled': false, 'required': true});
            $('.startTimePicker').addClass('badge badge-danger ml-1');
            $('.startTimePicker').text('必須');
            $('.endTimePicker').addClass('badge badge-danger ml-1');
            $('.endTimePicker').text('必須');
        }else{
            $('[name=start_time]').prop({'disabled': true, 'required': false});
            $('[name=end_time]').prop({'disabled': true, 'required': false});
            $('.startTimePicker').removeClass('badge badge-danger ml-1');
            $('.startTimePicker').text('');
            $('.endTimePicker').removeClass('badge badge-danger ml-1');
            $('.endTimePicker').text('');
        }
    }

    ableSelectTime()

    //開始時間、終了時間、申請日の記入を維持
    if($('[name=start_time]').val() ||$('[name=end_time]').val() || $('[name=date]').val()){
        const date = $('[name=date]').data();
        const start_time = $('[name=start_time]').data();
        const end_time = $('[name=end_time]').data();
        $('[name=date]').val(date.name);
        $('[name=start_time]').val(start_time.name);
        $('[name=end_time]').val(end_time.name);
    }

    
    //開始時間、終了時間を記入できるかどうか
    $('[name=appliedContent]').change(function() {
        ableSelectTime()
        $('[name=start_time]').val('');
        $('[name=end_time]').val('');
    });
})