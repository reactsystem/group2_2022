$(document).ready(function() 
{
    // 申請したい日
    $('#datePicker').datetimepicker({
        locale: 'ja',
        dayViewHeaderFormat: 'YYYY年M月',
        format: 'YYYY/MM/DD',
    });

    const left_time = $('#left_time').data();

    // 初期値なし
    if(!$('[name=appliedContent]').val()){
        $('[name=start_time]').val('');
        $('[name=end_time]').val('');
    }

    //時間入力必須化
    function timeToRequire(){
        $('[name=start_time]').prop({'disabled': false, 'required': true});
        $('[name=end_time]').prop({'disabled': false, 'required': true});
        $('.startTimePicker').addClass('badge badge-danger ml-1');
        $('.startTimePicker').text('必須');
        $('.endTimePicker').addClass('badge badge-danger ml-1');
        $('.endTimePicker').text('必須');
    }

    //時間入力させない
    function timeToDisable(){
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

    //時間外勤務と打刻時間修正を選ばれている時のみ開始時間、終了時間を記入可
    function ableSelectTime(){
        let text = $('[name=appliedContent] option:selected').text().trim();
        if(text === '時間外勤務'){

            // 開始時間、終了時間ともに必須
            timeToRequire();

            //初期設定
            iniTime();

            //開始時間、終了時間(終業時間より後のみ選択可)
            $('#startTimePicker').datetimepicker('minDate', moment({h:left_time.name.slice(0, 2), m:left_time.name.slice(3, 5)}));  
            $('#endTimePicker').datetimepicker('minDate', moment({h:left_time.name.slice(0, 2), m:left_time.name.slice(3, 5)}));
        }else if(text === '打刻時間修正'){

            // 開始時間、終了時間ともに必須ではない
            $('[name=start_time]').prop({'disabled': false, 'required': false});
            $('[name=end_time]').prop({'disabled': false, 'required': false});
            $('.startTimePicker').text('');
            $('.endTimePicker').text('');
            
            //初期設定
            iniTime();
            
            //開始時間、終了時間(何時でも選択可)
            $('#startTimePicker').datetimepicker('minDate', false);
            $('#endTimePicker').datetimepicker('minDate', false);
        }else{
            
            //時間入力させない
            timeToDisable()
        }
        
        //開始時間、終了時間のバリデーション
        let limitStartTime = left_time.name
        if(text === '時間外勤務'){
            $('[name=start_time]').on('blur', function(e){
                
                // 開始時間のエラーメッセージを初期化
                $('.startTimeErrorMsg').text('');
                $('.startTimeError').addClass('d-none');
                const inputTime = e.target.value.replace(':', '');
                
                //開始時間が終業時間より早かったらエラーメッセージ
                if(inputTime < left_time.name.replace(':', '')){
                    $('.startTimeError').removeClass('d-none');
                    $('.startTimeErrorMsg').text(`${limitStartTime}分以降を選択してください。`);
                }
                $('[name=end_time]').on('blur', function(val){
                    
                    // 終了時間のエラーメッセージを初期化
                    $('.endTimeErrorMsg').text('');
                    $('.endTimeError').addClass('d-none');
                    const endInputTime = val.target.value.replace(':', '');
                    
                    //終了時間が終業時間より早かったらエラーメッセージ
                    if(endInputTime <= left_time.name.replace(':', '')){
                        $('.endTimeError').removeClass('d-none');
                        $('.endTimeErrorMsg').text(`${limitStartTime}分以降を選択してください。`);
                    
                    //終了時間が開始時間より早かったらエラーメッセージ
                    }else if(endInputTime <= inputTime){
                        $('.endTimeError').removeClass('d-none');
                        $('.endTimeErrorMsg').text(`開始時間よりも遅い時間を選択してください。`);  
                    }
                })
            })
        }
    }

    ableSelectTime();

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
    $('#applied-content').change(function() {
        
        // 開始時間、終了時間のエラーメッセージを無しに
        $('.endTimeError').addClass('d-none');
        $('.startTimeError').addClass('d-none');

        //時間外勤務と打刻時間修正を選ばれている時のみ開始時間、終了時間を記入可
        ableSelectTime();
        
        // 開始時間、終了時間を初期化
        $('[name=start_time]').val('');
        $('[name=end_time]').val('');
    });

   
})