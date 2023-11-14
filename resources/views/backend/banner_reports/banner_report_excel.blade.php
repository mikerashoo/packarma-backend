<table class="table table-bordered table-striped datatable" id="dataTable" width="100%" cellspacing="0"
    data-url="banners_data">



    @foreach ($data->data as $column)
        <tr>
            <td>{{ $column->user_name }}</td>
            <td>{{ $column->email }}</td>
            <td>{{ $column->date }}</td>
            <td>{{ $data->start_date }}</td>
            <td>{{ $data->end_date }}</td>
        </tr>
    @endforeach
</table>
