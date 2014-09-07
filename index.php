
<?php
require_once 'core/init.php';
?>

<!DOCTYPE html>
<html>
    <head>
    	<title> cbxr log_reg testing </title> 
    	<style type="text/css">
    	body{
    		margin: 0;
    		padding: 0;
    		width: 100%;
    		background-color: #efefef;
    	}

    	#main-cont{
    		margin: 0 auto;
    		margin-top: 100px;
    		padding: 10px;
    		box-shadow: 8px 8px 8px rgba(0,0,0,0.1);
    		border-radius: 5px;
    		background-color: #fff;
    		font-family: Arial;
    		width: 350px;
    	}
    	</style>
    </head>

    <body>
    	<div id="main-cont">
    		<?php
    			// this is calling functions using our instance method
    			// DB::getInstance() creates a new instance of db
    			// we can then use that instance to go to methods
    			// an example is whenever we use DB::getInstance()->getAction()
				$user = DB::getInstance()->getAction('users', array('username', '=', 'Simple'));
				$allUsers = DB::getInstance()->query("SELECT * FROM users");

				if(!$user->count()){
					// if the query has no match
					echo 'No user found';
				}else{
					// loops through all of the rusults from our user query
					// it then returns the variable results because the function results() does so

					// the $allusers->username etc is accesting the collumn int the database, not the variable
					foreach($allUsers->results() as $allUsers){
						echo 'user ' . $allUsers->username . ' with password ' . $allUsers->password . ' found <br /><br />';
					}

					// we could also do
					//echo $allUsers->first()->username;
				}
    		?>
    	</div>
    </body>
