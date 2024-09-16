@extends('layouts.app')

@section('content')
<style>
    .tab {
        overflow: hidden;
        border: 1px solid #ccc;
        background-color: #f1f1f1;
    }

    .tab button {
        background-color: inherit;
        float: left;
        border: none;
        outline: none;
        cursor: pointer;
        padding: 14px 16px;
        transition: 0.3s;
        font-size: 17px;
    }

    .tab button:hover {
        background-color: #ddd;
    }

    .tab button.active {
        background-color: #ccc;
    }

    .tabcontent {
        display: none;
        padding: 6px 12px;
        border: 1px solid #ccc;
        border-top: none;
    }

    .topright {
        float: right;
        cursor: pointer;
        font-size: 28px;
    }

    .topright:hover {
        color: red;
    }
</style>

<div style="padding-left: 30px; border-width: 2px;">
    <div>
        <h3>Validate email.</h3>
        <br>
        <p>Authenticate emails via our single, API, or batch validation methods.</p>
    </div>
    <br>

    <div class="tab">
        <button class="tablinks" onclick="openCity(event, 'single')" id="defaultOpen">single</button>
        <button class="tablinks" onclick="openCity(event, 'API')">API</button>
        <button class="tablinks" onclick="openCity(event, 'batchvalidation')">batch validation</button>
    </div>

    <div id="single" class="tabcontent">
        <span onclick="this.parentElement.style.display='none'" class="topright">&times;</span>
        <h3>single</h3>
        <div class="container-fluid mt-3">
            <div class="row">
                <div class="col p-3" style="border-style: solid; border-width: 1px;border-radius: 30px;margin:auto;width: 100%;padding-bottom: 50px;">
                    <form method="POST" action="{{ route('validate.api.mail') }}" id="validateEmailViaApi">
                        @csrf
                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>
                            <div class="col-md-5">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Check') }}
                                </button>
                            </div>
                        </div>
                    </form>

                    <hr style="height:2px;border-width:0;color:gray;background-color:blue;">

                    <!-- Progress Bar -->
                    <div id="progress-bar-container" class="progress mb-3" style="display:none;">
                        <div id="progress-bar-format" class="progress-bar" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
                        <div id="progress-bar-domain" class="progress-bar bg-success" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
                        <div id="progress-bar-nogeneric" class="progress-bar bg-info" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
                        <div id="progress-bar-noblock" class="progress-bar bg-danger" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>

                    <div id="cf-response-message">
                        <div class="collapse multi-collapse" id="collapse">
                            <div class="card card-body" id="card"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <i id="format-icon" class="fas"></i>
                            <span id="format-message"></span>
                            <div class="collapse multi-collapse" id="format-collapse">
                                <div class="card card-body" id="format-card"></div>
                            </div>
                        </div>
                        <div class="col">
                            <i id="domain-icon" class="fas"></i>
                            <span id="domain-message"></span>
                            <div class="collapse multi-collapse" id="domain-collapse">
                                <div class="card card-body" id="domain-card"></div>
                            </div>
                        </div>
                        <div class="col">
                            <i id="nogeneric-icon" class="fas"></i>
                            <span id="nogeneric-message"></span>
                            <div class="collapse multi-collapse" id="nogeneric-collapse">
                                <div class="card card-body" id="nogeneric-card"></div>
                            </div>
                        </div>
                        <div class="col">
                            <i id="noblock-icon" class="fas"></i>
                            <span id="noblock-message"></span>
                            <div class="collapse multi-collapse" id="noblock-collapse">
                                <div class="card card-body" id="noblock-card"></div>
                            </div>
                        </div>
                    </div>
                    <div id="cf-response-message"></div>

                    <br>

                    <hr style="height:2px;border-width:0;color:gray;background-color:blue">
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target=".multi-collapse" aria-expanded="false" aria-controls="multiCollapseExample1 multiCollapseExample2">Toggle details</button>
                        </div>
                    </div>
                    <br><br>
                </div>
            </div>
        </div>
    </div>

    <div id="API" class="tabcontent">
        <span onclick="this.parentElement.style.display='none'" class="topright">&times;</span>
        <h3>API</h3>
    </div>

    <div id="batchvalidation" class="tabcontent">
        <span onclick="this.parentElement.style.display='none'" class="topright">&times;</span>
        <h3>batch validation</h3>
        <div style="padding-left: 30px; border-width: 2px;">
            <div>
                <h3>bulk email validation</h3>
                <br>
            </div>
            <div class="col p-3" style="border-style: solid; border-width: 1px;border-radius: 30px;margin:auto;width: 100%;padding-bottom: 50px;">
                <p>Click on the "Choose File" button to upload a file:</p>
                <form action="/action_page.php">
                    <input type="file" id="myFile" name="filename">
                    <input type="submit">
                </form>
            </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("validateEmailViaApi").addEventListener("submit", function(event) {
        event.preventDefault();

        const formData = new FormData(this);
        document.getElementById("progress-bar-container").style.display = "block";

        fetch('{{ route('validate.api.mail') }}', {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => { throw new Error(data.message || 'Unknown error'); });
            }
            return response.json();
        })
        .then(data => {
            if (data.validation) {
                const validation = data.validation;

                updateValidationSection("format", validation.format, "Valid format", "Invalid format",
                    "It appears to be formatted correctly and follows the structure of an email address.",
                    "The email address is not formatted correctly.");

                updateValidationSection("domain", validation.domain, "Valid domain", "Invalid domain",
                    "The domain of the email address is valid and has a valid DNS record.",
                    "The domain of the email address is invalid or does not have a valid DNS record.");

                updateValidationSection("nogeneric", validation.generic, "No Generic", "Generic",
                    "The email address does not seem to be a generic email address, such as support@, info@, or contact@.",
                    "The email address is from a generic domain.");

                updateValidationSection("noblock", validation.noblock, "Not Blocked", "Blocked",
                    "The email address is not blocklisted in our database of known spam email addresses.",
                    "The email address is blocklisted.");

                updateProgressBar(validation);

                document.getElementById("cf-response-message").innerText = `Validation Results: ${validation.results}%`;
            } else {
                document.getElementById("cf-response-message").innerText = 'Validation data not found in the response.';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById("cf-response-message").innerText = error.message || 'An error occurred. Please try again later.';
        });
    });

    function updateValidationSection(section, isValid, validText, invalidText, validDesc, invalidDesc) {
        const icon = document.getElementById(`${section}-icon`);
        const message = document.getElementById(`${section}-message`);
        const card = document.getElementById(`${section}-card`);
        const collapse = $(`#${section}-collapse`);

        if (isValid) {
            icon.classList.add("fa-check");
            icon.classList.remove("fa-times");
            message.textContent = validText;
            card.textContent = validDesc;
        } else {
            icon.classList.add("fa-times");
            icon.classList.remove("fa-check");
            message.textContent = invalidText;
            card.textContent = invalidDesc;
        }

        const collapseElement = document.getElementById(`${section}-collapse`);
        collapseElement.classList.remove('show');
        message.addEventListener('click', () => {
            $(collapse).collapse('toggle');
        });
    }

    function updateProgressBar(validation) {
        const totalChecks = 4;
        const completedChecks = Object.values(validation).filter(v => v === true).length;
        const progressPercentage = (completedChecks / totalChecks) * 100;

        const progressBarFormat = document.getElementById('progress-bar-format');
        const progressBarDomain = document.getElementById('progress-bar-domain');
        const progressBarNoGeneric = document.getElementById('progress-bar-nogeneric');
        const progressBarNoBlock = document.getElementById('progress-bar-noblock');

        progressBarFormat.style.width = `${validation.format ? 25 : 0}%`;
        progressBarDomain.style.width = `${validation.domain ? 25 : 0}%`;
        progressBarNoGeneric.style.width = `${validation.generic ? 25 : 0}%`;
        progressBarNoBlock.style.width = `${validation.noblock ? 25 : 0}%`;

        progressBarFormat.setAttribute('aria-valuenow', validation.format ? 25 : 0);
        progressBarDomain.setAttribute('aria-valuenow', validation.domain ? 25 : 0);
        progressBarNoGeneric.setAttribute('aria-valuenow', validation.generic ? 25 : 0);
        progressBarNoBlock.setAttribute('aria-valuenow', validation.noblock ? 25 : 0);
    }
});

function openCity(evt, cityName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(cityName).style.display = "block";
    evt.currentTarget.className += " active";
}

document.getElementById("defaultOpen").click();
</script>

@endsection
