<script>
    console.log('<?php echo $message; ?>');
    
    //
    Messenger().post({
        message: '<?php echo addslashes($message); ?>',
        type: 'info',
        hideAfter: 2
    });
</script>