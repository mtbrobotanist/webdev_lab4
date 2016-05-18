<!DOCTYPE html>
<?php
    require_once 'db.php';
    
    function newUser($user, $pass){
        $conn = openDB();
    
        $statement = $conn->prepare("INSERT INTO users(username, password) VALUES(:user, :pass)");
        $statement->bindParam(":user", $user);
        $statement->bindParam(":pass", $pass);
        $statement->execute();
        if($statement){
            echo "<div class='regform'><h3>Regristration completed!</h3></div>";
        }
        else{
            echo "<div class='regform'><h3>Invalid username or password</h3></div>";
        }
    }//end newUser()
    
    function signUp(){
        $conn = openDB();
        $user = $_POST['username'];
        $pass = $_POST['password'];
        
        if($user == ""){
            echo "<div class='regform'>Please enter a username!</div>";
        }
        else if($pass == ""){
            echo "<div class='regform'>Invalid Password!</div>";
        }
        else{
            $statement = $conn->prepare("SELECT username, password FROM users WHERE username=:user AND password=:pass");
            $statement->execute(array(':user'=>$user, ':pass'=>$pass));
            $rows = $statement->fetch(PDO::FETCH_ASSOC);
            
            if($rows['username'] == $user){
                echo "<div class'regform'>Username is already taken!</div>";
            }
            else{
                newUser($user, $pass);
            }
        }
    }//end signup()

    if(isset($_POST['register'])){
        signUp();
    }
?>

<html>
    <head>
        <title>Lab4</title>
        <link rel="stylesheet" href="lab4.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
        
        <script type="text/javascript">
            $(document).ready(function(){
                $("#onclick").click(function(){
                   $("#signupBox").css("display", "block");   
             });//end onclick function to bring up display block
                $("#exit").click(function(){
                    $(this).parent().parent().hide();
                });
            });//end document function
        </script>
    </head>
    
    <body class="mainpage">
        <h1>Welcome! Please log in!</h1>
        
        <form method="POST">
              <div class="inputs">Username:<input type="text" name="username"/>    
                  <p>Password:<input type="password" name="password"/></p>
                  <p><input type="submit" name="submit" value="Submit"/></p>   
            </div>
        </form>
        
          <p id="onclick">Not a user? Click here to Register</p>
                  <div id="signupBox"> 
                      <form method="POST" id="regForm">
                          <h3>Register Form</h3>
                          <div class="inputs">Username: <input type="text" name="username"></div>
                          <div class="inputs">Password: <input type="password" name="password"></div>
                            <input type="submit" name="register" value="Register">
                            <input type="button" id="exit" value="Cancel" />
                      </form>
                  </div>
    </body>
</html>

