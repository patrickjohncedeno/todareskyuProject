@extends('layout.unreg-layout.unreg-add')

@section('content_title', 'Unregistered Tricycle Complaints')

@section('content')


<div class="container bg-white px-4 py-2 mt-3 rounded-3 shadow">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif 
        
        <button type="button" class="btn p-0 mb-3 shadow-none border-0 back-button"
    onclick="window.location.href='{{ route('complaints.reg-inqueue') }}'">
    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="currentColor" class="bi bi-arrow-left text-dark" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
    </svg>
</button>
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }} <a href="{{ route('user.add') }}" class="alert-link">Add
                    user</a></div>
        @endif
        <form class="row g-3 needs-validation" action="{{ route('insert-unregistered') }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <div class="col-md-6">
                <label for="firstname" class="form-label">Complainant First Name</label>
                <input type="text" class="form-control form-control-sm shadow-sm" id="firstname" name="firstName">
                <div class="invalid-feedback">Please provide a first name.</div>
            </div>
            <div class="col-md-6">
                <label for="lastname" class="form-label">Complainant Last Name</label>
                <input type="text" class="form-control form-control-sm shadow-sm" id="lastname" name="lastName">
                <div class="invalid-feedback">Please provide a last name.</div>
            </div>
            <div class="col-12">
                <label for="address" class="form-label">Address</label>
                <input type="text" class="form-control form-control-sm shadow-sm" id="address" placeholder="1234 Main St"
                    name="address">
                <div class="invalid-feedback">Please provide an address.</div>
            </div>

            <div class="col-md-12">
                <label for="violation" class="form-label">Violation</label>
                <select id="violation" class="form-control form-select form-select-sm shadow-sm" name="violationID">

                    <option selected>Select Violation</option>
                    @foreach ($violations as $violation)
                        <option value="{{ $violation->violationID }}">{{ $violation->violationName }}</option>
                    @endforeach
                </select>
                <div class="invalid-feedback">Please select a violation.</div>
            </div>
            <div class="col-md-12">
                <label for="violation" class="form-label">Evidence Photo</label>
                <input type="file" class="form-control form-control-sm shadow-sm" name="evidencePhoto" id="evidencePhoto"
                    accept="image/*">
                <img id="preview" src="#" alt="Image Preview" style="display: none; max-width: 200px;" />

                @if ($errors->has('evidencePhoto'))
                    <div class="alert alert-danger">{{ $errors->first('evidencePhoto') }}</div>
                @endif
            </div>


            <div class="col-md-12">
                <label for="placeofincident" class="form-label">Plate Number</label>
                <input type="text" class="form-control form-control-sm shadow-sm" id="placeofincident" name="plateNumber">
            </div>
            <div class="col-md-12">
                <label for="placeofincident" class="form-label">Tricycle Color</label>
                <input type="text" class="form-control form-control-sm shadow-sm" id="placeofincident" name="tricycleColor">
            </div>
            <div class="col-md-12">
                <label for="placeofincident" class="form-label">Tricycle Description</label>
                <input type="text" class="form-control form-control-sm shadow-sm" id="placeofincident" name="tricycleDescription">
            </div>



            <div class="col-md-12">
                <label for="placeofincident" class="form-label">Place of Incident</label>
                <input type="text" class="form-control form-control-sm shadow-sm" id="placeofincident" name="location">
                <div class="invalid-feedback">Please provide the place of the incident.</div>
            </div>
            <div class="col-md-12 mb-3">
                <label for="summaryofincindent" class="form-label">Summary of Incident</label>
                <textarea class="form-control form-control-sm shadow-sm" id="summaryofincindent" rows="3" name="description"></textarea>
                <div class="invalid-feedback">Please provide a summary of the incident.</div>
            </div>
            <div class="col-md-12 text-end mb-2">
                <button type="submit" class="btn bg-gradient-blue text-white">Submit Complaint</button>
            </div>
        </form>
    </div>
    <script>
        document.getElementById('evidencePhoto').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('preview');

            if (file) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block'; // Show the image
                }

                reader.readAsDataURL(file); // Convert the file to a data URL
            } else {
                preview.src = '#'; // Reset the image source
                preview.style.display = 'none'; // Hide the image
            }
        });
    </script>
@endsection
