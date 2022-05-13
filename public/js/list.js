const searchForm = document.getElementById('search')
const department = document.getElementById('department');
const limit = document.getElementById('limit');
department.addEventListener('change', (e)=>{
    searchForm.submit();
})
limit.addEventListener('change', (e)=>{
    searchForm.submit();
})