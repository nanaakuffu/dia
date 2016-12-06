
function addscores(y, z) {
    var y_value;
    var z_value;
    if (isNaN(y.value)) {
        y_value = 0.0;
    } else {
        y_value = parseFloat(y.value);
    }

    if (isNaN(z.value)) {
        z_value = 0.0;
    } else {
        z_value = parseFloat(z.value);
    }
    return y_value + z_value;
}

function getscoreandgrade(ts, cs, es, gr, re) {
    var score = addscores(cs, es);
    var grade = '';
    var remark = '';

    if (score >= 90 && score <= 100) {
        grade = 'A';
        remark = 'Excellent';
    } else if (score >= 80 && score < 90) {
        grade = 'B';
        remark = 'Very Good';
    } else if (score >= 70 && score < 80) {
        grade = 'C';
        remark = 'Good';
    } else if (score >= 60 && score < 70) {
        grade = 'D';
        remark = 'Satisfactory';
    } else if (score >= 50 && score < 60) {
        grade = 'E';
        remark = 'Fair';
    } else {
        grade = 'F';
        remark = 'Fail';
    }
    ts.value = score;
    gr.value = grade;
    re.value = remark;
}

function getstudent(value) {
//    alert(value);
    $.ajax({
        type: "GET",
        url: "select_data.php",
        data: "choice="+value,
        success: function(data){
            $('#second_choice').html(data);
        }
    })
}

function verify_head() {
  if ( document.getElementById('head').checked == true) {
    document.getElementById('department').disabled = false;
  } else {
    document.getElementById('department').disabled = true;
  }
}

function verify_form() {
  if ( document.getElementById('form').checked == true) {
    document.getElementById('form_name').disabled = false;
  } else {
    document.getElementById('form_name').disabled = true;
  }
}
