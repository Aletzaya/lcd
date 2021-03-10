<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
<table width="87%" height="185" border="0">
  <tr>
    <td><div align="center">
        <p><font color="#0033FF" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>
		  <?php 
		  if($op=="dtg"){
		     echo "Descuento Gral.";
		  }else{
		     echo "Descuento por estudio";
		  }  	 
		  ?>
		 </strong></font></p>
        <form name="form1" method="post" action="">
          <p><font color="#0033FF" size="2" face="Verdana, Arial, Helvetica, sans-serif">Concepto: 
            <input name="textfield" type="text" size="30">
            </font>
        </form>
        <p><font color="#0033FF" size="2" face="Verdana, Arial, Helvetica, sans-serif">%dto 
          <input name="textfield2" type="text" size="5">
          </font></p>
        <p>
          <input type="submit" name="Submit" value="Enter">
        </p>
      </div></td>
  </tr>
</table>
<div align="center"></div>
</body>
</html>
