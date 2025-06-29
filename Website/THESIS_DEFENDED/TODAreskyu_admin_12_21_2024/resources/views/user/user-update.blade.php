@extends('layout.shared')

@section('commuter', 'active')

@section('title_head', 'Update Commuter')

@section('content')
<div class="container bg-white px-4 py-2 mt-3 rounded-3 shadow">
<button type="button" class="btn p-0 mb-3 shadow-none border-0 back-button"
    onclick="window.location.href='{{ route('userinfo') }}'">
    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="currentColor" class="bi bi-arrow-left text-dark" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
    </svg>
</button>
        <form action="{{ route('user.update', $user->userID) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label>
                    <h6>Commuter First Name: </h6>
                </label>
                <input type="text" name="firstName" class="form-control form-control-sm shadow-sm" value="{{ $user->firstName }}">
            </div>
            <div class="form-group">
                <label>
                    <h6>Commuter Last Name:</h6>
                </label>
                <input type="text" name="lastName" class="form-control form-control-sm shadow-sm" value="{{ $user->lastName }}">
            </div>
            <div class="form-group">
                <label>
                    <h6>Address: </h6>
                </label>
                <input type="text" name="address" class="form-control form-control-sm shadow-sm" value="{{ $user->address }}">
            </div>
            <div class="form-group">
                <label>
                    <h6>Phone Number: </h6>
                </label>
                <input type="tel" name="phoneNumber" class="form-control form-control-sm shadow-sm" pattern="\d{11}"
                    title="Contact number should be 11 digits" maxlength="11" value="{{ $user->phoneNumber }}">
            </div>
            <div class="form-group">
                <label>
                    <h6>Age: </h6>
                </label>
                <input type="number" name="age" class="form-control form-control-sm shadow-sm" value="{{ $user->age }}">
            </div>
            <div class="form-group">
                @if ($user->verified == 0)

                    @if (empty($user->validID))
                        <label>
                            <h6>Valid ID: <i>(You won't be able to file a complaint if you have no valid ID.)</i></h6>
                        </label>
                        <input type="file" class="form-control form-control-sm shadow-sm" name="validID" id="validID"
                            accept="image/*">
                        <img id="preview" src="#" alt="Image Preview" style="display: none; max-width: 200px;" />
                    @endif

                    @if ($errors->has('validID'))
                        <div class="alert alert-danger">{{ $errors->first('validID') }}</div>
                    @endif

                    @if (!empty($user->validID))
                        <label>
                            <h6>Valid ID: <i>(You won't be able to file a complaint if you have no valid ID.)</i></h6>
                        </label>
                        <input type="file" class="form-control form-control-sm shadow-sm mb-2" name="validID" id="validID"
                            accept="image/*" value="{{ $user->validID }}">
                        <img id="preview" src="#" alt="Image Preview" style="display: none; max-width: 200px;" />
                        <a href="#" data-bs-toggle="modal" data-bs-target="#imageModal">
                            <img src="{{ asset('storage/' . $user->validID) }}" alt="Image Preview"
                                style="display: block; max-width: 200px;" />
                        </a>
                        <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="imageModalLabel">Valid ID Preview</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <img src="{{ asset('storage/' . $user->validID) }}" alt="Large Image Preview"
                                            class="img-fluid" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if (!empty($user->validID))
                        <div class="input-group my-2">
                            <div class="input-group-text d-flex align-items-center">
                                <input class="form-check-input mt-0" type="checkbox" value="verified" name="verified"
                                    aria-label="Checkbox for following text input">
                                <p class="mb-0 ms-2">Verify Valid ID</p>
                            </div>
                        </div>
                    @endif
                @elseif($user->verified == 1)
                    <label>
                        <h6>Valid ID: <i>(Verified)</i></h6>
                    </label>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#imageModal">
                        <img src="{{ asset('storage/' . $user->validID) }}" alt="Image Preview"
                            style="display: block; max-width: 200px;" />
                    </a>
                    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="imageModalLabel">Valid ID Preview</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <img src="{{ asset('storage/' . $user->validID) }}" alt="Large Image Preview"
                                        class="img-fluid" />
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                @endif





            </div>
            <div class="text-end">
            <button type="submit" class="btn bg-gradient-blue mt-3 text-white">Update</button>
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
