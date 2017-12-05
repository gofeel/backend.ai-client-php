var editor = ace.edit("code");
editor.setTheme("ace/theme/monokai");
editor.getSession().setMode("ace/mode/python");
var continuation = false;

function setStatus(message) {
  if(message) {
    $("#status").html(message);
  }
}

function showResult(json) {
    var htmlOutput = '';
    $.each(json.result.console, function(k, c){
        if (c[0] == 'stdout') {
          htmlOutput = htmlOutput  + c[1];
        }
        if (c[0] == 'stderr') {
          htmlOutput = htmlOutput  + '<pre class="live-code-runner-error-message">'+c[1]+'</pre>';
        }
        if (c[0] == 'media') {
            if (c[1][0] === "image/svg+xml") {
                htmlOutput = htmlOutput + c[1][1];
            }
        }
    });
    htmlOutput = $("#result").html() + htmlOutput;
    $("#result").html(htmlOutput);
}

function sendCode(code, kernelId, runId) {
  var data = {
    "code": code,
    "kernelId": kernelId,
    "cont": (continuation ? 1:0),
    "runId": runId
  };
  $.post( "run.php", data, function( json ) {
    var items = [];
    if (json.result.status) {
        if (json.result.status == "continued") {
            continuation = true;
            setTimeout(() => sendCode(code, kernelId, runId), 1);
        } else if (json.result.status == "waiting-input") {
            continuation = true;
        } else {
            continuation = false;
            $.post( "destroy.php", data, function(j){
              $("#kernelId").val("");
              setStatus("Done");
            }, "json")
        }
    }
    if (json.result.console) {
      showResult(json);
    }
  }, 'json');
}

function createKernel(code) {
  var type = "python3";
  var data = {type: type};
  var res = $.ajax({
    url: "kernel.php",
    data:data,
    dataType:'json', 
    async: false
  }).done(
    function( json ) {
    var items = [];
    if (json.kernelId) {
      $("#kernelId").val(json.kernelId);
    }
  });
}

function makeRunId() {
  var text = "";
  var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

  for (var i = 0; i < 5; i++)
    text += possible.charAt(Math.floor(Math.random() * possible.length));

  return text;
}

$('#btn_run').click(function(e) {
  $("#result").html("");
  setStatus("KernelCheck");
  var code = "";
  var kernelId = $("#kernelId").val();
  var runId = makeRunId();
  if(kernelId == "") {
    setStatus("RequestKernel");
    createKernel();
    kernelId = $("#kernelId").val();
  }

  if (!continuation) {
    code = editor.getValue();
  }
  setStatus("Running...");
  sendCode(code, kernelId, runId);
});

