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

function sendCode(code, kernelId) {
  var data = {
    "code": code,
    "kernelId": kernelId,
    "cont": (continuation ? 1:0)
  };
  $.post( "run.php", data, function( json ) {
    var items = [];
    if (json.code != 200) {
      setStatus("Error - " + json.code);
    }
    if (json.result.status) {
        if (json.result.status == "continued") {
            continuation = true;
            setTimeout(() => sendCode(), 1);
        } else if (json.result.status == "waiting-input") {
            continuation = true;
        } else {
            continuation = false;
            setStatus("Done");
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
    if (json.code != 200) {
      setStatus("Error - " + json.code);
    }
    if (json.kernelId) {
      $("#kernelId").val(json.kernelId);
    }
  });
}

$('#btn_run').click(function(e) {
  $("#result").html("");
  setStatus("KernelCheck");
  var code = "";
  var kernelId = $("#kernelId").val();
  console.log("EEE");
  if(kernelId == "") {
    setStatus("RequestKernel");
    createKernel();
    kernelId = $("#kernelId").val();
  }

  if (!continuation) {
    code = editor.getValue();
  }
  setStatus("Running...");
  sendCode(code, kernelId);
});

