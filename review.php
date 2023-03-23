<?php include ('header.php') ?>
<?php
$date = $_GET['time'];
?>
<!-- Alert container -->
<div class="container-fluid px-5 w-100">
    <div class="btn p-4 text-light d-flex justify-content-center">
        <span href="#result" style="text-decoration: none;"></span>
        <div class="alert"><i class="fa-solid mr-2 "></i></div>
    </div>
</div>

<!-- Questions and Answers -->
<p class="question mx-5">Lorem ipsum</p>
<div class="mt-4 ml-5">
    <div class="mt-3 answer_block">
    </div>
</div>


 <!-- Footer -->
 <div class="fixed-bottom bg-light rounded border  py-2 ml-auto border border-secondary d-flex justify-content-center" style="z-index: 10; bottom:10px; width:200px">
    <a href="result.php?time=<?php echo $date ?>" class="btn-danger btn mx-3 px-4">Result Page</a>
</div>



<!-- Explanation -->
<div class="px-5" id="result" style="margin-bottom: 100px;">
    <h5 class="border-bottom pb-2 pt-5">Explanation : </h5>
    <div class="explanation" ></div>
</div>


<script>
    var queries = {};
    $.each(document.location.search.substr(1).split('&'), function(c, q) {
        var i = q.split('=');
        queries[i[0].toString()] = i[1].toString();
    });
    jsindex = Number(queries.question_id);
    // Fetch Questions
    $.getJSON('question.json', function(data) {

        var questionAnswers = JSON.parse(data[jsindex].content_text);
        $('.question').text(questionAnswers.question);
        $('.explanation').text(questionAnswers.explanation);
        let answer = ``;
        for (let i = 0; i < questionAnswers['answers'].length; i++) {
            answer += `
                <label class=" w-25 ml-2 d-flex answer_block">
                <input type="radio" name="click" class="mylabel">
                <div class="ml-3 ans_option">${questionAnswers['answers'][i]['answer']}</div>
            </label>
                `;
            $('.answer_block').html(answer)
        }



        var correct_answers = [];
        var correct_index = [];
        for (var i = 0; i < data.length; i++) {
            questionAnswers = JSON.parse(data[i].content_text);


            for (var j = 0; j < questionAnswers.answers.length; j++) {
                if (questionAnswers.answers[j].is_correct == 1) {

                    // console.log(questionAnswers.answers[j].answer);
                    correct_answers.push(questionAnswers.answers[j].is_correct);
                    correct_index.push(j);
                }
            }
        }

        // console.log(correct_answers)

        var storedArray = JSON.parse(sessionStorage.getItem("items"));
        var optionInd = JSON.parse(sessionStorage.getItem("optionInd"));
        // console.log(optionInd)


        if (correct_answers[jsindex] == storedArray[jsindex]) {
            $('.alert').append("correct");
            $('.fa-solid').addClass("fa-check");
            $('.alert').addClass("alert-success");
        } else if (null == storedArray[jsindex]) {
            $('.alert').append("unattempted");
            $('.fa-solid').addClass("fa-eye-slash");
            $('.alert').addClass("alert-secondary");
        } else {
            $('.alert').append("incorrect");
            $('.fa-solid').addClass("fa-xmark");
            $('.alert').addClass("alert-danger");
        }



        let optind = document.querySelectorAll('.mylabel');
        let ans_option = document.querySelectorAll('.ans_option');


        console.log("optionInd : "+optionInd);
        console.log(correct_index);


        for (let i = 0; i < 4; i++) {
            if (optionInd[jsindex] == i) {

                optind[i].setAttribute('checked', true);
                // console.log(i);
                if(optionInd[jsindex] == correct_index[jsindex]){
                    ans_option[i].classList.add('text-success');
                    // Optind[i].setAttribute('checked', false);
                }
                else{
                    ans_option[i].classList.add('text-danger');


                    let correct_ind = correct_index[jsindex];

                    ans_option[correct_ind].classList.add('text-success');

                    
                }
            }
        }


    });
</script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>



</body>

</html>