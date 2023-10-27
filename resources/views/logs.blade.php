@extends('index');
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-2">
                        <div class="mb-3">
                            <label for="year">Filter Tahun</label>
                            <select name="year" id="year" class="form-control mb-2">
                                @for ($i = 0; $i < 8; $i++)
                                    <option value="{{ date('Y') - $i }}">{{ date('Y') - $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-1">
                        <div class="mb-3">
                            <label for="year">&nbsp;</label>
                            <button type="button" class="btn btn-info form-control" onclick="filter_data()">Cari</button>
                        </div>
                    </div>
                </div>

                {{-- table easyui datagrid --}}
                <table id="dg" class="easyui-datagrid mt-2" style="width:99%;height:600px" singleSelect="true">
                    <thead>
                        <tr>
                            <th data-options="field:'updated_at_custom', sortable:true" width="20%">Waktu</th>
                            <th data-options="field:'data_desc', sortable:true" width="80%">Aktifitas</th>
                        </tr>
                    </thead>
                </table>

            </div>
        </div>
    </div>
</div>

<!-- easyui js datagrid -->
<script type="text/javascript">
    
    // datagrid
    $(document).ready(function() {
        filter_data();
    });

    function filter_data() {
        var data_year = $('#year').val();

        get_datagrid("/logs-data/"+data_year);
    }

    function get_datagrid(url = null) {
        $('#dg').datagrid({
            url: url,
            method: 'get',
            rownumbers: true,
            singleSelect: true,
            pagination: true,
            fitColumns: true,
            toolbar: '#toolbar',
            pageSize: 10,
            remoteFilter: true,
            defaultFilterType: 'label'
        });

        $('#dg').datagrid('enableFilter', [
            {field:'updated_at_custom',type:'text'},
            {field:'data_desc',type:'text'}
        ]);

    }

</script>

@endsection