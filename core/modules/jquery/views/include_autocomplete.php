<?php namespace nmvc\jquery; ?>
<?php $this->layout->enterSection("head"); ?>
<script type="text/javascript" src="<?php echo url("/static/cmod/jquery/jquery-autocomplete/lib/jquery.ajaxQueue.js"); ?>"></script>
<script type="text/javascript" src="<?php echo url("/static/cmod/jquery/jquery-autocomplete/lib/jquery.bgiframe.min.js"); ?>"></script>
<script type="text/javascript" src="<?php echo url("/static/cmod/jquery/jquery-autocomplete/lib/thickbox-compressed.js"); ?>"></script>
<script type="text/javascript" src="<?php echo url("/static/cmod/jquery/jquery-autocomplete/jquery.autocomplete.min.js"); ?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo url("/static/cmod/jquery/jquery-autocomplete/jquery.autocomplete.css"); ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo url("/static/cmod/jquery/jquery-autocomplete/lib/thickbox.css"); ?>" />
<?php $this->layout->exitSection(); ?>