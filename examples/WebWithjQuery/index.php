<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.2/css/bootstrap.min.css" integrity="sha256-0xvvRQ7me2T5twv99B/k4AxlQ4cFzB+7SOpgJtOl1pc=" crossorigin="anonymous" />
        <style>
body {padding-top: 20px;}
#code {height: 500px;}
        </style>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.2.8/ace.js" integrity="sha256-198+Grx89n2ofVwo1LWnNTXxIQZIPZJURv+K73cJ93U=" crossorigin="anonymous"></script>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <button class="btn btn-primary" id="btn_run">Run</button>
                </div>
            </div>
            <hr/>
            <div class="row">
                <div class="col-md-6">
                <div id=code>LEN = 25
for i in range(LEN):
    print(" "*(LEN-i) + "*" * (2*i + 1))</div>
                </div>
                <div class="col-md-6">
                    <h4>Result</h4>
                    <pre id=result></pre>
                    <p id=error></p>
                </div>
            </div>
            <hr/>
            <div class="row">
                <div class="col-lg-12">
                    <h4>Kernel</h4>
                    <input type=text readonly id=kernelId />
                </div>
                <div class="col-lg-12">
                    <h4>Status</h4>
                    <p id=status></p>
                </div>
            </div>
        </div>

    </body>
    <!-- Mainly scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.2/js/bootstrap.min.js" integrity="sha256-yO7sg/6L9lXu7aKRRm0mh3BDbd5OPkBBaoXQXTiT6JI=" crossorigin="anonymous"></script>
    <script src="js/runner.js"></script>
    </script>
</html>
