<!DOCTYPE html>
<html>

<head>
    <title>Send Email</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tagify/4.3.0/tagify.min.css" />
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            margin-top: 50px;
        }

        .card {
            border: none;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: #007bff;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.25);
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }

        .form-check-label {
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Send Email</h4>
                        <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#setCredentialModal">
                            Set Credentials
                        </button>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                        @endif

                        <form id="emailForm" action="{{ url('send-mail') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="email">Email addresses:</label>
                                <input type="text" class="form-control" id="email" name="email" required>
                                <span class="text-danger" id="emailError" style="display: none;">Please enter at least one valid email address.</span>
                            </div>
                            <div class="form-group">
                                <label for="subject">Subject:</label>
                                <input type="text" class="form-control" id="subject" name="subject" required>
                                <span class="text-danger" id="subjectError" style="display: none;">Please enter a subject.</span>
                            </div>
                            <div class="form-group">
                                <label for="body">Body:</label>
                                <textarea class="form-control" id="body" name="body" rows="4" required></textarea>
                                <span class="text-danger" id="bodyError" style="display: none;">Please enter the email body.</span>
                            </div>
                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="toggleRepeat">
                                    <label class="custom-control-label" for="toggleRepeat">
                                        Send multiple times
                                    </label>
                                </div>
                            </div>
                            <div class="form-group" id="repeatCountDiv" style="display: none;">
                                <label for="repeatCount">Number of times to send:</label>
                                <input type="number" class="form-control" id="repeatCount" name="repeatCount" min="1">
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Send Email</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="setCredentialModal" tabindex="-1" aria-labelledby="setCredentialModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="setCredentialModalLabel">Set Email Credentials</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="credentialForm">
                        <div class="form-group">
                            <label for="smtpServer">SMTP Server:</label>
                            <input type="text" class="form-control" id="smtpServer" name="smtpServer" required>
                        </div>
                        <div class="form-group">
                            <label for="smtpPort">SMTP Port:</label>
                            <input type="text" class="form-control" id="smtpPort" name="smtpPort" required>
                        </div>
                        <div class="form-group">
                            <label for="smtpUser">SMTP User:</label>
                            <input type="text" class="form-control" id="smtpUser" name="smtpUser" required>
                        </div>
                        <div class="form-group">
                            <label for="smtpPassword">SMTP Password:</label>
                            <input type="password" class="form-control" id="smtpPassword" name="smtpPassword" required>
                        </div>
                        <button type="button" class="btn btn-primary" id="saveCredentials">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tagify/4.3.0/tagify.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            var input = document.querySelector('#email');
            var tagify = new Tagify(input, {
                pattern: /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/,
                delimiters: ", ", // add multiple delimiters if needed
                enforceWhitelist: false,
                dropdown: {
                    enabled: 1 // show suggestions after 1 typed character
                }
            });

            $('#toggleRepeat').change(function() {
                if ($(this).is(':checked')) {
                    $('#repeatCountDiv').show();
                } else {
                    $('#repeatCountDiv').hide();
                    $('#repeatCount').val('');
                }
            });

            setTimeout(function() {
                $(".alert-success").fadeOut("slow");
            }, 3000);

            $('#emailForm').submit(function(e) {
                var isValid = true;
                $('#emailError, #subjectError, #bodyError').hide();

                if (!tagify.value.length) {
                    $('#emailError').show();
                    isValid = false;
                }

                if (!$('#subject').val().trim()) {
                    $('#subjectError').show();
                    isValid = false;
                }

                if (!$('#body').val().trim()) {
                    $('#bodyError').show();
                    isValid = false;
                }

                if (!isValid) {
                    e.preventDefault();
                }
            });

            $('#saveCredentials').click(function() {
                var smtpServer = $('#smtpServer').val().trim();
                var smtpPort = $('#smtpPort').val().trim();
                var smtpUser = $('#smtpUser').val().trim();
                var smtpPassword = $('#smtpPassword').val().trim();

                if (smtpServer && smtpPort && smtpUser && smtpPassword) {
                    $.ajax({
                        url: `{{ url('save-credentials') }}`,
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            smtpServer: smtpServer,
                            smtpPort: smtpPort,
                            smtpUser: smtpUser,
                            smtpPassword: smtpPassword
                        },
                        success: function(response) {
                            if (response.success) {
                                $('#setCredentialModal').modal('hide');
                            } else {
                                alert('Failed to save credentials.');
                            }
                        },
                        error: function() {
                            alert('Error saving credentials.');
                        }
                    });
                } else {
                    alert('Please fill in all fields.');
                }
            });

        });
    </script>
</body>

</html>
