@extends('layout.shared')

@section('commuter', 'active')

@section('title_head', 'Add Commuter')

@section('content')
<div class="container bg-white px-4 py-2 mt-3 rounded-3 shadow">
<button type="button" class="btn p-0 mb-3 shadow-none border-0 back-button"
    onclick="window.location.href='{{ route('userinfo') }}'">
    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="currentColor" class="bi bi-arrow-left text-dark" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
    </svg>
</button>
        <form action="{{ route('user.insert') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label>
                    <h6>Commuter First Name: <span style="color: red;">*</span></h6>
                </label>
                <input type="text" name="firstName" class="form-control form-control-sm shadow-sm">
            </div>
            <div class="form-group">
                <label>
                    <h6>Commuter Last Name: <span style="color: red;">*</span></h6>
                </label>
                <input type="text" name="lastName" class="form-control form-control-sm shadow-sm">
            </div>
            <div class="form-group">
                <label>
                    <h6>Address: <span style="color: red;">*</span></h6>
                </label>
                <input type="text" name="address" class="form-control form-control-sm shadow-sm">
            </div>
            <div class="form-group">
                <label>
                    <h6>Phone Number: <span style="color: red;">*</span></h6>
                </label>
                <input type="tel" name="phoneNumber" class="form-control form-control-sm shadow-sm" pattern="\d{11}"
                    title="Contact number should be 11 digits" maxlength="11">
            </div>
            <div class="form-group">
                <label>
                    <h6>Age: <span style="color: red;">*</span></h6>
                </label>
                <input type="number" name="age" class="form-control form-control-sm shadow-sm">
            </div>
            <div class="form-group">
                <label>
                    <h6>Valid ID: <i>(You won't be able to file a complaint if you have no valid ID.)</i></h6>
                </label>
                <input type="file" class="form-control form-control-sm shadow-sm" name="validID" id="validID" accept="image/*">
                <img id="preview" src="#" alt="Image Preview" style="display: none; max-width: 200px;" />

                @if ($errors->has('validID'))
                    <div class="alert alert-danger">{{ $errors->first('validID') }}</div>
                @endif
            </div>
            <div class="text-end">
            <button type="submit" class="btn bg-gradient-blue mt-3 mb-2 text-white">Create User</button>
            </div>
        </form>
    </div>
    <script>
        document.getElementById('validID').addEventListener('change', function(event) {
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
