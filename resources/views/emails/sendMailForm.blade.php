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

        /* Custom CSS for Tagify input */
        .tagify__input {
            min-height: 38px;
        }

        .tagify {
            width: 100%;
            min-height: 38px;
            max-height: 150px;
            overflow-y: auto;
            resize: vertical;
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
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                        @endif

                        <form action="{{ url('send-mail') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="email">Email addresses:</label>
                                <input type="text" class="form-control" id="email" name="email" required>
                                @if ($errors->has('email'))
                                <span class="text-danger">{{ $errors->first('email') }}</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="subject">Subject:</label>
                                <input type="text" class="form-control" id="subject" name="subject" required>
                                @if ($errors->has('subject'))
                                <span class="text-danger">{{ $errors->first('subject') }}</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="body">Body:</label>
                                <textarea class="form-control" id="body" name="body" rows="4" required></textarea>
                                @if ($errors->has('body'))
                                <span class="text-danger">{{ $errors->first('body') }}</span>
                                @endif
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
                                @if ($errors->has('repeatCount'))
                                <span class="text-danger">{{ $errors->first('repeatCount') }}</span>
                                @endif
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Send Email</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tagify/4.3.0/tagify.min.js"></script>
    <script>
        $(document).ready(function() {
            var input = document.querySelector('#email');
            new Tagify(input, {
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
            }, 5000);
        });
    </script>
</body>

</html>
