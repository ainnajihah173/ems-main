@extends('layouts.userNav')

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
@section('main-content')
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">{{ __('Consultation') }}</h1>

    @if (session('success'))
        <div class="alert alert-success border-left-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger border-left-danger" role="alert">
            <ul class="pl-4 my-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="col-lg-12 order-lg-1">

        <div class="card shadow mb-4">

            <div class="card-header py-3">

                {{-- <div class="card-body"> --}}
                {{-- start form --}}
                <form method="POST" id="form" action="{{ route('user.consultation.store') }}" autocomplete="off"
                    enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <input type="hidden" name="_method" value="PUT">
                    {{-- <div class="card-header py-2"> --}}
                    <label for="example-color-input" class="form-control-label mb-3">Consultation's Detail</label>
                    <div class="nav-wrapper position-relative end-0">
                        {{-- start tab navigation --}}
                        <ul class="nav nav-pills nav-fill p-1" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link mb-0 px-0 py-1 active" data-bs-toggle="tab" href="#applicant"
                                    role="tab" aria-controls="profile" aria-selected="true">
                                    Applicant's Information
                                </a>
                            </li>

                        </ul>
                        {{-- end tab navigation --}}
                        <br>
                        <div class="tab-content" id="myTabContent">
                            {{-- start applicant tabs --}}
                            <div class="tab-pane fade show active" id="applicant" role="tabpanel"
                                aria-labelledby="applicant_tab">
                                <div class="pl-lg-4">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group focused">
                                                <label class="form-control-label" for="name">Name<span
                                                        class="small text-danger">*</span></label>
                                                <input type="text" id="name" class="form-control"
                                                    name="applicant_name" placeholder="Name"
                                                    value="{{ $user->name }}"required>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group focused">
                                                <label class="form-control-label" for="birthdate">Date of Birth</label>
                                                <input type="date" id="birthdate" class="form-control"
                                                    name="applicant_birthdate" placeholder="date of birth"required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-control-label" for="applicant_email">Email address<span
                                                        class="small text-danger">*</span></label>
                                                <input type="email" id="applicant_email" class="form-control"
                                                    name="applicant_email"
                                                    placeholder="example@example.com"value="{{ $user->email }}"required>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-control-label" for="applicant_IcNum">IC Number<span
                                                        class="small text-danger">*</span></label>
                                                <input type="text" id="applicant_IcNum" class="form-control"
                                                    name="applicant_IcNum" placeholder="IC Number"
                                                    value="{{ $user->ic }}" disabled>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group focused">
                                                <label class="form-control-label" for="applicant_gender">Gender</label>
                                                <input type="text" id="applicant_gender" class="form-control"
                                                    name="applicant_gender"
                                                    placeholder="gender"value="{{ $user->gender }}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group focused">
                                                <label class="form-control-label" for="applicant_phoneNo">Phone
                                                    Number</label>
                                                <input type="text" id="phoneNo" class="form-control"
                                                    name="applicant_phoneNo" placeholder="phone number"
                                                    value="{{ $user->contact }}"required>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group focused">
                                                <label class="form-control-label"
                                                    for="applicant_nationality">Nationality</label>
                                                <input type="text" id="applicant_nationality" class="form-control"
                                                    name="applicant_nationality" placeholder="Nationality"required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <label class="form-control-label" for="applicant_address">Home Address<span
                                                    class="small text-danger">*</span></label>
                                            <textarea id="applicant_address" class="form-control" name="applicant_address" placeholder="Address"required></textarea>

                                        </div>
                                    </div>
                                </div>

                            </div>
                            {{-- end applicant tabs --}}
                            {{-- start spouse tabs --}}
 
                            {{-- end marriage tabs --}}
                        </div>
                    </div>
                    <!-- Button -->
                    <br><br>
                    <div class="pl-lg-4">
                        <div class="row">
                            <div class="col text-center">
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </div>
                    </div>
                </form>

                {{-- </div> --}}

            </div>

        </div>

    </div>

@endsection
