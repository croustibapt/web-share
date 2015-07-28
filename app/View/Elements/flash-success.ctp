<script>
    console.log('<?php echo $message; ?>');
    
    //
    Messenger().post({
        message: '<?php echo addslashes($message); ?>',
        type: 'success',
        hideAfter: 2
    });
</script>