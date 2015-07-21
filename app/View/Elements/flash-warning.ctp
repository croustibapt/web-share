<script>
    console.log('<?php echo $message; ?>');
    toastr.options = {
        "newestOnTop": true,
        "positionClass": "toast-top-center"
    };
    toastr.warning('<?php echo $message; ?>', 'Attention');
</script>