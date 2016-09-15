</div>
<div id='footer'>
	<span id='copyr'>Soul Mate &copy; 2016 Copyright, All Rights Reserved.</span>
</div>
<script src="js/footer1.js"></script>
<?php
if (nullif($_SESSION['login'])) {
	echo "<script src='js/footer2.js'></script>";
} ?>
	</body>
</html>
