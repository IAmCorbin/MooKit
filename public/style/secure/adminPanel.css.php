<?php header("Content-type: text/css");  ?>

.adminTitle { font-family: Monospace; font-weight: bold; font-size: 30px; border-bottom: 4px groove #AAA; margin-bottom: 5px; }

<? /* <!-- FIX ZEBRA HOVER COLORS --> */ ?>
#links tbody tr.alt:hover, #users tbody tr.alt:hover { background-color: #FAA; }

<? /*<!-- DELETE USER BUTTON --> */?>
td.adminDeleteUser { font-family: Monospace; font-weight: bold; text-align: center; font-size: 30px; }
td.adminDeleteUser:hover { font-weight: bold; font-size: 30px; color: #500; }

<? /* <!-- ACCESS LEVEL BUTTONS --> */ ?>
span.adminAccessInc, span.adminAccessDec { right: 0px; font-family: Monospace; font-weight: bold; font-size: 30px;  margin-left: 30px; border: dashed 1px black; }
span.adminAccessInc:hover { border: 2px solid green; }
span.adminAccessDec:hover { border: 2px solid red; }

<? /*<!-- DELETE LINK BUTTON --> */?>
td.adminDeleteLink { font-family: Monospace; font-weight: bold; text-align: center; font-size: 30px; }
td.adminDeleteLink:hover { font-weight: bold; font-size: 30px; color: #500; }

<? /* <!-- Add Menu Link Form --> */ ?>
#adminAddLink { width: 425px; border: inset green 4px; padding: 10px; background: #66A966; font-size: 10px; }
#adminAddLink * { font-family: monospace; }
#adminAddLink h1 { font-size: 14px; border-bottom: dashed black 2px; margin-bottom: 10px; }
#adminAddLink label { display: block; position: relative;  }
#adminAddLink label span { display: block; float: left; width: 70px; }
#adminAddLink label input[type="text"] { margin-bottom: 5px; border: none; background: #77BA77; }
#adminAddLink label input[type="submit"] { border:outset 2px #77BA77; background: #66A966; text-transform: uppercase; }
#adminAddLink label input[type="submit"]:hover { border: inset 2px #77BA77; background: #66A966; }
<? /* <!-- Add Edit Link Form --> */ ?>
form.adminEditLink { width: 425px; border: inset blue 4px; padding: 10px; background: #6666A9; font-size: 10px; }
form.adminEditLink * { font-family: monospace; }
form.adminEditLink h1 { font-size: 14px; border-bottom: dashed black 2px; margin-bottom: 10px; }
form.adminEditLink label { display: block; position: relative;  }
form.adminEditLink label span { display: block; float: left; width: 70px; }
form.adminEditLink label input[type="text"] { margin-bottom: 5px; border: none; background: #7777BA; }
form.adminEditLink label input[type="submit"] { border:outset 2px #7777BA; background: #6666A9; text-transform: uppercase; }
form.adminEditLink label input[type="submit"]:hover { border: inset 2px #7777BA; background: #6666A9; }
<? /* <!-- SubLinks --> */ ?>
li.sublinkAdd:hover { border: outset black 1px; background: #999; }
.EDITING * { background: green, color: green; border: green; }