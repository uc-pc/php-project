<?php include ('header.php') ?>

<?php
$date = $_GET['time'];
?>
<!-- Head Content -->
<div class="p-5 text-center">
    <h3 class="border-bottom pb-2">Exercise 1 : Fullstack Developer Training</h3>
    <h6><span class="text-success font-style-bold">Test Attend</span> On : <?php echo $date; ?></h6>
    <span class="btn border border-secondary p-1 px-4 bg-light "><span style="color:purple"><i class="fa-solid fa-square-poll-vertical"></i> <span class="result"></span></span><br><span>Result</span></span>
    <span class="btn border border-secondary p-1 px-4 bg-light "><span class="text-info"><i class="fa-solid fa-list"></i> <span class="items"></span></span><br><span> Items</span></span>
    <span class="btn border border-secondary p-1 px-4 bg-light "><span class="text-success"><i class="fa-solid fa-check"></i> <span class="correctcount"></span></span><br><span>Correct</span></span>
    <span class="btn border border-secondary p-1 px-4 bg-light "><span class="text-danger"><i class="fa-solid fa-xmark"></i> <span class="incorrectcount"></span></span><br><span>Incorrect</span></span>
    <span class="btn border border-secondary p-1 px-4 bg-light "><span class="text-warning"><i class="fa-solid fa-eye-slash"></i><span class="unattempt"></span></span><br><span>Unattempted</span></span>
</div>
<!-- Score Table -->
<div class="card" style="margin: 20px 20px 80px 20px; border-radius:20px; padding:30px">
    <table class="table table-striped shadow">
        <thead class="thead-dark" >
            <tr>
                <th>Sr. No.</th>
                <th>Questions Snippets</th>
                <th><span class="ans">A</span><span class="ans">B</span><span class="ans">C</span><span class="ans">D</span></th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

    <!-- Footer -->
 <div class="fixed-bottom bg-light rounded border  py-2 ml-auto border border-secondary d-flex justify-content-center" style="z-index: 10; bottom:10px; width:200px">
    <a href="index.php" id="home" class="btn-danger btn mx-3 px-4">Index Page</a>
</div>
</div>
<div></div>
    <!-- <script src="jquery.js"></script> -->
    <script>
        // $(document).ready(function(){

        var resultdata = JSON.parse(sessionStorage.getItem("resultdata"));
        var storedArray = JSON.parse(sessionStorage.getItem("items"));

        var optionInd = JSON.parse(sessionStorage.getItem("optionInd"));


        let correct = storedArray.filter(function(value) {

            if (value == 1) {
                return value;
            }

        })
        console.log(resultdata);




        $(".correctcount").append(correct.length);


        let result = (correct.length * 100) / 11;
        $(".result").append(result.toFixed(2) + "%");

        $(".incorrectcount").append((resultdata[1]) - correct.length);
        $(".items").append(resultdata[0]);
        $(".unattempt").append(resultdata[0] - resultdata[1]);




        // console.log(resultdata);
        // })
        $.getJSON('question.json', function(data) {

            let tabledata = ``;



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

            // console.log(correct_index);



            for (let i = 0; i < data.length; i++) {

                var questionAnswers = data[i].snippet;


                tabledata += `
                        <tr class="datalist">
                            <td>${i+1}</td>
                            <td><a href="review.php?question_id=${i}&time=<?php echo $date ?>" style="text-decoration: none; color:black">${questionAnswers}</a></td>
                            <td class="list">`;
                            console.log(optionInd[i] +"=="+ correct_index[i])
                            for (let j = 0; j < 4; j++) {
                                if(correct_index[i]==j)
                                {
                                    if(optionInd[i] == null)
                                    {
                                        tabledata += `<div class="ans"><i class="fa-sharp fa-solid fa-circle"></i></div>`;
                                    }
                                    else{
                                        tabledata += `<div class="ans correct"><i class="fa-sharp fa-solid fa-circle"></i></div>`;
                                    }
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
            var optiondata = JSON.parse(sessionStorage.getItem("optionInd"));
        });

        $("#home").click(function(){
            sessionStorage.clear();
        });
    </script>












<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>



</body>

</html>