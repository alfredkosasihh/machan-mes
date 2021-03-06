@extends('layouts.myapp')

@section('css')
<style>
    .space-item {
        margin-left: 10px;
    }
    .breadcrumb-custom {
        background-color: #3D404C;
        width: 99%;
        margin:0px auto;
        padding: 15px 15px;
        margin-bottom: 20px;
        list-style: none;
        border-radius: 4px;
        color: #fff;
    }
    .total-data {
        width: 98%;
        margin:0px auto;
    }
    .table-pos {
        margin: 0px auto;
        width: 98%;
    }
    .thead-color {
        background-color: #E85726;
        color: #fff;
        height: 10px;
    }
</style>
@endsection

@section('content')
<div id="page-wrapper">
    <div class="container-fluid">
        <h2>休息時間設定</h2>
        <ol class="breadcrumb">
            <img src="{{ asset('img/u12.png') }}">
            <span class="space-item">系統管理</span>
            <span class="space-item">></span>
            <span class="space-item">休息時間設定<span>
        </ol>
        <div class="breadcrumb-custom">
            <span>資料列表</span>
            <div style="float:right; margin-top:-7px">
                <a  href="{{ route('rest-time.create') }}">
                    <button class="btn btn-success" >
                        新增
                    </button>
                </a>
            </div>
        </div>
        <div class="total-data">
            載入筆數 |
            <span id="data-num"></span>
        </div>
        <div style="margin-top:15px;">
            <table class="table table-striped table-pos" id="resttime-table">
                <thead class="thead-color">
                    <tr>
                        <th scope="col">序</th>
                        <th scope="col">休息類別</th>
                        <th scope="col">操作</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <div style="text-align:right">
                <span style="display: inline-block; margin-top: 27px;">
                        <span>每頁顯示筆數</span>
                        <select id="amount" onchange="getRestTimeData(); $('#pagination-demo').twbsPagination('destroy');">
                            <option value="10" selected>10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                </span>
                <ul id="pagination-demo" class="pagination-sm" style="vertical-align: top;"></ul>
            </div>
        </div>
    </div>
</div>
<script>
    let lastPage;
    const getRestTimeData = (page = 1) => {
        const amount = $('#amount').val();   
        axios.get('{{ route('rest-data') }}', {
            params: {
                amount,
                page,
            }
        }).then(({ data }) => { 
            lastPage = data.last_page;
            const rests = data.data;
            $('#data-num').text(`共 ${data.total} 筆`);
            $('#resttime-table tbody').empty();
            rests.forEach((rest, key) => {
                $('#resttime-table tbody').append(`
                    <tr>
                        <th scope="row">${key + 1 + (page - 1) * amount}</th>
                        <td>${rest.rest_name}</td>
                        <td style="width:20%">
                            <a class="btn btn-primary" href="rest-time/${rest.id}/edit">編輯</a>
                            <form action="rest-time/${rest.id}"  onsubmit="return checkyn()" method="POST" style="display:inline-block">
                                @method('DELETE')
                                @csrf
                                <div>
                                    <button class="btn btn-danger btn">刪除</button>
                                </div>
                            </form>
                        </td>
                    </tr>
                `)
            });
               
            $('#pagination-demo').twbsPagination({
                totalPages: lastPage,
                visiblePages: 5,
                first:'頁首',
                last:'頁尾',
                prev:'<',
                next:'>',
                initiateStartPageClick: false,
                onPageClick: function (event, page) {
                    getRestTimeData(page)
                }
            });
        });
    }

    function checkyn(){
        var check = confirm("是否要刪除該筆資料");
        if (check) {
            return true;
        } else {
            return false;
        }
    }
    getRestTimeData();
</script>
@endsection
