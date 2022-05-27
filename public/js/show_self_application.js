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