<?php header("Content-type: text/css");  ?>

table.users { border: double black 4px; }
table.users tr td { padding: 5px;}
tr.usersHead td { font-weight: bold; font-size: 20px; background: #CCF;}

<? /*<!-- DELETE USER BUTTON --> */?>
td.adminDeleteUser { font-family: Monospace; font-weight: bold; text-align: center; font-size: 30px; }
td.adminDeleteUser:hover { font-weight: bold; font-size: 30px; color: #500; }
<? /*<!-- DELETE USER CONFIRMATION LAYER --> */?>
div.adminDeleteUserConfirm { position: fixed; left: 0; top: 0; width: 100%; height: 100%; display: none; background: #500; z-index: 3000; }
div.adminDeleteUserConfirmContent { position:fixed !important; position: absolute; /*ie6 and above*/ left: 40%; top: 100px; width: 300px; height: 150px; border: solid black 2px; background-color: #AAA; display: none; text-align: center; padding: 20px; z-index: 3001; }
.adminDeleteUserConfirmClose { font-weight: bold; font-size: 30px; cursor: pointer; border: solid black 1px; background: #888; width: 70px; height: 40px; float: left; margin-left: 30px; margin: 20px; z-index: 3002; }


<? /* <!-- ACCESS LEVEL BUTTONS --> */ ?>
span.adminAccessInc, span.adminAccessDec { right: 0px; font-family: Monospace; font-weight: bold; font-size: 30px;  margin-left: 30px; border: dashed 1px black; }
span.adminAccessInc:hover { border: 2px solid green; }
span.adminAccessDec:hover { border: 2px solid red; }
<? /* <!-- ACCESS LEVEL INCREASE CONFIRMATION LAYER --> */ ?>
div.adminAccessInc { position: fixed; left: 0; top: 0; width: 100%; height: 100%; display: none; background: #050; z-index: 3000; }
div.adminAccessIncContent { position:fixed !important; position: absolute; /*ie6 and above*/ left: 40%; top: 100px; width: 300px; height: 150px; border: solid black 2px; background-color: #AAA; display: none; text-align: center; padding: 20px; z-index: 3001; }
.adminAccessIncClose { font-weight: bold; font-size: 30px; cursor: pointer; border: solid black 1px; background: #888; width: 70px; height: 40px; float: left; margin-left: 30px; margin: 20px; z-index: 3002; }
<? /* <!-- ACCESS LEVEL DECREASE CONFIRMATION LAYER --> */ ?>
div.adminAccessDec { position: fixed; left: 0; top: 0; width: 100%; height: 100%; display: none; background: #005; z-index: 3000; }
div.adminAccessDecContent { position:fixed !important; position: absolute; /*ie6 and above*/ left: 40%; top: 100px; width: 300px; height: 150px; border: solid black 2px; background-color: #AAA; display: none; text-align: center; padding: 20px; z-index: 3001; }
.adminAccessDecClose { font-weight: bold; font-size: 30px; cursor: pointer; border: solid black 1px; background: #888; width: 70px; height: 40px; float: left; margin-left: 30px; margin: 20px; z-index: 3002; }



.userTest { background-color: #AAF; cursor: pointer; }
.userTest:hover { background-color: #FAA; }