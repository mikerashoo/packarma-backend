<div>
    <section class="users-list-wrapper">
        <div class="users-list-table">
            <div class="row">


                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">Manage Benefits</h5>
                                </div>

                            </div>
                        </div>
                        <div class="card-content p-4">
                            <form id="addSolutionBenefitForm" method="post" action="{{ route('subscription_benefits.add', ['id'=> $data['id']]) }}">
                                @csrf
                                <div class="tab-content">
                                    <div class="tab-pane fade mt-2 show active" id="details" role="tabpanel" aria-labelledby="details-tab">

                                        <div id="benefits-container">
                                            @foreach($data['benefits'] as $index => $benefit)
                                                <div class="mb-3 row" id="description_{{ $index }}">
                                                    <div class="col-1 text-right">
                                                        <button type="button" class="btn btn-danger mt-2 btn btn-success btn-sm" onclick="removeBenefit('description_{{ $index }}')"><i class="fa fa-trash"></i></button>
                                                    </div>
                                                    <div class="col-10">
                                                        <textarea class="form-control" name="benefits[]" >{{ $benefit->description }}</textarea>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        <div class="mb-3 row">
                                            <div class="col-1 text-right">
                                            </div>
                                            <div class="col-10">
                                                <button type="button" class="btn btn-primary" onclick="addBenefit()">Add Benefit</button>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-sm-12 pb-4 pr-4">
                                        <div class="pull-right">
                                            <button type="submit" class="btn btn-success">Submit</button>
                                            <a href="{{ URL::previous() }}" class="btn btn-danger px-3 py-1">Cancel</a>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <script>
                                function addBenefit() {
                                    var container = document.getElementById('benefits-container');

                                    var div = document.createElement('div');
                                    var descriptionId = 'description_' + new Date().getTime(); // Unique ID for each new description

                                    div.innerHTML = `
                                        <div class="mb-3 row" id="${descriptionId}">
                                            <div class="col-1 text-right">
                                                <button type="button" class="btn btn-danger mt-2 btn btn-success btn-sm" onclick="removeBenefit('${descriptionId}')"><i class="fa fa-trash"></i></button>
                                            </div>
                                            <div class="col-10">
                                                <textarea class="form-control" name="benefits[]"></textarea>
                                            </div>
                                        </div>
                                    `;

                                    container.appendChild(div);
                                }

                                function removeBenefit(descriptionId) {
                                    var descriptionElement = document.getElementById(descriptionId);

                                    if (descriptionElement) {
                                        descriptionElement.remove();
                                    }
                                }

                                function submitForm(formId, method) {
                                    // Your existing submitForm function logic
                                }
                            </script>


                        </div>
                    </div>
                </div>
            </div>
        </div>


    </section>
</div>

