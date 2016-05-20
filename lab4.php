<!DOCTYPE html>
<?php
    require_once 'db.php';
    
    $conn = openDB();
    
    function newUser($user, $pass){        
      //if(preg_match("#.*^(?=.{8,20})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$#", $pass)){
            $hash = password_hash($pass, PASSWORD_DEFAULT);
        //} 

        $statement = $conn->prepare("INSERT INTO users(username, password) VALUES(:user, :pass)");
        $statement->bindParam(":user", $user);
        $statement->bindParam(":pass", $hash);
        $statement->execute();
        if($statement){
            echo "<div class='regform'><h3>Regristration completed!</h3></div>";
        }
        else{
            echo "<div class='regform'><h3>Invalid username or password</h3></div>";
        }
    }//end newUser()
    
    function signUp(){
        $user = $_POST['username'];
        $pass = $_POST['password'];
        
        if($user == ""){
            echo "<div class='regform'>Please enter a username!</div>";
        }
        else if($pass == ""){
            echo "<div class='regform'>Invalid Password!</div>";
        }
        else{
            $statement = $conn->prepare("SELECT username FROM users WHERE username=:user");
            $statement->execute(array(':user'=>$user));
            $rows = $statement->fetch(PDO::FETCH_ASSOC);
            
            if($rows['username'] == $user){
                echo "<div class'regform'>Username is already taken!</div>";
            }
            else{
                newUser($user, $pass);
            }
        }
    }//end signup()
    
    function login($user, $pass){
        $statement = $conn->prepare("SELECT * FROM users WHERE username=:user LIMIT 1");
        $statement->execute(array(':user'=>$user));
        $userRow = $statement->fetch(PDO::FETCH_ASSOC);
        //$sucessfull = $userRow['id'];
        if($statement->rowCount() > 0){
            if(password_verify($pass, $userRow['password'])){
                echo "Welcome $user";
                $_SESSION['username'] = $user;
                header("Location: http://www.facebook.com");
                return true;
            }
            else{
                echo "Wrong username or password!";
            }
        }
    }//end login();

    if(isset($_POST['register'])){
        signUp();
    }
    else if(isset($_POST['submit'])){
        $user = $_POST['username'];
        $pass = $_POST['password'];
        login($user, $pass);
    }
        
?>

<html>
    <head>
        <title>Lab4</title>
        <link rel="stylesheet" href="lab4.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
        
        <script type="text/javascript">
            
            function passwordStrength(pass){
                var score = 0;
                if(!pass){
                    return score;
                }
                
                //award every unique letter until 5 repititions
                var letter = new Object();
                for (var i = 0; i < pass.length; i++) {
                       letter[pass[i]] = (letter[pass[i]] || 0) + 1;
                       score += 5.0 / letter[pass[i]];
            }
            
               //bonus points for mixing it up
                var variations = {
                    digits: /\d/.test(pass),
                    lower: /[a-z]/.test(pass),
                    upper: /[A-Z]/.test(pass),
                    nonWords: /\W/.test(pass),
             }

                variationCount = 0;
                for (var check in variations) {
                     variationCount += (variations[check] == true) ? 1 : 0;
            }
                score += (variationCount - 1) * 10;

                 return parseInt(score);
            }//end passwordStength();
            
            
            function Strength(pass){
                var score = passwordStrength(pass);
                if(score > 80)
                    return "Strong";
                if(score > 60)
                    return "Good";
                if(score > 40)
                    return "Weak";
                return "";
            }//end strength()
            
            $(document).ready(function(){
                $("#onclick").click(function(){//will bring up display box if click
                   $("#signupBox").css("display", "block");  
                   $("#password").on("keypress keyup keydown", function(){
                       var pass = $(this).val();
                   $("#mypassword").text(Strength(pass));  
                });//end mypassword function
             });//end onclick function 
                $("#exit").click(function(){//will exit the display box is cancel is hit
                    $(this).parent().parent().hide();
                });//end exit function
            });//end document function
        </script>
    </head>
    
    <body class="mainpage">
        <h1>Welcome! Please log in!</h1>
        
        
        <form method="POST">
              <div class="inputs">Username:<input type="text" name="username"/>    
                  <p>Password:<input type="password" name="password"/></p>
                  <p id="submitbtn"><input type="submit" name="submit" value="Submit"/></p>   
            </div>
        </form>
        
          <p id="onclick">Not a user? Click here to Register</p>
                  <div id="signupBox"> 
                      <form method="POST" id="regForm">
                          <h3>Register Form</h3>
                          <div class="inputs">Username: <input type="text" name="username"></div>
                          <div class="inputs">Password: <input type="password" name="password"></div>
                          <div>
                              Strength: <div class="intext" id="mypassword"></div>
                          </div>
                            <input type="submit" name="register" value="Register">
                            <input type="button" id="exit" value="Cancel" />
                      </form>
                  </div>
    </body>
</html>

