<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<title> confirm delete</title>
</head>
<body>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="100%" height="100%" align="center" valign="middle"> 
      <table width="200" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#000000">
        <tr> 
          <td bgcolor="#FFFF99"><table width="200" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFF99">
              <tr> 
                <td colspan="2" align="center"><br>
                  <br> <font color="#006666"><strong>Are 
                  you sure to </strong></font>
                  <p><font color="#006666"><strong> delete this?</strong></font><br>
                    <br>
                    <br>
                </td>
              </tr>
              <tr> 
                <td width="50%" align="center"> <form name="form1" method="post" action="delete.php?myy=<?php echo($HTTP_GET_VARS['myy']) . '&lang=' . $HTTP_GET_VARS['lang'] ?>">
                    <input type="submit" name="delete" value=" yes ">
                  </form></td>
                <td width="50%" align="center"> <form name="form2" method="post" action="delete.php?myy=<?php echo($HTTP_GET_VARS['myy']) . '&lang=' . $HTTP_GET_VARS['lang'] ?>">
                    <input type="submit" name="delete2" value=" no ">
                  </form></td>
              </tr>
              <tr> 
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
            </table></td>
        </tr>
      </table><p>&nbsp;</p></td>
  </tr>
</table>
</body>
</html>
