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

    // HTMLで直接設定したエラーメッセージを削除
    function DelErrMsg(){
        $('.startTimeErrorMsg').text('');
        $('.startTimeError').addClass('d-none');
        $('.endTimeErrorMsg').text('');
        $('.endTimeError').addClass('d-none');
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

            //開始時間、終了時間のバリデーション
            let limitStartTime = left_time.name

            $('[name=start_time]').on('blur', function(e){
                // 始業時間と終業時間のエラーメッセージが空なら申請ボタンを押せないように
                if($('.endTimeErrorMsg').text('') && $('.startTimeErrorMsg').text('') || $('.endTimeErrorMsg').is(':empty') && $('.startTimeErrorMsg').is(':empty')){
                    $('#application-button').prop("disabled", false); 
                }
                
                //開始時間が終業時間より早かったらエラーメッセージ
                if(e.target.value.replace(':', '') < left_time.name.replace(':', '')){
                    $('.startTimeError').removeClass('d-none');
                    $('.startTimeErrorMsg').text(`${limitStartTime}分以降を選択してください。`);
                    $('#application-button').prop("disabled", true); 
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
                        $('#application-button').prop("disabled", true);
                    
                    //終了時間が開始時間より早かったらエラーメッセージ
                    }else if(endInputTime <= e.target.value.replace(':', '')){
                        $('.endTimeError').removeClass('d-none');
                        $('.endTimeErrorMsg').text(`開始時間よりも遅い時間を選択してください。`); 
                        $('#application-button').prop("disabled", true); 
                    }else{
                        $('.endTimeErrorMsg').text('');
                        $('.endTimeError').addClass('d-none');
                    }
                    //開始時間が終業時間より早かったらエラーメッセージ
                    if(e.target.value.replace(':', '') < left_time.name.replace(':', '')){
                        $('.startTimeError').removeClass('d-none');
                        $('.startTimeErrorMsg').text(`${limitStartTime}分以降を選択してください。`);
                        $('#application-button').prop("disabled", true);
                    }
                })
            })
            $('[name = end_time]').on('blur', val=>{
                // 始業時間と終業時間のエラーメッセージが空なら申請ボタンを押せるように
                if($('.endTimeErrorMsg').text('') && $('.startTimeErrorMsg').text('') || $('.endTimeErrorMsg').is(':empty') && $('.startTimeErrorMsg').is(':empty')){
                    $('#application-button').prop("disabled", false); 
                }
                //終了時間が終業時間より早かったらエラーメッセージ
                const endInputTime = val.target.value.replace(':', '');
                if(endInputTime <= left_time.name.replace(':', '')){
                    $('.endTimeError').removeClass('d-none');
                    $('.endTimeErrorMsg').text(`${limitStartTime}分以降を選択してください。`);
                    $('#application-button').prop("disabled", true);
                }
                //終了時間が開始時間より早かったらエラーメッセージ
                $('[name = start_time]').on('blur', e=>{
                    const inputTime = e.target.value.replace(':', '');
                    if(val.target.value.replace(':', '') <= inputTime){
                        $('.endTimeError').removeClass('d-none');
                        $('.endTimeErrorMsg').text(`開始時間よりも遅い時間を選択してください。`); 
                        $('#application-button').prop("disabled", true); 
                    }else{
                        $('.endTimeErrorMsg').text('');
                        $('.endTimeError').addClass('d-none');
                    }
                })
            })
        }else if(text === '打刻時間修正'){

            // 申請日を空にする
            $('[name=date]').val('');

            // 開始時間は必須、終了時間は必須ではない
            $('[name=start_time]').prop({'disabled': false, 'required': true});
            $('.startTimePicker').addClass('badge badge-danger ml-1');
            $('.startTimePicker').text('必須');
            $('[name=end_time]').prop({'disabled': false, 'required': false});
            $('.endTimePicker').text('');

            // 申請するボタンを有効化
            $('#application-button').prop("disabled", false);
            
            //初期設定(DateTimePickerをTime型に)
            iniTime();

            // HTMLで直接設定したエラーメッセージを削除
            DelErrMsg();

            // 開始時間を入力した際にHTMLで直接設定したエラーメッセージを削除
            $('[name=start_time]').on('blur', function(){
                DelErrMsg();
            })

            // 終了時間を入力した際にHTMLで直接設定したエラーメッセージを削除
            $('[name=end_time]').on('blur', function(){
                DelErrMsg();
            })
       
        }else{
            
            //時間入力させない
            timeToDisable();

            // HTMLで直接設定したエラーメッセージを削除
            DelErrMsg();

             // 申請するボタンを有効化
             $('#application-button').prop("disabled", false);
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

    //申請日を選択後、申請日をget送信 sessionを使う
    function useSession(){
        // 申請日を選んだら      
        $('[name=date]').on('blur', e=>{
            //申請日のセッションをリセット
            window.sessionStorage.removeItem(['appliedDate']);
             // 申請内容のセッション削除
            window.sessionStorage.removeItem(['appliedContent']);
            // 申請内容と申請日をセッションに
            window.sessionStorage.setItem(['appliedContent'], $('#applied-content').val());
            window.sessionStorage.setItem(['appliedDate'], e.target.value);

            // 申請日をget送信
            if(window.sessionStorage.getItem(['appliedContent']) === '5'){
                let setParam = `?date=${e.target.value.replaceAll('/', '-')}`;
                location.search = setParam;
                if(window.sessionStorage.getItem(['reason'])){
                    reasonElement.value = window.sessionStorage.getItem(['reason']);
                }
            }    
        });
    }

    // 申請内容のセッションに打刻時間修正が入っていたら
    if(window.sessionStorage.getItem(['appliedContent']) === '5'){

        // get送信後申請理由を保持
        const reasonElement = document.getElementById('reason');
        reasonElement.addEventListener('change', (e)=>{
            localStorage.setItem(['reason'], e.target.value);
        })
        reasonElement.value =  localStorage.getItem(['reason'])

        // 申請内容を打刻時間修正に
        const select = document.getElementById('applied-content');
        select.options[5].selected = true;

        useSession();

        // 開始時間を必須化
        $('[name=start_time]').prop({'disabled': false, 'required': true});
        $('.startTimePicker').addClass('badge badge-danger ml-1');
        $('.startTimePicker').text('必須');

        // 申請日をget送信した日に
        $('[name=date]').val(window.sessionStorage.getItem(['appliedDate']));

        //終了時間のボックスを有効化
        $('[name=end_time]').prop({'disabled': false});

        iniTime();
        
        // 申請日の打刻した開始時間と終了時間を初期値として設定
        let start_time = $('#start_time').data();
        let end_time = $('#end_time').data();
        if(start_time){
            $('[name=start_time]').datetimepicker({ defaultDate: moment({ hour: start_time.start.slice(0,2), minute: start_time.start.slice(3,5) }), format: 'HH:mm'});
            $('[name=end_time]').datetimepicker({ defaultDate: moment({ hour: end_time.end.slice(0,2), minute: end_time.end.slice(3,5) }), format: 'HH:mm'});
        }
    }

    // 申請フォーム外になるとセッション削除
    const url = location.pathname
    if(url === '/application/form'){
        // 全てのセッションの削除
        window.sessionStorage.clear();
        // ローカルストレージの中身をクリア
        localStorage.clear()
    }

    //開始時間、終了時間を記入できるかどうか
    $('#applied-content').change(function() {

        // // 申請内容をセッションに
        window.sessionStorage.setItem(['appliedContent'], $('#applied-content').val());

        // 打刻時間修正、申請日を選択後、申請日をget送信 sessionを使う
        if(window.sessionStorage.getItem(['appliedContent']) === '5'){
            useSession();
        }

        // 開始時間、終了時間のエラーメッセージを無しに
        $('.endTimeError').addClass('d-none');
        $('.startTimeError').addClass('d-none');
    
        //時間外勤務と打刻時間修正を選ばれている時のみ開始時間、終了時間を記入可
        ableSelectTime();
        $('#application-button').prop("disabled", false);
    
        // 開始時間、終了時間を初期化
        $('[name=start_time]').val('');
        $('[name=end_time]').val('');

        if(window.sessionStorage.getItem(['appliedContent']) === '4'){
            let limitStartTime = left_time.name;
            $('[name=start_time]').val(limitStartTime);
        }

		// 申請項目が変更されたら有給のエラーメッセージを無しに
        $('.appTypeError').addClass('d-none');
    });

    // 申請ボタンをクリックした際にセッション削除
    $('#application-button').on('click', ()=>{
        //申請日のセッションをリセット
        window.sessionStorage.removeItem(['appliedDate']);
        // 申請内容のセッション削除
        window.sessionStorage.removeItem(['appliedContent']);
    })

	// 有給申請されたときに有給がなかったらメッセージを表示する
	$('#form-app').submit(function() {
		var app_type = $('[name=appliedContent] option:selected').text().trim();
		var left_days = $('input#left-days').val();

		// 有給の残り日数がない場合
		if(app_type === '有給休暇' && left_days <= 0)
		{
			// error message
			$('.appTypeError').removeClass('d-none');
			$('.appTypeErrorMsg').text('有給休暇の残り日数が 0日 です。');

			// submitキャンセル
			return false;
		}
	});
})