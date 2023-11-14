<section class="users-list-wrapper">
    <div class="users-list-table">

        @include('backend.banner_reports.banner_report_tables')

        <a href={{ $data->downloadLink }}
            class="border-t py-2 bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded">
            Export to Excel
        </a>
    </div>
</section>
