<?php header("Content-type: text/css");  ?>

.adminTitle { font-family: Monospace; font-weight: bold; font-size: 30px; border-bottom: 4px groove #AAA; margin-bottom: 5px; }

<? /* <!-- USER TABLE STYLE --> */ ?>
#users { clear: left; border: double black 4px; }
#users *, #users_pagination * { font-family: Monospace; }
#users tr td { padding: 5px;}
#users thead th { height: 50px; font-weight: bold; font-size: 20px; background: #CCF; vertical-align: top; }
#users thead th span { display: block; position: relative; bottom: 0px; left: 40%; width: 14px; height: 15px; }
<? //Was trying to modify SortingTable class to change class of different element 
/*#users thead th span.forward_sort {  background: url('../../img/sprites.png') no-repeat 0px 0px; }
#users thead th span.reverse_sort { background: url('../../img/sprites.png') no-repeat -14px 0px; }*/ ?>
#users thead th.forward_sort {  background: #88F; }
#users thead th.reverse_sort { background: #C8B; }
#users tbody tr { background-color: #AAF; cursor: pointer; }
#users tbody tr:hover, #users tbody tr.alt:hover { background-color: #FAA; }
#users tbody tr.alt { background-color: #88D; }
#users_pagination li { margin-left: 50px; margin: 10px; float: left; list-style-type: none; }
#users_pagination li a.currentPage { background: #AAA }


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


