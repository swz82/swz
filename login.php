<?php
 
/*require_once 'model.php';


$sql="select id,username,password from user where username='$name' AND password='$pwd';";
$result=mysqli_query($conn,$sql);
$row=mysqli_num_rows($result);*/

$name=$_POST['username'];
$pwd=$_POST['password'];
 
//if(!$row)
if($name=='')
{
    echo "<script>alert('Please enter username!');location='login.html'</script>";
}
else if($pwd=='')
{
    echo "<script>alert('Please enter password!');location='login.html'</script>";
}
else if($name!='swz'||$pwd!='123')
{
    echo "<script>alert('Wrong password!');location='login.html'</script>";       
}
else
{
    echo "<script>alert('Success!');location='web1.php'</script>";
};