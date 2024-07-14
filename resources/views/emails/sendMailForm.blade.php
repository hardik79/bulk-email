<!DOCTYPE html>
<html>
<head>
    <title>Send Email</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tagify/4.3.0/tagify.min.css" />
</head>
<body>
<div class="container mt-5">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <h4>Send Email</h4>

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
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="toggleRepeat">
                        <label class="form-check-label" for="toggleRepeat">
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
                <button type="submit" class="btn btn-primary">Send Email</button>
            </form>
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
    });
</script>
</body>
</html>
