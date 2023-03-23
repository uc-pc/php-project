<?php include ('header.php') ?>
<?php 
date_default_timezone_set("Asia/kolkata"); 
$date = date("d-M-y h:i A");
?>


<div class="container-fluid px-5">
    <p class="mt-5 question mx-5" style="font-size: 20px; font-weight:500"></p>


    <div class="mt-5 ml-5">
        <div class="mt-3 answer_block">
        </div>
    </div>
    <!-- side panel -->
    <div id="local-navbar" class="local-navbar card card-body bg-light">
      <h2 class="text-center pb-2" style="font-family: 'Pacifico', cursive;">Question side panel</h2>
    </div>
</div>

 <!-- Footer -->
 <div class="fixed-bottom bg-light rounded border py-2 ml-auto border border-secondary d-flex justify-content-center" style="z-index: 10; bottom: 10px; width:750px">
      <span class="time_counter bg-transparent border-0 font-weight-bold mr-4 d-flex align-items-center" id="timer"></span>
      <button  class="btn-dark btn mx-3 px-4 " id="slide-button"><i class="fa-solid fa-list"></i> List</button>
      <button id='prev' class="btn btn-outline-info mx-3 px-4 Previous"><i class="fa-solid fa-circle-left"></i> Prev</button>
      <span class="pt-2"><span class="currentPage">1</span> of 11</span>
      <button id='next' class="btn btn-outline-info mx-3 px-4 next"><i class="fa-solid fa-circle-right"></i> Next</button>
      <button class="btn-danger btn mx-3 px-4 endtest" id="end-btn" data-toggle="modal" data-target="#mymodal"><i class="fa-solid fa-flag-checkered"></i> End Test</button>
    </div>


<!-- Modal -->
<div class="modal fade" id="mymodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Confirmation</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center">
        This action will end your test. Do you want to proceed?
        <button class="btn"><span class="font-weight-bold items"><i class="fa-solid fa-list"></i></span></button>
        <button class="btn"><span class="font-weight-bold attempt"></span></button>
        <button class="btn"><span class="font-weight-bold unattempt"></span></button>
      </div>
      <div class="modal-footer">
        <a href="index.php"><button type="button" class="btn btn-secondary">Home Page</button></a>
        <a href="result.php?time=<?php echo $date ?>"><button type="button" class="btn btn-danger proceed">End Test</button></a>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>

<script>
  
  // Side Panel enable or disable
    $(document).ready(function() {
      $("#slide-button").click(
          function() {
              $('#local-navbar').toggleClass("show");
          }
      )

      $(".side_list").click(
          function() {
              $('#local-navbar').removeClass("show");
          }
      )

    // Timer
    var minutes = 2; // set the minutes
    var seconds = 00; // set the seconds
    var countdown = setInterval(function() {
    if (seconds == 0) {
        minutes--;
        seconds = 59;
    } else {
        seconds--;
    }
    $('.time_counter').html(minutes + "m " + seconds + "s");
    if (minutes == 0 && seconds == 0) {
        clearInterval(countdown);
        $(".endtest").click();
    }
    }, 1000);


    let optionData =  $(".mylabel");


    });

    $.getJSON('question.json', function(data) {

      // Fetch Side Questions
        let listdata = ``;
        for (let i = 0; i < data.length; i++) {
            var sideQuestion = data[i].snippet;
            listdata += `<div class="w-100 side_list border-bottom py-3 shadow-sm p-3 mb-2" val="${i}" style="cursor:pointer;border:1px solid #cfbfbf">${i+1+") "}${sideQuestion}</div>`;
        }
        $('#local-navbar').append(listdata);







        let arr = [];
        let arr2 = [];

        function loadoption(ind = 0) {
          // Fetch Question and answers
            var questionAnswers = JSON.parse(data[ind].content_text);
            $('.question').text(questionAnswers.question);
            let answer = ``;
            for (let i = 0; i < questionAnswers['answers'].length; i++) {
                answer += `
                <label class="w-75 ml-2 d-flex answer_block">
                <input type="radio" name="click" class="mylabel" questionId="${data[ind].content_id}" value="${questionAnswers['answers'][i]['is_correct']}" option="${i}">
                <div class="ml-3 ans_option">${questionAnswers['answers'][i]['answer']}</div>
                </label>
                `;
                $('.answer_block').html(answer)
            }

            $(".mylabel").click(function(e) {
                arr[ind] = $(e.target).attr('value');
                arr2[ind] = $(e.target).attr('option');
            })



            window.sessionStorage.setItem("optionInd", JSON.stringify(arr2));

                let retString = sessionStorage.getItem("optionInd")
                let retArray = JSON.parse(retString)
                let optind = document.querySelectorAll('.mylabel')
                for (let i = 0; i < 4; i++) {
                    if(retArray[ind]==i)
                    {
                        optind[i].setAttribute('checked', true);
                    }
                }

            


            sessionStorage.setItem("items", JSON.stringify(arr));


            $(".endtest").click(function() {

                window.sessionStorage.setItem("optionInd", JSON.stringify(arr2));
                
                
                let items = `<i class="fa-solid fa-list"></i> ${data.length} Items</span>`;
                $('.items').html(items);

                window.sessionStorage.setItem("items", JSON.stringify(arr));

                var storedArray = JSON.parse(sessionStorage.getItem("items"));


    
                let Attemped = storedArray.filter(function(value) {
    
                    if (value != null) {
                        return value;
                    }
    
                })



                let attemp = `<span class="font-weight-bold"><i class="fa-solid fa-eye"></i></i> ${Attemped.length} Attempted</span>`;

                $('.attempt').html(attemp);
                
                let unattemp = `<span class="font-weight-bold"><i class="fa-solid fa-eye-slash"></i></i> ${data.length-Attemped.length} Unattempted</span>`;

                $('.unattempt').html(unattemp);

                let resultdata = [];
                resultdata.push(data.length);
                resultdata.push(Attemped.length);
                resultdata.push($(".time").attr("value"));


                $(".proceed").click(function()
                {
                    window.sessionStorage.setItem("resultdata", JSON.stringify(resultdata));
                })

            })

        }

        let tabindex = 0;
        if (tabindex == 0) {
            loadoption();
            $('.currentPage').text(tabindex + 1);

        }

        $(".side_list").click(function(e) {
                index = $(e.target).attr('val');
                // console.log(typeof);
                loadoption(index);     
                tabindex=index           
                $(".currentPage").text(parseInt(index)+1);
                
            })

        $('.totalPage').text(data.length);

        $('.Previous').click(function() {
            if (tabindex > 0) {
                tabindex--;
                loadoption(tabindex);
                $('.currentPage').text(tabindex + 1);
            }
        })

        $('.next').click(function() {
            if (tabindex < data.length - 1) {
                tabindex++;
                loadoption(tabindex);
                $('.currentPage').text(tabindex + 1);
            }
        })



    });
</script>






<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>


</body>

</html>