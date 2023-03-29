<?php 
include ('header.php'); 
$date = $_GET['time'];
$question_id = $_GET['question_id'];
?>
<!-- Alert container -->
<div class="container-fluid px-5 w-100">
    <div class="btn p-2 text-light d-flex justify-content-center">
        <span href="#result" style="text-decoration: none;"></span>
        <div class="alert"><i class="fa-solid mr-2 icon"></i></div>
    </div>
</div>
<!-- Questions and Answers -->
<p class="question mx-5">Lorem ipsum</p>
<div class="mt-2 ml-5" style="pointer-events: none;">
    <div class="mt-3 answer_block" ></div>
</div>
<!-- Explanation -->
<div class="px-5" id="result" style="margin-bottom: 100px;">
    <h5 class="border-bottom pb-2 pt-2">Explanation : </h5>
    <div class="explanation" ></div>
</div>
<!-- side panel-->
<div id="local-navbar" class="local-navbar card card-body bg-light" style="overflow-y: scroll;">
    <h2 class="text-center pb-2" style="font-family: 'Pacifico', cursive;">Question side panel</h2>
</div>
 <!-- Footer -->
 <div class="fixed-bottom py-2 ml-auto d-flex justify-content-center" style="z-index: 10; bottom: 10px; width:650px;background-color:#c7c7c7;border-radius:25px">
    <button  class="btn-dark btn mx-3 px-4 " id="slide-button"> List</button>
    <a class="a_previous"><button id='prev' class="btn btn-outline-info mx-3 px-4 Previous"> Prev</button></a>
    <span class="pt-2"><span class="currentPage"><?php echo $question_id+1 ?></span> of 11</span>
    <a class="a_next"><button id='next' class="btn btn-outline-info mx-3 px-4 next"> Next</button></a>
    <a href="result.php?time=<?php echo $date ?>" class="btn-danger btn mx-3 px-4"> Result Page</a>
</div>
<script>
    // Hide side list when click outside
    window.addEventListener('click', function(e){   
        let _opened = $('#local-navbar').hasClass('show');
        if (_opened===true && !document.getElementById('local-navbar').contains(e.target) && !document.getElementById('slide-button').contains(e.target)){
            $('#local-navbar').toggleClass('show')
        } 
    });
    // Side panel toggle
    $(document).ready(function() {
      $("#slide-button").click(
          function() {
              $('#local-navbar').toggleClass("show");
          }
      );
    });
    // Fetch Questions
    jsindex = <?php echo $question_id ?>;
    $.getJSON('question.json', function(data) {
        // Enable and disable pagination
        if(jsindex==0)
            {
                $('.Previous').prop("disabled",true);
            }
            else if(jsindex==data.length-1)
            {
                $('.next').prop("disabled",true);
            }
            else{
                $('.Previous').prop("disabled",false);
                $('.next').prop("disabled",false);
            }

        // Side panel fetch
        let listdata = ``;
        for (let i = 0; i < data.length; i++) {
            let sideQuestion = data[i].snippet;
            if(i==jsindex)
            {
                listdata += `<a class="slideLink"  style="text-decoration: none; color:black"><div class="w-100 side_list shadow-sm border p-2 mb-1 py-3 text-primary" val="${i}" style="cursor:pointer">${i+1+") "}${sideQuestion}</div></a>`;
            }
            else{
                listdata += `<a class="slideLink"  style="text-decoration: none; color:black"><div class="w-100 side_list shadow-sm border p-2 mb-1 py-3" val="${i}" style="cursor:pointer">${i+1+") "}${sideQuestion}</div></a>`;
            }
        }
        $('#local-navbar').append(listdata);
        // Fetch questions and answers
        var questionAnswers = JSON.parse(data[jsindex].content_text);
        $('.question').text(jsindex+1+") "+questionAnswers.question);
        $('.explanation').html(questionAnswers.explanation);
        let answer = ``;
        for (let i = 0; i < questionAnswers['answers'].length; i++) {
            answer += `
                <label class="w-auto ml-2 d-flex answer_block">
                <input type="radio" name="click" class="mylabel">
                <span class="ml-3 ans_option">${questionAnswers['answers'][i]['answer']}</span>
                </label>`;
            $('.answer_block').html(answer)
        }
        // Check is correct
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
        // status alert
        var storedArray = JSON.parse(sessionStorage.getItem("items"));
        var optionInd = JSON.parse(sessionStorage.getItem("optionInd"));
        if (correct_answers[jsindex] == storedArray[jsindex]) {
            $('.alert').append("correct");
            $('.icon').addClass("fa-check");
            $('.alert').addClass("alert-success");
        } else if (null == storedArray[jsindex]) {
            $('.alert').append("unattempted");
            $('.icon').addClass("fa-eye-slash");
            $('.alert').addClass("alert-secondary");
        } else {
            $('.alert').append("incorrect");
            $('.icon').addClass("fa-xmark");
            $('.alert').addClass("alert-danger");
        }
        // Options Highlight
        let optind = document.querySelectorAll('.mylabel');
        let ans_option = document.querySelectorAll('.ans_option');
        for (let i = 0; i < 4; i++) {
            if (optionInd[jsindex] == i) {
                optind[i].setAttribute('checked', true);
                if(optionInd[jsindex] == correct_index[jsindex]){
                    ans_option[i].classList.add('text-success');
                }
                else{
                    ans_option[i].classList.add('text-danger');
                    let correct_ind = correct_index[jsindex];
                    ans_option[correct_ind].classList.add('text-success'); 
                }
            }
        }
        // Footer functionality
        let tabindex = jsindex;
        console.log("jsindex : "+jsindex);
        console.log("tabindex : "+tabindex);
        if (tabindex == 0) {
            $('.currentPage').text(jsindex + 1);
        }
        $(".side_list").click(function(e) {
            index = $(e.target).attr('val');
            tabindex = index;
            $('.slideLink').attr('href', `review.php?question_id=${index}&time=<?php echo $date ?>`)
            colChange(tabindex);
            console.log(index)
        })
        $('.totalPage').text(data.length);
        $('.Previous').click(function() {
            if (tabindex > 0) {
                tabindex--;
                $('.currentPage').text(tabindex + 1);
                $('.a_previous').attr('href',`review.php?question_id=${tabindex}&time=<?php echo $date ?>`);
            colChange(tabindex);
            }
        })
        $('.next').click(function() {
            if (tabindex < data.length - 1) {
                tabindex++;
                $('.currentPage').text(tabindex + 1);
                $('.a_next').attr('href',`review.php?question_id=${tabindex}&time=<?php echo $date ?>`);
            colChange(tabindex);
            }
        })
    });
</script>
</body>
</html>