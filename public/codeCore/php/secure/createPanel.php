<?
//require Create Access
if(Security::clearance() & ACCESS_CREATE) {
?>

<div class="testCreate">
	Welcome To The Create Zone!
</div>

<div id="testCreateJS">

</div>

<?
} else
	echo "Unauthorized";
?>