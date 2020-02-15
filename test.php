<?php
$arr = 456;
setcookie('arr',456,time()+3600);
setcookie('a',1,time()+3600);
setcookie('b',2,time()+3600);
setcookie('c',3,time()+3600);
setcookie('d',4,time()+3600);
echo "<script>alert('Success!');location='test.html'</script>";
?>