<script>
    console.log('<?php echo $message; ?>');
    
    //
    Messenger().post({
        message: '<?php echo addslashes($message); ?>',
        type: 'error',
        hideAfter: 2
    });
</script>