const searchForm = document.getElementById('search')
const department = document.getElementById('department');
const limit = document.getElementById('limit');
department.addEventListener('change', (e)=>{
    searchForm.submit();
})
limit.addEventListener('change', (e)=>{
    searchForm.submit();
})

$(document).ready(function() {
    $('#employees').tablesorter({
        headers:{
            0: { sorter: false },
            3: { sorter: false },
            4: { sorter: false }
        }
    });
});

$(document).ready(function() {
    $('#application').tablesorter({
        headers:{
            0: { sorter: false },
            2: { sorter: false },
            4: { sorter: false }
        }
    });
});