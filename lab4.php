<?php

//session_start();

require_once 'db.php';

$db;
$page_num = 1;

if (!isset($_SESSION['username']))
{
   header("Location: login.php");
}

if(isset($_POST['page'])) // 2
{
    $page_num = $_POST['page'];
}



function checkInput()
{
   
    if(!isset($_POST['to']))
    {
        die("User did not enter 'To:' field");
    }

    if(!isset($_POST['body']))
    {
        die("User did enter 'Body:' field");
    }
    if(!isset($_POST['month']) || $_POST['month'] == "")
    {
        die("User did enter 'month:' field");
    }
    if(!isset($_POST['day']) || $_POST['day'] == "")
    {
        die("User did enter 'day:' field");
    }
    if(!isset($_POST['year']) || $_POST['year'] == "")
    {
        die("User did enter 'year:' field");
    }
    if(!isset($_POST['time']) || $_POST['time'] == "")
    {
        die("User did enter 'time:' field");
    }
    
    $user_name = $_SESSION['username'];
    if(preg_match('/\s/',$user_name))
    {
        die('username can not contain white space');
    }
    $to = $_POST['to'];
    if(preg_match('/\s/',$to))
    {
        die("'To:' field can not contain whitespace");
    }
    if(!preg_match('/\@/',$to))
    {
        die("'To:' field must contain an '@' symbol");
    }
    
    //$subject = $_POST['subject'];
    $body = $_POST['body'];
    $month = monthToInt($_POST['month']);
    $day = $_POST['day'];
    $year = $_POST['year'];
    $time = $_POST['time'];
    
    $date_time = $year . "-" . $month . "-" . $day . " " . $time . ":00";
    
    $db = openDB();
    if(!$db)
    {
        die("could not open database");
    }
    //username is a unique field in the database along with artifical primary key user_id
    $stmt = $db->prepare("SELECT user_id FROM users WHERE username = '$user_name'");
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
   
    $user_id = $row['user_id'];
    if(!$user_id)
    {
        $db = null;
        die("no such user id or user name in database");
    }
    
    $stmt = $db->prepare("INSERT INTO emails (user_id, email, message, date_time, sent)"
                                  . "VALUES ($user_id, '$to',   '$body', '$date_time', 0)");
    $stmt->execute();
    
    echo "<h3>submission successful </h3>";
}

function monthToInt($month){
    if ($month == "Jan"){
        return 1;
    }else if ($month == "Feb"){
        return 2;
    }else if ($month == "Mar"){
        return 3;
    }else if ($month == "Apr"){
        return 4;
    }else if ($month == "May"){
        return 5;
    }else if ($month == "Jun"){
        return 6;
    }else if ($month == "Jul"){
        return 7;
    }else if ($month == "Aug"){
        return 8;
    }else if ($month == "Sep"){
        return 9;
    }else if ($month == "Oct"){
        return 10;
    }else if ($month == "Nov"){
        return 11;
    }
    else{
        return 12;
    }
}


if($page_num == 2)
{
    checkInput();
}

?>


<html>
    <head>
        <script type = "text/javascript">
            
            var month_selector;
            var day_selector;
            var year_selector;
            var time_selector;
            var months = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
            var days_per_month = [31,28,31,30,31,30,31,31,30,31,30,31];
            
            function displayDays()
            {
                var cur_month = month_selector.value;
                if(cur_month == null)
                    return;
                    
                var i;
                for (i = 0; i < months.length; i++)
                {
                    if(cur_month === months[i])
                        break;
                }
                
                
                for(var j = 0; j < days_per_month[i]; j++)
                {
                    day_selector.innerHTML += "<option>" + (j+1) + "</option>";
                }
                
            }
            
            function init()
            {
                month_selector = document.getElementById("month");
                day_selector = document.getElementById("day");
                year_selector = document.getElementById("year");
                time_selector = document.getElementById("time");
                
                // populate month drop down selector
                for(var i = 0; i < months.length; i++)
                {
                    month_selector.innerHTML += "<option>" + months[i] + "</option>";
                }
                
                // dont show days unless month is selected, happens in displayDays
                
                var year = new Date().getFullYear();
                //populate year selector
                for(var i = 0; i < 20; i++){
                    year_selector.innerHTML += "<option>"+ (year+i) +"</option>"
                }
                
                var hour = 0;
                var minute = "00";
                for(var i = 0; i < 47; i++)
                {   
                    var str_hour = (hour < 10)? "0" + hour : hour;
                    
                    var time_option = "<option><time>"
                            + str_hour + ":" + minute 
                            + "</time></option>" ;

                    time_selector.innerHTML += time_option;

                    if(i % 2 === 0)
                    {   //increment hour
                        hour++; 
                        minute = "00";
                    }
                    else
                    {   //half hour
                        minute = "30"; 
                    }
                }
                
                month_selector.addEventListener("change",displayDays);
            }
            
            window.addEventListener("load",init);
            
        </script>
    </head>
    
    <body>
        
        <div>
            <form id="the_form" method="post" action = "">
                <input type = "hidden" name = "page" value = "2">
                <label id = "to">To:</label> <input name = "to" type="text"></br>
                <label id = "body">Body:</label> <textarea name = "body"></textarea></br>
                <label id = "when_to_send">When to Send:</label></br>
                <label>month</label>
                    <select id = "month" name ="month" >
                        <option selected></option>
                    </select>
                <label>day</label>
                    <select id = "day" name = "day">
                        <option selected></option>
                    </select>
                <label>year</label>
                    <select id = "year" name = "year">
                        <option selected></option>
                    </select></br>
                <label>time</label>
                    <select id = "time" name = "time">
                        <option selected></option>
                    </select></br>
                <input type="submit" value="Submit">
            </form>  
        </div>
      
    </body>
    
</html>