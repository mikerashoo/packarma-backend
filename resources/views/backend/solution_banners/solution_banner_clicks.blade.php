<section class="users-list-wrapper">
    <div class="users-list-table">
        <table class="table table-bordered table-striped datatable" id="dataTable" width="100%" cellspacing="0"
            data-url="banners_data">



            <tr>
                <th id="user_name" data-orderable="false" data-searchable="false">
                    User Name</th>

                <th id="email" data-orderable="false" data-searchable="false">
                    Email</th>

                <th id="date" data-orderable="false" data-searchable="false">
                    Date</th>



            </tr>

            @foreach ($data as $column)
                <tr>
                    <td>{{ $column->user_name }}</td>
                    <td>{{ $column->date }}</td>
                </tr>
            @endforeach
        </table>
    </div>
</section>
