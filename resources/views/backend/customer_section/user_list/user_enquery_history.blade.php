<section class="users-list-wrapper">
    <div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <table class="table table-bordered table-striped datatable" id="dataTable" width="100%" cellspacing="0" data-url="customer_enquiry_data">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Product Name</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($user->enquries) == 0)
                                    <tr>
                                        <td colspan="4">No Enquiries Found!</td>
                                    </tr>
                                @endif
                                @foreach ($user->enquries as $enquery)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $enquery->product_id }}</td>
                                    <td>{{ \Carbon\Carbon::parse($enquery->created_at)->fromNow() }}</td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                role="switch"
                                                id="flexSwitchCheck{{ $enquery->id }}"
                                                {{ $enquery->is_shown ? 'checked' : '' }}
                                                onclick="toggleEnqueryStatus({{ $enquery->id }}, {{ $user->id }})">
                                            <label class="form-check-label" for="flexSwitchCheck{{ $enquery->id }}">Not hidden </label>
                                        </div>
                                    </td>

                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        function toggleEnqueryStatus(enqueryId, $id) {

        const checkbox = document.getElementById(`flexSwitchCheck${enqueryId}`);

            const status = checkbox.checked ? '1' : '0'; // Convert boolean to string


            axios.post(`/webadmin/user_enquery_history/${$id}/updateStatus`, {
                status: status,
                enqueryId: enqueryId
            })
            .then(response => {
                // Handle success if needed
            })
            .catch(error => {
                // Handle error if needed
            });
        }
    </script>
</section>
