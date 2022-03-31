<section class="users-list-wrapper">
    <div class="users-list-table">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-sm-7">
                                    <h5 class="pt-2">Edit Country : {{ $data['data']->country_name }}</h5>
                                </div>
                                <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                    <a href="{{ URL::previous() }}" class="btn btn-sm btn-primary px-3 py-1"><i class="fa fa-arrow-left"></i> Back</a>
                                </div>
                            </div>
                        </div>
                        <!-- <hr class="mb-0"> -->
                        <div class="card-body">
                            <form id="editCountryForm" method="post" action="saveCountry?id={{ $data['data']->id }}">
                                <h4 class="form-section"><i class="ft-info"></i> Details</h4>
                                @csrf
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label>Country Name</label>
                                        <input class="form-control" type="text" id="country_name" name="country_name" value="{{ $data['data']->country_name }}"><br />
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Phone Code</label>
                                        <input class="form-control" type="number" id="phone_code" name="phone_code" value="{{ $data['data']->phone_code }}"><br />
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Phone Length</label>
                                        <input class="form-control" type="number" value="{{ $data['data']->phone_length }}" id="phone_length" name="phone_length" value="{{ $data['data']->phone_length }}"><br />
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Currency</label>
                                        <select class="select2" id="currency_id" name="currency_id" style="width: 100% !important;">
                                            <option value="">Select</option>
                                            @foreach ($data['currency'] as $currency)
                                                @if ($currency->id == $data['data']->currency_id)
                                                    <option value="{{ $currency->id }}" selected>{{ $currency->currency_code }}</option>
                                                @else
                                                    <option value="{{ $currency->id }}">{{ $currency->currency_code }}</option>
                                                @endif
                                            @endforeach
                                        </select><br />
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="pull-right">
                                            <button type="button" class="btn btn-success" onclick="submitForm('editCountryForm','post')">Submit</button>
                                            <a href="{{ URL::previous() }}" class="btn btn-sm btn-primary px-3 py-1"><i class="fa fa-arrow-left"></i> Back</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    $('.select2').select2();
</script>
