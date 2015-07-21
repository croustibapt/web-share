<script>
    console.log('<?php echo $message; ?>');
    toastr.options = {
        "newestOnTop": true,
        "positionClass": "toast-top-center"
    };
    toastr.error('<?php echo $message; ?>', 'Erreur');
</script>