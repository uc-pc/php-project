<?php 
include ('header.php');
$date = $_GET['time'];
?>
<body>
<!-- Head Content -->
<div class="p-2 text-center">
    <h3 class="border-bottom pb-2">Exercise 1 : Fullstack Developer Training</h3>
    <h6><span class="text-success font-style-bold">Test Attend</span> On : <?php echo $date; ?></h6>
    <span class="btn border border-secondary p-1 px-4 bg-light "><span style="color:purple"><i class="fa-solid fa-square-poll-vertical"></i> <span class="result"></span></span><br><span>Result</span></span>
    <span class="btn border border-secondary p-1 px-4 bg-light "><span class="text-info"><i class="fa-solid fa-list"></i> <span class="items"></span></span><br><span> Items</span></span>
    <span class="btn border border-secondary p-1 px-4 bg-light "><span class="text-success"><i class="fa-solid fa-check"></i> <span class="correctcount"></span></span><br><span>Correct</span></span>
    <span class="btn border border-secondary p-1 px-4 bg-light "><span class="text-danger"><i class="fa-solid fa-xmark"></i> <span class="incorrectcount"></span></span><br><span>Incorrect</span></span>
    <span class="btn border border-secondary p-1 px-4 bg-light "><span class="text-warning"><i class="fa-solid fa-eye-slash"></i><span class="unattempt"></span></span><br><span>Unattempted</span></span>
</div>
<!-- Score Table -->
<div class="card" style="margin: 0px 20px 80px 20px; border-radius:20px; padding:20px">
    <table class="table table-striped shadow">
        <thead class="thead-dark" >
            <tr>
                <th>Sr. No.</th>
                <th>Questions Snippets</th>
                <th>Status</th>
                <th><span class="ans">A</span><span class="ans">B</span><span class="ans">C</span><span class="ans">D</span></th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
<script>
    var resultdata = JSON.parse(sessionStorage.getItem("resultdata"));
    var storedArray = JSON.parse(sessionStorage.getItem("items"));
    var optionInd = JSON.parse(sessionStorage.getItem("optionInd"));
    let correct = storedArray.filter(function(value) {
        if (value == 1) {
            return value;
        }
    })
    // Calculations

    $(".correctcount").append(correct.length);
    let result = (correct.length * 100) / 11;
    $(".result").append(result.toFixed(2) + "%");
    $(".incorrectcount").append((resultdata[1]) - correct.length);
    $(".items").append(resultdata[0]);
    $(".unattempt").append(resultdata[0] - resultdata[1]);

    // sessionStorage.setItem("attempt", "Smith");

    // Fetch file
    $.getJSON('question.json', function(data) {
        let tabledata = ``;
        var correct_answers = [];
        var correct_index = [];
        for (var i = 0; i < data.length; i++) {
            questionAnswers = JSON.parse(data[i].content_text);
            for (var j = 0; j < questionAnswers.answers.length; j++) {
                if (questionAnswers.answers[j].is_correct == 1) {
                    correct_answers.push(questionAnswers.answers[j].is_correct);
                    correct_index.push(j);
                }
            }
        }
        // Fetch Questions snippet
        for (let i = 0; i < data.length; i++) {
            var questionAnswers = data[i].snippet;
            tabledata += `<tr class="datalist">
                        <td>${i+1}</td>
                        <td><a href="review.php?question_id=${i}&time=<?php echo $date ?>" style="text-decoration: none; color:black">${questionAnswers}</a></td>
                        <td class="status">
                            <div class="statusBox">
                                <i class="fa-solid mr-1 result-icon"></i>
                            </div>
                        </td>
                        <td class="list">`;
                for (let j = 0; j < 4; j++) {
                    if(correct_index[i]==j)
                    {
                            tabledata += `<div class="ans correct"><i class="fa-sharp fa-solid fa-circle"></i></div>`;
                    }
                    else{
                        if(optionInd[i] == j)
                        {
                            tabledata += `<div class="ans incorrect"><i class="fa-sharp fa-solid fa-circle"></i></div>`;
                        }
                        else{
                            tabledata += `<div class="ans"><i class="fa-sharp fa-solid fa-circle"></i></div>`;
                        }
                    }
                }
            tabledata +=`</td> </tr>`;
            }
            $('tbody').html(tabledata);
            // status column column
            let status = document.querySelectorAll(".statusBox");
            let resulticon = document.querySelectorAll(".result-icon");
            for (let i = 0; i < data.length; i++) {
                if (correct_answers[i] == storedArray[i]) {
                    status[i].append("Correct");
                    status[i].classList.add("correct");
                    resulticon[i].classList.add("fa-check")
                } else if (null == storedArray[i]) {
                    status[i].append("Unattempted");
                    status[i].classList.add("unattempted");
                    resulticon[i].classList.add("fa-eye-slash")
                } else {
                    status[i].append("Incorrect");
                    status[i].classList.add("incorrect");
                    resulticon[i].classList.add("fa-xmark")
                }
            }
        var optiondata = JSON.parse(sessionStorage.getItem("optionInd"));
    });
    $(".img").click(function(){
        sessionStorage.clear();
    });
</script>
</body>
</html>