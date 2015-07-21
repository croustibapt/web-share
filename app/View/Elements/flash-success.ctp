<script>
    console.log('<?php echo $message; ?>');
    toastr.options = {
        "newestOnTop": true,
        "positionClass": "toast-top-center"
    };
    toastr.success('<?php echo $message; ?>', 'Information');
</script>