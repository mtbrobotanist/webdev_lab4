<?php

function checkInput()
{
    if(!$_SESSION['username'])
    {
        header("Location: login.php");
    }
    
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
                <label id = "to">To:</label> <input type="text"></br>
                <label id = "subject">Subject:</label> <input type="text"></br>
                <label id = "body">Body:</label> <textarea></textarea></br>
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